<?php

namespace ItForFree\rusphp\File\Image;

use ItForFree\rusphp\File\TempFile;

/**
 * Класс для изменение размеров (обрезки) изображений
 * (версия которая режет картинку по центру)\
 * 
 * Помимо собственно функций обрезки содержит удобную во многих случаях showInFormat
 */
class ImageResizer
{
    
    /**
     * @var bool Позволяет выставить заголовок с датой последнего изменения файла,
     *           что в свою очередь даёт браузеру возможность не загружать картинку повторно
     */
    public static $allowBrowserImagesCache = true;
    
    /**
     * Поддиректория общей директории загруженных файлов, в которой хранятся изображения
     *
     * @var string
     */
    private static $uploadImagesDir = "images/";


    
    public static $usePlaceholderIfFileNotexists = true;
    
    /**
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
     * 
     * @param  $imageFilePath
     * @param type $format
     */
    public static function showInFormat($imageFilePath, $format = '', $usePlaceholderIfFileNotexists = true)
    {

        $usePlaceholder = false;
        if (!file_exists($imageFilePath)) {
            if (self::$usePlaceholderIfFileNotexists) {
                $imageFilePath = self::randomDefaultImage();
                $usePlaceholder = true;
            } else {
                throw new \Exception('Source Image file not found!');
            }
        }
        
        if (!$format) {
            return self::ShowImage($imageFilePath); // отдаём как есть
        }
        
        if (!$usePlaceholder) { // Зададим новый путь для картинки, параллельно скопировав её  
            
            $newImagePath = self::CopyImage($imageFilePath, $format);
        } else {
           $descriptor = TempFile::copy($imageFilePath);
           self::$tmpFiles[$format]['link'] = $descriptor;
           $newImagePath = TempFile::getPath($descriptor);
           self::$tmpFiles[$format]['path'] = $newImagePath;
        }
        
        self::resizeAsInFormat($newImagePath, $format);    
        
        return self::ShowImage($newImagePath);
        
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
            self::StrongImageResize($imagePath, $imgParams['width'], 
                $imgParams['height'], $imgParams['position'], 
                $imgParams['proportionalOnlyWithResolution']);
        } else {
            self::ImageResize($imagePath, $imgParams['width'],
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
    private static function CopyImage($imagePath, $subdirName)
    {
        $path = pathinfo($imagePath);
        

        $newPath = $path['dirname'] . "/" . $subdirName;

        if(!is_dir($newPath)) {
            if(!mkdir($newPath, 0777, true)) {
                throw new \Exception('');
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
    public static function randomDefaultImage()
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
     * @param type $image
     * @return \EmptyActionResult
     */
    private static function ShowImage($image)
    {
        $info = getImageSize($image);

        header("Content-Type: " . $info['mime']);
        header("Last-Modified: " . date(DATE_RFC822, filemtime($image)));
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
     * Пропорционально уменьшает размеры изображения
     *
     * @param string $imageFilePath Адрес файла с изображением
     * @param int $maxWidth  Максимальная ширина уменьшенного изображения
     * @param int $maxHeight Максимальная высота уменьшенного изображения
     */ 
    public static function ImageResize( $imageFilePath, $maxWidth, $maxHeight)
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
    public static function StrongImageResize($imageFilePath, $maxWidth, $maxHeight, 
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
