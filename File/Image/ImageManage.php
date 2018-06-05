<?php

namespace \ItForFree\rusphp\File\Image;

/**
 * Класс для работы с изображениями 
 * (версия которая режет картинку по центру)
 *
 * ver. 1.1.1
 *
 */
class ImageManager
{
    /**
     * Поддиректория общей директории загруженных файлов, в которой хранятся изображения
     *
     * @var string
     */
    private static $uploadImagesDir = "images/";

    /**
     * Выполнять ли обрезку пропорционально данным размерам (true) или считать их реальными (false)
     * 
     * @var boolean 
     */
    public static $proportionalResize = false;

    /**
     * Загружаем изображение на сервер
     *
     * @param string $imageName Название поля name тега input, через который загружается изображение
     * @return string 
     */
    public static function ImageUpload($imageName)
    {
        return FileManager::UploadFile($imageName, self::$uploadImagesDir);
    }

    /**
     * Пропорционально уменьшает размеры изображения
     *
     * @param string $imageFilePath Адрес файла с изображением
     * @param int $maxWidth  Максимальная ширина уменьшенного изображения
     * @param int $maxHeight Максимальная высота уменьшенного изображения
     */ 
    public static function ImageResize( $imageFilePath, $maxWidth, $maxHeight)
    {
        /**
         * Переводим абсолютный путь в относительный
         */
        if($imageFilePath{0} == "/") $imageFilePath = IncPaths::$ROOT_PATH . substr($imageFilePath, 1);

        /**
         * Провеяем файл на существование
         */
        if(!file_exists($imageFilePath)) throw new Exception("Файл " . $imageFilePath . " не найден");

        /**
         * Проверяем, чтобы указанный файл был изображением
         */
        if(!($imageInfo = @getimagesize($imageFilePath))) throw new Exception("Файл " . $imageFilePath . " не является картинкой");

        /**
         * Получаем параметры изобраения:
         * 		- ширину
         * 		- высоту
         * 		- тип изображения (gif, jpeg, png...)
         */
        $imageWidth  = $imageInfo[0];
        $imageHeight = $imageInfo[1];
        $imageType   = $imageInfo[2];

        switch($imageType)
        {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($imageFilePath);
                break;

            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($imageFilePath);
                break;

            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($imageFilePath);
                break;

            default:
                throw new Exception("Данный тип файла не поддерживается");
                break;
        }

        if($imageWidth > $maxWidth or $imageHeight > $maxHeight)
        {
            $fK    = $imageWidth / $imageHeight;
            $fDefK = $maxWidth / $maxHeight;
            if($fDefK > $fK)
            {
                $iNewY = $maxHeight;
                $fNewK = $imageHeight / $maxHeight;
                $iNewX = intval($imageWidth / $fNewK);
            }
            else
            {
                $iNewX = $maxWidth;
                $fNewK = $imageWidth / $maxWidth;
                $iNewY = intval($imageHeight / $fNewK);
            }
            $oNewImage = imagecreatetruecolor($iNewX, $iNewY);
            if($imageType == IMAGETYPE_PNG or $imageType == IMAGETYPE_GIF)
            {
                imagecolortransparent($oNewImage, imagecolorallocate($oNewImage, 0, 0, 0));
//				imagecolortransparent($oNewImage, imagecolorallocatealpha($oNewImage, 0, 0, 0, 127));
                imagealphablending($oNewImage, false);
                imagesavealpha($oNewImage, true);
            }

            imagecopyresampled($oNewImage, $image, 0, 0, 0, 0, $iNewX, $iNewY, $imageWidth, $imageHeight);

            switch($imageType)
            {
                case IMAGETYPE_GIF:
                    imagegif($oNewImage, $imageFilePath);
                    break;

                case IMAGETYPE_JPEG:
                    imagejpeg($oNewImage, $imageFilePath, 90);
                    break;

                case IMAGETYPE_PNG:
                    imagepng($oNewImage, $imageFilePath);
                    break;

                default:
                    throw new Exception("Данный тип файла не поддерживается");
                    break;
            }
        }
    }

    /**
     * Жетское уменьшение размеров изображения.
     * Размеры выходного изображения будут в точности равны заданным размерам
     *
     * @param string $imageFilePath
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $position позиция окна обрезки на изображении 0 - центр, 1 - сверху 2 - снизу
     */
    public static function StrongImageResize($imageFilePath, $maxWidth, $maxHeight, $position = 0)
    {
        if($imageFilePath{0} == "/") $imageFilePath = IncPaths::$ROOT_PATH . $imageFilePath;

        if(!file_exists($imageFilePath)) throw new Exception("Файл " . $imageFilePath . " не найден");

        if(!($imageInfo = getimagesize($imageFilePath))) throw new Exception("Файл " . $imageFilePath . " не является картинкой");

        if(self::$proportionalResize)
        { // вычисляем реальные размеры, на случай если требуется пропорциональная обрезка
            $size      = self::getMaxProportionalWidthAndHeight($imageInfo[0], $imageInfo[1], $maxWidth, $maxHeight);
            $maxWidth  = $size['width'];
            $maxHeight = $size['height'];
        }

        $imageWidth  = $imageInfo[0];
        $imageHeight = $imageInfo[1];
        $imageType   = $imageInfo[2];

        switch($imageType)
        {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($imageFilePath);
                break;

            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($imageFilePath);
                break;

            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($imageFilePath);
                break;

            default:
                throw new Exception("Данный тип файла не поддерживается");
                break;
        }

        $dx = 0;
        $dy = 0;
        if($imageHeight == $imageWidth)
        {
            $position = false;
        }
        if($imageWidth > $maxWidth or $imageHeight > $maxHeight)
        {
            $fK    = $imageWidth / $imageHeight;
            $fDefK = $maxWidth / $maxHeight;
            if($fDefK < $fK)
            {
                $iNewY = $imageHeight;
                $iNewX = intval($fDefK * $iNewY);
                if(!$position)
                {
                    $dx = intval(($imageWidth - $iNewX) / 2);
                }
                elseif($position == 2)
                {
                    $dx = intval(($imageWidth - $iNewX));
                }
            }
            else
            {
                $iNewX = $imageWidth;
                $iNewY = intval($iNewX / $fDefK);
                if(!$position)
                {
                    $dy = intval(($imageHeight - $iNewY) / 2);
                }
                elseif($position == 2)
                {
                    $dy = intval(($imageHeight - $iNewY));
                }
            }
            $oNewImage = imagecreatetruecolor($maxWidth, $maxHeight);

            if($imageType == IMAGETYPE_PNG or $imageType == IMAGETYPE_GIF)
            {
                imagecolortransparent($oNewImage, imagecolorallocate($oNewImage, 0, 0, 0));
//				imagecolortransparent($oNewImage, imagecolorallocatealpha($oNewImage, 0, 0, 0, 127));
                imagealphablending($oNewImage, false);
                imagesavealpha($oNewImage, true);
            }

            imagecopyresampled($oNewImage, $image, 0, 0, $dx, $dy, $maxWidth, $maxHeight, $iNewX, $iNewY);

            switch($imageType)
            {
                case IMAGETYPE_GIF:
                    imagegif($oNewImage, $imageFilePath);
                    break;

                case IMAGETYPE_JPEG:
                    imagejpeg($oNewImage, $imageFilePath, 90);
                    break;

                case IMAGETYPE_PNG:
                    imagepng($oNewImage, $imageFilePath);
                    break;

                default:
                    throw new Exception("Данный тип файла не поддерживается");
                    break;
            }
        }
    }

    /**
     * Вычислит реальные размеры для обрезки картики так, чтобы длина к ширине относились как $proporWidth/$proporHeight
     * -- при этом сохранится максимально возможный размер изображения
     * 
     * @param int $currentRealWidth    настоящая длина (ширина) оригинального файла
     * @param int $currentRealHeight   настоящая высота данного файла
     * @param int $proporWidth         пропорциональное значение желаемой длины (ширины)
     * @param int $proporHeight        пропорциональное значение желаемой высоты
     * @return array
     */
    public static function getMaxProportionalWidthAndHeight($currentRealWidth, $currentRealHeight, $proporWidth, $proporHeight)
    {
        $width  = $currentRealWidth;
        $height = $currentRealHeight;

        if(($currentRealWidth / $currentRealHeight) < ($proporWidth / $proporHeight))
        {
            $height = $currentRealWidth * $proporHeight / $proporWidth; // уменьшаем высоту (подгоняем под пропорцию $proporWidth / $proporHeigh)
        }
        else if(($currentRealWidth / $currentRealHeight) > ($proporWidth / $proporHeight))
        {
            $width = $currentRealHeight * $proporWidth / $proporHeight;  // уменьшаем ширину (подгоняем под пропорцию $proporWidth / $proporHeigh)
        }

        $result = array(
            'width'  => $width,
            'height' => $height
        );

        return $result;
    }

    /**
     * Удаляет файл с изображением с сервера
     *
     * @param string $imageFile
     */
    public static function ImageDelete(
    /**
     * Адрес к удаляемому файлу
     */
    $imageFilePath
    )
    {
        FileManager::DeleteFile($imageFilePath);
    }

    /**
     * Копирует файл с изображением
     * В зависимости от параметра type название файла-копии может быть следующим:
     * 		1. type = prefix 	- 	название файла-копии получается из названия исходного файла
     * 								путем прибавления профикса, записанного в переменной argument
     * 		2. type = postfix 	- 	название файла-копии получается из названия исходного файла
     * 								путем прибавления постфикса, записанного в переменной argument
     * 		3. type = new		-	файлу-копии присваивается название, записанное в переменной argument
     *
     * @param string $imageFilePath
     * @param string $type
     * @param mix $argument
     * @return string
     */
    public static function ImageCopy($imageFilePath, $type, $argument = "")
    {
        return FileManager::CopyFile($imageFilePath, $type, $argument);
    }

    public static function ImageCrop($imageFilePath, $x1, $y1, $x2, $y2, $width, $height)
    {
        if($imageFilePath{0} == "/") $imageFilePath = IncPaths::$ROOT_PATH . $imageFilePath;

        if(!file_exists($imageFilePath)) throw new Exception("Файл " . $imageFilePath . " не найден");

        if(!($imageInfo = getimagesize($imageFilePath))) throw new Exception("Файл " . $imageFilePath . " не является картинкой");

        $imageWidth  = $imageInfo[0];
        $imageHeight = $imageInfo[1];
        $imageType   = $imageInfo[2];

        switch($imageType)
        {
            case IMAGETYPE_GIF:
                $image = imagecreatefromgif($imageFilePath);
                break;

            case IMAGETYPE_JPEG:
                $image = imagecreatefromjpeg($imageFilePath);
                break;

            case IMAGETYPE_PNG:
                $image = imagecreatefrompng($imageFilePath);
                break;

            default:
                throw new Exception("Данный тип файла не поддерживается");
                break;
        }

        /**
         * Создаем новое изображение
         */
        $newImage = imagecreatetruecolor($width, $height);
        imagecopyresampled($newImage, $image, 0, 0, $x1, $y1, $width, $height, $x2 - $x1, $y2 - $y1);

        /**
         * Генерим имя файла для нового изображения
         */
        $fname = basename($imageFilePath);
        $dir   = dirname($imageFilePath) . "/";

        $newFname         = "cropped_" . $fname;
        $newImageFilePath = $dir . $newFname;

        switch($imageType)
        {
            case IMAGETYPE_GIF:
                imagegif($newImage, $newImageFilePath);
                break;

            case IMAGETYPE_JPEG:
                imagejpeg($newImage, $newImageFilePath, 90);
                break;

            case IMAGETYPE_PNG:
                imagepng($newImage, $newImageFilePath);
                break;

            default:
                throw new Exception("Данный тип файла не поддерживается");
                break;
        }

        /**
         * Вычисляем абсолютный путь к файлу
         */
        $root_dir         = IncPaths::$ROOT_PATH;
        $newImageFilePath = str_replace($root_dir, "", $newImageFilePath);

        if($newImageFilePath{0} != "/") $newImageFilePath = "/" . $newImageFilePath;

        /**
         * Возваращаем абсолютный путь к новому файлу
         */
        return $newImageFilePath;
    }

    public static function LoadExternalImage($imageURL, $imagePath = "/images/")
    {
        $filePath = FileManager::LoadExternalFile($imageURL, $imagePath);

        $imageFilePath = $filePath;
        if($imageFilePath{0} == "/") $imageFilePath = IncPaths::$ROOT_PATH . $imageFilePath;

        if(!file_exists($imageFilePath)) throw new Exception("Файл " . $imageFilePath . " не был загружен");

        if(filesize($imageFilePath) <= 1 || !($imageInfo = getimagesize($imageFilePath)))
        {
            FileManager::DeleteFile($imageFilePath);
            throw new Exception("Файл " . $imageFilePath . " не является картинкой");
        }

        return $filePath;
    }

}
