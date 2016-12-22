<?php

$url = 'http://test2.zuzuche.com/_yds_/curl/request.php';


ini_set("memory_limit", "1024M");

set_time_limit(600);

error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';


$auth =	base64_encode('654753115@qq.com:a654753115');

$context = array(
    'http' => array(
        'proxy' => 'tcp://124.88.67.52:843',
        'request_fulluri' => true,
        ),
    );
$context = stream_context_create($context);

$html    = file_get_contents($url, false, $context);

print_r(json_decode($html, true));



