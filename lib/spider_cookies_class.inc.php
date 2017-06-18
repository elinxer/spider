<?php


class spider_cookies_class {


    /**
     * cookie json转string
     * @param string $str json数据
     * @return string
     */
    public function cookies_to_str_editthiscookie($str)
    {

        $cookies = json_decode($str, true);

        $cookie = array();
        foreach ($cookies as $item) {
            $cookie[] = $item['name'].'='.$item['value'];
        }

        return implode(';', $cookie);

    }


}