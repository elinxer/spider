<?php
/**
 * 爬取图吧数据 mapbar.com
 * 2017-03-11
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');

for ($i=1;$i<=31917708; $i++)
{
    $pageSize = 100;
    $first = ($i-1)*$pageSize;
    $last  = $first + $pageSize;
    if ($i == 1) {
        $limit = "0,100";
    }else {
        $limit = "{$first},{$last}";
    }

    $rs = db::get_all("select * from spider_mapbar_location_name where is_load=0 limit {$limit}");
    if (empty($rs)) {
        echo "data is done for loader\n\r";
        break;
    }
    foreach ($rs as $k => $item)
    {
        $url = $item['url'];
        $html = requests::get($url);
        $detail = selector::select($html, "//div[contains(@class, 'POI_wrap')]");
        $insert = [
            'location_name_id' => (int)$item['id'],
            'content' => htmlspecialchars($detail)
        ];
        db::insert('spider_mapbar_location_info', $insert);
        db::update('spider_mapbar_location_name', ['is_load'=>1], "id={$item['id']}");
    }
    echo "this part is done:{$first}-{$last}\n\r";
}

die();

//

$rs = db::get_all("select * from spider_mapbar_location where id>19272");

foreach ($rs as $k => $item)
{
    $url = $item['url'];
    $html = requests::get($url);
    $html = str_replace('&#13;', '', $html);
    $html = selector::select($html, "//div[contains(@class, 'sortC')]/dl/dd");

    if (is_array($html))
    {
        $insert = [];
        foreach ($html as $hk => $hv)
        {

            $hv_rs_text = selector::select($hv, "//a");
            $hv_rs_href = selector::select($hv, "//a/@href");
            if (!is_array($hv_rs_text) && !empty($hv_rs_text))
            {
                $hv_rs_text = array($hv_rs_text);
                $hv_rs_href = array($hv_rs_href);
            }

            foreach ($hv_rs_text as $hvk=>$hvv)
            {
                $insert[] = [
                    'location_id' => $item['id'],
                    'name' => $hvv,
                    'url' => $hv_rs_href[$hvk],
                    'created_at' => date('Y-m-d H:i:s'),
                ];
            }

        }
        echo "spider and fix done,prepare insert...\n\r";
        db::insert_batch('spider_mapbar_location_name', $insert);
        echo "success insert,begin next location:{$item['id']}\n\r";
    }
    else{
        echo "jump error data,begin next location:{$item['id']}\n\r";
    }
}




die();

// 爬取城市地标数据
$rs = db::get_all("select * from spider_mapbar where 1");

foreach ($rs as $k => $item)
{
    $url = $item['url'];
    $html = requests::get($url);
    $html = str_replace('&#13;', '', $html);
    $html = selector::select($html, "//div[contains(@class, 'sort')]");
    $html = selector::select($html[0], "//div[contains(@class, 'isortRow')]");

    if (is_array($html))
    {
        $html_str = '';
        foreach ($html as $v)
        {
            $html_str .= $v;
        }
        $html_tag = selector::select($html_str, "//h3");
        $html_arr = selector::select($html_str, "//div[contains(@class, 'isortBox')]");
        $insert = array();
        foreach ($html_tag as $kt => $t)
        {
            $html_arr_a_text = selector::select($html_arr[$kt], "//a");
            $html_arr_a_href = selector::select($html_arr[$kt], "//a/@href");

            $t = strip_tags($t);
            $t = str_replace('&#13;', '', $t);
            $t = trim($t);

            if (!is_array($html_arr_a_text))
            {
                echo "the TAG only one.\n\r";
                $html_arr_a_text = trim($html_arr_a_text);
                if (empty($html_arr_a_text))
                {
                    continue;
                }

                $insert[] = [
                    'city_id' => $item['id'],
                    'cat_name' => $t,
                    'name' => $html_arr_a_text,
                    'url' => $html_arr_a_href,
                    'created_at' => date('Y-m-d H:i:s')
                ];
                continue;
            }

            foreach ($html_arr_a_text as $nk => $name)
            {
                $insert[] = [
                    'city_id' => $item['id'],
                    'cat_name' => $t,
                    'name' => $name,
                    'url' => $html_arr_a_href[$nk],
                    'created_at' => date('Y-m-d H:i:s')
                ];
            }
        }
        echo "spider and fix done,prepare insert...\n\r";
        db::insert_batch('spider_mapbar_location', $insert);
        echo "success insert,begin next city:{$item['id']}\n\r";
    }
    else{
        echo "jump error data,begin next city:{$item['id']}\n\r";
    }
}


die();

// 获取城市列表
$url = 'http://poi.mapbar.com/';
$html = requests::get($url);
$html = selector::select($html, "//dl[@id='city_list']");
$a_text = selector::select($html, "//a");
$a_href = selector::select($html, "//a/@href");

foreach ($a_text as $k => $item)
{
    $insert[] = [
        'name' => $item,
        'url'=> $a_href[$k],
    ];
}

//db::insert_batch('spider_mapbar', $insert);
