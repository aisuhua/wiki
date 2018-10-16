<?php
/**
 * 当文件发生变化时，使用 `pcntl_exec` 重启进程
 * 当接收到结束信号量时，把当前剩下的工作完成后，使用 `exit` 退出进程
 * 当进程工作时间超过 10 秒，则重启进程
 * 当进程内存占用大小超过 50M，则重启进程
 * 该进程委托 Supervisor 进行管理，实现退出后自动重启该进程
 */

/**
 * 检查运行中的脚本，以及其所包含的文件是否发生修改
 *
 * 只用于 PHP CLI
 *
 * @param bool $passive 只要发生文件变化立即返回结果，即只返回一个变化的文件信息
 * @return array
 */
function get_changed_files($passive = true)
{
    $changed_files = [];
    static $files = [];
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

// 处理信号量
$signals = [
    SIGTERM => 'SIGTERM',
    SIGHUP  => 'SIGHUP',
    SIGINT  => 'SIGINT',
    SIGQUIT => 'SIGQUIT',
];

$sig_handler = function ($signo) use ($signals) {
    $sigtype = isset($signals[$signo]) ? $signals[$signo] : 'UNKNOWN';
    echo "{$sigtype}: {$signo}, signal handler called, peacefully exit.", PHP_EOL;
    exit();
};

pcntl_signal(SIGTERM, $sig_handler); // kill
pcntl_signal(SIGHUP, $sig_handler); // kill -s HUP or kill -1
pcntl_signal(SIGINT, $sig_handler); // Ctrl-C
pcntl_signal(SIGQUIT, $sig_handler); // kill -3

// start from here
echo '=== start ===', PHP_EOL;
$start_time = time();

while(true)
{
    // 检查是否有信号量需要处理
    pcntl_signal_dispatch();

    // 检查是否有文件发生变化
    $changed_files = get_changed_files();
    if($changed_files)
    {
        foreach ($changed_files as $file_path => $file_info)
        {
            $pid = posix_getpid();
            echo sprintf(
                "[%s] was modified at %s(%s), PID-{$pid} rerun automatically." . PHP_EOL,
                basename($file_path),
                date('Y-m-d H:i:s', $file_info['time']),
                $file_info['size']
            );
        }

        // 重新运行进程
        pcntl_exec('/usr/bin/php', $argv);
    }

    // 检查工作时间是否超过 10 秒
    if (time() - $start_time >= 10)
    {
        $pid = posix_getpid();
        echo "Process has been running for 10s, PID-{$pid} rerun automatically.", PHP_EOL;

        // 重新运行进程
        pcntl_exec('/usr/bin/php', $argv);
    }

    // 检查内存占用是否超过 50M
    if(memory_get_usage(true) > 50 * 1024 * 1024)
    {
        $pid = posix_getpid();
        echo "Process out of Memory 50 MB, PID-{$pid} rerun automatically.", PHP_EOL;

        // 重新运行进程
        pcntl_exec('/usr/bin/php', $argv);
    }

    echo '--- loop ---', PHP_EOL;

    $i = 0;
    while($i++ < 3) {
        echo $i, PHP_EOL;
        sleep(1);
    }

    echo 'memory_usage ' . memory_get_usage(true), ', running_time ', time() - $start_time, PHP_EOL;
}
