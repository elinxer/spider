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

$donain = 'http://www.paper.edu.cn';

//requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');
//
//requests::set_referer("http://www.paper.edu.cn/html/releasepaper/2014/06/1/");
//$content = requests::get("http://www.paper.edu.cn/download/downPaper/201406-1");
//file_put_contents('12.pdf', $content);

$url_list = db::get_all("select id,num,url from spider.spider_keji_cate WHERE pid!=0 AND id!=1 AND pid!=1");

foreach ($url_list as $k => $item)
{
    for ($i=1; $i<=1000;$i++)
    {

        $page_url = $item['url'];
        $page_url = preg_replace('/-(\d+)-(\d+)-(\d+)/is', '/$1/$2/$3', $page_url);
        $page_url = str_replace('.html', '?1=1&per_page=10&order=date_down&1=1&page='.$i, $page_url);

        requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');
        $con = requests::get($page_url);
        $con = selector::select($con, "//div[contains(@class, 'r_two')]");
        $con = selector::select($con, "//div[contains(@class, 'cmtdiv')]");

        if (empty($con))
        {
            break;
        }
        $arr = array();
        if (!empty($con))
        {
            if (!is_array($con))
            {
                $con = array($con);
            }
            foreach ($con as $list)
            {
                $list = str_replace('&#13;', '', $list);
                $title = selector::select($list, "//p[1]/span/a");
                $link = selector::select($list, "//p[1]/span/a/@href");
                $author = selector::select($list, "//p[2]/span[2]");
                // ===
                $page_con = requests::get($link);
                $page_con = selector::select($page_con, "@location.href='(.*)'@", 'regex');
                $re_url = $donain . $page_con;
                $page_con = requests::get($re_url);
                $page_con = selector::select($page_con, "//div[@id='right']");
                $title_en = selector::select($page_con, "//div[contains(@class, 'cmtdiv')]/p[2]");
                $released_at = selector::select($page_con, "//div[contains(@class, 'cmtdiv')]/p[3]");
                $released_at = selector::select($released_at, "@(\d{4}-\d{2}-\d{2})@", 'regex');

                $page_con = selector::select($page_con, "//div[contains(@class, 'w794')]");

                $cn_con = selector::select($page_con, "//div[4]");
                $cn_desc = $cn_key = $en_desc = $en_key ='';
                if (!empty($cn_con))
                {
                    $cn_con = selector::select($cn_con, "@<strong>.*?</strong>(.*)<strong>.*?</strong>(.*)@is", 'regex');
                    $cn_desc = isset($cn_con[0])?$cn_con[0]:'';
                    $cn_key = isset($cn_con[1])?strip_tags($cn_con[1]):'';
                }

                $en_con = selector::select($page_con, "//div[7]");
                if (!empty($en_con))
                {
                    $en_con = selector::select($en_con, "@<strong>.*?</strong>(.*)<strong>.*?</strong>(.*)@is", 'regex');
                    $en_desc = isset($en_con[0])?$en_con[0]:'';
                    $en_key  = isset($en_con[1])?strip_tags($en_con[1]):'';
                }

                $author_desc = selector::select($page_con, "//div[9]/div");
                if (!empty($author_desc))
                {
                    $author_desc = selector::select($author_desc, "@</strong>(.*)<strong>@", 'regex');
                    $author_desc = strip_tags($author_desc);
                }

                $pdf = selector::select($page_con, "//div[8]/a/@href");

                if (is_array($pdf))
                {
                    $pdf = $pdf[1];
                }
                $pdf = $donain . $pdf;

                $hash = md5($link);
                $arr[] = array(
                    'hash' => $hash,
                    'title' => $title,
                    'link' => $link,
                    'author' => $author,
                    'add_time' => time(),
                    'pdf_link' =>$pdf?:'',
                    'author_desc' => $author_desc?:'',
                    'cn_desc' => $cn_desc?:'',
                    'cn_key' => $cn_key?:'',
                    'en_desc' => $en_desc?:'',
                    'en_key' => $en_key?:'',
                    'page_link' => $page_url,
                    'released_at' => $released_at,
                    'title_en' => $title_en?:'',
                    'refer_url' => $re_url?:''
                );

            }
        }
        echo db::insert_batch($keji_tbl, $arr);
    }
}

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
