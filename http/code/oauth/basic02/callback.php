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
        'header'  => array(
            "Accept: application/json",
            "Content-type: application/json"
        ),
        'method'  => 'POST',
        'content' => json_encode($data)
    )
);

$context  = stream_context_create($options);
$result = file_get_contents($url, false, $context);

// Output: {"access_token":"679ad23c56658b1af2a1464081d3dd4686dc2fdd","token_type":"bearer","scope":"user:email"}
var_dump($result);

// Access Token
$access_token = json_decode($result)->access_token;

// Get the user info
$url = 'https://api.github.com/user';
$options = array(
    'http'=> array(
        'method'=> 'GET',
        'header'  => array(
            "Authorization: token {$access_token}",
            "Accept: application/json",
            "User-Agent: aisuhua"
        ),
    )
);

$context = stream_context_create($options);
$result = file_get_contents($url, false, $context);
var_dump($result);

$email = json_decode($result)->email;
var_dump($email);
