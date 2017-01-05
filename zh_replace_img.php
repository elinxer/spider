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


$result = $db_obj->_get_data_from_table("zhiteer.article_list", false, $where, '*', '0,100');

echo "还有文章：" . count($result);
echo '<br>';

//print_r($result);die();

foreach ($result as $k=> $item) {

    echo $item['link'] . '<br>';

    $content    = $item['ori_content'];
    $img_links  = $spider_factory_obj->F('zzc_bbs:get_img_from_article', $content, 2, '/face/');

    echo $bbs_id = $item['id'];

    $tokens_arr = array();
    $img_re     = array();
    $img_insert_arr = array();

    foreach ($img_links['img_links'] as $ik=>$iv)
    {

        
        $link  = current(explode('?imageView2', $iv));
        $token = md5($link);

        $tokens_arr[] = "'".$token."'";

        $where = "token='{$token}' and url!=''";
        list($img) = $db_obj->_get_data_from_table($img_tbl, false, $where, 'token,url');

        if(!empty($img))
        {
            $img_re[] = $img;
        } else
        {
            $img_insert_arr[] = array(
                'token'      => $token,
                'source_url' => $link,
                'channel'    => $config['img_channel'],
                'add_time'   => time(),
            );
        }

    }

    //print_r($img_insert_arr);die();
    //$db_obj->_insert_tbl_filed($img_insert_arr, $img_tbl, true);

    if((count($tokens_arr)) != (count($img_re)))
    {
        echo $k .'___';
        echo count($tokens_arr);
        echo '<=>'.count($img_re);
        echo "该篇文章id:({$item['id']})图片未下载完成<br>";
        continue;
    }

    $img_links = array();
    foreach ($img_re as $r)
    {
        $img_links[$r['token']] = $r['url'];
    }

    $pregRule  = "/<img.*?src=\"(.*?)\".*?>/is";
    $content   = preg_replace_callback( $pregRule,

        function ($matches) use ($img_links) {

            $link     = current(explode('?imageView2', $matches[1]));
            $token    = md5($link);
            $img_link = $img_links[$token];
            $img_link = str_replace("img.zuzuche.com", "imgcdn1.zuzuche.com", $img_link);

            if(!empty($img_link)) {
                $img_link .= "!/fw/1000/unsharp/true/quality/50/format/jpg";
                return $matches[0] = str_replace($matches[1], $img_link, $matches[0]);
            }

            if (strpos($link, '/face/') !== false) { //去掉表情图片
                return '';
            }

            return $matches[0];
        },
        $content
    );

    if(!empty($content))
    {
        $update_arr = array(
            'id'      => $item['id'],
            'is_done' => 1,
            'content' => $content,
            'markdown_content' => $markdown_content,
        );

        echo $markdown_content;
        echo '<br>------------';
        echo $content; die();

        $where_str = "id={$item['id']}";
        //print_r($update_arr);die();
        $db_obj->_update_table_filed($update_arr, $bbs_tbl, $where_str);
    }
    else {
        echo '内容为空';
    }

}