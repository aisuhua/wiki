<?php

$client_id = 'e395fdbbe24f0a12177c';
$client_secret = '499364389b65fea85e6296bfb6bac33e46c77e8e';
$authorization_code = $_GET['code'];
$url = 'https://github.com/login/oauth/access_token';

$data = array(
    'client_id' => $client_id,
    'client_secret' => $client_secret,
    'code' => $authorization_code
);

$options = array(
    'http' => array(
        'header'  => "Content-type: application/json\r\n",
        'method'  => 'POST',
        'content' => json_encode($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);
var_dump($result);