<?php

ini_set("memory_limit", "20M");
require dirname(__FILE__).'/phpspider/core/init.php';

$url = $_GET["url"];

requests::read_img($url);

/**
 * CURL 读取远程图片数据
 *
 * @param $url
 * @param int $pattern
 * @return array|bool
 */
function read_img($url, $pattern=1)
{
    if (!empty($url) && $pattern == 1)
    {
        $url = str_replace("http:/","http://",$url);
        $dir = pathinfo($url);
        $host = $dir['dirname'];
        $refer = $host.'/';
        $ch = curl_init($url);
        curl_setopt ($ch, CURLOPT_REFERER, $refer);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_BINARYTRANSFER, 1);
        $data = curl_exec($ch);
        curl_close($ch);
        $ext = strtolower(substr(strrchr($url,'.'),1,10));
        $types = array(
            'gif'=>'image/gif',
            'jpeg'=>'image/jpeg',
            'jpg'=>'image/jpeg',
            'jpe'=>'image/jpeg',
            'png'=>'image/png',
        );
        $type = $types[$ext] ? $types[$ext] : 'image/jpeg';
        return array('type'=>$type, 'data'=>$data);
    }
    return false;
}


/**
 * 获取远程文件数据
 *
 * @param $url
 * @return array
 */
function http_get_data($url) {

    $ch = curl_init ();
    curl_setopt ( $ch, CURLOPT_CUSTOMREQUEST, 'GET' );
    curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt ( $ch, CURLOPT_URL, $url );
    ob_start ();
    curl_exec ( $ch );
    $return_content = ob_get_contents ();
    ob_end_clean ();

    $return_code = curl_getinfo ($ch);
    return array('info'=>$return_code, 'data'=>$return_content);
}
