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