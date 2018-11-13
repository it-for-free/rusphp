<?php
namespace ItForFree\rusphp\Common\Directory;

/**
 * Для работы с директориями (папками)
 * (clear folder directory -- delete all files)
 */
class Directory {
   
    /**
     * Удалит всё из директории (полностью очистит её)
     * 
     * @param string $path путь к папкет
     */
    public static function clear($path)
    {   
        if (is_dir($path)) {
            array_map('unlink', array_filter((array) glob("$path*")));
        }
    }
    
}
