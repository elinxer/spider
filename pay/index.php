<?php
/**
 * 爬脚本之家的所有php文章
 */
//header("Content-type: text/html; charset=GBK");
ini_set("memory_limit", "2048M");
require dirname(__FILE__).'/../phpspider/core/init.php';

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');

requests::set_header('cookie', 'ALIPAYJSESSIONID=RZ12ledYdN0QYBqKDVvLagakcdWoGXauthRZ13GZ00');

requests::$input_encoding = 'GBK';
requests::$output_encoding = 'utf-8';
$r = requests::get('https://consumeprod.alipay.com/record/standard.htm');

$r = selector::remove($r, "//script");
preg_match_all('#<table class="ui-record-table table-index-bill" id="tradeRecordsIndex" width="100%">(.*)</table>#iUs',$r, $arr);

$html = $arr[0][0];
$arr = selector::select($html, "//tr");

foreach ($arr as $k=> $item)
{
    if ($k==0) continue;
    $tr = $item;
    $tdArr = selector::select($tr, "//td/[2]");

    print_r($tdArr);
}

