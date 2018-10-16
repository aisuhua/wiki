<?php
/**
 * 在程序运行中处理信号量
 *
 * 可让后台进程平滑重启，防止程序被常见的一些型号量中断
 * 程序接收到信号量后不会马上做出处理，
 * 只有当调用 `pcntl_signal_dispatch()` 时才会触发信号量处理方法。
 * 进程在每一次循环的开始位置处理信号量，然后作出相应的动作，比如：重启或退出
 * 保证了正在执行的任务能顺利执行完成后，再执行退出或重启，避免进程中途退出。
 *
 * 若执行 `kill -9` 强制退出，则无法保证任务能完整执行
 *
 * @link http://php.net/manual/en/function.pcntl-signal.php#92803
 */

$sig_handler = function ($signo) {
    echo $signo, PHP_EOL;
};

pcntl_signal(SIGTERM, $sig_handler); // kill
pcntl_signal(SIGHUP, $sig_handler); // kill -s HUP or kill -1
pcntl_signal(SIGINT, $sig_handler); // Ctrl-C
pcntl_signal(SIGQUIT, $sig_handler); // kill -3

while(true) {
    // handle the signal at the beginning of your main loop
    pcntl_signal_dispatch();

    // do something here
}