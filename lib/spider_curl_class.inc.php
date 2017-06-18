<?php

/**
 * Class spider_curl_class
 */
class spider_curl_class {

    /**
     * 获取远程图片数据
     * @param $url
     * @return string
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

    /**
     * curl post 数据获取返回内容
     * @param string $url post地址
     * @param array $post_data 需要post的数据
     * @param array $header 需要post的http报头
     * @param array|bool $proxy_ip [24.5.151.253:8118]
     * @return array
     */
    public function curl_post($url, $post_data=array(), $header=array(), $proxy_ip=false) {
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
        if (@$proxy_ip != false) { //使用代理ip
            $header[] = 'Client_Ip: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255);
            $header[] = 'X-Forwarded-For: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255);
            // curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //开启会获取失败
            curl_setopt($curl, CURLOPT_PROXY, $proxy_ip);
        }
        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl,CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); // 解码压缩文件
        curl_setopt ($curl, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
        curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
        curl_setopt($curl, CURLOPT_TIMEOUT, 120); // 设置超时限制防止死循环
        $request = curl_exec($curl);
        $content = json_decode($request,true);
        curl_close($curl);
        if (!$content) {
            return $request;
        } else {
            return $content;
        }
    }


    /**
     * curl get 数据获取返回内容
     * @param string $url get地址
     * @param array $header 需要发送get的http报头
     * @return array
     */
    public function curl_get($url, $header = array(), $proxy_ip=false) {

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);

        if (@$proxy_ip != false) { //使用代理ip
            $header[] = 'Client_Ip: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255);
            $header[] = 'X-Forwarded-For: '.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255).'.'.mt_rand(0, 255);
            // curl_setopt($curl, CURLOPT_PROXYTYPE, CURLPROXY_HTTP); //开启会获取失败
            curl_setopt($curl, CURLOPT_PROXY, $proxy_ip);
        }

        if(empty($header)) {

            $header[] = "user-agent: Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.106 Safari/537.36";

        }

        curl_setopt($curl, CURLOPT_HTTPHEADER, $header);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_ENCODING, 'gzip,deflate'); // 解码压缩文件
        curl_setopt($curl, CURLOPT_COOKIEFILE, dirname(__FILE__).'/cookie.txt');
        curl_setopt($curl, CURLOPT_COOKIEJAR, dirname(__FILE__).'/cookie.txt');
        curl_setopt($curl, CURLOPT_TIMEOUT, 120); // 设置超时限制防止死循环
        $request = curl_exec($curl);
        $content = json_decode($request,true);

        curl_close($curl);
        if (!$content) {
            return $request;
        } else {
            return $content;
        }

    }


    // 优化版
    function get_contents_by_multi_url($url_array,$timeout=60,$max=16)
    {

        $url_array = array_values( $url_array );

        $timeout    =   $timeout*1;
        if($timeout<5||$timeout>120)    $timeout=60;

        $rs = array();
        $info_arr = array();
        $index = 0;
        $need = count( $url_array );
        $total = $need;
        $max = $need < $max ? $need : $max;
        $multi = curl_multi_init();

        for( $i = 0; $i < $max; $i++ )
        {
            $single = curl_init();
            curl_setopt( $single, CURLOPT_URL, $url_array[$index++] );
            curl_setopt( $single, CURLOPT_TIMEOUT, $timeout );
            curl_setopt( $single, CURLOPT_CONNECTTIMEOUT, 8 );
            curl_setopt( $single, CURLOPT_RETURNTRANSFER, 1 );
            curl_setopt( $single, CURLOPT_HEADER, 0 );
            curl_setopt( $single, CURLOPT_NOSIGNAL, true );
            curl_setopt( $single, CURLOPT_ENCODING, 'gzip, deflate');
            curl_setopt( $single, CURLOPT_REFERER, 'http://www.zhiteer.com/self/');
            curl_setopt( $single, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.24) Gecko/20111103 Firefox/3.6.24' );
            curl_multi_add_handle( $multi, $single );
        }

        do
        {
            if ( ( $status = curl_multi_exec( $multi, $active ) ) != CURLM_CALL_MULTI_PERFORM )
            {
                if ( $status != CURLM_OK ) break;

                while( $done = curl_multi_info_read( $multi ) )
                {
                    $rs[] = curl_multi_getcontent( $done['handle'] );
                    if ( isset( $_GET['_debug'] ) ) $info_arr[] = curl_getinfo( $done['handle'] );
                    curl_multi_remove_handle( $multi, $done['handle'] );
                    curl_close( $done['handle'] );

                    $need--;

                    if ( $need > 0 && isset( $url_array[$index] ) )
                    {
                        $single = curl_init();
                        curl_setopt( $single, CURLOPT_URL, $url_array[$index++] );
                        curl_setopt( $single, CURLOPT_TIMEOUT, $timeout );
                        curl_setopt( $single, CURLOPT_CONNECTTIMEOUT, 8 );
                        curl_setopt( $single, CURLOPT_RETURNTRANSFER, 1 );
                        curl_setopt( $single, CURLOPT_HEADER, 0 );
                        curl_setopt( $single, CURLOPT_NOSIGNAL, true );
                        curl_setopt( $single, CURLOPT_ENCODING, 'gzip, deflate');
                        curl_setopt( $single, CURLOPT_REFERER, 'http://www.zhiteer.com/self/');
                        curl_setopt( $single, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9.2.24) Gecko/20111103 Firefox/3.6.24' );
                        curl_multi_add_handle( $multi, $single );
                    }

                    if ( $active > 0 ) curl_multi_select( $multi, 0.5 );
                }
            }
        }
        while( $active > 0 );

        curl_multi_close( $multi );

        return $rs;
    }


}


