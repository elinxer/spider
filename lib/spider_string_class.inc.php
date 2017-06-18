<?php


/**
 * Class spider_str_class
 */
class spider_string_class {

    /**
     * gbk 转 utf-8
     * @param $str
     * @return string
     */
    function gbk_to_utf8($str){
        return mb_convert_encoding($str, 'utf-8', 'gbk');
    }


    /**
     * utf-8 转 gbk
     * @param $str
     * @return string
     */
    function utf8_to_gbk($str){
        return mb_convert_encoding($str, 'gbk', 'utf-8');
    }

    /**
     * 数组返回组合字符串
     * @param $arr
     * @param string $impode
     * @param string $field
     * @return string
     */
    public function array_to_str($arr, $impode=";", $field="")
    {
        if(empty($arr)) return '';

        $str_arr = array();
        foreach ($arr as $item) {
            $item = $field?$item[$field]:$item;
            if(is_array($item)) { // 不合并数组
                return '';
            }
            $str_arr[] = $item;
        }

        return implode($impode, $str_arr);
    }

    /**
     * 多个连续空格只保留一个
     *
     * @param string $string 待转换的字符串
     * @return string
     */
    static public function merge_spaces ( $string )
    {
        return preg_replace ( "/\s(?=\s)/","\\1", $string );
    }

    /**
     * 多个连续换行只保留一个
     *
     * @param string $string 待转换的字符串
     * @return string
     */
    static public function merge_wrap_line ( $string )
    {
        return preg_replace ( "/\n(?=\n)/","\\1", $string );
        //return preg_replace ( "/\n\n(?=\n)/","\\1", $string ); // 两个以上
    }

}