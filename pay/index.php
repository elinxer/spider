<?php
/**
 * 爬脚本之家的所有php文章
 */
//header("Content-type: text/html; charset=GBK");
ini_set("memory_limit", "2048M");
require dirname(__FILE__).'/../phpspider/core/init.php';

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');

requests::set_header('cookie', 'ALIPAYJSESSIONID=RZ12ledYdN0QYBqKDVvLagakcdWoGXauthRZ13GZ00');

$r = requests::get('https://consumeprod.alipay.com/record/standard.htm');
requests::$input_encoding = 'GBK';
$arr = selector::select($r, "//tr");



//print_r($arr);
print_r($r);
