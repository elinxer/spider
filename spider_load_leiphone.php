<?php

ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';


//$url = 'http://www.leiphone.com/';
$url = 'http://www.leiphone.com/site/AjaxLoad/page/1';
$html = requests::get($url);

$result = json_decode($html, true);

$html = $result['html'];
$html = str_replace('&#13;', '', $html);

$arr = selector::select($html, "//li");

$news = [];
foreach ($arr as $k => $item)
{
    $item = str_replace('&#13;', '', $item);

    $title = selector::select($item, "//div[contains(@class, 'word')]/h3/a");
    $title = str_replace("\r\n", '', $title);
    $title = trim($title);

    $link = selector::select($item, "//div[contains(@class, 'word')]/h3/a/@href");

    $img = selector::select($item, "//div[contains(@class, 'img')]/a/img/@data-original");

    $tags = selector::select($item, "//div[contains(@class, 'tags')]/a");

    $author = selector::select($item, "//div[contains(@class, 'word')]/div/a");
    $author = trim(strip_tags($author));

    $author_avater = selector::select($item, "//div[contains(@class, 'word')]/div/a/img/@src");

    if (is_array($tags))
    {
        $tags = implode(',', $tags);
    }

    $content_html = requests::get($link);

    $intro = selector::select($content_html, "//div[contains(@class, 'article-lead')]");
    $intro = trim(str_replace('导语：', '', strip_tags($intro)));

    $author_intro = '';

    $released_at = selector::select($content_html, "//*[@class='time']");
    $released_at = trim($released_at);

    $content = selector::select($content_html, "//div[contains(@class, 'article-left')]/div[1]");
    $content = trim($content);

    $news[] = [
        'hash' => md5('leiphone'.$link),
        'title' => $title,
        'link'  => $link,
        'cover' => $img,
        'img_original' => current(explode('?image', $img)),
        'tags' => $tags,
        'author'=> $author,
        'author_intro' => $author_intro,
        'author_avater'=> $author_avater,
        'content' => $content,
        'released_at' => $released_at,
        'intro' => $intro,
        'created_at' => time()
    ];

    break;
}

print_r($news);
