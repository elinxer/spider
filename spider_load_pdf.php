<?php

ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);
$keji_tbl = "spider.spider_keji";

$url_list = db::get_all("SELECT * FROM `spider_keji` WHERE page_link like '%/subject/110%';");
requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');

foreach ($url_list as $k => $item)
{

    $pdf_link = $item['link'];
    $pdf_link = preg_replace('/.*?(\d{6}-\d+).*/is', 'http://www.paper.edu.cn/download/downPaper/$1', $pdf_link);

    // http://www.paper.edu.cn/releasepaper/content/200902-1.html
    $hash = md5($pdf_link);
    if (db::get_one("select * from spider_pdf WHERE hash='{$hash}'"))
    {
        echo $hash . " exsits \n";
        continue;
    }
    requests::set_referer($item['refer_url']);
    $con = requests::get($pdf_link);

    $path = '/pdf/math/' . $hash .'.pdf';
    $file_path = __DIR__ . '/../wwwfile' . $path;
    if (strlen($con) > 1024 * 10)
    {
        file_put_contents($file_path, $con);
        echo $hash . " donwloaded \n";
        db::insert("spider_pdf", array('hash'=>$hash, 'path'=>$path, 'pdf_link'=>$pdf_link));
    }

}


