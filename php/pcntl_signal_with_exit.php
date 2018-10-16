<?php
/**
 * 接收到特定的型号量后退出进程
 *
 * 使用 `exit()` 退出进程后，可配合 Supervisor 等进程管理软件实现自动重启
 * 其效果跟使用 `pcntl_exec()` 一样
 * `pcntl_exec()` 重启原有的进程 PID 不变，而退出后再重启是全新的进程 PID 会不一样。
 *
 * @link http://php.net/manual/en/function.pcntl-signal.php#92803
 */

$sig_handler = function ($signo) {
    echo $signo, PHP_EOL;

    exit();
};

pcntl_signal(SIGTERM, $sig_handler); // kill
pcntl_signal(SIGHUP, $sig_handler); // kill -s HUP or kill -1
pcntl_signal(SIGINT, $sig_handler); // Ctrl-C
pcntl_signal(SIGQUIT, $sig_handler); // kill -3

while(true) {
    // handle the signal at the beginning of your main loop
    pcntl_signal_dispatch();

    // do something here
    $i = 0;
    while($i++ < 10) {
        sleep(1);
    }
}