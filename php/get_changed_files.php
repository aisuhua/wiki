<?php
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

// test
while(true)
{
    $changed_files = get_changed_files();
    if($changed_files)
    {
        // 当有文件发生变化时，退出程序
        print_r($changed_files);
        exit();
    }

    sleep(5);
}