<?php

// http://www.paper.edu.cn/
/**
 * 中国科技论文在线 Spider
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
$keji_tbl = "spider.spider_keji";

$url_list = db::get_all("");

$url = 'http://www.paper.edu.cn/releasepaper/subject/11044/常微分方程/2/11044';
requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');
$con = requests::get($url);
$con = selector::select($con, "//div[contains(@class, 'r_two')]");
$con = selector::select($con, "//div[contains(@class, 'cmtdiv')]");

$arr = array();
if (!empty($con))
{
    foreach ($con as $k => $list)
    {
        $list = str_replace('&#13;', '', $list);

        $title = selector::select($list, "//p[1]/span/a");
        $link = selector::select($list, "//p[1]/span/a/@href");
        $author = selector::select($list, "//p[2]/span[2]");
        $hash = md5($link);
        $arr[] = array(
            'hash' => $hash,
            'title' => $title,
            'link' => $link,
            'author' => $author,
            'add_time' => time(),
        );
    }
}

echo db::insert_batch($keji_tbl, $arr);

print_r($arr);

die();
die('===========获取分类=============');

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);
$cat_tbl = "spider.spider_keji_cate";
$plist = db::get_all("select * from {$cat_tbl} WHERE pid=0 and id!=1");

$donain = 'http://www.paper.edu.cn';

$arr = array();
if ( !empty($plist) )
{
    foreach ($plist as $cat)
    {
        $content = requests::get($cat['url']);
        $result = selector::select($content, "//div[contains(@id, 'tt')]/ul/li");
        foreach ($result as $k => $item)
        {
            $pid = $cat['id'];
            $title = selector::select($item, "//a");
            $title = preg_replace('/（(.*)）/is', '|$1', $title);
            $tmp = explode('|', $title);
            $title = $tmp[0];
            $num = $tmp[1];
            $url = selector::select($item, "//a/@href");
            $arr[] = array(
                'title'=>$title,
                'url' => $donain . $url,
                'pid' => $pid,
                'num' => $num,
                'hash' => md5($pid.$title),
                'add_time' => time()
            );
        }
    }

}

echo db::insert_batch($cat_tbl, $arr);

//print_r($arr);
