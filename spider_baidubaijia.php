<?php
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

/* Do NOT delete this comment */
/* 不要删除这段注释 */


$configs = array(
    'name' => '百度百家网',
    'tasknum' => 1,
    'log_show' => true,
    'domains' => array(
        'liuzhaohui.baijia.baidu.com'
    ),

    'export' => array(

        'type' => 'csv',
        'file' => __DIR__ . '/baidubaijia.csv',
    ),

    'scan_urls' => array(
        "http://liuzhaohui.baijia.baidu.com/",
    ),
    'list_url_regexes' => array(
        "http://liuzhaohui.baijia.baidu.com/"
    ),
    'content_url_regexes' => array(
        "http://liuzhaohui.baijia.baidu.com/article/\d+",
    ),
    'fields' => array(
        // 标题
        array(
            'name' => "title",
            'selector' => "//div[@id='page']/h1",
            'required' => true,
        ),


    ),
);

$spider = new phpspider($configs);

//$spider->on_status_code = function($status_code, $url, $content, $phpspider)
//{
//    //	如果状态码为429，说明对方网站设置了不让同一个客户端同时请求太多次
//    if ($status_code	==	'429')
//    {
//        //将url插入待爬的队列中,等待再次爬取
//        $phpspider->add_url($url);
//        //当前页先不处理了
//        return	false;
//    }
//
//    // 不拦截的状态码这里记得要返回，否则后面内容就都空了
//    return	$content;
//};


//$spider->on_extract_field = function($fieldname, $data, $page)
//{
//    return $data;
//};

////判断当前网页是否被反爬虫了
//$spider->is_anti_spider = function($url, $content, $phpspider)
//{
//    // $content中包含"404页面不存在"字符串
//    if (strpos($content, "404页面不存在") !== false)
//    {
//        // 如果使用了代理IP，IP切换需要时间，这里可以添加到队列等下次换了IP再抓取
//        // $phpspider->add_url($url);
//        return true; // 告诉框架网页被反爬虫了，不要继续处理它
//    }
//    // 当前页面没有被反爬虫，可以继续处理
//    return false;
//};

////网页下载完成之后调用. 主要用来对下载的网页进行处理
//$spider->on_download_page = function($page, $phpspider)
//{
//    $page_html = "<div id=\"comment-pages\"><span>5</span></div>";
//    $index = strpos($page['row'], "</body>");
//    $page['raw'] = substr($page['raw'], 0, $index) . $page_html
//        . substr($page['raw'], $index);
//    return $page;
//};

////生成一个新的url添加到待爬队列中，并通知爬虫不再从当前网页中发现待爬url
//$spider->on_scan_page = function($page, $content, $phpspider)
//{
//    $array = json_decode($page['raw'], true);
//    foreach ($array as $v)
//    {
//        $lastid = $v['id'];
//        // 生成一个新的url
//        $url = $page['url'] . $lastid;
//        // 将新的url插入待爬的队列中
//        $phpspider->add_url($url);
//    }
//
//    // 通知爬虫不再从当前网页中发现待爬url
//    return false;
//};



$spider->start();

