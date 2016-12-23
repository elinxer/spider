<?php
ini_set("memory_limit", "2048M");

set_time_limit(0);

error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';
require dirname(__FILE__).'/phpspider/core/db.php';



$cookie_arr = array(
//    1=> '__utma=51854390.157642449.1482490012.1482490012.1482490012.1;__utmb=51854390.4.10.1482490012;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20160305=1^3=entry_date=20160305=1;__utmz=51854390.1482490012.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none);_zap=091249d7-22f8-4016-bc36-f7c71cf10f09;cap_id="ZDY5OWQ5YzI1NDM0NGEwMWE2YmMwZjVhNThiMWY1OGM=|1482490009|4e27f0153d5527016279ed0235d7aa8fab8ff2e5";d_c0="AIDCRkHmCguPThLOCv8IbLzSM3IZY4Gxcnw=|1482490009";l_cap_id="ZTEwYWU3NjU1MTU4NGY4MzgwZTBhMTQ1OTQwYjE0MTI=|1482490009|d8fed47260bb3b78c09a0677c69b621d8958e4f3";login="MTNlNjRjOTE3OGZiNGE5Yzg4YTU5N2RiNmFlMjBlN2Q=|1482490015|5ced7a64268dfddcf1761b75d2206d22dd5ed10b";n_c=1;q_c1=bc0a4eba1b8d4acdaba75d2bf585902e|1482490009000|1482490009000;r_cap_id="OWQ2NDI5YWYyZDNiNDllMmIxOGQ2ODE2MmE4NmE4Mjg=|1482490009|dd0e78e21cbb22913c013de57c762572fbfa4673";z_c0=Mi4wQUJCQVVPeUxrUWtBZ01KR1FlWUtDeGNBQUFCaEFsVk5uNDJFV0FCOEpBNGZXejh0dlNQUGo0aG5adWRVUk5pbDZR|1482490018|da840e0e70c17fb2b75fd5e7e8132fc0455a685a;_xsrf=5d4571f095291484761213fb731e188e',
//    2=>'__utma=51854390.26866101.1482471056.1482471056.1482471056.1;__utmb=51854390.45.9.1482471606723;__utmc=51854390;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482471056.1.1.utmcsr=(direct)|utmccn=(direct)|utmcmd=(none);_zap=572a3c99-b283-4793-a05e-908bae752db2;a_t="2.0ABBAUOyLkQkXAAAAJAhnWAAQQFDsi5EJAIBAQaShmwoXAAAAYQJVTSIIZ1gAFvf8Jhws0GtuzHmrLk8NKN3AAn0ybfaKsJTpbKigTsHc9zfxKbODrA==";cap_id="MTRkMTc1N2I4M2IzNDM0OGIxOGU5MzBiM2FlYzk4OWU=|1482471055|4b961686e23eec76b7c22806aafc6386c52503ba";d_c0="AIBAQaShmwqPTsuaxRE7PLY-YNKh5-XOKuQ=|1475022939";imhuman_cap_id="ZjYyYjVhNjM2OTdmNDFkN2E2OTEyOWE5YTllMWNmYjM=|1482455843|6cd4713ff283012bc9299e5ce474e318484dadea";l_cap_id="MDUzMWZhNWQ1NDRkNDc3OGJjMmYxYmM4OWJiZTlmZjQ=|1482471055|0fdf29cdd3e6eb924328ef68d18d510b49b18cd5";login="Y2VkYjNkZGNlNWNmNDBhZjkyNzkyZWM1Y2Q2NmI5ZTA=|1482471067|5e38719fb555dd19bb317fa98e65e62c0da1c119";n_c=1;q_c1=3af6372162c64571b6dd5d4fc8fb1001|1480469612000|1475022939000;r_cap_id="M2E3NDRhNjk1MDFlNGI1MmFjYTBiNTE3MWU2ZmM2OTY=|1482471058|472b74785c5c44210e5302425926544c15ab90f0";z_c0=Mi4wQUFBQThlNG9BQUFBZ0VCQnBLR2JDaGNBQUFCaEFsVk5jVVdFV0FDRTdVS295YXdDQkhlRkRNN01tZWgtdDF1Mmd3|1482471680|6f4e261981fc57eac492d21f9bd154f7176f81f2;_xsrf=6bd81225f060ebe2e952ee2972cd9298',
//    3=>'__utma=51854390.592750333.1482473194.1482473194.1482473194.1;__utmb=51854390.4.10.1482473194;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482473194.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=c164ec46-24ef-4b28-8250-16c491820f0f;cap_id="MzhkYmUzZDJmOGIzNDdhNTkwZWFmMDE4MWFlNTczMmI=|1482473165|069a24f02d880c188134c825cd9a854bfb20a8d7";d_c0="AFDCzhmmCguPTldpheRY6y0q9q0IyY09cG8=|1482473192";l_cap_id="MWRiZThmNDExZDM5NGY1NTg0MDZlYzU2NGM3MWRlYjM=|1482473165|936fa0fbb0777e1643604ada1dcdb7ae7026dc65";login="NWZmM2VlM2ZlNDExNGY4MmFmYjYxMzcyYmZjMzFjYjU=|1482473166|a1f5197f7659abf4b8e80ab8466376f4fc07547b";n_c=1;q_c1=0ebf47735e3e41239f584a1361c2f7f7|1482473165000|1482473165000;z_c0="QUFBQTktNG9BQUFYQUFBQVlRSlZUZU5MaEZqTjhUR1NGaVVQQVVYMjVHOWhaRV9zODhjellnPT0=|1482473197|fe48f309c31d4b7326b17ba7a4b47eb82ca1063d";_xsrf=aad404bcb73cf6cee2ce67dc511504e9',
//    4=>'__utma=51854390.592750333.1482473194.1482473194.1482473194.1;__utmb=51854390.8.10.1482473194;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482473194.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=e1db6987-348d-4d6a-8543-ef2150958e4a;_zap=c164ec46-24ef-4b28-8250-16c491820f0f;cap_id="MjRmZjg2YWNlNDg5NGE5ZWFhOTcwNjE5NTI1NDU1MWI=|1482473278|685d8b074aee978af6aca9cd9e60fc9f3b957989";d_c0="AFDCzhmmCguPTldpheRY6y0q9q0IyY09cG8=|1482473192";l_cap_id="OTJiMjY3NWQwZjAwNDQ1NDhkZTNjM2U4ZTFjNDE1NDU=|1482473278|08178a80ed569fc053a250c484a2763e03b287ec";login="NWZmM2VlM2ZlNDExNGY4MmFmYjYxMzcyYmZjMzFjYjU=|1482473166|a1f5197f7659abf4b8e80ab8466376f4fc07547b";n_c=1;q_c1=2cec877c31cc4356b9e7b084691b63ee|1482473278000|1482473278000;r_cap_id="Mjc5NGJkYjczYjUzNGI1ZmIwMjhkNjJlYjBhOTFlMTk=|1482473280|69642017299b153c5624f3aec5207f1ab6af717a";z_c0=Mi4wQUFDQThlNG9BQUFBVU1MT0dhWUtDeGNBQUFCaEFsVk5XVXlFV0FCU05zYzMyVFptb21OY2Vmc1BlV3N5elkwaHpB|1482473307|446e05da8befe9e4b7cea169aa411744ea4b4df1;_xsrf=aad404bcb73cf6cee2ce67dc511504e9',

    5=>'__utma=51854390.1394274446.1482508901.1482508901.1482508901.1;__utmb=51854390.4.10.1482508901;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482508901.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=597d537d-06fb-47f7-9d4b-14d0d0085cf6;cap_id="NjJkMmExYzdlMDI4NDhmOWFhMTFlMTUyNDM5MGE0OTA=|1482508898|09a2d0e77af14d23f374fc858bea052a3e015ddb";d_c0="AIBCClAuCwuPTlib0Yj6qv3oXNeSy2di36w=|1482508899";l_cap_id="NTE5ZGQ4ZGYzY2RiNDIzMzhkZDBiZjhhNmUwNDBkMzQ=|1482508898|19c697285acc477945205f8de3a529f58db10e31";login="MjliZWEyZjMxY2M1NDQxNzkyN2JlNWUyZjM2ZTEzMGY=|1482508917|deaa24af3c4ba9bfdba46fc7af2c618550d353ab";n_c=1;q_c1=b75eab80acc94e6a8da307e0efa68f4b|1482508898000|1482508898000;r_cap_id="N2Q5YzdmZTU5ZjM0NDMyODk1NjhlMGEwYmQyMTgyMmM=|1482508900|724185d0ee7fab557d1721e35e4bbca66c7a91c4";z_c0=Mi4wQUFBQTktNG9BQUFBZ0VJS1VDNExDeGNBQUFCaEFsVk5kZGVFV0FBdFVMTzEtX0RRaFNiby1yc3BmbjNRWElzVS1R|1482508920|840856e54cf6f65a30278ad3934785ccd406abe9;_xsrf=6c167b32dddb0768dd5156c2d69ca772',

    6=>'__utma=51854390.1394274446.1482508901.1482508901.1482508901.1;__utmb=51854390.10.10.1482508901;__utmc=51854390;__utmt=1;__utmv=51854390.100--|2=registration_date=20140320=1^3=entry_date=20140320=1;__utmz=51854390.1482508901.1.1.utmcsr=zhihu.com|utmccn=(referral)|utmcmd=referral|utmcct=/;_zap=597d537d-06fb-47f7-9d4b-14d0d0085cf6;cap_id="YjI2MWMyMmZmN2JmNDUzOGFmNjZiYTI5MmMwYmQ0Yjk=|1482509733|11980a8285ead221739c3e563661867538da4b5c";d_c0="AIBCClAuCwuPTlib0Yj6qv3oXNeSy2di36w=|1482508899";l_cap_id="OWIxOGIxYzk1YTRiNGIxMTkyOWI2NTk3OTI5MDUxMTk=|1482509733|454943a4a370d6049dee6b0882ef73f9ead34cc5";login="MDcwMTJhYzQ5NjQwNDA2MTlmOWVlNzI1NDY5ZmY0ZGI=|1482509749|e947ada91e93798b1dbdaf291824981d9c5ecf30";n_c=1;q_c1=b75eab80acc94e6a8da307e0efa68f4b|1482508898000|1482508898000;r_cap_id="NmY0ZGQ0NDA4MzAzNDBiMmJlZTc4OGEzYzkyYWM4YTk=|1482509734|5b2c40105dca3d43393a2b9f7dd7c7cce1ce5499";z_c0=Mi4wQUFDQXQtNG9BQUFBZ0VJS1VDNExDeGNBQUFCaEFsVk50ZHFFV0FCQmdsalpxUWVzdHEwdDRRMy01TkU1aFZtYWRB|1482509754|bca48e97e41c3678aa2e33905124fd3dc9896026;_xsrf=6c167b32dddb0768dd5156c2d69ca772',

);

requests::set_header('Cookie', $cookie_arr[6]);


// 获取所有知乎话题链接

// 获取话题基本信息接口https://www.zhihu.com/node/TopicProfileCardV2?params={"url_token":"20060030"}


$root_url = "https://www.zhihu.com/topic/19776749/organize/entire";
$r   = requests::get($root_url);
$arr = selector::select($r, "@<input type=\"hidden\" name=\"_xsrf\" value=\"(.*)\"/>@", 'regex');
$post_data = array('_xsrf' => $arr);

//设置代理
$proxies = array(
    'http' => 'http://H63CM9OB7937832P:635BCC6BACA7E2C1@proxy.abuyun.com:9010',
    //'https' => 'http://user:pass@host:port',
);

//requests::set_proxies($proxies);

$root = requests::post($root_url, $post_data);

var_dump($root);

//https://www.zhihu.com/topic/19776749/organize/entire?child=&parent=19615623 //显示子话题
//https://www.zhihu.com/topic/19776749/organize/entire?child=19746496&parent=19776751 // 加载更多

$topic_root = json_decode($root, true);
$topic_root = $topic_root['msg'];


$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
    );



echo '<pre>';

//print_r($topic_root);
//die();

zh_topic_tree($topic_root[1][5], $post_data, $cookie_arr);

function zh_topic_tree($root, $post_data, $cookie_arr)
{

    if (count($root) >= 2)
    {
        $root_item  = $root[0];
        $child_item = $root[1];

        if ($root_item[0] == 'load' && empty($child_item))
        { //print_r("kong"); 循环子级话题

            sleep(1);

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

            sleep(1);

            $more_url   = "https://www.zhihu.com/topic/19776749/organize/entire?child={$root_item['2']}&parent={$root_item[3]}";

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
                zh_topic_tree($child, $post_data, $cookie_arr);

            }

        }


    }

}


