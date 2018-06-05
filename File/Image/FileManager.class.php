<?php

namespace \ItForFree\rusphp\File\Image;

/**
 * Класс для работы с файлами
 *
 * @version  1.9.1
 *
 */
class FileManager
{
	/**
	 * Абсолютный адрес директории, в которую загружаются файлы
	 *
	 * @var string
	 */
	private static $upload_dir = "/uploaded/";

	/**
	 * Адрес поддиректории, в которую будет загружен текущий файл
	 *
	 * @var string
	 */
	public static $defaultLocalDir = "files/";

	const DIR_ID = 0;
	const FILE_ID = 1;

	const UNITS_BYTES = 0;
	const UNITS_KILOBYTES = 1;
	const UNITS_MEGABYTES = 2;
	const UNITS_GIGABYTES = 3;
	const UNITS_TERABYTES = 4;

	/**
	 * Загружает файл на сервер.
	 *
	 * @param string $fileName
	 * @param string $localDir
	 * @return string
	 */
	public static function UploadFile(
		/**
		 * Название поля name тега input, через который загружается файл
		 */
		$fileName,
		/**
		 * Адрес поддиректории, в которую будет загружен текущий файл
		 */
		$localDir = null
	)
	{
		/**
		 * Проверяем, был ли загружен файл на сервер
		 */
		if (!isset($_FILES[$fileName]) || $_FILES[$fileName]['name'] == "")
			return null;


		if ($localDir == null)
			$localDir = self::$defaultLocalDir;
		else
		{
			/**
			 * Переводим абсолютный путь в относительный.
			 */
			if ($localDir{0} == "/")
			{
				$localDir = substr($localDir, 1);
			}

			/**
			 * Убираем слэш в конце пути
			 */
			if ($localDir{count($localDir - 1)} != "/")
			{
				$localDir .= "/";
			}
		}

		$localDir = str_replace("//", "/", $localDir);

		/**
		 * Разбиваем строку путь на массив, состоящий из названий директорий.
		 * Если таких директорий нет, пытаемся их создать
		 */
		$localDirsArray = explode("/", $localDir);
		$dirsNumber = count($localDirsArray);
		$path = IncPaths::$ROOT_PATH.self::$upload_dir;
		for ($i = 0; $i < $dirsNumber-1; $i++)
		{
			$path .= $localDirsArray[$i];
			if (!is_dir($path))
			{
				if (!mkdir($path, 0777))
					throw new Exception('Не удалось создать директорию '.implode("/", $localDirsArray));
			}
			$path .= '/';
		}

		/**
		 * Необходимые директории созданы, начинаем загружать файл
		 */
		$uploadDir = $path;

		/**
		 * Удаляем двойные слешы из пути директории
		 * Не знаю, откуда они могут появиться, но все же... :)
		 */
		$dir = str_replace('//', '/', $uploadDir);

		/**
		 * Вытаскиваем название загружаемого файла
		 */
		$fname = $_FILES[$fileName]['name'];

		/**
		 * Вырезаем из навания расширение
		 */
		$m = explode('.', $fname);
		$ext = $m[count($m) - 1];
		unset($m[count($m) - 1]);
		$name = implode('.', $m);
		$name = Transliter::TranslitToStrictName($name);

		/**
		 * Если файл с таким названием существует, припысываем к названию случайное число до тех пор,
		 * пока название не будет уникальным
		 */
		while (file_exists($dir.$name.'.'.$ext))
		{
			$name .= '_'.rand(0, 100);
		}
		$fname = $name.'.'.$ext;

		/**
		 * Копируем файл из временной директории в указанное место
		 */
		if (!move_uploaded_file($_FILES[$fileName]['tmp_name'], $dir.$fname))
			throw new Exception("Не удалось переместить файл из временной директории");

		/**
		 * Возвращаем относительный путь к загруженному файлу
		 */
		return self::$upload_dir.$localDir.$fname;
	}

	/**
	 * Удаляет файл с сервера
	 *
	 * @param string $filePath
	 */
	public static function DeleteFile(
		/**
		 * Адрес файла
		 */
		$filePath
	)
	{
		//Переводим абсолютный путь в относительный.
		if ($filePath{0} == '/')
			$filePath = IncPaths::$ROOT_PATH.$filePath;

		$filePath = str_replace('//', '/', $filePath);

		//Проверяем наличие файла
		if (!file_exists($filePath))
			throw new Exception("Файл ".$filePath." не найден");

		//Удаляем файл
		if (!unlink($filePath))
			throw new Exception("Не удалось удалить файл ".$filePath);
	}

	/**
	 * Создает копию файла
	 * В зависимости от параметра type название файла-копии может быть следующим:
	 * 		1. type = prefix 	- 	название файла-копии получается из названия исходного файла
	 * 								путем прибавления профикса, записанного в переменной argument
	 * 		2. type = postfix 	- 	название файла-копии получается из названия исходного файла
	 * 								путем прибавления постфикса, записанного в переменной argument
	 * 		3. type = new		-	файлу-копии присваивается название, записанное в переменной argument
	 *
	 * @param string $filePath
	 * @param string $type
	 * @param mix $argument
	 * @return string
	 */
	public static function CopyFile(
		/**
		 * Адрес файла
		 */
		$filePath,
		/**
		 * Тип копирования
		 */
		$type,
		/**
		 * Дополнительный аргумент
		 */
		$argument = ""
	)
	{
		// Переводим абсолютный путь в относительный.
		if ($filePath{0} == "/")
			$filePath = IncPaths::$ROOT_PATH.$filePath;

		$filePath = str_replace("//", "/", $filePath);

		// Проверка существования файла
		if (!file_exists($filePath))
			throw new FileNotFoundException('Файл '.$filePath.' не найден');

		$type = strtolower($type);

		// Получаем название файла и директорию, в которой он находится
		$fname = basename($filePath);
		$dir = dirname($filePath).'/';

		// Получаем расширение файла
		$m = explode('.', $fname);
		$ext = $m[count($m) - 1];
		unset($m[count($m) - 1]);
		$name = implode('.', $m);

		switch ($type)
		{
			case "prefix":
				$newName = $argument.$name;
				while (file_exists($dir.$newName.".".$ext))
				{
					$newName = $argument.$name."_".rand(0, 100);
				}
				$fname = $newName.".".$ext;
				break;

			case "postfix":
				$newName = $name.$argument;
				while (file_exists($dir.$newName.".".$ext))
				{
					$newName = $name.$argument."_".rand(0, 100);
				}
				$fname = $newName.".".$ext;
				break;

			case "new":
				$newName = $argument;
				while (file_exists($dir.$newName.".".$ext))
				{
					$newName = $argument."_".rand(0, 100);
				}
				$fname = $newName.".".$ext;
				break;

			default:
				throw new Exception("Тип копирования ".$type." не поддерживается");
		}

		// Копируем
		copy($filePath, $dir.$fname);

		// Вычисляем относительный путь к файлу
		$root_dir = IncPaths::$ROOT_PATH;
		$dir = str_replace($root_dir, '', $dir);
		if ($dir{0} != "/")
			$dir = "/".$dir;

		// Возваращаем относительный путь к новому файлу
		return $dir.$fname;
	}

	/**
	* Перемещение файла
	* Может заменять существующий или найти уникальное имя, если файл уже есть
	* Если директории назначения не существует, то производится попытка создать ее
	*
	*/
	public static function MoveFile($filePath, $destinationDirectory, $newName = null, $replaceExisting = false)
	{
		// Переводим относительный путь в абсолютный.
		if (strpos($filePath, IncPaths::$ROOT_PATH) === false)
			$filePath = IncPaths::$ROOT_PATH.$filePath;

		$filePath = str_replace('//', '/', $filePath);

		// Переводим относительный путь в абсолютный.
		if (strpos($destinationDirectory, IncPaths::$ROOT_PATH) === false)
			$destinationDirectory = IncPaths::$ROOT_PATH.$destinationDirectory;

		$destinationDirectory = str_replace('//', '/', $destinationDirectory);

		// Проверка существования файла
		if (!file_exists($filePath))
			throw new FileNotFoundException('Файл '.$filePath.' не найден');

		// Проверка возможности удалить файл
		if (!is_writable($filePath))
			throw new Exception('Файл '.$filePath.' не может быть перемещён. Недостаточно прав.');

		// rev 199 добавлена попытка создать папку назначения
		if (!is_dir($destinationDirectory))
		{
			if (!mkdir($destinationDirectory, 0777, true)){
				throw new Exception('Файл '.$filePath.' не может быть перемещён. Целевой путь не директория. '.$destinationDirectory);
			}
		}

		if ($destinationDirectory[strlen($destinationDirectory) - 1] != '/')
			$destinationDirectory .= '/';

		if (!is_writable($destinationDirectory))
			throw new Exception('Файл '.$filePath.' не может быть перемещён. Недостаточно прав на запись в целевую директорию. '.$destinationDirectory);

		// Получаем название файла и директорию, в которой он находится
		$fname = basename($filePath);
		$dir = dirname($filePath).'/';
		if (empty($newName))
			$newName = $fname;

		if ($replaceExisting)
		{
			if (file_exists($destinationDirectory . $newName) && !is_writable($destinationDirectory . $newName))
				throw new Exception('Файл с таким именем уже существует, но его нельзя удалить -- нет прав.');

			rename($filePath, $destinationDirectory.$newName);
		}
		else
		{
			// Получаем расширение файла
			$m = explode('.', $newName);
			$ext = $m[count($m) - 1];
			unset($m[count($m) - 1]);
			$name = implode('.', $m);

			while (file_exists($destinationDirectory.$name.'.'.$ext))
			{
				$name = $name.'_'.rand(0, 100);
			}
			$newName = $name.'.'.$ext;
			rename($filePath, $destinationDirectory.$newName);
		}

		// Вычисляем относительный путь к файлу
		$destinationDirectory = str_replace(IncPaths::$ROOT_PATH, '', $destinationDirectory);
		if ($destinationDirectory{0} != '/')
			$destinationDirectory = '/'.$destinationDirectory;

		// Возваращаем относительный путь к новому файлу
		return $destinationDirectory.$newName;
	}

	/**
	* Функция получения размера файла
	*/
	public static function GetFileSize($filePath, $units = self::UNITS_KILOBYTES)
	{
		// Переводим относительный путь в абсолютный.
		if (strpos($filePath, IncPaths::$ROOT_PATH) === false)
			$filePath = IncPaths::$ROOT_PATH.$filePath;

		$filePath = str_replace('//', '/', $filePath);

		// Проверка существования файла
		if (!file_exists($filePath))
			throw new FileNotFoundException('Файл '.$filePath.' не найден');

		$filesize = filesize($filePath);
		switch ($units)
		{
			case self::UNITS_BYTES:
				return $filesize;
				break;
			case self::UNITS_KILOBYTES:
				return $filesize / 1024;
				break;
			case self::UNITS_MEGABYTES:
				return $filesize / (1024*1024);
				break;
			case self::UNITS_GIGABYTES:
				return $filesize / (1024*1024*1024);
				break;
			case self::UNITS_TERABYTES:
				return $filesize / (1024*1024*1024*1024);
				break;
			default:
				return $filesize;
		}
	}

	public static function LoadExternalFile($fileURL, $localDir = null, $fileName = null)
	{
		if (!Url::Exists($fileURL))
			throw new FileNotFoundException("Файл по указанному URL не найден");

		/**
		 * Максимальный размер загружаемого файла (в байтах)
		 * Надо бы вынести эту переменную куда-нибудь в другое место... может быть как настройку сайта
		 */
		$maxFileSize = 1024 * 1024 * 2;

		/**
		 * Смотрим размер загружаемого файла
		 */
		if (Url::FileSize($fileURL) > $maxFileSize)
			throw new Exception("Выбранный файл слишком большой, загрузить не можем");

		/**
		 * Определяем путь к директории, в которую будет загружен файл
		 */
		$path = self::PrepareLocalDir($localDir);

		/**
		 * Определяем название файла
		 */
		if ($fileName == null)
		{
			$fileUrlArr = explode("/", $fileURL);
			$fileName = $fileUrlArr[count($fileUrlArr)-1];
		}

		/**
		 * Вырезаем из навания файла его расширение
		 */
		$fileNameArr = explode(".", $fileName);
		$ext = $fileNameArr[count($fileNameArr) - 1];
		unset($fileNameArr[count($fileNameArr) - 1]);
		$name = implode(".", $fileNameArr);

		/**
		 * Смотрим, чтобы название файла было уникальным.
		 * В случае существования файла с таким названием, приписываем к нему случайное число.
		 */
		while (file_exists($path.$name.".".$ext))
		{
			$name .= rand(0, 100);
		}
		$fileName = $name.".".$ext;

		/**
		 * Копируем файл к себе
		 */
		copy($fileURL, $path.$fileName);

		/**
		 * Возвращаем путь к файлу
		 */
		return str_replace(IncPaths::$ROOT_PATH, '/', $path.$fileName);
	}

	/**
	 * Возвращает путь к директории на нашем сервере, в который будет загружен файл
	 *
	 * @param 	string $localDir
	 * @return 	string
	 */
	private static function PrepareLocalDir($localDir = null)
	{
		if ($localDir == null)
			$localDir = self::$defaultLocalDir;
		else
		{
			/**
			 * Переводим абсолютный путь в относительный.
			 */
			if ($localDir{0} == "/")
			{
				$localDir = substr($localDir, 1);
			}

			/**
			 * Убираем слэш в конце пути
			 */
			if ($localDir{count($localDir - 1)} != "/")
			{
				$localDir .= "/";
			}
		}

		/**
		 * Убираем на всякий случай двойные слеши, вдруг они там есть :)
		 */
		$localDir = str_replace("//", "/", $localDir);

		/**
		 * Разбиваем строку путь на массив, состоящий из названий директорий.
		 * Создаем несуществующий директории
		 */
		$localDirsArray = explode("/", $localDir);
		$dirsNumber = count($localDirsArray);
		$path = IncPaths::$ROOT_PATH . self::$upload_dir;
		for ($i = 0; $i < $dirsNumber-1; $i++)
		{
			$path .= $localDirsArray[$i];
			if (!is_dir($path))
			{
				if (!mkdir($path, 0777))
					throw new Exception('Не удалось создать директорию '.implode("/", $localDirsArray));
			}
			$path .= "/";
		}

		$path = str_replace("//", "/", $path);

		return $path;
	}

	/**
	 * Возвращает содержимое директории: массив файлов и поддиректорий
	 *
	 * @param string $directory
	 * @return array
	 */
	public static function GetDirectory($directory)
	{
		/**
		 * Инициализируем массивы
		 */
		$aFiles = array();
        $aDirs = array();

        /**
         * Получаем массив всех поддиректорий и файлов указанного каталога
         */
        $files = glob($directory.'*');
        foreach ($files as $file)
        {
            if (is_dir($file))
            {
            	$aDirs[] = array
            	(
            	   'type'  => self::DIR_ID,
        	       'path'  => $file,
        	       'name'  => basename($file)
            	);
            }
            else
            {
            	$aFiles[] = array
            	(
            	   'type'  => self::FILE_ID,
        	       'path'  => $file,
        	       'name'  => basename($file)
            	);
            }
        }

        return array_merge($aDirs, $aFiles);
	}

	public static function GetDirectoryInfo($directory)
    {
        static $dirLen = 0;
        if (empty($dirLen))
        {
             $dirLen = strlen($directory)-1;
        }

        $size = 0;
        $filesCount = 0;
        $dirsCount = 0;
        $tFilesCount = 0;

        $maxFile = array('file'=>'', 'size'=>0);
        $maxDir = array('dir'=>'', 'count'=>0);

        $files = glob($directory.'*');

        foreach ($files as $file)
        {
            $name = basename($file);
            if (is_dir($file))
            {
                if ($name == '.' or $name == '..')
                {
                    continue;
                }
                $info = self::GetDirectoryInfo($file.'/');
                $size += $info['size'];
                $dirsCount += (1 + $info['dirsCount']);
                $filesCount += $info['filesCount'];
                if ($info['maxFile']['size'] > $maxFile['size'])
                {
                    $maxFile = $info['maxFile'];
                }
                if ($info['maxDir']['count'] > $maxDir['count'])
                {
                    $maxDir = $info['maxDir'];
                }
            }
            else
            {
                $tSize = (int) @filesize($file);
                if ($tSize>$maxFile['size'])
                {
                    $maxFile = array('file'=>substr($file, $dirLen), 'size'=>$tSize);
                }
                $size += $tSize;
                $filesCount++;
                $tFilesCount++;
            }
        }

        if ($tFilesCount > $maxDir['count'])
        {
            $maxDir = array('dir'=>substr($directory, $dirLen), 'count'=>$tFilesCount);
        }

        return array
        (
            'size'=>$size,
            'filesCount'=>$filesCount,
            'dirsCount'=>$dirsCount,
            'maxFile'=>$maxFile,
            'maxDir'=>$maxDir
        );
    }
}

?>