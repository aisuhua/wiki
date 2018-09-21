文件大小格式化

```php
/**
 * 文件大小单位转换
 *
 * @param int $bytes
 * @param int $length
 * @param string $max_unit
 * @return string
 */
function size_format($bytes, $length = 2, $max_unit = '')
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

// Example:
echo size_format(2184689650);
// Output: 
// 2.03GB
```

格式化时长

```php
/**
 *  将时长格式化为：x小时x分钟x秒
 *
 * @param int $seconds
 * @return string
 */
function format_seconds($seconds) 
{
    $hours = floor($seconds / 3600);
    $minutes = floor(($seconds / 60) % 60);
    $seconds = $seconds % 60;

    return "{$hours}小时{$minutes}分钟{$seconds}秒";
}

// Example:
echo format_seconds(5461);
// Output: 
// 1小时31分钟1秒
```

