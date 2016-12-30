<?php
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

/* Do NOT delete this comment */
/* 不要删除这段注释 */

$configs = array(
    'name' => 'u17_comic',
    'log_show' => true,
    'tasknum' => 1,
    'max_try' => 3,
    //'interval' => 3000,
    //'save_running_state' => true,
    'domains' => array(
        'www.u17.com',
    ),
    'scan_urls' => array(
        'http://www.u17.com/comic_list/th99_gr99_ca99_ss0_ob9_ac99_as0_wm0_co99_ct99_p1.html'
    ),
    'list_url_regexes' => array(
        "http://www.u17.com/comic_list/th99_gr99_ca99_ss0_ob9_ac99_as0_wm0_co99_ct99_p\d+.html$"
    ),
    'content_url_regexes' => array(
        "http://www.u17.com/comic/\d+.html$",
    ),
    'export' => array(
        'type' => 'csv',
        'file' => PATH_DATA.'/u17_comic.csv',
    ),
    //'export' => array(
    //'type'  => 'sql',
    //'file'  => PATH_DATA.'/qiushibaike.sql',
    //'table' => 'content',
    //),
//    'export' => array(
//        'type' => 'db',
//        'table' => 'iijuzi',
//    ),
    'fields' => array(
        array(
            'name' => "comic_name",
            'selector' => "//div[@class='comic_info']/div[@class='left']//h1[contains(@class,'fl')]",
            'required' => true,
        ),
        array(
            'name' => "comic_chapter",
            'selector' => "//ul[@id='chapter']//a/@href",
            'required' => true,
            'repeated' => true,
        ),
        array(
            'name' => "comic_chapter_images",
            'selector' => "//ul[@id='chapter']//a/@href",
            'required' => true,
            'repeated' => true,
        ),
    ),
);

$spider = new phpspider($configs);
$spider->on_extract_field = function($fieldname, $data, $page)
{
    if($fieldname == 'comic_name'){
        $data = trim($data,'&#13;');
        $data = trim($data);
    }elseif ($fieldname == 'comic_chapter') {
        if(!empty($data) && is_array($data)){
            $data = implode('|',$data);
        }
    }elseif ($fieldname == 'comic_chapter_images'){
        $chapterImages = array();
        if(!empty($data)){
            if(!is_array($data)){
                $data = array($data);
            }
            //根据data数据去抓取数据
            foreach ($data as $item){

                $chapterHtml = requests::get($item);
                $chapterId = selector::select($item,'#http://www.u17.com/chapter/(\d+).html#','regex');
                $chapterId = intval($chapterId);
                if(!empty($chapterHtml)){
                    $imageStr = selector::select($chapterHtml,"/image_list:.*evalJSON\(\'(.*)\'\),/isU",'regex');
                    if(!empty($imageStr)){
                        $image_list = json_decode($imageStr,1);
                        foreach ($image_list as $img) {
                            $chapterImages[$chapterId][$img['image_id']] = base64_decode($img['src']);
                        }
                    }
                    log::debug('chapterImages=>'.var_export($chapterImages,1));
                }
            }
        }
        $data = serialize($chapterImages);
    }
    log::debug($fieldname.'=>'.var_export($data,1));
    return $data;
};

$spider->start();


