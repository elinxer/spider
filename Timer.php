<?php
use Workerman\Worker;
use \Workerman\Lib\Timer;
require_once 'workerman/Autoloader.php';

//创建一个Worker监听127.0.0.1:8000, 使用websocket协议通讯
$ws_worker = new Worker("websocket://127.0.0.1:4001");

//启动4个进程对外提供服务
$ws_worker->count = 1;
//当接收到客户端发来的数据后显示数据并回发到客户端
$ws_worker->onMessage = function($connection, $data) {
    //显示数据
    echo "you just received: $data\n";

    //向客户端回发数据
    $connection->send("you just send: $data");
};

$ws_worker->onWorkerStart = function($ws_worker)
{
    // 每秒执行一次
    $time_interval = 20000;
    Timer::add($time_interval, function()
    {
        @file_get_contents('http://spider.zhiteer.com/news/post_art_china.php');
        @file_get_contents('http://spider.zhiteer.com/news/post_163_tech.php');
        @file_get_contents('http://spider.zhiteer.com/news/post_leiphone.php');
        @file_get_contents('http://spider.zhiteer.com/news/post_huxiu.php');
        @file_get_contents('http://spider.zhiteer.com/news/kanchai.php');
        echo "task run\n";
    });
};

//运行worker
$ws_worker->runAll();