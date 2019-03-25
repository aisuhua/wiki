<?php

$file = $_GET['file'];
$expires = $_GET['expires'];
$ua = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
$speed = $_GET['speed'];
$user_id = $_GET['user_id'];
$hash = $_GET['hash'];

// 验证 hash 是否正确（含 UA 校验）
$key = '123456';
$hash_real = md5($key . "{$expires}{$file}{$ua}{$speed}{$user_id}");

if ($hash !== $hash_real) {
    // return http_response_code(403);
}

// 验证时间是否过期
if ($expires < time()) {
    return http_response_code(410);
}

// 验证用户是否登录
function check_login() {
    // $_COOKIE
    return true;
}

if (!check_login()) {
    return http_response_code(403);
}

// 验证用户是否有权限下载
// 如需验证权限可在生成下载地址时传递更多的业务字段
function check_privilege() {
    return true;
}

if (!check_privilege()) {
    return http_response_code(403);
}

header('Content-type: application/octet-stream');
header('Content-Disposition: attachment; filename="' . basename($file) . '"'); // 自定义文件名
header('Cache-Control: no-cache');
header("X-Accel-Limit-Rate: {$speed}"); // 限速
header("X-Accel-Redirect: /download/{$file}");
