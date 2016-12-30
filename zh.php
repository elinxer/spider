<?php

ini_set("memory_limit", "2048M");

set_time_limit(0);

error_reporting(E_ALL);

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


for ($i=1; $i<=74; $i++)
{
    $url = 'http://www.xicidaili.com/wn/'.$i;

    $html = requests::get($url);

    $ips = selector::select($html, "//tr[contains(@class, 'odd')]");

    $insert = array();

    foreach ($ips as $k=>$item)
    {
        $ip = selector::select($item, "//td[2]");
        $port = selector::select($item, "//td[3]");
        $address = selector::select($item, "//td[4]/a");
        $anonymous = selector::select($item, "//td[5]");
        $protocol = selector::select($item, "//td[6]");
        $anonymous = selector::select($item, "//td[5]");
        $anonymous = selector::select($item, "//td[5]");
        $anonymous = selector::select($item, "//td[5]");
        $anonymous = selector::select($item, "//td[5]");

        $insert[] = array(
            'ip'=>$ip,
            'port'=>$port,
            'address' => $address,
            'anonymous' => $anonymous,
            'protocol' => strtolower($protocol),
        );

    }


    db::insert_batch('spider_proxy_ip', $insert);

}

