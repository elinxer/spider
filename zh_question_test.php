<?php

ini_set("memory_limit", "2048M");

set_time_limit(0);

// error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';
require dirname(__FILE__).'/phpspider/core/db.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);

$sql = "SELECT * FROM `zh_question_list` WHERE loaded=0 and loading=0 limit 1";
$question = db::get_one($sql);

if (!empty($question))
{
    $sql = "UPDATE `zh_question_list` SET loading=1 WHERE question_code={$question['question_code']}";
    db::query($sql);
}

$ip = db::get_one("SELECT * FROM `spider_proxy_ip` WHERE protocol='http' ORDER BY RAND() LIMIT 1;");
requests::set_proxies(array('http'=>"tcp://{$ip['ip']}:{$ip['port']}"));

//requests::set_proxies(array('http'=>"tcp://218.76.106.78:3128"));

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');

$question_url  = "https://www.zhihu.com/question/{$question['question_code']}";

//$question_url = 'http://test2.zuzuche.com/_yds_/curl/request.php';

$question_page = requests::get($question_url);

$answer_arr = selector::select($question_page, "//div[contains(@class, 'zm-item-answer  zm-item-expanded')]");

//
//$context = array(
//    'https' => array(
//        'proxy' => "tcp://218.29.111.106:9999",
//        'request_fulluri' => true,
//        'timeout' => 5
//    ),
//
//);
//
//$context = stream_context_create($context);
//echo file_get_contents($question_url, false, $context);
//
//print_r(requests::$info); die();

$insert = array();
if (!empty($answer_arr))
{
    foreach ($answer_arr as $answer)
    {
        $insert[] = get_answer($answer, $question);
    }
    db::insert_batch('zh_question_answer', $insert);

}

$file_path = "html/zh_question_page/{$question['question_code']}.html";
file_put_contents($file_path, $question_page);

$_xsrf = selector::select($question_page, "@<input type=\"hidden\" name=\"_xsrf\" value=\"(.*)\"/>@", 'regex');
requests::set_header('X-Xsrftoken', $_xsrf);

for ($i=1; $i<=50; $i++)
{
    $post_data['method'] = 'next';
    $post_data['params'] = json_encode(array(
        'url_token' => $question['question_code'],
        'pagesize'  => 10,
        'offset'    => 10  * $i
    ));

    $answer_url    = 'https://www.zhihu.com/node/QuestionAnswerListV2';

    $answer_result = requests::post($answer_url, $post_data);

    $answer_result = json_decode($answer_result, true);
    $answer_result = $answer_result['msg'];

    if (!empty($answer_result))
    {
        $insert = array();
        foreach ($answer_result as $answer)
        {
            $insert[] = get_answer($answer, $question);
        }
        $has = 1;
        db::insert_batch('zh_question_answer', $insert);
    }
    else{
        break;
    }

    sleep(2);
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

function get_answer($answer, $question)
{

    $agree_vote  = selector::select($answer, "//div[contains(@class, 'zm-item-vote-info')]/@data-votecount");
    $answer_url  = selector::select($answer, "//link[@itemprop='url']/@href");
    $answer_code = selector::select($answer, "//meta[@itemprop='answer-url-token']/@content");
    $answer_id   = selector::select($answer, "//meta[@itemprop='answer-id']/@content");

    $answer_commit_num    = selector::select($answer, "//a[@name='addcomment']");
    $answer_commit_num    = selector::select($answer_commit_num, '@(\d+)@', 'regex');

    $answer_author_name   = selector::select($answer, "//a[contains(@class, 'author-link')]");
    $answer_author_name   = $answer_author_name?:'匿名';
    $answer_author_a_desc = selector::select($answer, "//span[contains(@class, 'badge-summary')]/a");
    $answer_author_desc   = selector::select($answer, "//span[contains(@class, 'bio')]");
    $answer_author_avater = selector::select($answer, "//a/img[contains(@class, 'zm-list-avatar')]/@src");

    $answer_author_url    = selector::select($answer, "//a[contains(@class, 'author-link')]/@href");

    $answer_author_code   = explode('/', $answer_author_url);
    $answer_author_code   = isset($answer_author_code[2]) ? $answer_author_code[2] : '';

    $answer_last_edit_time = selector::select($answer, "//a[contains(@class, 'answer-date-link')]");
    $answer_last_edit_time = selector::select($answer_last_edit_time, '@(\d{4})-(\d{1,2})-(\d{1,2})@', "regex");
    if (count($answer_last_edit_time)>=2)
    {
        $answer_last_edit_time = strtotime(implode('-', $answer_last_edit_time))?:0;
    }

    $answer_publish_time   = selector::select($answer, "//a[contains(@class, 'answer-date-link')]/@data-tooltip");
    $answer_publish_time   = selector::select($answer_publish_time, '@(\d{4})-(\d{1,2})-(\d{1,2})@', "regex");

    if (count($answer_publish_time)>=2)
    {
        $answer_publish_time = strtotime(implode('-', $answer_publish_time))?:0;
    }

    if (empty($answer_publish_time))
    {
        $answer_publish_time = $answer_last_edit_time;
    }

    $insert = array(
        'question_code' => $question['question_code'],
        'question_url'  => $question['question_url'],
        'answer_code' => $answer_code,
        'answer_id' => $answer_id,
        'answer_url' => $answer_url,
        'answer_last_edit_time' => $answer_last_edit_time,
        'answer_publish_time' => $answer_publish_time,
        'answer_agree' => $agree_vote,
        'answer_author_code' => $answer_author_code,
        'answer_author_url' => $answer_author_url,
        'answer_author_name' => $answer_author_name,
        'answer_author_avater' => $answer_author_avater,
        'answer_author_desc' => $answer_author_desc,
        'answer_author_a_desc' => $answer_author_a_desc,
        'answer_commit_num' => $answer_commit_num,
        'add_time'=> time(),

        'answer_html_content' => $answer,
    );

    return $insert;
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
echo "<script>window.location.href='http://127.0.0.2/spider/zh_question_answer.php?time={$time}';</script>";

