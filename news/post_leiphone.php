<?php
/**
 * 雷锋网文章下载
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/../phpspider/core/init.php';

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

    $cover = selector::select($item, "//div[contains(@class, 'img')]/a/img/@data-original");
    $cover = current(explode('?image', $cover));

    $tags = selector::select($item, "//div[contains(@class, 'tags')]/a");
    $author_name = selector::select($item, "//div[contains(@class, 'word')]/div/a");
    $author_name = trim(strip_tags($author_name));
    $author_avater = selector::select($item, "//div[contains(@class, 'word')]/div/a/img/@src");
    $content_html = requests::get($link);
    $intro = selector::select($content_html, "//div[contains(@class, 'article-lead')]");
    $intro = trim(str_replace('导语：', '', strip_tags($intro)));
    $author_intro = '';
    $released_at = selector::select($content_html, "//*[@class='time']");
    $released_at = date('Y-m-d H:i:s', strtotime($released_at));
    if (empty($released_at) || $released_at == '1970-01-01 08:00:00') {
        $released_at = date('Y-m-d H:i:s');
    }
    $content = selector::select($content_html, "//div[contains(@class, 'article-left')]/div[1]");
    $content = trim($content);
    $content = selector::remove($content, "//iframe");

    if (empty($intro) && !empty($content)) {
        $intro = substr(strip_tags($content), 0, 200);
    }

    // =======================

    $content = preg_replace_callback( "#<p>(.*)</p>#iUs", function ($matches)
    {
        $matches[1] = trim(strip_tags($matches[1]));
        if ($matches[1] == '&nbsp;' || $matches[1] == ' '|| strlen($matches[1])<=2)
        {
            return '';
        }
        return $matches[0];
    }, $content);

    if (is_array($tags)) {
        foreach ($tags as $tkk=>$tt)
        {
            $tags[$tkk] = trim($tt);
        }
    } else {
        $tags = array(trim($tags));
    }

    $tags[] = '新闻';
    $tags[] = '雷锋网';
    $tag_ids = [];
    if (!empty($tags))
    {
        foreach ($tags as $tag)
        {
            $tr = db::get_one("select * from tags where `name`='{$tag}' limit 1");
            if (!empty($tr)) {
                $tag_ids[] = $tr['id'];
            } else {
                $tag = [
                    'name' => $tag,
                    'pid' => '20069729',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
                $tag_ids[] = db::insert('tags', $tag);
            }
        }
        $tag_ids = array_unique($tag_ids);
        $tag_ids = array_values($tag_ids);
    }
    if(empty($author_name)) {
        $author_name = '匿名作者';
    }
    $author = db::get_one("SELECT * FROM `authors` WHERE author_name='{$author_name}' LIMIT 1;");
    $author_id = $author['author_id'];
    if (empty($author_id))
    { //添加作者
        $author_id = db::insert('authors',
            [
                'author_name'=> $author_name,
                'author_desc'=> '',
                'author_avater' => $author_avater,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => $released_at,
            ]
        );
        $author = db::get_one("SELECT * FROM `authors` WHERE author_name='{$author_name}' LIMIT 1;");
        $author_id = $author['author_id'];
    }
    if (db::get_one("select * from posts where `title`='{$title}' and author_id='{$author_id}' limit 1")) {
        echo " jump\r\n";
        continue;
    }
    $post_id = 0;
    if (!empty($title))
    {
        $posts = [
            'title' => $title,
            'intro' => trim(strip_tags($intro)),
            'author_id' => $author_id,
            'cover' => $cover,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'released_at' => $released_at,
            'released' => 1
        ];
        $post_id = db::insert('posts', $posts);
        // 更新标签关系
        if (!empty($tag_ids))
        {
            foreach ($tag_ids as $t)
            {
                $ttb = db::get_one("select * from tag_taggables where taggable_id='{$post_id}' and tag_id='{$t}'");
                if(!empty($ttb)) {
                    continue;
                } else {
                    $tag_taggables = [
                        'tag_id' => $t,
                        'taggable_id' => $post_id,
                        'taggable_type' => 'App\\\Models\\\Post',
                    ];
                    db::insert('tag_taggables', $tag_taggables);
                }
            }
        }
    }
    if (!empty($post_id))
    {// 插入内容
        $posts_content = [
            'post_id' => $post_id,
            'post_content'=>$content,
            'created_at'=>date('Y-m-d H:i:s'),
            'updated_at'=>date('Y-m-d H:i:s'),
        ];
        db::insert('post_content', $posts_content);
    }
    // ===========================================
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