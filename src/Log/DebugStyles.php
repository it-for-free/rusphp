<?php

namespace ItForFree\rusphp\Log;

/**
 * CSS и разметка для распечатки данных в браузере
 */
class DebugStyles
{
   /**
    * @var string Открывающий тег или их последовательность д вывода с оригиналньыми отступами
    */
   public static $preOpen = '<pre style="text-align: left;">';
   
   
    /**
    * @var string  Закрывающий тег или их последовательность д вывода с оригиналньыми отступам
    */
   public static $preClose = "</pre>";

   
   /**
    * @var string  xdebug Закрывающий тег или их последовательность д вывода с оригиналньыми отступам
    */
   public static $xdebugOpen = '<div style="text-align: left;">';
   
   /**
    * @var string  xdebug Закрывающий тег или их последовательность д вывода с оригиналньыми отступам
    */
   public static $xdebugClose = "</div>";
   
   
   public static $commentOpen = '<i style="background-color: #99ffcc;">';
   public static $commentClose = "</i>";
}