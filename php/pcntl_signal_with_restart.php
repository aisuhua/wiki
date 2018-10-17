<?php
/**
 * 接收到特定的信号量后进行重启
 *
 * 使用 `pcntl_exec()` 在现有进程上重启，从而实现进程“永不退出”
 * 重启后的进程已经包含了程序的最新源代码
 *
 * !!! 不建议在处理 Supervisor stop/restart 信号量时使用该方法
 *
 * @link https://stackoverflow.com/questions/9798438/automatically-restart-php-script-on-exit
 * @link http://php.net/manual/en/function.pcntl-signal.php#92803
 */

echo ++$argv[1], PHP_EOL;
$_ = $_SERVER['_'];

$sig_handler = function ($signo) use($argv, $_) {
    echo $signo, PHP_EOL;

    // restart myself
    pcntl_exec($_, $argv);
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