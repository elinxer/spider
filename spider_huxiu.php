<?php
/**
 * 爬取虎嗅网数据
 * 2017-03-11
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');

$url = "https://www.huxiu.com/";

$html = requests::get($url);

$arr = selector::select($html, "//a/@href");
if (empty($arr)) {
    echo "list is empty";die();
}
foreach ($arr as $k => $item)
{
    if (strpos($item, '/article/') === false)
    {
        unset($arr[$k]);continue;
    }
    $arr[$k] = $url . $item;
}
$arr = array_values($arr);
$num = 0;
foreach ($arr as $link)
{
    $hash = md5('huxiu'.$link);
    if (db::get_one("select * from zhiteer.spider_news WHERE hash='{$hash}'")) {
        continue;
    }
    $content_html = requests::get($link);
    $article_wrap = selector::select($content_html, "//div[contains(@class, 'article-wrap')]");
    if (is_array($article_wrap)) {
        $article_wrap = current($article_wrap);
    }
    $title = selector::select($article_wrap, "//h1");
    $title = trim($title);

    $tags = selector::select($article_wrap, "//div[contains(@class, 'column-link-box')]/a");
    if (is_array($tags)) {
        $tags = implode(",", $tags);
    }

    $wrap_right = selector::select($content_html, "//div[contains(@class, 'wrap-right')]");
    $author_html = selector::select($wrap_right, "//div[contains(@class, 'box-author-info')]");

    $author_name = selector::select($author_html, "//div[contains(@class, 'author-name')]/a[1]");
    $author_name = trim(strip_tags($author_name));
    $author_avater = selector::select($author_html, "//div[contains(@class, 'author-face')]/a/img/@src");

    $cover = selector::select($article_wrap, "//div[contains(@class, 'article-img-box')]/img/@src");
    $released_at = selector::select($article_wrap, "//span[@class='article-time pull-left']");
    $released_at = strtotime($released_at);

    $content = selector::select($content_html, "//div[contains(@class, 'article-content-wrap')]");
    $content = trim($content);
    $content = selector::remove($content, "//iframe");

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
        'tags' => $tags,
        'author_name'=> $author_name,
        'author_intro' => '',
        'author_avater'=> $author_avater,
        'content' => $content,
        'released_at' => $released_at,
        'intro' => $intro,
        'channel' => 'huxiu',
        'created_at' => time()
    ];

    //print_r($news);die();
    db::insert("spider_news", $news);

    echo $num++;
    echo " loaded done\r\n";
}

//print_r($arr);

