<?php

ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';


$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);

$url = "http://www.lz13.cn";
// 经典语录、名人名言、励志名言
$r = db::get_one("select * from spider_lz WHERE channel_link='http://www.lz13.cn/lizhi/mingrenmingyan.html'");

for ($i=207; $i<=207; $i++)
{
    echo $i . ' start ';
    $url = "http://www.lz13.cn/lizhi/mingrenmingyan-{$i}.html";
    $list = requests::get($url);
    $list = selector::select($list, "//div[contains(@class, 'PostHead')]/span/h3");

    if (!empty($list))
    {
        foreach ($list as $item)
        {
            $list_url = selector::select($item, "//a/@href");
            $tag = selector::select($item, "//a");
            $content = requests::get($list_url);
            $content = selector::remove($content, "//strong");
            $content = selector::remove($content, "//font");
            $content = selector::select($content, "//div[contains(@class, 'PostContent')]/p");
            if (!is_array($content))
            {
                $content = preg_replace('/（<a.*?）/is', '', $content);
                $content = preg_replace('/<a.*?>/is', '', $content);
                $content = str_replace('</a>', '', $content);
                $content = strip_tags($content);
                $content = explode('　　　　', $content);
                foreach ($content as $k=>$con)
                {
                    if (empty($con)) {
                        unset($content[$k]);
                        continue;
                    }
                    $content[$k] = preg_replace('/.*?\d+、/is', '', $con);
                }
            }
            else
            {
                $content = array_values($content);
                foreach ($content as $k=>$con)
                {
                    if (empty($con)) {
                        unset($content[$k]);
                        continue;
                    }
                    $con = preg_replace('/（<a.*?）/is', '', $con);
                    $con = preg_replace('/\(.*?\)/is', '', $con);
                    $con = preg_replace('/<a.*?>/is', '', $con);
                    $con = str_replace('</a>', '', $con);
                    $con = strip_tags($con);
                    $content[$k] = preg_replace('/.*?\d+、/is', '', $con);
                }
            }
            unset($content[0]);
            $content = array_values($content);
            foreach ($content as $con)
            {
                $arr = explode('——', $con);
                $con = $arr[0];
                $author = isset($arr[1])?$arr[1]:'';

                $insert[] = array(
                    'channel_name' => $r['channel_name'],
                    'channel_link' => $r['channel_link'],
                    'channel_list_url'=> $url,
                    'url' => $list_url,
                    'tags' => $tag,
                    'content' => $con,
                    'author' => $author?:'',
                    'add_time' => time(),
                    'hash'=> md5($con)
                );
            }

            db::insert_batch("spider_lz", $insert);
        }
    }

    echo "end \r\n\r";
}




