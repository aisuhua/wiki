<?php

$access_token = '900524e32f3959794d2efac7a592e0d908302c34';
$url = 'https://api.github.com/user?access_token=' . $access_token;

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_USERAGENT, 'aisuhua');
curl_setopt($ch, CURLOPT_HEADER, [
    'Accept: application/json'
]);

$result = curl_exec($ch);
var_dump($result);