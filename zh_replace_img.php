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


$result = db::get_all("SELECT * FROM zhiteer.article_content WHERE done=0 LIMIT 0,100");

foreach ($result as $k=> $article)
{
    $html = $article['post_content'];
    $html = selector::remove($html, "//noscript");

    $img_links  = selector::select($html, "//img/@data-original");

    if (empty($img_links))
    {
        //print_r($html);
        //db::update('zhiteer.article_content', array('done'=>2));
        continue;
    }

    if (!is_array($img_links))
    {
        $links = array($img_links);
    }

    $tokens_arr = array();
    $img_arr    = array();

    if (!empty($links))
    { // 获取图片库
        $img_links = array_values(array_unique($links));
        $img_insert_arr = array();
        foreach ($links as $link)
        {
            $token = md5($link);
            $link  = preg_replace("#pic\d+#is", 'pic', $link);
            $link  = preg_replace("#_.*?jpg#is", '.jpg', $link);
            $link  = preg_replace("#_.*?png#is", '.png', $link);
            $link  = preg_replace("#_.*?gif#is", '.gif', $link);

            $tokens_arr[] = "'".$token."'";
            $where = "token='{$token}' and url!=''";

            $sql = "select * from spider.spider_images WHERE {$where}";
            $img_re = db::get_one($sql);

            if(!empty($img_re))
            {
                $img_arr[] = $img_re;
            }
        }
    }

    if((count($tokens_arr)) != (count($img_arr)))
    { // 图片数据校对
        echo $k .'___';
        echo count($tokens_arr);
        echo '<=>'.count($img_arr);
        echo "该篇文章id:({$article['post_id']})图片未下载完成<br>";
        continue;
    }

    $img_links = array();
    foreach ($img_arr as $r)
    {
        $img_links[$r['token']] = $r['url'];
    }

    $pregRule  = "/<img.*?src=\"(.*?)\".*?>/is";
    $content   = preg_replace_callback( $pregRule, function ($matches) use ($img_links)
        {
            $origin_img = selector::select($matches[0], "//img/@data-original");

            $link     = $origin_img;
            $token    = md5($link);
            $img_link = $img_links[$token];

            if(!empty($img_link)) {
                $img_link = "http://pic.zhiteer.com" . $img_link;
                return str_replace($matches[1], $img_link, $matches[0]);
            }

            if (strpos($link, '/face/') !== false) { //去掉表情图片
                return '';
            }

            return $matches[0];
        },
        $html
    );

    if(!empty($content))
    {
        $update = array(
            'done' => 0,
            'post_content_html' => $content,
            //'markdown_content' => $markdown_content,
        );

        $where_str = "post_id={$article['post_id']}";
        //print_r($update);die();

        db::update("zhiteer.article_content", $update, $where_str);

        echo "http://zhiteer.com/articles/{$article['post_id']}";
        die();
    }
    else {
        echo '内容为空';
    }

}