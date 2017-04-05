<?php
/**
 * 爬脚本之家的所有php文章
 */
ini_set("memory_limit", "2048M");
require dirname(__FILE__).'/../phpspider/core/init.php';

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');
die();
$tags = ['php', '网络编程', 'web编程', '脚本语言', '世界上最好的语言'];
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

for ($i=1; $i<=250; $i++)
{
    //$list_url = "http://www.jb51.net/list/list_15_{$i}.htm";
    $html = requests::get($list_url);
    $content = selector::select($html, "//div[@class='artlist clearfix']");
    $list_arr = selector::select($content, "//dt");
    if (is_array($list_arr))
    {
        foreach ($list_arr as $item)
        {
            $realesed_at = selector::select($item, "//span");
            $realesed_at = str_replace('日期:', '', $realesed_at);
            $realesed_at = trim($realesed_at);

            $realesed_at = date('Y-m-d H:i:s', strtotime($realesed_at));
            if (empty($realesed_at) || $realesed_at == '1970-01-01 08:00:00') {
                $realesed_at = date('Y-m-d H:i:s');
            }

            $title = selector::select($item, "//a");
            $title = strip_tags($title);
            $url = selector::select($item, "//a/@href");
            $url = 'http://www.jb51.net'. $url;
            $content_html = requests::get($url);
            $intro = selector::select($content_html, "//div[@id='art_demo']");
            $content = selector::select($content_html, "//div[@id='content']");

            $author = db::get_one("SELECT * FROM `authors` WHERE author_name='脚本之家' LIMIT 1;");
            $author_id = $author['author_id'];
            if (empty($author_id))
            { //添加作者
                $author_id = db::insert('authors',
                    [
                        'author_name'=> '脚本之家',
                        'author_desc'=> ' 脚本之家(徐州蓝佳网络科技有限公司旗下)成立于2006年，是一个专业的收藏整理多类脚本学习资料的网站,本站为个人网站，访客主要是网站建设、网页设计和网络编程开发人员及业余网页爱好者，网站定位于最新的网页制作教程，网站建设指南，网络编程，网页素材下载，网页相关书籍，以及网络安全知识和操作系统知识等。',
                        'author_avater' => 'http://www.jb51.net/images/logo.gif',
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => $realesed_at,
                    ]
                );
                $author = db::get_one("SELECT * FROM `authors` WHERE author_name='脚本之家' LIMIT 1;");
                $author_id = $author['author_id'];
            }

            $post_id = 0;
            if (!empty($title))
            {
                $posts = [
                    'title' => $title,
                    'intro' => trim(strip_tags($intro)),
                    'author_id' => $author_id,
                    'cover' => 'http://files.jb51.net/images/lm_img/php.png',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                    'released_at' => $realesed_at,
                ];
                $post_id = db::insert('posts', $posts);
                // 更新标签关系
                if (!empty($tag_ids)) {
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
        }
    }
    echo "{$i}\n\r";
}
