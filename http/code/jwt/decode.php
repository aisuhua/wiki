<?php

$jwt = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyX2lkIjoxMjN9.NYlecdiqVuRg0XkWvjFvpLvglmfR1ZT7f8HeDDEoSx8';
list($base64UrlHeader, $base64UrlPayload, $base64UrlSignature) = explode('.', $jwt);

// Check signature
$signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
$base64UrlSignatureCheck = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
if ($base64UrlSignature !== $base64UrlSignatureCheck)
{
    exit('Invalid signature.');
}

$header = base64_decode(str_pad(
    str_replace(['-', '_'], ['+', '/'], $base64UrlHeader),
    strlen($base64UrlHeader) % 4,
    '=',
STR_PAD_RIGHT
));

$payload = base64_decode(str_pad(
    str_replace(['-', '_'], ['+', '/'], $base64UrlPayload),
    strlen($base64UrlPayload) % 4,
    '=',
    STR_PAD_RIGHT
));

// {"typ":"JWT","alg":"HS256"}
echo $header, PHP_EOL;

// {"user_id":123}
echo $header, PHP_EOL;



