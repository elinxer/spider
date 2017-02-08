<?php

ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';


$url = 'http://www.leiphone.com/';
// http://www.leiphone.com/site/AjaxLoad/page/1
$html = requests::get($url);

echo $html;