<?php

require dirname(__FILE__).'/phpspider/core/init.php';

$url = 'http://test2.zuzuche.com/_yds_/curl/request.php';
// $url = 'http://w.zuzuche.com/ssp/api/info.php?id=12b4ba28&callback=__showcaseJSONP_1482806958542_3';

//$url = 'http://w.zuzuche.com/ssp/api/info.php?id=12b4ba28&callback=__showcaseJSONP_1482907333414_3';

$url = 'https://www.zhihu.com//question/19554005';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);

$url = 'https://sp0.baidu.com/9_Q4sjW91Qh3otqbppnN2DJv/pae/channel/data/asyncqury?cb=jQuery110205355966680955171_1483691356756&appid=4001&com=yuantong&nu=123123&vcode=&token=&_=1483691356758&qq-pf-to=pcqq.group';

$ip = db::get_one("SELECT * FROM `spider_proxy_ip` WHERE protocol='https' ORDER BY RAND() LIMIT 1;");
requests::set_proxies(array('http'=>"tcp://{$ip['ip']}:{$ip['port']}"));
requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');
echo requests::get($url);

die();

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

