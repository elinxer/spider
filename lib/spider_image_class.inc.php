<?php


class spider_image_class {




    /**
     * 图片地址替换成压缩URL
     * @param string $content 内容
     * @param string $suffix 后缀
     * @return mixed|string
     */
    public function replace_img_thumb_url($content="",$suffix="!/fw/680/quality/75/format/jpg")
    {
        $pregRule = "/<[img|IMG].*?src=[\'|\"](.*?(?:[\.jpg|\.jpeg|\.png|\.gif|\.bmp]))[\'|\"].*?[\/]?>/";
        $content = preg_replace($pregRule, '<img src="${1}'.$suffix.'" />', $content);
        return $content;
    }


    /**
     * @author Elinx
     * 检查本地数据图片是否损坏-仅支持本地数据，远程网络数据不支持
     * @param array $arr 图书链接数组
     * $arr = array(array('path'=>'/upload/abc.jpg','other'=>''),)
     * @return array
     */
    public function check_img_is_full($arr)
    {
        if(empty($arr)) return array();

        foreach ($arr as $k => $item) {

            $path = $item['path'];
            $ext  = pathinfo($path, PATHINFO_EXTENSION);

            $not_full = 0;
            switch ($ext) { //支持后缀
                case 'jpeg':
                    if(@imagecreatefromjpeg($path) == false)  $not_full = 1;
                    break;
                case 'git':
                    if(@imagecreatefromgif($path) == false) $not_full = 1;
                    break;
                case 'png':
                    if(@imagecreatefrompng($path) == false) $not_full = 1;
                    break;
                case 'wbmp':
                    if(@imagecreatefromwbmp($path) == false) $not_full = 1;
                    break;
                default:
                    if(@imagecreatefromjpeg($path) == false) $not_full = 1;
                    break;
            }

            $arr[$k]['not_full'] = $not_full;
        }

        return $arr;
    }




}