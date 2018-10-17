<?php
/**
 * 当文件发生变化时，使用 `pcntl_exec` 重新运行
 * 当接收到结束信号量时，把当前剩下的工作完成后，使用 `exit` 退出进程
 * 当进程工作时间超过 10 秒，则重新运行
 * 当进程内存占用大小超过 50M，则重新运行
 * 该进程可委托 Supervisor 进行管理，可实现因意外退出后自动重启
 */
require (__DIR__ . '/../functions.php');

// 处理信号量
$signals = [
    SIGTERM => 'SIGTERM',
    SIGHUP  => 'SIGHUP',
    SIGINT  => 'SIGINT',
    SIGQUIT => 'SIGQUIT',
];

$sig_handler = function ($signo) use ($signals)
{
    echo sprintf(
        '%s>> %s: %d, signal handler called, PID-%d exit peacefully.' . PHP_EOL,
        date('Y-m-d H:i:s'),
        isset($signals[$signo]) ? $signals[$signo] : 'Unknown',
        $signo,
        posix_getpid()
    );

    exit();
};

pcntl_signal(SIGTERM, $sig_handler); // kill
pcntl_signal(SIGHUP, $sig_handler); // kill -s HUP or kill -1
pcntl_signal(SIGINT, $sig_handler); // Ctrl-C
pcntl_signal(SIGQUIT, $sig_handler); // kill -3

// start from here
echo 'start...', PHP_EOL;
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
            echo sprintf(
                '%s>> %s was modified at %s(%s), PID-%d rerun automatically.' . PHP_EOL,
                date('Y-m-d H:i:s'),
                basename($file_path),
                date('Y-m-d H:i:s', $file_info['time']),
                $file_info['size'],
                posix_getpid()
            );
        }

        rerun_process();
    }

    // 检查工作时间是否超过 10 秒
    if (time() - $start_time >= 10)
    {
        echo sprintf(
            '%s>> Process has been running for 10s, PID-%d rerun automatically.' . PHP_EOL,
            date('Y-m-d H:i:s'),
            posix_getpid()
        );

        rerun_process();
    }

    // 检查内存占用是否超过 50M
    if(memory_get_usage(true) > 50 * 1024 * 1024)
    {
        echo sprintf(
            '%s>> Process has been running for 10s, PID-%d rerun automatically.' . PHP_EOL,
            date('Y-m-d H:i:s'),
            posix_getpid()
        );

        rerun_process();
    }

    // 在这里开始进行业务处理

    $i = 0;
    while($i++ < 3)
    {
        echo $i, PHP_EOL;
        sleep(1);
    }

    // 输出每次循环后的状态信息
    echo sprintf(
        '%s>> memory %s, uptime %s.' . PHP_EOL,
        date('Y-m-d H:i:s'),
        format_size(memory_get_usage(true)),
        format_seconds(time() - $start_time)
    );
}

/**
 * 重新运行进程
 */
function rerun_process()
{
    pcntl_exec('/usr/bin/php', $_SERVER['argv']);
}