<?php
/**
 * 爬取虎嗅网数据
 * 2017-03-11
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__) . '/../phpspider/core/init.php';

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

    $wrap_right = selector::select($content_html, "//div[contains(@class, 'wrap-right')]");
    $author_html = selector::select($wrap_right, "//div[contains(@class, 'box-author-info')]");

    $author_name = selector::select($author_html, "//div[contains(@class, 'author-name')]/a[1]");
    $author_name = trim(strip_tags($author_name));
    $author_avater = selector::select($author_html, "//div[contains(@class, 'author-face')]/a/img/@src");

    $cover = selector::select($article_wrap, "//div[contains(@class, 'article-img-box')]/img/@src");
    $released_at = selector::select($article_wrap, "//span[@class='article-time pull-left']");
    $released_at = date('Y-m-d H:i:s', strtotime($released_at));
    if (empty($released_at) || $released_at == '1970-01-01 08:00:00') {
        $released_at = date('Y-m-d H:i:s');
    }

    $content = selector::select($content_html, "//div[contains(@class, 'article-content-wrap')]");
    $content = trim($content);
    $content = selector::remove($content, "//iframe");

    $intro = '';
    if (empty($intro) && !empty($content)) {
        $intro = substr(strip_tags($content), 0, 400);
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
    $tags[] = '虎嗅网';

    $tag_ids = [];
    if (!empty($tags))
    {
        foreach ($tags as $tag)
        {
            $tag = trim($tag);
            if (empty($tag)) {
                continue;
            }
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

//print_r($arr);

