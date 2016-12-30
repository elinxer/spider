<?php

$url = 'http://test2.zuzuche.com/_yds_/curl/request.php';
// $url = 'http://w.zuzuche.com/ssp/api/info.php?id=12b4ba28&callback=__showcaseJSONP_1482806958542_3';

//$url = 'http://w.zuzuche.com/ssp/api/info.php?id=12b4ba28&callback=__showcaseJSONP_1482907333414_3';

$url = 'https://www.zhihu.com//question/19554005';

ini_set("memory_limit", "1024M");

set_time_limit(600);

error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';


//$auth =	base64_encode('654753115@qq.com:a654753115');

 $context = array(
      'https' => array(
          	'proxy' => 'tcp://218.29.111.106:9999',
          	'request_fulluri' => true,
          ),
//     'http' => array(
//         	'proxy' => 'tcp://60.222.221.62:443',
//         	'request_fulluri' => true,
//         ),
 );

 $context = stream_context_create($context);

 echo $html    = file_get_contents($url, false, $context);

die();
//requests::set_client_ip('124.88.67.52:843');

//$proxies = array(
//    'http' => 'http://124.88.67.52:843',
//    //'https' => 'http://user:pass@host:port',
//);
$proxies = array(
    'http' => 'http://121.14.6.236:80',
    //'https' => 'http://user:pass@host:port',
);

requests::set_proxies($proxies);

echo requests::get($url);

