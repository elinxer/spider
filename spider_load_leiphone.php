<?php
/**
 * 雷锋网文章下载
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'121.43.191.145',
    'port'		=>	3306,
    'user'		=>	'zhiteer',
    'pass'		=>	'a654753115',
    'name'		=>	'zhiteer',
);

//$url = 'http://www.leiphone.com/';
$url = 'http://www.leiphone.com/site/AjaxLoad/page/1';
$html = requests::get($url);

$result = json_decode($html, true);

$html = $result['html'];
$html = str_replace('&#13;', '', $html);

$arr = selector::select($html, "//li");

$num = 0;

$news = [];
if (empty($arr)) {
    echo "list is empty";exit();
}
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
    $released_at = strtotime($released_at);

    $content = selector::select($content_html, "//div[contains(@class, 'article-left')]/div[1]");
    $content = trim($content);
    $content = selector::remove($content, "//iframe");

    if (empty($intro) && !empty($content)) {
        $intro = substr(strip_tags($content), 0, 200);
    }

    $news = [
        'hash' => $hash,
        'title' => $title,
        'link'  => $link,
        'cover' => $img,
        'cover_original' => current(explode('?image', $img)),
        'tags' => $tags,
        'author_name'=> $author,
        'author_intro' => $author_intro,
        'author_avater'=> $author_avater,
        'content' => $content,
        'released_at' => $released_at,
        'intro' => $intro,
        'channel' => 'leiphone',
        'created_at' => time()
    ];

    //print_r($news);die();
    db::insert("spider_news", $news);

    echo $num++;
    echo " loaded done\r\n";
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
/**



// 清洗图片数据
$ori_content = preg_replace_callback( "/<img.*?src=\"(.*?)\".*?>/is", function ($matches) {

    if (strpos($matches[0], 'data-src="') !== false) {

        $matches[0] = preg_replace("/[^(\-)]src=\"(.*)\"/iUs", '', $matches[0]);    // 去掉src属性
        $matches[0] = str_replace("data-src=\"", 'src="', $matches[0]); // 重置图片src性

        //重新匹配图片
        preg_match("/<img.*?src=\"(.*?)\".*?>/is", $matches[0], $img);
        $img_url = current(explode('?imageView2', $img[1]));
    }
    else {
        $img_url = $matches[1];
    }

    if (!empty($img_url)) {

        if (strpos($img_url, '/face/') !== false) { //去掉表情图片
            return '';
        }

        return "<p><img src=\"{$img_url}\" /></p>";
    } else {
        return '';
    }

}, $ori_content
);

 */