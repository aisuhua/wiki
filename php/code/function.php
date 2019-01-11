<?php

/**
 * To get the memory usage in KB or MB
 * @param $size
 * @return string
 *
 * @link http://php.net/manual/zh/function.memory-get-usage.php#96280
 */
function convert($size)
{
    $unit=array('b','kb','mb','gb','tb','pb');
    return @round($size/pow(1024,($i=floor(log($size,1024)))),2).' '.$unit[$i];
}

echo convert(memory_get_usage(true)); // 123 kb

/**
 * 文件大小单位转换
 *
 * @param int $bytes
 * @param int $length
 * @param string $max_unit
 * @return string
 */
function format_size($bytes, $length = 2, $max_unit = '')
{
    $max_unit = strtoupper($max_unit);
    $unit = array('B', 'KB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB', 'DB', 'NB');
    $extension = $unit[0];
    $max = count($unit);
    for ($i = 1; (($i < $max) && ($bytes >= 1024) && $max_unit != $unit[$i - 1]); $i++)
    {
        $bytes /= 1024;
        $extension = $unit[$i];
    }
    return round($bytes, $length) . $extension;
}

echo format_size(2184689650);

/**
 *  将时长格式化为：hours:minutes:seconds
 *
 * @param int $seconds
 * @param string $delimiter
 * @return string
 */
function format_seconds($seconds, $delimiter = ':')
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $seconds = $seconds % 60;
    return "{$hours}{$delimiter}{$minutes}{$delimiter}{$seconds}";
}

