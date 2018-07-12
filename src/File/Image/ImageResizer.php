<?php

namespace \ItForFree\rusphp\File\Image;

/**
 * Класс для изменение размеров (обрезки) изображений
 * (версия которая режет картинку по центру)\
 * 
 * Помимо собственно функций обрезки содержит удобную во многих случаях showInFormat
 */
class ImageResizer
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
     * 
     * @param  $imageFilePath
     * @param type $format
     */
    public static function showInFormat($imageFilePath, $format = '')
    {
        if (!$format) {
            // надо проверить есть ли вообще такой файл!
            return self::ShowImage($imageFilePath); // отдаём как есть
        }
        
      // Определим параметры обрезки, разбрав стоку формата
        $size     = explode("x", $format);
        $width    = $size[0];
        $height   = $size[1];
        $strong   = (isset($size[2]) && strtolower($size[2]) == 's') ? true : false;
        /**
        * b - снизу
        * t - сверху
        * другое/отсутствие - центр
        */
        $position = (isset($size[3]) && $size[3] == 'b') ? 2 : 
            ((isset($size[3]) && $size[3] == 't') ? 1 : 0);
        
      // Зададим новый путь дял картинки, параллельно скопировав её  
        $newImagePath = self::CopyImage($imageFilePath, $format);
        
        if ($strong) { // определяем способ обрезки
            self::StrongImageResize($newImagePath, $width, $height, $position);
        } else {
            self::ImageResize($newImagePath, $width, $height);
        }
        
        return $this->ShowImage($newImagePath);
        
    }
    
    /**
     * Cкопирует картинку, лежащую по адресу $imagePath в подпапку $subdirName, 
     * лежащую в той же директории, что и сама картинка.
     * Вернёт путь к копии.
     * 
     * @param string $imagePath   полный путь к копируемой картинке
     * @param string $subdirName  имяподпапки
     * @return string             путь к копии
     * @throws Exception
     */
    private static function CopyImage($imagePath, $subdirName)
    {
        $path    = pathinfo($imagePath);
        $newPath = $path['dirname'] . "/" . $size;
        if(!is_dir($newPath))
        {
            if(!mkdir($newPath, 0777, true))
            {
                throw new Exception('');
            }
        }
        $newImage = $newPath . "/" . $path['basename'];

        if(!copy($imagePath, $newImage))
        {
            throw new Exception();
        }
        return $newImage;
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

        if($this->allowBrowserImagesCache)
        {
            if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && // не передаём дважды уже переданные файлы
                (strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == filemtime($image)))
            {
                // send the last mod time of the file back
                header('Last-Modified: ' . gmdate('D, d M Y H:i:s', filemtime($image)) . ' GMT', true, 304);
                exit;
            }
        }

        readfile($image);

        return new EmptyActionResult();
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

        if (self::$proportionalResize)
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



}
