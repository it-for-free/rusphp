<?php

namespace ItForFree\rusphp\Common\Map;

use ItForFree\rusphp\Common\Map\MapSettings;

/**
 * Для конфигурирования онлайн-карты,
 * в частности получения центра и зума по набору переданных  координат
 *
 * Ключевые слова: координаты, геомертия, среднее арифметическое, центр прямоугольника,
 *  яндекс карты, гугл карты
 * 
 *  @author Eugene Ivkov <ghostichek@gmail.com> (автор идеи и основной реализации)
 */
class MapConfigurator {
    
    /**
     *
     * @var array $coords массив координат вида:
        [
            [55.8853, 37.4737]
            [56.8853, 38.4737]
            [54.8853, 38.4737]
        ]   
     * 
     */
    protected $coords;
    
    /**
     *
     * @var array массив кординат, по которым можно построить прямоугольник,
     *  внутри которого лежит набор координат передаваемый в качестве исходного
     *  для рассчета в разные методы этого класса.
     *  по факту  это: массив максимальных и минимальных координат для каждой оси
     *  (исходя из него считаем как минимум зам и центр), вида:
     *  [ 
            'xMax' => $coords[0][0],
            'xMin' => $coords[0][0],
            'yMax' => $coords[0][1],
            'yMin' => $coords[0][1],
        ];
     */
    protected $parentRectangularCoords = [];


    /**
     * @param array $coords массив координат вида аналогичного документации поля $this->coords
     */
    public function __construct($coords) {
        $this->coords = $coords;
        $this->setParentRectangularCoords($coords);
    }
    
    /**
     * Пересчитает координаты прямоугольника $this->parentRectangularCoords, охватывающего все переданные координаты
     * 
     * @param array $coords массив координат вида аналогичного документации поля $this->coords
     */
    public function setParentRectangularCoords($coords)
    {
        $parentAreaCoords = [ 
            'xMax' => $coords[0][0],
            'xMin' => $coords[0][0],
            'yMax' => $coords[0][1],
            'yMin' => $coords[0][1],
        ];
        
        foreach ($coords as $pair) {
            if ($parentAreaCoords['xMin'] > $pair[0]) {
                $parentAreaCoords['xMin'] = $pair[0];
            } else if ($parentAreaCoords['xMax'] < $pair[0]) {
                $parentAreaCoords['xMax'] = $pair[0];
            }
            
            if ($parentAreaCoords['yMin'] > $pair[1]) {
                $parentAreaCoords['yMin'] = $pair[1];
            } else if ($parentAreaCoords['yMax'] < $pair[1]) {
                $parentAreaCoords['yMax'] = $pair[1];
            }
        }
        
        $this->parentRectangularCoords =  $parentAreaCoords;
    }
    
    /**
     * Вернёт массив со всеми доступными настройками,
     * в частности координаты центра карты и значение приближения (zoom)
     * 
     * @param  bool $addCoords если true, то добавит в массив и переданные
     *         в класс координаты, на базе которых производится рассчет 
     *         остального как элемент 'coords' => []
     * @return \ItForFree\rusphp\Common\Map\MapSettings
     */
    public function getAllSettings($addCoords = false)
    {
        
        $settings = new MapSettings();
        $settings->center = $this->getCenter();
        $settings->zoom = $this->getZoom();
        
        if ($addCoords) {
            $settings->coords = $this->coords;
        }
        
        return $settings;
    }

    /**
     * Координаты центра карты
     * 
     * @return array массив чисел (пара) вида
     *      [55.8853, 37.4737]
     */
    public function getCenter()
    {
        $arr = $this->parentRectangularCoords;
        
        $cx = preg_replace("/\,/",'.', strval(($arr['xMax'] + $arr['xMin']) / 2));
        $cy = preg_replace("/\,/",'.', strval(($arr['yMax'] + $arr['yMin']) / 2));
        
        return [$cx + 0, $cy  + 0];
    }

    /**
     * Значение зума (приближения) карты
     * 
     * @return int
     */
    private function getZoom()
    {
        $arr = $this->parentRectangularCoords;
        
        $dx = $arr['xMax'] - $arr['xMin'];
        $dy = $arr['yMax'] - $arr['yMin'];
        $xzoom = 4;
        if ($dx <= 0.001) $xzoom = 19;
            elseif($dx <= 0.010) $xzoom = 14;
            elseif($dx <= 0.100) $xzoom = 11;
            elseif($dx <= 1.000) $xzoom = 8;
            elseif($dx <= 20.000) $xzoom = 5;
            else $xzoom = 4;
        $yzoom = 4;
        if ($dy <= 0.001) $yzoom = 19;
            elseif($dy <= 0.010) $yzoom = 16;
            elseif($dy <= 0.100) $yzoom = 12;
            elseif($dy <= 1.000) $yzoom = 10;
            elseif($dy <= 20.000) $yzoom = 7;
            else $yzoom = 4;
        $zoom = max($xzoom,$yzoom);
        
        return $zoom;
    }
    
    
    /**
     * Конвертирует координаты (из обычного массива в формат для гугл карт)
     * 
     * @todo (count($pointsCoords) > 2) скорее всего нелогично. проверить.
     * 
     * @param array $pointsCoords массив или массив массивов координат
     * @return array
     */
    public static function convertCoordsForGoogleMaps($pointsCoords) 
    {
        $result = null;
        if (is_array($pointsCoords) && (count($pointsCoords) > 2)) { // если число элементов больше двух, то считаем что это массив массиов
            foreach ($pointsCoords as $key => $pCoords) {
                $result[$key] = ['lat' => (float) $pCoords[0],
                'lng' => (float) $pCoords[1]];
            }
            return $result;
        }
        else {
            $result = ['lat' => (float) $pointsCoords[0],
                'lng' => (float) $pointsCoords[1]];
            return $result;
        }
    }
}
