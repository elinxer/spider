<?php

/**
 * 通用入库网络图片
 */
ignore_user_abort();
set_time_limit(7200);

ini_set("memory_limit", "2048M");

set_time_limit(700);

error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';
require dirname(__FILE__).'/phpspider/core/db.php';
require dirname(__FILE__).'/phpspider/core/util.php';


$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'zhiteer',
);

$num       = isset($_GET['num'])?$_GET['num']:100; // 张数
$count     = isset($_GET['count'])?$_GET['count']:1; // 最大数
$page_num  = isset($_GET['page_num'])?$_GET['page_num']:1; // 每次下载数

$channel   = "zh_avater";

$where_str = "url=''";

$img_link  = db::get_all("select * from user_author WHERE {$where_str} ");

foreach ($img_link as $k => $item) {


    $global_dir  = trim(chunk_split(substr(abs(crc32(md5(rand(0, 1000)))),0, 4), 2,'/'),'/');

    $upload_path =   "/static/avater/" . $global_dir . '/';

    if(!is_dir(__DIR__ . $upload_path)) {
        @mkdir(iconv("UTF-8", "GBK", __DIR__ . $upload_path), 0777, true);
    }

    $source_url = $item['author_avater'];

    $img_token = md5($source_url);

    // 获取远程图片数据
    $file_result = util::http_get_data($source_url);

    if (empty($file_result['data'])) {
        echo "文件数据缺少<br>";
        continue;
    }

    $file_info   = $file_result['info'];

    $mime = explode('/', $file_info['content_type']);
    $mime = $mime[1];

    switch ($mime) { //支持后缀
        case 'jpeg':
            $img_mime = 'jpg';
            break;
        case 'git':
            $img_mime = 'gif';
            break;
        default:
            $img_mime = 'jpg';
            break;
    }

    if(strlen($file_result['data']) != ($file_info['download_content_length'])) {

        if($mime == 'jpg') {

            $picturedata = substr($file_result['data'], -2, 2);
            $end_code    = urlencode($picturedata);
            if($end_code != '%FF%D9') {
                continue;
            }

        }

        echo "文件数据缺少<br>";
        continue;
    }

    $file_name = $img_token;
    $file_path = "{$upload_path}{$file_name}.{$img_mime}";


    if(!empty($file_info)) {

        if(file_exists($file_path)) {
            if(strpos ($file_path, 'img/static') <= 0) {
                @unlink($file_path);
            }
        }

        echo '入库：'.$file_path;


        $re    = file_put_contents(__DIR__ . $file_path, $file_result['data']);


        $update_arr = array(

            'url'    => $file_path
        );

        $where_str = "author_id={$item['author_id']}";
        if($re > 0)
        {
            db::update('user_author', $update_arr, $where_str);
            echo "入库成功<br>";
        } else {
            echo "入库失败<br>";
        }
    }

    

}
echo 'done';
