<?php

ini_set("memory_limit", "2048M");

set_time_limit(0);

error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';
require dirname(__FILE__).'/phpspider/core/db.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);


$answer = db::get_one("SELECT 	* FROM 	spider.`zh_question_answer`  LEFT JOIN zh_question_list on zh_question_list.question_code=zh_question_answer.question_code LIMIT 1;");
$author = db::get_one("SELECT * FROM zhiteer.`user_author` WHERE author_cn_name='{$answer['answer_author_name']}' limit 1");

if (empty($author))
{ // 更新作者
    $author = array(
        'author_cn_name' => $answer['answer_author_name'],
        'author_en_name' => $answer['answer_author_code'],
        'author_desc'    => $answer['answer_author_desc'],
        'author_avater'  => str_replace('_s.jpg', '_xl.jpg', $answer['answer_author_avater']),
        'author_tags'    => $answer['answer_author_a_desc'],
    );
    $author['author_id'] = db::insert('zhiteer.user_author', $author);
}

$html = selector::select($answer['answer_html_content'], "//div[contains(@class, 'zm-editable-content')]");


if (!empty($html))
{ // 数据清洗
    $html = str_replace('<i class="icon-external"/>', '', $html);
    $html = str_replace('<u>', '', $html);
    $html = str_replace('</u>', '', $html);

    $html = preg_replace ( "#<br/><br/>#is","<br/>", $html );
    $html = preg_replace ( "#<br/><br/><br/>#is","<br/><br/>", $html );
    $html = preg_replace ( "#<br/><br/><br/><br/>#is","<br/><br/>", $html );
}

$image_links = selector::select($html, "//img/@data-original");


if (!empty($image_links))
{ // 图片入库
    $image_links = array_values(array_unique($image_links));
    $img_insert_arr = array();
    foreach ($image_links as $k => $link)
    {
        $token = md5($link);
        $link  = preg_replace("#pic\d+#is", 'pic', $link);
        $link  = preg_replace("#_.*?.jpg#is", '.jpg', $link);

        $img_insert_arr = array(
            'token'      => $token,
            'source_url' => $link,
            'channel'    => 'zhihu',
            'add_time'   => time(),
        );
        db::insert('spider.spider_images', $img_insert_arr);
    }
}

if (!empty($html))
{ // 新增文章
    $article = array(
        'post_title'  => $answer['question_title'],
        'post_author' => $author['author_id'],
        'post_publish_time' => $answer['answer_publish_time'],
        'post_comefrom' => 'zhihu'
    );
    //$post_id = db::insert('zhiteer.article_list', $article);

    if (!empty($post_id))
    {
        //db::insert('zhiteer.article_content', array('post_id' => $post_id,'post_content'=>$html));
    }

}

print_r($html);

//print_r($answer);
