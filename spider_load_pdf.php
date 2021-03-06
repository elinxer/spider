<?php

ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'zhiteer',
);

fwrite(STDOUT,'in => id:');
$topic_pid = fgets(STDIN);
fwrite(STDOUT,'in => dir:');
$dir_name = fgets(STDIN);

if (empty($topic_pid) || empty($dir_name))
{
    echo 'id & dir empty';
    exit();
}
//177,178,179,182,184,186

$dir_name = str_replace("\r\n", '', $dir_name);


$url_list = db::get_all("SELECT * FROM zhiteer.`papers` WHERE topic_pid={$topic_pid}");


requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');

foreach ($url_list as $k => $item)
{

    $pdf_link = $item['link'];
    $pdf_link = preg_replace('/.*?(\d{6}-\d+).*/is', 'http://www.paper.edu.cn/download/downPaper/$1', $pdf_link);

    $hash = md5($pdf_link);
    if (db::get_one("select * from zhiteer.paper_pdf WHERE hash='{$hash}'"))
    {
        echo $hash . " exsits \n";
        continue;
    }
    requests::set_referer($item['refer_url']);
    $con = requests::get($pdf_link);

    $path = '/pdf/'.$dir_name.'/' . $hash .'.pdf';

    $file_path = __DIR__ . '/../../wwwfile' . $path;

    if (strlen($con) > 1024 * 10)
    {
        file_put_contents($file_path, $con);
        echo $hash . " donwloaded \n";

        db::insert("paper_pdf", array('hash'=>$hash, 'path'=>$path, 'pdf_link'=>$pdf_link));
        db::update('papers', ['pdf_hash'=>$hash], "id={$item['id']}");

    }

}


