<?php

namespace ItForFree\rusphp\File\Image;

use ItForFree\rusphp\File\TempFile;
use ItForFree\rusphp\Log\SimpleFileLog as FileLog;

/**
 *  Класс для изменение размеров (обрезки) изображений как обычны образом, 
 * так и "на лету" с отдачей браузер 
 * (можно использовать в контроллере приложения,
 * отственно за отдачу картинок в произвольных разрешениях).
 * 
 *   Помимо собственно функций обрезки содержит удобный во многих случаях метод showInFormat(),
 * производящий обрезку изображения и его отдачу по заданногй ссылке в браузер "на лету"
 * (для этого вам просто нужно вызвать метод данного класса).
 * 
 * @todo можно добавить опцию, чтобы складывать изменённые версии в отдельную папку -- это облегчало бы перенос сайтов, 
 * а также позволяло бы без критических потерь очищать диск в случае нехватки места.
 * 
 * @author Eugene Ivkov <ghostichek@gmail.com> (автор идеи и основной реализации)
 */
class ImageResizer
{
    /**
     * @var bool Позволяет выставить заголовок с датой последнего изменения файла,
     *           что в свою очередь даёт браузеру возможность не загружать картинку повторно
     */
    public static $allowBrowserImagesCache = true;
    
    /**
     * Показывать ли рыбы, вместо отсутствующих изображений
     * @var bool 
     */
    public static $usePlaceholderIfFileNotexists = true;
    
    /**
     * @see использование массива по сути бессмысленно, если скрипт инициллизирован для обработки одиночной картинки, тем не менее пусть пока будет.
     * 
     * Массив открытых временных файлов --
     * подразумевается. что они будут использощваться для рыб, 
     * загруженных вместо ненайденных избражений.
     * В качестве ключа используется формат,
     * внутри же каэдого эелмента подмассив из двух значений:
     * link => // храним дескриптор, чтобы файл не был удалён (он может понадобится много раз за один проход скрипта, нет смысла создавать его каждый раз заново, если нужна рыба этого фората)
     * path     => // путь к временному файлу.
     * @var array
     */
    protected static $tmpFiles = array();
    
    /**
     * Отдаст картинку браузеру как фалй (выставив необходимые заголовки), 
     * в случае наличия формата, будет создана копия изображения (нужны права на запись), 
     * преобразованная в соответсвтии с данным форматом (изменение размера).
     * 
     * В случае, если $imageFilePath не существует, будет использована картинрка-рыба,
     * которая также поддаётся обратке по формату, преобразованная комия кладётся во временный php файл
     * (т.е. не требуются права на запись в конкретные папки проекта).
     * 
     * @param string $imageFilePath  реальный полный путь к файлу изображения
     * @param string $format     строка формата, см. документацию
     * @param boolean $usePlaceholderIfFileNotexists  использовать ли картинку-рыбу в случае отсутствия файла (по умолчанию включено -- true), или бросать исключение-преджупреждение об отсутствии файла.
     * @return null   т.к. производит отдачу файла в поток вывода  
     * @throws \Exception
     */
    public static function showInFormat($imageFilePath, $format = '', $usePlaceholderIfFileNotexists = true)
    {
        $usePlaceHolder = false;
        if (!is_file($imageFilePath) || !file_exists($imageFilePath)) {   
            if (self::$usePlaceholderIfFileNotexists) {
                self::responePlaceholderNotModifiedIfNeed();
                $imageFilePath = self::getRandomDefaultImage();
                $usePlaceHolder = true;
            } else {
                throw new \Exception('Source Image file not found!');
            }
        }
        
        if (!$format) { // в случае если формат не указан
            if (!$usePlaceHolder) { // и при этом файл существует (и не предполгается использование замены-рыбы)
                return self::ShowImage($imageFilePath, $usePlaceHolder); // отдаём как есть
            } else {
                $format = 'origin';
            }
        }
        
        $maybeAlreadyExistsFile = self::getFormatVersionName($imageFilePath, $format, $usePlaceHolder);
        if ($maybeAlreadyExistsFile 
                && is_file($maybeAlreadyExistsFile)) {
            return self::ShowImage($maybeAlreadyExistsFile );  // отдаём, раз эта веррсия уже создана
        }

        $newImagePath = self::copyAndGetPath($imageFilePath, $format, $usePlaceHolder);
        if ($format != 'origin') {
            self::resizeAsInFormat($newImagePath, $format);    
        }
        
        return self::ShowImage($newImagePath, $usePlaceHolder); 
    }
    
    /**
     * Вернёт гипотетический путь к отформатированный версии картинк (такой какой он будет при копировании, но без реального копирования) 
     * или если речь идёт о временном файле, созданном во время этого запуска скрипта -- то временный путь.
     * Если же таких путей не обнаружено -- вернёт false/
     * Метод нужен для использоватния в проверке существования файла @todo можно исопльзовать при переоперделении правила хранения отформатированных версий
     * 
     * @param srting $imageFilePath путь к исходной картинке
     * @param srting $subdirName   имя поддиректории для гипотетическогос сохранения к копии
     * @param bool $tempFile     признак того, что речь идёт о временном файле
     * @return string|false false в случае отсутствия предположения о возможном пути (по сути происходит в случае, когда подразумевается работа с временным файлом который ещё не был создан - а значит его не было в массиве)
     */
    protected static function getFormatVersionName($imageFilePath, $subdirName, $tempFile = false) 
    {
        $newPath = false;
        if (!$tempFile) {
            $pathInfo = pathinfo($imageFilePath);            
            $newPath = $pathInfo['dirname'] . DIRECTORY_SEPARATOR 
                . $subdirName . DIRECTORY_SEPARATOR . $pathInfo['basename'];
            
        } else {
            if (isset(self::$tmpFiles[$subdirName]['path'])) {
                $newPath = self::$tmpFiles[$subdirName]['path'];
            }
        }
        
        return $newPath;
    }
 
    
    /**
     * Изображение будет либо скопировано в обычную папку, либо во временную,
     * для временных файлов, дескрипторы и пути будут сохранены в статическом поле данного класса
     * 
     * @param string $imageFilePath    путь к исходной картинке
     * @param string $subFolder        имя подпапки
     * @param bool $copyInTempFolder   Признак того,
     *                             что надо копировать во временную папку, так-как копия лежит 
     *                              в vendor-e, а туда писать нежелательно т.к. скорее всего неб удет прав)
     * @return string           путь к копии
     */
    protected  static function copyAndGetPath($imageFilePath, $subFolder, $copyInTempFolder)
    {
        if (!$copyInTempFolder) { // Зададим новый путь для картинки, параллельно скопировав её  
            $newImagePath = self::CopyImage($imageFilePath, $subFolder);
        } else {
           $descriptor = TempFile::copy($imageFilePath);
           self::$tmpFiles[$subFolder]['link'] = $descriptor;
           $newImagePath = TempFile::getPath($descriptor);
           self::$tmpFiles[$subFolder]['path'] = $newImagePath;
        }
        
        return $newImagePath;
    }
    
    /**
     * Обрежет картинку в соответствии с выбранным форматом
     * 
     * @param string $imagePath путь к картинке
     * @param string $format    строка формата -- описаие см в FormatStringParser::getParams()
     */
    public static function resizeAsInFormat($imagePath, $format)
    {
        $imgParams = FormatStringParser::getParams($format);
 
        if ($imgParams['strong']) { // определяем способ обрезки
            self::resizeStrong($imagePath, $imgParams['width'], 
                $imgParams['height'], $imgParams['position'], 
                $imgParams['proportionalOnlyWithResolution']);
        } else {
            self::resize($imagePath, $imgParams['width'],
                $imgParams['height']);
        }
    }
    
    /**
     * Cкопирует картинку, лежащую по адресу $imagePath в подпапку $subdirName, 
     * лежащую в той же директории, что и сама картинка.
     * Вернёт путь к копии.
     * 
     * @param string $imagePath   полный путь к копируемой картинке
     * @param string $subdirName  имяподпапки
     * @return string             путь к копии
     * @throws \Exception
     */
    protected static function CopyImage($imagePath, $subdirName)
    {
        $path = pathinfo($imagePath);
        

        $newPath = $path['dirname'] . "/" . $subdirName;

        if(!is_dir($newPath)) {
            if(!mkdir($newPath, 0777, true)) {
                throw new \Exception('Не удалось скопировать изображение');
            }
        }
        $newImage = $newPath . "/" . $path['basename'];

        if(!copy($imagePath, $newImage)) {
            throw new \Exception('Error: Cannot copy image!');
        }
        
        return $newImage;
    }
    
        /**
     * Получит случайный файл из некоторой диреткории (рыбу картинки)
     * 
     * @param string $randomDir  путь к директории, откуда нужно взять случайный файл
     * @return string            путь к случайному файлу из этой директории
     */
    protected static function getRandomDefaultImage()
    {
        $randomDir = dirname(__FILE__) . '/placeholders';
        $files     = glob($randomDir . '/*.*');
        $file      = array_rand($files);
//        var_dump($files[$file]); die();

        return $files[$file];
    }
    
    
    /**
     * Отдаст файл с установкой соответствующих заголовоков
     * 
     * @param int $image   путь к картинке
     * @param bool $isTempFile   является ли файл временным
     */
    protected static function showImage($image, $isTempFile = false)
    {
       // var_dump( $isTempFile); die();
        $info = getImageSize($image);

        if ($isTempFile) {
            $lastModifyDate = date(DATE_RFC822, strtotime("1 Semptember 2011")); // временные файл всегда создаются только что, но выставим им старую дату, иначе кеширование браузером одного и тоже будет невомзожно
            touch($image, strtotime("1 Semptember 2011")); // меняем мета-дату файла
        } else {
           $lastModifyDate =  date(DATE_RFC822, filemtime($image));
        }
        
        header("Content-Type: " . $info['mime']);
        header("Last-Modified: " . $lastModifyDate);
        header("Cache-Control: private, max-age=10800, pre-check=10800");
        header("Pragma: private");
        header("Expires: " . date(DATE_RFC822, strtotime(" 2 day")));

        if (self::$allowBrowserImagesCache) {
            if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && // не передаём дважды уже переданные файлы
                (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($image)))
            {
                // send the last mod time of the file back
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image)) . ' GMT', true, 304);
                exit;
            }
        }

        readfile($image);
    }
    
    /**
     * Ответит, что ресурс бл измене давно (фиксировнная дата в прошлом), если срди заголовоком запроса 
     * есть "IF_MODIFIED_SINCE"
     * 
     * Подразумевается, что эта функция вызывается для картинки-заметеля (если запрашиваемый файл не найден на диске, а 
     * картинк-заменитель иже была загружен браузером.).
     * 
     * @see Если первый раз при загрузке произодёт ошибка уже после выставленных заголовоков файла, то неправильный ответ может закешироваться,
     * но по-идее таких проблемы при развороте пакета быть не должно.
     * 
     */
    protected static function responePlaceholderNotModifiedIfNeed()
    {
        if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE'])) {
            header('Last-Modified: ' . date(DATE_RFC822, strtotime("1 Semptember 2011")), true, 304);
            exit;
        }
    }


    /**
     * Пропорционально уменьшает размеры изображения
     *
     * @param string $imageFilePath Адрес файла с изображением
     * @param int $maxWidth  Максимальная ширина уменьшенного изображения
     * @param int $maxHeight Максимальная высота уменьшенного изображения
     */ 
    public static function resize( $imageFilePath, $maxWidth, $maxHeight)
    {
        /**
         * Провеяем файл на существование
         */
        if(!file_exists($imageFilePath)) throw new \Exception("Файл " . $imageFilePath . " не найден");

        /**
         * Проверяем, чтобы указанный файл был изображением
         */
        if(!($imageInfo = @getimagesize($imageFilePath))) throw new \Exception("Файл " . $imageFilePath . " не является картинкой");

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
            
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($imageFilePath);
                break;

            default:
                throw new \Exception("Данный тип файла не поддерживается");
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
                
                case IMAGETYPE_BMP:
                    imagebmp($imageFilePath);
                    break;

                default:
                    throw new \Exception("Данный тип файла не поддерживается");
                    break;
            }
        }
    }

    /**
     * Размеры выходного изображения будут в точности ("strong") равны заданным размерам
     *
     * @param string $imageFilePath
     * @param int $maxWidth
     * @param int $maxHeight
     * @param int $position позиция окна обрезки на изображении 0 - центр, 1 - сверху 2 - снизу
     * @param type $proportionalOnlyWithResolution  по-умлочанию false, если true, то  $maxWidth и $maxHeight 
     *                      воспринимаются просто как отношения сторон, а не как реальные размеры,
     *                      реальное же разрешение выбирается максимально доступным,
     *                      но пропорциональным данным значениям.
     * @throws Exception
     */
    public static function resizeStrong($imageFilePath, $maxWidth, $maxHeight, 
        $position = 0, $proportionalOnlyWithResolution = false)
    {

        if(!file_exists($imageFilePath)) throw new \Exception("Файл " . $imageFilePath . " не найден");

        if(!($imageInfo = getimagesize($imageFilePath))) throw new \Exception("Файл " . $imageFilePath . " не является картинкой");

        if ($proportionalOnlyWithResolution)
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
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($imageFilePath);
                break;

            default:
                throw new \Exception("Данный тип файла не поддерживается");
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
                
                case IMAGETYPE_BMP:
                    imagebmp($imageFilePath);
                    break;

                default:
                    throw new \Exception("Данный тип файла не поддерживается");
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
     * @todo Необходимо уточнить назначение и подправить документацию
     * 
     * @param string $imageFilePath
     * @param type $x1
     * @param type $y1
     * @param type $x2
     * @param type $y2
     * @param type $width
     * @param type $height
     * @return string
     * @throws \Exception
     */
    public static function ImageCrop($imageFilePath, $x1, $y1, $x2, $y2, $width, $height)
    {
        if($imageFilePath{0} == "/") $imageFilePath = IncPaths::$ROOT_PATH . $imageFilePath;

        if(!file_exists($imageFilePath)) throw new \Exception("Файл " . $imageFilePath . " не найден");

        if(!($imageInfo = getimagesize($imageFilePath))) throw new \Exception("Файл " . $imageFilePath . " не является картинкой");

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
            case IMAGETYPE_BMP:
                $image = imagecreatefrombmp($imageFilePath);
                break;

            default:
                throw new \Exception("Данный тип файла не поддерживается");
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
                throw new \Exception("Данный тип файла не поддерживается");
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
}
