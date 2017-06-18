<?php


class spider_array_class {


    /**
     * 均匀地去指定数组数据里取N等份
     * @param $ori_arr
     * @param $patt_count 2W无压力
     * @return array
     */
    public function chunk_arr_eq($ori_arr,  $patt_count=1) {

        if(empty($ori_arr)) die();

        $ori_count     = count($ori_arr);

        if($patt_count > $ori_count) die();

        $rate_num      = floor($ori_count/$patt_count);
        $chunk_ori_arr = array_chunk($ori_arr, $rate_num);


        if(count(end($chunk_ori_arr)) < $rate_num) {
            array_pop($chunk_ori_arr);
        }


        $add_num = $patt_count - count($chunk_ori_arr);

        $add_arr = array();
        if($add_num > 0) {
            $add_arr_key   = array_rand($ori_arr, $add_num);
            foreach ($add_arr_key as $item) {
                $add_arr[] = $ori_arr[$item];
            }
        }

        $return = array();
        foreach ($chunk_ori_arr as $k=>$item) {
            $return[] = $item[array_rand($item, 1)];
        }

        $return = array_merge($add_arr, $return);

        shuffle($return);

        if(count($return) >= $patt_count) {

            $return_key = array_rand($return, $patt_count);

            $tmp = array();
            foreach ($return_key as $k=>$item) {
                $tmp[] = $return[$item];
            }

            $return = $tmp;
        }

        return $return;
    }


}