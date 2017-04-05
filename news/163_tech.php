<?php
/**
 * 爬取网易网智能栏目数据
 * 2017-03-11
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/../phpspider/core/init.php';

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
$url = "http://tech.163.com/smart/";
$html = requests::get($url);


$arr = selector::select($html, "//a/@href");
if (empty($arr)) {
    echo "list is empty";die();
}
foreach ($arr as $k => $item)
{
    if (preg_match("#http://tech.163.com/\d{2}/\d{4}/\d{2}.*?#iUs", $item) ==false)
    {
        unset($arr[$k]);continue;
    }
    $arr[$k] = $item;
}
$arr = array_values($arr);

$num = 0;
foreach ($arr as $link)
{
    $hash = md5('tech.163.com/smart'.$link);
    if (db::get_one("select * from zhiteer.spider_news WHERE hash='{$hash}'")) {
        continue;
    }
    $content_html = requests::get($link);
    $article_wrap = selector::select($content_html, "//div[contains(@class, 'post_content_main')]");
    if (is_array($article_wrap)) {
        $article_wrap = current($article_wrap);
    }
    $title = selector::select($article_wrap, "//h1");
    $title = trim($title);

    $released_at = selector::select($article_wrap, "//div[@class='post_time_source']");
    $released_at = preg_match("#\d{4}-\d{2}-\d{2}#iUs", $released_at);
    $released_at = strtotime($released_at);
    if (empty($released_at)) {
        $released_at = time();
    }
    $content = selector::select($article_wrap, "//div[contains(@class, 'post_text')]");
    $content = trim($content);
    $content = selector::remove($content, "//iframe");

    $cover = selector::select($content, "//img[1]/@src");
    if (is_array($cover)) {
        $cover = current($cover);
    }
    $intro = '';
    if (empty($intro) && !empty($content)) {
        $intro = substr(strip_tags($content), 0, 400);
    }

    $news = [
        'hash' => $hash,
        'title' => $title,
        'link'  => $link,
        'cover' => $cover,
        'cover_original' => current(explode('?image', $cover)),
        'tags' => '人工智能',
        'author_name'=> '网易智能',
        'author_intro' => '',
        'author_avater'=> 'http://img2.cache.netease.com/f2e/tech/smart2017/images/logo.gif?768',
        'content' => $content,
        'released_at' => $released_at,
        'intro' => $intro,
        'channel' => 'tech.163.com/smart',
        'created_at' => time()
    ];

    //print_r($news);die();
    db::insert("spider_news", $news);

    echo $num++;
    echo " loaded done\r\n";
}
