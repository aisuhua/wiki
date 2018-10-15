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

检测文件是否被修改，并自动重启或退出进程

```php
/**
 * 检测文件修改
 *
 * @param int $period 时间间隔(<0:随机1秒到-$period之间; =0:每次; >0:距离上次$period秒后)
 * @param int $delay  延迟时间(<0:随机1秒到-$delay之间; =0:不延迟; >0:延迟$delay秒)
 * @param bool $rerun 是否重新运行
 */
function check_files($period = 60, $delay = 10, $rerun = true)
{
    if (PHP_SAPI != 'cli')
    {
        return;
    }

    static $last_time = 0;
    static $files = array();

    if ($period < 0)
    {
        $period = rand(1, -$period);
    }

    if (0 < $period && time() - $last_time < $period)
    {
        return;
    }

    $last_time = time();
    $fs = get_included_files();

    foreach ($fs as $f)
    {
        clearstatcache(true, $f);
        $size = filesize($f);
        $time = filemtime($f);

        if (!isset($files[$f]))
        {
            $files[$f] = array('size' => $size, 'time' => $time);
        }
        elseif ($files[$f]['size'] != $size || $files[$f]['time'] != $time)
        {
            if ($delay < 0)
            {
                $delay = rand(1, -$delay);
            }

            $pid = posix_getpid();
            echo sprintf("[%s] was modified at %s(%s), PID-{$pid} will exit/rerun after {$delay} seconds." . PHP_EOL,
                basename($f), date('Y-m-d H:i:s', $time), $size);

            if (0 < $delay)
            {
                sleep($delay);
            }

            if ($rerun)
            {
                restart_process();
            }

            exit();
        }
    }
}

/**
 * 重启 cli 下的 php 进程
 */
function restart_process()
{
    if (PHP_SAPI != 'cli' || !function_exists('pcntl_exec') || !isset($_SERVER['argv']))
    {
        return;
    }

    // Supervisor 托管时 $_SERVER['_'] 不存在
    // 执行 php demo.php 时 $_SERVER['_'] = '/usr/bin/php'，不会执行 pcntl_exec
    // 执行 nohup php demo02.php > files/content 2>&1 &，$_SERVER['_'] = '/usr/bin/nohup'

    // 如果用 nohup 启动的 PHP 进程 $_SERVER['_'] 是 /usr/bin/nohup
    // 需要找到 PHP 命令路径覆盖 $_SERVER['_']
    if (!isset($_SERVER['_']) ||
        !in_array(basename($_SERVER['_']), array('php', 'php-cli')))
    {
        $paths = explode(':', $_SERVER['PATH']);
        foreach ($paths as $path)
        {
            if (is_file("{$path}/php"))
            {
                $_SERVER['_'] = "{$path}/php";
                break;
            }
            else if (file_exists("{$path}/php-cli"))
            {
                $_SERVER['_'] = "{$path}/php-cli";
                break;
            }
        }

        pcntl_exec($_SERVER['_'], $_SERVER['argv']);
    }
}

// Example:
require ('files/a.php');
require ('files/b.php');

while(true)
{
    // the third argument rerun is true/false.
    check_files(1, 1, true); 
}
```

检测正在执行的文件是否发生变化

```php
<?php
require ('files/a.php');
require ('files/b.php');

$stats = [];

while(true)
{
    $files = get_included_files();

    foreach ($files as $file)
    {
        clearstatcache(true, $file);

        if(!isset($stats[$file]))
        {
            $stats[$file] = [];
            $stats[$file]['filemtime'] = filemtime($file);
            $stats[$file]['filesize'] = filesize($file);
            continue;
        }

        // 重新加载的文件信息
        $filemtime = filemtime($file);
        $filesize = filesize($file);

        if($stats[$file]['filemtime'] != $filemtime || $filesize != $stats[$file]['filesize'])
        {
            echo 'file ', $file, ' changed.', PHP_EOL;
        }

        // 重新设值
        $stats[$file]['filemtime'] = filemtime($file);
        $stats[$file]['filesize'] = filesize($file);
    }

    // 如果不停顿一下，会偶现警告错误：
    // PHP Warning:  filemtime(): stat failed for /www/web/learn/php/demo01.php in /www/web/learn/php/demo01.php on line 24
    usleep(10000);
}
```
