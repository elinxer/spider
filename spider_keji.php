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
$cat_tbl = "spider.spider_keji_cate";


// 首发论文

$url = 'http://www.paper.edu.cn/releasepaper';

$list = requests::get($url);

$list = selector::select($list, "//div[contains(@class, 'xkbj')]/ul/li");

foreach ($list as $item)
{
    if (!empty($item))
    {
        $url = selector::select($item, '//a/@href');
        $url = 'http://www.paper.edu.cn' . $url;
        $con = requests::get($url);
        $title = selector::select($con, "//div[contains(@class, 'sf_mbxdh')]");
        $title = explode('&gt;&gt;', $title);
        if (isset($title[2]))
        {
            $title = $title[2];
            $hash = md5('0'.$title);
            $arr[] = array(
                'title'=>$title,
                'url' => $url,
                'pid' => 0,
                'num' => 0,
                'hash' => $hash,
                'add_time' => time()
            );
        }
    }
}

echo db::insert_batch($cat_tbl, $arr);

//print_r($arr);




die();


$plist = db::get_all("select * from {$cat_tbl} WHERE  1");

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
