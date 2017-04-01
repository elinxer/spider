<?php
/**
 * 爬取艺术中国首页资讯栏目数据
 * 2017-03-18
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
$url = "http://art.china.cn/";
$html = requests::get($url);

$arr = selector::select($html, "//a/@href");
if (empty($arr)) {
    echo "list is empty";die();
}
foreach ($arr as $k => $item)
{
    if (preg_match("#zixun/\d{4}-\d{2}/\d+/.*?#iUs", $item) ==false)
    {
        unset($arr[$k]);continue;
    }
    $arr[$k] = $url . $item;
}
if (!empty($arr)) {
    $arr = array_unique($arr);
    $arr = array_values($arr);
}

$num = 0;
foreach ($arr as $link)
{
    $hash = md5('tech.163.com/smart'.$link);
    if (db::get_one("select * from zhiteer.spider_news WHERE hash='{$hash}'")) {
        continue;
    }
    $content_html = requests::get($link);
    $article_wrap = selector::select($content_html, "//div[contains(@class, 'left')]");
    if (is_array($article_wrap)) {
        $article_wrap = current($article_wrap);
    }
    $title = selector::select($article_wrap, "//h1");
    $title = trim($title);

    $released_at = selector::select($article_wrap, "//span[@id='pubtime_baidu']");
    $released_at = trim($released_at);
    $released_at = strtotime($released_at);

    $content = selector::select($article_wrap, "//div[contains(@class, 'content')]");
    $content = trim($content);
    $content = selector::remove($content, "//iframe");

    $content = str_replace('src="../../images/', 'src="http://art.china.cn/zixun/images/', $content);

    $cover = selector::select($content, "//img[1]/@src");
    if (is_array($cover)) {
        $cover = current($cover);
    }
    $intro = '';
    if (empty($intro) && !empty($content)) {
        $intro = substr(strip_tags($content), 0, 400);
        $intro = trim($intro);
    }

    $news = [
        'hash' => $hash,
        'title' => $title,
        'link'  => $link,
        'cover' => $cover,
        'cover_original' => current(explode('?image', $cover)),
        'tags' => '艺术中国',
        'author_name'=> '艺术中国',
        'author_intro' => '',
        'author_avater'=> 'http://art.china.cn/images/2015ArtChina/logo.jpg',
        'content' => $content,
        'released_at' => $released_at,
        'intro' => $intro,
        'channel' => 'art.china',
        'created_at' => time()
    ];

    //print_r($news);die();
    db::insert("spider_news", $news);

    echo $num++;
    echo " loaded done\r\n";
}
