<?php
ini_set("memory_limit", "2048M");

set_time_limit(0);

error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';
require dirname(__FILE__).'/phpspider/core/db.php';

$cookie = '__utma=51854390.363194686.1481697018.1481697018.1481772398.2;__utmb=51854390.12.9.1481772442684;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20160305=1^3=entry_date=20160305=1;__utmz=51854390.1481697018.1.1.utmcsr=baidu|utmccn=(organic)|utmcmd=organic;_zap=572a3c99-b283-4793-a05e-908bae752db2;a_t="2.0ABBAUOyLkQkXAAAAJAhnWAAQQFDsi5EJAIBAQaShmwoXAAAAYQJVTSIIZ1gAFvf8Jhws0GtuzHmrLk8NKN3AAn0ybfaKsJTpbKigTsHc9zfxKbODrA==";cap_id="ZTM2NGQ2ODg1NzJhNGRiYmI3MjI2YTg2MWI2MTUxMzE=|1481699373|872b464dbe4807167ab7388f848c09eb30323c50";d_c0="AIBAQaShmwqPTsuaxRE7PLY-YNKh5-XOKuQ=|1475022939";l_cap_id="YjM0OWVjNTA3NGVkNGYxOGEwZjYzNTc3MGUyMDJlZWE=|1481699374|3409a094c118ffb0449baa2cf74eaee45c27a067";login="ODg4ZmZkNTJhYmJjNGZiMGE5OTFjZjA0M2Y3MWQxYTE=|1481699374|224c562b2d655ba50c01ada4f467bd673c9e2b37";q_c1=3af6372162c64571b6dd5d4fc8fb1001|1480469612000|1475022939000;r_cap_id="OGQyYjI5ODZkZGNkNDk5ZmI4YTgzZTNlZDljYTE3ODM=|1481697671|5e1026c1b0f9ea6d38ff1a2c0d9c82997a5a16d6";z_c0=Mi4wQUJCQVVPeUxrUWtBZ0VCQnBLR2JDaGNBQUFCaEFsVk5OWDE0V0FCUHpiZG1aMFdOa3pKNlJ4UE14Zngybkl0WmJn|1481772450|f716c26e55e4aa316b393386c49a744962417318;_xsrf=6bd81225f060ebe2e952ee2972cd9298;s-i=1;s-q=%E6%95%B0%E6%8D%AE%E7%A7%91%E5%AD%A6;s-t=autocomplete;sid=8a921vao';
requests::set_header('Cookie', $cookie);

// 获取所有知乎话题链接

// 获取话题基本信息接口https://www.zhihu.com/node/TopicProfileCardV2?params={"url_token":"20060030"}


$root_url = "https://www.zhihu.com/topic/19776749/organize/entire";

$r   = requests::get($root_url);

$arr = selector::select($r, "@<input type=\"hidden\" name=\"_xsrf\" value=\"(.*)\"/>@", 'regex');

$post_data = array(
    '_xsrf' => $arr
);

//设置代理
$proxies = array(
    'http' => 'http://H63CM9OB7937832P:635BCC6BACA7E2C1@proxy.abuyun.com:9010',
    //'https' => 'http://user:pass@host:port',
);
requests::set_proxies($proxies);

$r  = requests::post($root_url, $post_data);

//https://www.zhihu.com/topic/19776749/organize/entire?child=&parent=19615623 //显示子话题
//https://www.zhihu.com/topic/19776749/organize/entire?child=19746496&parent=19776751 // 加载更多

$topic_root = json_decode($r, true);
$topic_root = $topic_root['msg'];


$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
    );



echo '<pre>';
//
//print_r($topic_root);
//die();

zh_topic_tree($topic_root[1][0], $post_data);

function zh_topic_tree($root, $post_data)
{

    if (count($root) >= 2)
    {
        $root_item  = $root[0];
        $child_item = $root[1];

        if ($root_item[0] == 'load' && empty($child_item))
        { //print_r("kong"); 循环子级话题

            $sub_topic_url = 'https://www.zhihu.com/topic/19776749/organize/entire?child=&parent='.$root_item[2];

            if (empty($root_item[2]) && !empty($root_item[3]))
            {
                $sub_topic_url = 'https://www.zhihu.com/topic/19776749/organize/entire?child=&parent='.$root_item[3];
            }

            $child_post = requests::post($sub_topic_url, $post_data);
            $topic_root = json_decode($child_post, true);
            $child_item = $topic_root['msg'][1];
        }
        else
        {
            $pid = isset($root_item[99])?$root_item[99]:0;
            $insert = array(
                'pid'  => $pid,
                'name' => $root_item[1],
                'topic_code' => $root_item[2]
            );
            db::insert('zh_topic', $insert);
        }

        if (!empty($root_item[2]) && !empty($root_item[3]))
        { //获取加载更多

            $more_url = "https://www.zhihu.com/topic/19776749/organize/entire?child={$root_item['2']}&parent={$root_item[3]}";

            $more_post  = requests::post($more_url, $post_data);
            $topic_root = json_decode($more_post, true);
            $more_item  = $topic_root['msg'][1];

            if (!empty($child_item) && is_array($more_item))
            {
                $child_item = array_merge($child_item, $more_item);
            }

            if (empty($child_item)) $child_item = $more_item;

        }


        if (!empty($child_item))
        { //递归结构

            foreach ($child_item as $k=>$child)
            {

                if (isset($root_item[3])){
                    $child[0][99]   = $root_item[3];
                }
                else {
                    $child[0][99]   = $root_item[2];
                }

                //print_r($root_item);
                zh_topic_tree($child, $post_data);
            }

        }


    }

}


