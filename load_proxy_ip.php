<?php

ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

/* Do NOT delete this comment */
/* 不要删除这段注释 */

$configs = array(
    'name' => 'ip抓取',
    //'log_show' => true,
    'tasknum' => 1,
    //'save_running_state' => true,
    'domains' => array(
        'xicidaili.com',
        'www.xicidaili.com'
    ),
    'scan_urls' => array(
        'http://www.xicidaili.com/wn/1'
    ),
    'list_url_regexes' => array(
        //"http://www.xicidaili.com/wn/\d+"
    ),
    'content_url_regexes' => array(
        "http://www.xicidaili.com/wn/\d+"
    ),
    'max_try' => 5,

    'export' => array(
        'type'  => 'sql',
        'file'  => PATH_DATA.'/spider_proxy_ip.sql',
        'table' => 'spider_proxy_ip',
    ),


    'fields' => array(
        array(
            'name' => "ip",
            'selector' => "//tr[contains(@class, 'odd')]/td[2]",
            'required' => true,
        ),
        array(
            'name' => "port",
            'selector' => "//tr[contains(@class, 'odd')]/td[3]",
            'required' => true,
        ),
        array(
            'name' => "address",
            'selector' => "//tr[contains(@class, 'odd')]/td[4]/a",
            'required' => true,
        ),
        array(
            'name' => "anonymous",
            'selector' => "//tr[contains(@class, 'odd')]/td[5]",
            'required' => true,
        ),
        array(
            'name' => "live_day",
            'selector' => "//tr[contains(@class, 'odd')]/td[9]",
            'required' => true,
        ),
    ),
);
$spider = new phpspider($configs);

$spider->start();

