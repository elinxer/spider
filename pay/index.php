<?php
/**
 * 爬脚本之家的所有php文章
 */
//header("Content-type: text/html; charset=GBK");
ini_set("memory_limit", "2048M");
require dirname(__FILE__).'/../phpspider/core/init.php';

requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.75 Safari/537.36');

requests::set_header('cookie', 'ALIPAYJSESSIONID=GZ00HFyoqMX2IzN3FbIpXwlFpk0v3kconsumeprodGZ00');

requests::$input_encoding = 'GBK';
requests::$output_encoding = 'utf-8';
$r = requests::get('https://consumeprod.alipay.com/record/standard.htm');

requests::get_response_headers($r);

requests::get_response_cookies('https://consumeprod.alipay.com');
var_dump(requests::$info);
var_dump(requests::$domain_cookies);
if (requests::$status_code != 200) {
    echo date('Y-m-d H:i:s');
    die('请求失败');
}
$r = selector::remove($r, "//script");
preg_match_all('#<table class="ui-record-table table-index-bill" id="tradeRecordsIndex" width="100%">(.*)</table>#iUs',$r, $arr);

$html = $arr[0][0];
$arr = selector::select($html, "//tr");

foreach ($arr as $k=> $item)
{
    if ($k==0) continue;
    $tr = $item;
    $td = selector::select($tr, "//td[3]");
    $orderIds[] = selector::select($td, "//a/@data-clipboard-text");
}
print_r($orderIds);

?>

<script>
    setTimeout(function(){
        //10s后要执行的代码
        location.reload();
    }, 5000);
</script>

