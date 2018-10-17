<?php
require (__DIR__ . '/../functions.php');

while(true)
{
    $changed_files = get_changed_files(10);
    if($changed_files)
    {
        // 当有文件发生变化时，退出程序
        print_r($changed_files);
        exit();
    }
}