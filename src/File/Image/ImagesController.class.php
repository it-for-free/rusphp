<?php

/**
 * @deprecated Просто для ознакомления со старой логикой
 * 
 * Возвращает изображение заданного размера
 * формат запроса
 * /getimage/[ID]/[ширина]x[высота]x[S]x[позиция обрезанного участка]/
 * Обязательные поля:
 *  ID - значение imageID в таблице greeny_images
 *  Ширина
 *  Высота
 * Необязятельные поля:
 *  S - strong - образание области заданного размера из исходного изображения. Если параметр отсутствует изображение пропорционально сжимается по большей стороне
 *  Позиция - T,B,C - позиционирование обрезанного участка по большей стороне. T - сверху(слева), B - снизу(справа), C(или отсутствует) - по центру
 *
 *
 * @author Prog
 * @version 2.1
 * @requires DB v2.1
 */
class ImagesController 
{
    /**
     * @var bool Позволяет выставить заголовок с датой последнего изменения файла,
     *           что в свою очередь даёт браузеру возможность не загружать картинку повторно
     */
    public $allowBrowserImagesCache = true;

    /**
     * Вычленяет ширину изображения из строки параметров изображения
     * (например, из строки 257_121_s_b)
     * 
     * @param string $imageParameters
     * 
     * @return string
     */
    public function getImageWidth(string $imageParameters): string
    {
        $arrayImageParameters = explode('_', $imageParameters);
        
        $imageWidth = $arrayImageParameters[0];
        
        if (empty($imageWidth) || !is_numeric($imageWidth)) {
            throw InvalidArgumentException('Ширина изобаржения не передана');
        }
        
        return $imageWidth;
    }
    
    /**
     * Вычленяет высоту изображения из строки параметров изображения
     * (например, из строки 257_121_s_b)
     * Формат ответа 
     * 
     * @param string $imageParameters
     * 
     * @return string
     */
    public function getImageHeight(string $imageParameters): string
    {
        $arrayImageParameters = explode('_', $imageParameters);
        
        $imageHeight = $arrayImageParameters[1];
        
        if (empty($imageHeight) || !is_numeric($imageHeight)) {
            throw InvalidArgumentException('Высота изобаржения не передана');
        }
        
        return $imageHeight;
    }
    
    /**
     * Вычленяет строгий ли размер изображения из строки параметров изображения 
     * (например, из строки 257_121_s_b)
     * 
     * @param string $imageParameters
     * 
     * @return bool
     */
    public function isImageSizeStrong(string $imageParameters): bool
    {
        $arrayImageParameters = explode('_', $imageParameters);
        
        $isStrong = $arrayImageParameters[2];
        
        return (isset($isStrong) && strtolower($isStrong) == 's') ? true : false;
    }
    
    /**
     * Вычленяет позицию изображения из строки параметров изображения 
     * (например, из строки 257_121_s_b)
     * 
     * @param string $imageParameters
     * 
     * @return string
     */
    public function getImagePosition(string $imageParameters): string
    {
        $arrayImageParameters = explode('_', $imageParameters);
        
        switch ($arrayImageParameters[3]):
            case 't':
                $imagePosition = 'top';
                break;
            case 'b':
                $imagePosition = 'bottom';
                break;
            default :
                $imagePosition = 'center';
        endswitch;
        
        return $imagePosition;
    }
    
    /**
     * 
     * @param string $imagePath
     * 
     * @return string
     */
    public function getImage(string $imagePath): string
    {
        // здесь функционал поиска изобаржения по пути
        // например, с использовнием Doctrine и хранением пути к файлу в БД?
        
        return $image;
    }
    
    /**
     * Основной метод, выводящий изображение
     * 
     * @param string $imagePath
     * 
     * @return string
     */
    public function showImage(string $imageParameters, string $imagePath): string
    {
        if ($this->isImageSizeStrong($imageParameters)) {
            // делаем стронг обрезку и позиционирование, созраняем новый ыайл и его отдаём
        } else {
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

            return $image; 
        }
    }
    
    public function Index($args = null)
    {
        $args[0] = str_replace('_', '', $args[0]); // нужно для пустых imageID, чтобы $args[0] вообще существовал (см. документацию BP)
        if(!isset($args[0]) || !is_numeric($args[0]))
        {
            // throw new PageNotFoundException("");
            $imageID = '';
        }
        else
        {
            $imageID = $args[0];
        }

        try
        {
            $imageData = self::GetImagePath($imageID);
            $image     = $imageData['src'];
            $pos       = isset($imageData['position']) ? $imageData['position'] : 0;
            $size      = isset($args[1]) ? $args[1] : false;
            if(!$size)
            {
                return $this->ShowImage($image);
            }

            $path     = pathinfo($image);
            $newPath  = $path['dirname'] . "/" . $size;
            $newImage = str_replace("//", "/", $newPath . "/" . $path['basename']);
            if(is_file($newImage))
            {
                return $this->ShowImage($newImage);
            }

            if(!isset($size[0]) || !is_numeric($size[0]) || !isset($size[1]) || !is_numeric($size[1]))
            {
                return $this->ShowImage($image);
            }
            $newImage = $this->CopyImage($image, $size);

            $size     = explode("x", $size);
            $width    = $size[0];
            $height   = $size[1];
            $strong   = (isset($size[2]) && strtolower($size[2]) == 's') ? true : false;
            /**
             * b - снизу
             * t - сверху
             * другое/отсутствие - центр
             */
            $position = (isset($size[3]) && $size[3] == 'b') ? 2 : ((isset($size[3]) && $size[3] == 't') ? 1 : $pos);
//			die($position);

            $newImage = str_replace(IncPaths::$ROOT_PATH, '', $newImage);
            if($newImage{0} != '/') $newImage = '/' . $newImage;

            if($strong)
            {
                ImageManager::StrongImageResize($newImage, $width, $height, $position);
            }
            else
            {
                ImageManager::ImageResize($newImage, $width, $height);
            }
            $newImage = preg_replace('/^\//', IncPaths::$ROOT_PATH, $newImage);
            return $this->ShowImage($newImage);
        }
        catch(Exception $ex)
        {
            die($ex->getMessage());
        }
    }

    /**
     * Возвращает путь к изображению по ИД, если нет такого или ИД не указан возвращает рандомную заглушку
     * @param int $imageID
     * @return array(
     *  src ->> путь к картинке
     *  position -> тип обрезания
     * )
     */
    public static function GetImagePath($imageID)
    {
        $noImage = array('src' => self::randomDefaultImage(), 'position' => 0);
        //echo $noImage['src']; die();
        $query   = "SELECT src,position FROM greeny_images WHERE imageID=:imageID";
        if(empty($imageID) || !is_numeric($imageID))
        {
            return $noImage;
        }

        if(!DB::CheckField('greeny_images', 'position')) $query = "SELECT src,0 as position FROM greeny_images WHERE imageID=:imageID";

        $imageFilePath = DB::QueryOneRecordToArrayEx($query, array("imageID" => array($imageID, "int")));
        if(!empty($imageFilePath['src']) && $imageFilePath['src']{0} == "/")
        {
            $imageFilePath['src'] = IncPaths::$ROOT_PATH . UTF8::substr($imageFilePath['src'], 1);
        }

        if(empty($imageFilePath) || empty($imageFilePath['src']) || !is_file($imageFilePath['src']))
        {
            return $noImage;
        }
        
       // echo $imageFilePath['src']; die();

        return $imageFilePath;
    }

    /**
     * Получит случайный файл из некоторой диреткории (рыбу картинки)
     * 
     * @param string $randomDir  путь к директории, откуда нужно взять случайный файл
     * @return string            путь к случайному файлу из этой директории
     */
    public static function randomDefaultImage($randomDir = '/uploaded/rybi')
    {
        $randomDir = IncPaths::$ROOT_PATH . $randomDir;
        $files     = glob($randomDir . '/*.*');
        $file      = array_rand($files);

        return $files[$file];
    }

    private function ShowImage($image)
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

    private function CopyImage($image, $size)
    {
        $path    = pathinfo($image);
        $newPath = $path['dirname'] . "/" . $size;
        if(!is_dir($newPath))
        {
            if(!mkdir($newPath, 0777, true))
            {
                throw new Exception();
            }
        }
        $newImage = $newPath . "/" . $path['basename'];

        if(!copy($image, $newImage))
        {
            throw new Exception();
        }
        return $newImage;
    }

    public static function CheckSize($imageID, $sizes)
    {
        try
        {
            $image = self::GetImagePath($imageID);

//			if($image{0} == "/")
//				$image = IncPaths::$ROOT_PATH . $image;

            if(!is_file($image) || !($imageInfo = getimagesize($image)))
            {
//				FB::log($image, "image");
//				FB::log("1", "image error");
                return false;
            }
//			FB::log($imageInfo, "imageInfo");
            $size        = explode("x", $sizes);
            $width       = $size[0];
            $height      = $size[1];
            $imageWidth  = $imageInfo[0];
            $imageHeight = $imageInfo[1];

            if($imageWidth <= $width || $imageHeight <= $height)
            {
                return false;
            }
            return true;
        }
        catch(Exception $ex)
        {
//			FB::log("0", "image error");
            return false;
        }
    }

    // проверим встречается ли подстрока в строке
    public static function is_in_str($str, $substr)
    {
        $result = strpos($str, $substr);
        if($result === FALSE) // если это действительно FALSE, а не ноль, например
            return false;
        else return true;
    }

}
