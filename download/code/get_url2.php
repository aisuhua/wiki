<?php

$host = 'http://download.example.com';
$key = '123456';
$expires = time() + 3600;
$file = 'myfile';
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$user_id = '10086'; // Get from login state
$speed = 200 * 1024; // 100KB

$hash = md5($key . "{$expires}{$file}{$ua}{$speed}{$user_id}");

$download_url = "{$host}/download.php?file={$file}&expires={$expires}&speed={$speed}&user_id={$user_id}&hash={$hash}";
echo $download_url, PHP_EOL;
