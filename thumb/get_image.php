<?php
$file = $_GET['file'];
$protocol = 'https';
$host = 'raw.githubusercontent.com';
$uri = "aisuhua/upload-demo/master/basic/uploads/{$file}";
$args = '';

header("X-Accel-Redirect: /internal_redirect/{$protocol}/{$host}/{$uri}{$args}");