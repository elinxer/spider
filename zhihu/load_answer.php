<?php
/**
 * 加载知乎问题答案
 */
ini_set("memory_limit", "2048M");
require dirname(__FILE__).'/../phpspider/core/init.php';

$cookie_arr = array(

    '__utma=51854390.1967793815.1482631584.1482631584.1482631584.1;__utmb=51854390.4.10.1482631584;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482631584.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none);_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="N2Y1ZjYxMDkxNWRlNGQyNGIyODlmMWFhYjhlOGU2MDI=|1482631584|4d6507021464a22b7b140a9c1b027d44cc1efa20";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="OTFkODRmMDdlMTA2NGMzYzgwOGJiNzUzMzIwYjU3YjM=|1482631584|bcbf5c72fc602041b01ddc6571b6356314944fc5";login="NzljMzM5Mjg3MjllNGU3M2IzZDZjNTBiYmU2MWZiNzQ=|1482631597|206f555048693b7a1d239bb7ce41570d0db9686c";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="MjY3NzAzMTc1NzZiNDQ5MDk4YzE0ZjgyOGQ4ZDE5MDk=|1482631585|5ab50e6df6a2726050030bb50744bbc60d6a581d";z_c0="QUFBQTktNG9BQUFYQUFBQVlRSlZUYTYyaGxnV1VUaVdjR3U3TEhZWVY4XzlyWmhDcnVRNkRBPT0=|1482631602|9c4de3096065da7f90e8e10e19861ef2e9e471de";_xsrf=ac02023316a54918fdb7706fdfe4a2a3',
    '23316a54918fdb7706fdfe4a2a3__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.6.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFDQThlNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk4wbWlIV0FDdWd2NmpXMTNZMk9XZkNvSVpKSWdLaWpBNDh3|1482677206|833a16623720057cfd99786033d8b06c23ffe178;_xsrf=ac020',
    '__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.10.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFBQThlNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk5IMm1IV0FCa1BVeE9vSXBoUzNTOF9hSHpoZmJ2VUJwdWVR|1482677281|02751fa585997a5c24f267282ed35f8819769bfe;_xsrf=ac02023316a54918fdb7706fdfe4a2a3',
    '__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.14.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFDQXQtNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk5VV21IV0FEZkx1VTQtd1cwRlYxejFQczMxUlo3QUtKcEhB|1482677334|60feca7051e43e3d9324cabefb2919a499103083;_xsrf=ac02023316a54918fdb7706fdfe4a2a3',
    '__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.18.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFBQXQtNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk5rbW1IV0FCSVpIaGhrOE5LZ1NHenVsUkJ0MXNOVXBQOHZR|1482677399|67db2733b5478bda013b3cde05c68b412daaf18a;_xsrf=ac02023316a54918fdb7706fdfe4a2a3',
    '__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.22.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFEQXVlNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk55Mm1IV0FBVUx2cUxFb3ZISjJCRlh0dHlEZ0s2RHJDcUd3|1482677457|4807d891eab71cdc164004f8454d6fb7ed77dd1b;_xsrf=ac02023316a54918fdb7706fdfe4a2a3'

);

$rand = rand(1,99);

//requests::set_header('Cookie', $cookie_arr[rand(0,5)]);

$sql = "SELECT * FROM `zh_question_list` WHERE loaded=0 and loading=0 and id>265 limit 1";
$question = db::get_one($sql);

if (!empty($question))
{
    $sql = "UPDATE `zh_question_list` SET loading=1 WHERE question_code={$question['question_code']}";
    db::query($sql);
}

$ip = db::get_one("SELECT * FROM `spider_proxy_ip` WHERE protocol='https' ORDER BY RAND() LIMIT 1;");
requests::set_proxies(array('http'=>"tcp://{$ip['ip']}:{$ip['port']}"));

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');

$question_url  = "https://www.zhihu.com/question/{$question['question_code']}";

//$question_url = 'http://test2.zuzuche.com/_yds_/curl/request.php';

$question_page = requests::get($question_url);

$header_html = selector::select($question_page, "//div[contains(@class, 'QuestionHeader')]");
if (is_array($header_html)) {
    $header_html = current($header_html);
}
$tags = selector::select($header_html, "//span[@class='Tag-content']");
if (is_array($tags)) {
    foreach ($tags as $tk=>$t)
    {
        $tags[$tk] = strip_tags($t);
    }
} else {
    $tags = strip_tags($tags);
    $tags = array($tags);
}
array_push($tags, '问答');
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
$question_detail = selector::select($header_html, "//div[@class='QuestionHeader-detail']");
$answer_html = selector::select($question_page, "//div[contains(@class, 'Card')]");
if (is_array($answer_html)) {
    $answer_html = current($answer_html);
}
$answer_arr = selector::select($answer_html, "//div[@class='List-item']");
$insert = array();
if (!empty($answer_arr))
{
    foreach ($answer_arr as $answer)
    {
        $content = selector::select($answer, "//div[@class='RichContent-inner']");
        $content = preg_replace_callback( "/<img.*?src=\"(.*?)\".*?>/is", function ($matches)
        {
            if (strpos($matches[0], 'data-src="') !== false)
            {
                $matches[0] = preg_replace("/[^(\-)]src=\"(.*)\"/iUs", '', $matches[0]);    // 去掉src属性
                $matches[0] = str_replace("data-src=\"", 'src="', $matches[0]); // 重置图片src性
            }
            if (strpos($matches[0], 'data-original="') !== false)
            {
                $matches[0] = preg_replace("/[^(\-)]src=\"(.*)\"/iUs", '', $matches[0]);    // 去掉src属性
                $matches[0] = str_replace("data-original=\"", 'src="', $matches[0]); // 重置图片src性
            }
            if (strpos($matches[0], 'data-actualsrc="') !== false)
            {
                $matches[0] = preg_replace("/[^(\-)]src=\"(.*)\"/iUs", '', $matches[0]);    // 去掉src属性
                $matches[0] = str_replace("data-actualsrc=\"", 'src="', $matches[0]); // 重置图片src性
            }
            $html = $matches[0];
            return $html?:$matches[0];
        }, $content);

        $publish_time = selector::select($answer, "//div[@class='ContentItem-time']");
        $publish_time = selector::select($publish_time, "//span/@data-tooltip");
        $publish_time = explode(' ', $publish_time);
        $publish_time = end($publish_time);
        $publish_time = trim($publish_time);
        $publish_time = date('Y-m-d H:i:s', strtotime($publish_time));
        if (empty($publish_time) || $publish_time == '1970-01-01 08:00:00') {
            $publish_time = date('Y-m-d H:i:s');
        }
        $author_html = selector::select($answer, "//div[@class='AuthorInfo']");
        $author_avater = selector::select($author_html, "//img/@src");
        if (strpos($author_avater, '_x')!==false) {
            $author_avater = preg_replace("#_x[a-zA-Z0-9]#iUs", '', $author_avater);
        }
        $author_name = selector::select($author_html, "//div[@class='AuthorInfo-name']");
        $author_name = strip_tags($author_name);
        $author_desc = selector::select($author_html, "//div[@class='RichText AuthorInfo-badge']");

        $author = db::get_one("SELECT * FROM `authors` WHERE author_name='{$author_name}' LIMIT 1;");
        $author_id = $author['author_id'];
        if (empty($author_id))
        { //添加作者
            $author_id = db::insert('authors',
                [
                    'author_name'=>$author_name,
                    'author_desc'=>$author_desc,
                    'author_avater' => $author_avater,
                    'created_at' => $publish_time,
                    'updated_at' => $publish_time,
                ]
            );
            $author = db::get_one("SELECT * FROM `authors` WHERE author_name='{$author_name}' LIMIT 1;");
            $author_id = $author['author_id'];
        }
        echo $publish_time;
        $post_id = 0;
        $title = $question['question_title'];
        $question_detail = str_replace('显示全部','', $question_detail);
        if (!empty($title))
        {
            $posts = [
                'title' => $title,
                'intro' => trim(strip_tags($question_detail)),
                'author_id' => $author_id,
                'cover' => '',
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'released_at' => $publish_time,
                'released' => 1,
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

sleep(1);

if (!empty($has))
{
    $sql = "UPDATE `zh_question_list` SET loaded=1 WHERE question_code={$question['question_code']}";
    db::query($sql);
}

if (empty($question))
{
    die('问题列表全部加载完成');
}

function file_get_contents_proxy($url, $ips=array())
{
    if (!empty($ips))
    {
        $ip = $ips[rand(0, count($ips)-1)];
        if ($ip['http'] == 'http')
        {
            $context = array(
                'http' => array(
                    'proxy' => "tcp://{$ip['ip']}:{$ip['port']}",
                    'request_fulluri' => true,
                    'timeout' => 5
                ),

            );
        }
        else {
            $context = array(
                'https' => array(
                    'proxy' => "tcp://{$ip['ip']}:{$ip['port']}",
                    'request_fulluri' => true,
                    'timeout' => 5
                ),

            );
        }

        $context = stream_context_create($context);
        return file_get_contents($url, false, $context);
    }
    return '';
}



$time = time();
echo "<script>window.location.href='http://127.0.0.2/zhihu/load_answer.php?time={$time}';</script>";

