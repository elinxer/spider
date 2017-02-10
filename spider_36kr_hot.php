<?php
/**
 * 雷锋网文章下载
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'zhiteer',
);

$url = 'http://36kr.com/';

echo $html = requests::get($url);

$arr = selector::select($html, "//div[contains(@class, 'hot_article')]/ul/li");

print_r($arr);
die();

$num = 0;

$news = [];
foreach ($arr as $k => $item)
{
    $item = str_replace('&#13;', '', $item);

    $title = selector::select($item, "//div[contains(@class, 'word')]/h3/a");
    $title = str_replace("\r\n", '', $title);
    $title = trim($title);

    $link = selector::select($item, "//div[contains(@class, 'word')]/h3/a/@href");

    $hash = md5('leiphone'.$link);

    if (db::get_one("select * from zhiteer.spider_news WHERE hash='{$hash}'"))
    {
        continue;
    }

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

    $news = [
        'hash' => $hash,
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

    db::insert("spider_news", $news);

    echo $num++;
    echo " 加载完成\r\n";
}

if ($num == 0)
{
    echo "no update!\r\n";
}

/**
 *
 * -- 表结构

DROP TABLE IF EXISTS `spider_news`;
CREATE TABLE `spider_news` (
`id` int(15) NOT NULL AUTO_INCREMENT,
`hash` varchar(100) NOT NULL,
`title` varchar(255) NOT NULL,
`link` varchar(500) NOT NULL,
`cover` varchar(500) DEFAULT NULL,
`img_original` varchar(500) DEFAULT NULL,
`tags` varchar(255) DEFAULT NULL,
`author` varchar(255) NOT NULL,
`author_intro` varchar(255) DEFAULT NULL,
`author_avater` varchar(500) DEFAULT NULL,
`content` longtext NOT NULL,
`intro` varchar(500) DEFAULT NULL,
`released_at` int(13) NOT NULL,
`created_at` datetime NOT NULL,
PRIMARY KEY (`id`),
UNIQUE KEY `hash` (`hash`),
KEY `title` (`title`),
KEY `author` (`author`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8;
 */