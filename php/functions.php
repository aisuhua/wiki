<?php
/**
 * 检查运行中的脚本，以及其所包含的文件是否发生修改
 *
 * 只用于 PHP CLI
 * 参数 `$period` 的作用是为了防止检测过于频繁
 *
 * @param int $period 时间间隔 (<0:随机1秒到-$period之间; =0:每次; >0:距离上次$period秒后)
 * @param bool $passive 只要发生文件变化立即返回结果，即只返回一个变化的文件信息
 * @return array 返回发生变化的文件信息，没有文件变化时返回空数组
 */
function get_changed_files($period = 1, $passive = true)
{
    $changed_files = [];
    static $files = [];
    static $last_time = 0;

    if ($period < 0)
    {
        $period = rand(1, -$period);
    }

    if (0 < $period && time() - $last_time < $period)
    {
        return $changed_files;
    }

    $last_time = time();

    // 获取本程序所加载的所有文件
    $fs = get_included_files();

    foreach ($fs as $f)
    {
        // 清理文件的元数据缓存
        clearstatcache(true, $f);
        $size = filesize($f);
        $time = filemtime($f);

        // 缓存首次加载的文件信息
        if (!isset($files[$f]))
        {
            $files[$f] = [
                'size' => $size,
                'time' => $time
            ];
            continue;
        }

        if ($files[$f]['size'] != $size || $files[$f]['time'] != $time)
        {
            $changed_files[$f] = [
                'size' => $size,
                'time' => $time
            ];

            if ($passive)
            {
                break;
            }
        }
    }

    return $changed_files;
}

/**
 * 文件大小单位转换
 *
 * @param int $bytes
 * @param int $length
 * @param string $max_unit
 * @return string
 *
 * Example:
 * ```code
 * echo format_size(2184689650);
 * ```
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