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
$result = db::get_all("SELECT * FROM zhiteer.article_content WHERE done=0 LIMIT 0,1000");

if (empty($result)) die('空');

foreach ($result as $k=> $article)
{
    $html = $article['post_content'];
    $html = selector::remove($html, "//noscript");

    //更好a标签链接
    $html = preg_replace_callback("/<a.*?href=\"\/\/(.*?)\".*?>/is", function ($match)
        {
            $arr  = explode('target=', $match[1]);
            if (isset($arr[1]))
            {
                $link = urldecode($arr[1]);
            }
            else {
                $link = $match[1];
            }
            return "<a href='{$link}' target='_blank'>";
    }, $html);

    $img_links  = selector::select($html, "//img/@data-original");

    if (!is_array($img_links) && !empty($img_links))
    {
        $img_links = array($img_links);
    }

    if (empty($img_links))
    {
        //print_r($html);
        $no = db::update('zhiteer.article_content', array('done'=>2), "post_id={$article['post_id']}");
        echo('2=>'.$no . ': '.$article['post_id'].'<br>');

    }

    $tokens_arr = array();
    $img_arr    = array();

    if (!empty($img_links))
    { // 获取图片库
        $img_links = array_values(array_unique($img_links));
        $img_insert_arr = array();
        foreach ($img_links as $link)
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

            if (strpos($link, 'v2-')!==false) {
                $link = explode('/v2-', $link);
                $source_url = "http://zhihu-pics.img-cn-beijing.aliyuncs.com/v2-" . $link[1];
                $token  = md5($source_url);
                $where  = "token='{$token}' and url!=''";
                $sql    = "select * from spider.spider_images WHERE {$where}";
                $img_re = db::get_one($sql);
            }

            if(!empty($img_re))
            {
                $img_arr[] = $img_re;
            }
            else {
                $img_insert_arr = array(
                    'token'      => $token,
                    'source_url' => $link,
                    'channel'    => 'zh',
                    'add_time'   => time(),
                );
                //db::insert('spider.spider_images', $img_insert_arr);
            }
        }
    }

    if((count($tokens_arr)) != (count($img_arr)))
    { // 图片数据校对
        //print_r($img_links);
        echo $k .'___';
        echo count($tokens_arr);
        echo '<=>'.count($img_arr);
        echo "post_id:({$article['post_id']})图片未下载完成<br>";
        continue;
    }

    $tmp = array();
    foreach ($img_arr as $r)
    {
        $tmp[$r['token']] = $r['url'];
    }
    $img_links = $tmp;

    $pregRule  = "/<img.*?src=\"(.*?)\".*?>/is";
    $content   = preg_replace_callback( $pregRule, function ($matches) use ($img_links)
        {
            $origin_img = selector::select($matches[0], "//img/@data-original");
            if (empty($origin_img))
            {
                $origin_img = selector::select($matches[0], "//img/@data-actualsrc");
            }

            if (strpos($origin_img, 'v2-')!==false) {
                $link = explode('/v2-', $origin_img);
                $origin_img = "http://zhihu-pics.img-cn-beijing.aliyuncs.com/v2-" . $link[1];
            }

            $link     = $origin_img;
            $token    = md5($link);
            $img_link = isset($img_links[$token])?$img_links[$token]:'';

            if (empty($img_link)) return '';
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
            'done' => 1,
            'post_content_html' => $content,
            //'markdown_content' => $markdown_content,
        );

        $where_str = "post_id={$article['post_id']}";
        //print_r($update);die();

        $r = db::update("zhiteer.article_content", $update, $where_str);

        echo($r.'=>'.$article['post_id'].'<br>');

        //echo "http://zhiteer.com/articles/{$article['post_id']}";

    }
    else {
        echo '内容为空';
    }

}