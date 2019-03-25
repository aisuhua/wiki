<?php

$host = 'http://download.example.com';
$uri = '/download/nginx-1.14.1.tar.gz';
$key = '123456';
$expires = time() + 3600;
$md5_hash = str_replace('=', '', strtr(base64_encode(md5("{$key}{$expires}{$uri}", true)), '+/', '-_'));

$download_url = "{$host}{$uri}?md5={$md5_hash}&expires={$expires}";
echo $download_url, PHP_EOL;