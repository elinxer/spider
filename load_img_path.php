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

$img_tbl = "spider.spider_images";

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);

$num       = isset($_GET['num'])?$_GET['num']:100; // 张数
$count     = isset($_GET['count'])?$_GET['count']:1; // 最大数
$page_num  = isset($_GET['page_num'])?$_GET['page_num']:1; // 每次下载数

$channel   = "zh";

$where_str = "url='' and channel='zhihu' and having_load!=1";
$limit     = "0, {$page_num}";

$img_link  = db::get_one("select * from {$img_tbl} WHERE {$where_str} limit {$limit}");

var_dump($img_link);

if(empty($img_link)) die('图片已经全部加载完成');

$img_link = array($img_link);

foreach ($img_link as $k => $item) {

    if ($item['having_load'] == 1) continue;
    db::update($img_tbl, array('having_load'=>1), "img_id={$item['img_id']}");

    $img_token = $item['token'];

    $global_dir  = trim(chunk_split(substr(abs(crc32(md5(rand(0, 1000)))),0, 4), 2,'/'),'/');

    $upload_path =   "/static/{$channel}/" . $global_dir . '/';

    if(!is_dir(__DIR__ . $upload_path)) {
        @mkdir(iconv("UTF-8", "GBK", __DIR__ . $upload_path), 0777, true);
    }

    $source_url = $item['source_url'];

    // 获取远程图片数据
    $file_result = util::http_get_data($source_url);
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
        $imgsi = getimagesize($item['source_url']); //获取图片数据

        $update_arr = array(
            'url'    => $file_path,
            'path'   => $file_path,
            'token'  => $file_name,
            'width'  => $imgsi[0],
            'height' => $imgsi[1],
        );

        $where_str = "img_id={$item['img_id']}";
        if($re > 0)
        {
            db::update($img_tbl, $update_arr, $where_str);
            echo "入库成功<br>";
        } else {
            echo "入库失败<br>";
        }
    }

    if($count > $num) break;

    usleep(300);

    $count = $count + 1;
    echo "<script>window.location.href='http://127.0.0.2/spider/load_img_path.php?num={$num}&count={$count}';</script>";

}
