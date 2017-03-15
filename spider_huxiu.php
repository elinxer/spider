<?php
/**
 * 爬取图吧数据 mapbar.com
 * 2017-03-11
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');

$url = "https://www.huxiu.com/";

$html = requests::get($url);

$arr = selector::select($html, "//a/@href");

foreach ($arr as $k => $item)
{
    if (strpos($item, '/article/') === false)
    {
        unset($arr[$k]);continue;
    }
    $arr[$k] = $url . $item;
}
$arr = array_values($arr);
print_r($arr);

