<?php

ini_set("memory_limit", "2048M");

set_time_limit(0);

// error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';
require dirname(__FILE__).'/phpspider/core/db.php';

$cookie_arr = array(

    '__utma=51854390.1967793815.1482631584.1482631584.1482631584.1;__utmb=51854390.4.10.1482631584;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482631584.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none);_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="N2Y1ZjYxMDkxNWRlNGQyNGIyODlmMWFhYjhlOGU2MDI=|1482631584|4d6507021464a22b7b140a9c1b027d44cc1efa20";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="OTFkODRmMDdlMTA2NGMzYzgwOGJiNzUzMzIwYjU3YjM=|1482631584|bcbf5c72fc602041b01ddc6571b6356314944fc5";login="NzljMzM5Mjg3MjllNGU3M2IzZDZjNTBiYmU2MWZiNzQ=|1482631597|206f555048693b7a1d239bb7ce41570d0db9686c";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="MjY3NzAzMTc1NzZiNDQ5MDk4YzE0ZjgyOGQ4ZDE5MDk=|1482631585|5ab50e6df6a2726050030bb50744bbc60d6a581d";z_c0="QUFBQTktNG9BQUFYQUFBQVlRSlZUYTYyaGxnV1VUaVdjR3U3TEhZWVY4XzlyWmhDcnVRNkRBPT0=|1482631602|9c4de3096065da7f90e8e10e19861ef2e9e471de";_xsrf=ac02023316a54918fdb7706fdfe4a2a3',
    '23316a54918fdb7706fdfe4a2a3__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.6.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFDQThlNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk4wbWlIV0FDdWd2NmpXMTNZMk9XZkNvSVpKSWdLaWpBNDh3|1482677206|833a16623720057cfd99786033d8b06c23ffe178;_xsrf=ac020',
    '__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.10.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFBQThlNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk5IMm1IV0FCa1BVeE9vSXBoUzNTOF9hSHpoZmJ2VUJwdWVR|1482677281|02751fa585997a5c24f267282ed35f8819769bfe;_xsrf=ac02023316a54918fdb7706fdfe4a2a3',
    '__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.14.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFDQXQtNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk5VV21IV0FEZkx1VTQtd1cwRlYxejFQczMxUlo3QUtKcEhB|1482677334|60feca7051e43e3d9324cabefb2919a499103083;_xsrf=ac02023316a54918fdb7706fdfe4a2a3',
    '__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.18.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFBQXQtNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk5rbW1IV0FCSVpIaGhrOE5LZ1NHenVsUkJ0MXNOVXBQOHZR|1482677399|67db2733b5478bda013b3cde05c68b412daaf18a;_xsrf=ac02023316a54918fdb7706fdfe4a2a3',
    '__utma=51854390.416412106.1482643601.1482662464.1482676083.3;__utmb=51854390.22.10.1482676083;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482643601.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=0fc13b64-52aa-443b-b8e6-760ded883e04;cap_id="NDU1YmJhNzIyYzcyNDIzMjg0OWExNGE0ZDNiYTY4MGI=|1482677048|aa4e57469e8eb9e70b0f520d193192f6c41388c9";d_c0="AIDCpWMCDQuPTvRpKJaK-39NqNlvO5cs0q0=|1482631602";l_cap_id="MWY2YTg3MjMyMTkzNGQ0NTkwMjFlNGU2YTk4MGFiM2U=|1482677048|88e3e4020050f2914f9618bfdc806eb2e4f138c0";login="YjA2ZjY2MjY4YzlkNDg0NDhjYzAzOGUxNjIwOWVkZTM=|1482677202|bf3d724e373a863f1a84062bf9b95d1c13aea9c9";n_c=1;q_c1=fe0c959cdeda49e8a7717751c7a01c36|1482631584000|1482631584000;r_cap_id="NDliYjZjZmQwMDBmNGRkMmJkNWE5NGRhYWMyZmI0MTk=|1482677165|21d7f1af3ffc237002796728ea2373e4ca373442";z_c0=Mi4wQUFEQXVlNG9BQUFBZ01LbFl3SU5DeGNBQUFCaEFsVk55Mm1IV0FBVUx2cUxFb3ZISjJCRlh0dHlEZ0s2RHJDcUd3|1482677457|4807d891eab71cdc164004f8454d6fb7ed77dd1b;_xsrf=ac02023316a54918fdb7706fdfe4a2a3'

);


$ips_arr = array(
//    array('ip'=>'121.14.6.236','http'=>'https','port'=>80),
//    array('ip'=>'124.88.67.10','http'=>'http','port'=>80),
//    array('ip'=>'111.155.124.71','http'=>'http','port'=>8123),
//    array('ip'=>'183.129.151.130','http'=>'http','port'=>80),
//    array('ip'=>'110.73.3.247','http'=>'http','port'=>8123),
//    array('ip'=>'124.88.67.52','http'=>'http','port'=>843),
);

$rand = rand(1,99);

//if ($rand > 60 && $rand <= 70)
//{
//    requests::set_proxies(array('http' => 'http://H63CM9OB7937832P:635BCC6BACA7E2C1@proxy.abuyun.com:9010'));
//}
//
//if ($rand <20)
//{
//    requests::set_proxies(array('http' => 'http://124.88.67.52:843'));
//}
//
//if ($rand >30 && $rand<=40)
//{
//    requests::set_proxies(array('http' => 'http://110.73.3.247:8123'));
//}
//
//if ($rand >40 && $rand<=50)
//{
//    requests::set_proxies(array('http' => 'http://183.129.151.130:80'));
//}
//
//if ($rand >50 && $rand <=55){
//    requests::set_proxies(array('http'=>'http://111.155.124.71:8123'));
//}
//
//if ($rand >70 && $rand <=80){
//    requests::set_proxies(array('http'=>'http://124.88.67.10:80'));
//}
//
//if ($rand >80 && $rand <=90){
//    requests::set_proxies(array('http'=>'http://121.14.6.236:80'));
//}

//requests::set_header('Cookie', $cookie_arr[rand(0,5)]);

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
    //db::query($sql);
}

echo '<pre>';
print_r($question);

requests::set_proxies(array('https'=>'https://218.29.111.106:9999'));

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');

$question_url  = "https://www.zhihu.com/question/{$question['question_code']}";
echo $question_page = requests::get($question_url);
$answer_arr = selector::select($question_page, "//div[contains(@class, 'zm-item-answer  zm-item-expanded')]");


$context = array(
    'https' => array(
        'proxy' => "tcp://218.29.111.106:9999",
        'request_fulluri' => true,
        'timeout' => 5
    ),

);

$context = stream_context_create($context);
echo file_get_contents($question_url, false, $context);

print_r(requests::$info); die();

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
    $a = new requests();
    $a->set_proxies(array('http'=>'http://124.88.67.10:80'));
    echo $answer_result = requests::post($answer_url, $post_data);
    print_r(requests::$info);
    die();

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
    //db::query($sql);
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
echo "<script>window.location.href='http://127.0.0.2/spider/zh_question_content.php?time={$time}';</script>";

