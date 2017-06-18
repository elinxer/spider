<?php

/**
 * Elinx
 * Class spider_file_class
 */


class spider_file_class {


    /**
     * 生成随机目录结构【离散】
     * @param int $total_num 总数量
     * @param int $per_num 每份数量
     * @return string
     */
    public function get_rand_directory($total_num=4, $per_num=2)
    {
        $rand_num  = rand(0, 1000);
        $hash      = md5($rand_num);
        $count_str = abs(crc32($hash));
        $sub_str   = substr($count_str, 0, $total_num);
        $chunk_str = chunk_split($sub_str, $per_num, '/');
        $rand_dir  = trim($chunk_str, '/');

        return $rand_dir;
        //return trim(chunk_split(substr(abs(crc32(md5(rand(0, 1000)))),0, $total_num), $per_num,'/'),'/');
    }







}



