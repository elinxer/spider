<?php
/**
 * 手表大全
 */
ini_set("memory_limit", "1024M");
require dirname(__FILE__).'/phpspider/core/init.php';

$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);
requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
$list = db::get_all("select * from spider_watch where pid!=0 limit 10000,60000");

foreach ($list as $k =>$v)
{
    $url = str_replace('.html', '', $v['url']);
    for ($i=1; $i<=100; $i++)
    {
        $url = $url . '/p' .$i. '/';
        $html = requests::get($url);
        $arr = selector::select($html, "//div[contains(@class, 'item_r_l_in')]/ul/li");
        if (!empty($arr))
        {
            $insert = [];
            foreach ($arr as $lk=> $iv)
            {
                $title = selector::select($iv, "//h2/a");
                $title = strip_tags($title);
                $index_url   = selector::select($iv, "//h2/a/@href");
                $hash  = md5($index_url);
                $cat_id = $v['id'];
                $image = selector::select($iv, "//div/a/img/@src");
                $price = selector::select($iv, "//i[contains(@class, 'item_price')]");
                $price = str_replace('<b>￥</b>', '', $price);
                $price = trim($price);

                $insert[] = [
                    'title' => $title,
                    'image' => $image,
                    'price' => $price,
                    'url' => $index_url,
                    'hash' => $hash,
                    'cat_id' => $cat_id,
                    'created_at' => time()
                ];

            }
            db::insert_batch('spider_watch_list', $insert);
        }
        else
        {
            break;
        }
        echo $url .' done';
    }
}




die();

$list = db::get_all("select * from spider_watch where pid!=0");
foreach ($list as $k => $item)
{
    requests::set_useragent(' Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.87 Safari/537.36');
    $html = requests::get($item['url']);
    $sub_text = selector::select($html, "//div[contains(@class, 'item_sub_x')]/a");
    $sub_href = selector::select($html, "//div[contains(@class, 'item_sub_x')]/a/@href");
    foreach ($sub_text as $sk => $sv)
    {
        $parents[] = array(
            'pid' => $item['id'],
            'title' => $sv,
            'url' => 'http://www.iwatch365.com'.$sub_href[$sk],
            'created_at' => time()
        );
    }
    echo $item['id'] . " success\n\r";
    db::insert_batch("spider_watch", $parents);
}


die();

$str = '<div class="item_list">
              <div class="item_l_t">品&nbsp;&nbsp;牌</div>
              <div class="item_l_a">
              
                              
          
                                          
                <div class="item_sub_list gray_line">
                  <h2 class="orang_title">顶级奢华</h2>
                  <p> 
                                
                
                      <a href="/ap/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">爱彼</a>
                
                      <a href="/pp/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">百达翡丽</a>
                
                      <a href="/breguet/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宝玑</a>
                
                      <a href="/jb/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宝珀</a>
                
                      <a href="/piaget/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">伯爵</a>
                
                      <a href="/fm/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">法穆兰</a>
                
                      <a href="/go/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">格拉苏蒂原创</a>
                
                      <a href="/jlc/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">积家</a>
                
                      <a href="/vc/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">江诗丹顿</a>
                
                      <a href="/al/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">朗格</a>
                
                      <a href="/rm/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">理查德·米勒</a>
                
                      <a href="/rd/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">罗杰·杜彼</a>
                
                      <a href="/parmigiani/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">帕玛强尼</a>
                
                      <a href="/un/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">雅典</a>
                
                      <a href="/jd/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">雅克德罗</a>
                
                      <a href="/hublot/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宇舶</a>
                
                      <a href="/gp/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">芝柏</a>
                
                      <a href="/hm/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">亨利慕时</a>
                
                      <a href="/gf/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">高珀富斯</a>
                
                      <a href="/bovet/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">播威</a>
                
                      <a href="/christorphe/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">Christopheclaret</a>
                
                      <a href="/debethune/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">debethune</a>
                
                      <a href="/dewitt/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">迪威特</a>
                                
               </p>
               
               	         
                </div>
                              
                <div class="item_sub_list gray_line">
                  <h2 class="orang_title">经典奢华</h2>
                  <p> 
                                
                
                      <a href="/iwc/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">万国</a>
                
                      <a href="/breitling/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">百年灵</a>
                
                      <a href="/br/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">柏莱士</a>
                
                      <a href="/bvlgari/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宝格丽</a>
                
                      <a href="/vca/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">梵克雅宝</a>
                
                      <a href="/cartier/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">卡地亚</a>
                
                      <a href="/corum/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">昆仑</a>
                
                      <a href="/rolex/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">劳力士</a>
                
                      <a href="/omega/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">欧米茄</a>
                
                      <a href="/panerai/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">沛纳海</a>
                
                      <a href="/chronoswiss/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">瑞宝</a>
                
                      <a href="/chopard/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">萧邦</a>
                
                      <a href="/zenith/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">真力时</a>
                
                      <a href="/juvenia/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">尊皇</a>
                
                      <a href="/arminstrom/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">Arminstrom</a>
                
                      <a href="/arnoldandson/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">亚诺表</a>
                
                      <a href="/hautlence/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">豪朗时</a>
                                
               </p>
               
               	         
                </div>
                              
                <div class="item_sub_list gray_line">
                  <h2 class="orang_title">经典豪华</h2>
                  <p> 
                                
                
                      <a href="/nomos/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">NOMOS</a>
                
                      <a href="/ml/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">艾美</a>
                
                      <a href="/cfb/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宝齐莱</a>
                
                      <a href="/ball/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">波尔</a>
                
                      <a href="/tudor/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">帝舵</a>
                
                      <a href="/oris/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">豪利时</a>
                
                      <a href="/tagheuer/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">豪雅</a>
                
                      <a href="/fc/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">康斯登</a>
                
                      <a href="/longines/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">浪琴</a>
                
                      <a href="/rado/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">雷达</a>
                
                      <a href="/rw/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">蕾蒙威</a>
                
                      <a href="/bm/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">名士</a>
                
                      <a href="/jr/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">尚维沙</a>
                
                      <a href="/montblanc/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">万宝龙</a>
                
                      <a href="/ec/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">艾米龙</a>
                
                      <a href="/bremont/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宝名表</a>
                
                      <a href="/graham/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">格林汉姆</a>
                
                      <a href="/sinn/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">Sinn辛恩</a>
                                
               </p>
               
               	         
                </div>
                              
                <div class="item_sub_list gray_line">
                  <h2 class="orang_title">亲民传统</h2>
                  <p> 
                                
                
                      <a href="/beijing/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">北京</a>
                
                      <a href="/orient/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">东方双狮</a>
                
                      <a href="/fiyta/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">飞亚达</a>
                
                      <a href="/seagull/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">海鸥手表</a>
                
                      <a href="/hamilton/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">汉米尔顿</a>
                
                      <a href="/seiko/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">精工</a>
                
                      <a href="/casio/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">卡西欧</a>
                
                      <a href="/titoni/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">梅花</a>
                
                      <a href="/mido/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">美度</a>
                
                      <a href="/movado/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">摩凡陀</a>
                
                      <a href="/mavin/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">摩纹</a>
                
                      <a href="/doxa/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">时度</a>
                
                      <a href="/tissot/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">天梭</a>
                
                      <a href="/citizen/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">西铁城</a>
                
                      <a href="/certina/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">雪铁纳</a>
                
                      <a href="/eb/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">依波路</a>
                
                      <a href="/rossini/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">罗西尼</a>
                
                      <a href="/tb/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">天霸</a>
                
                      <a href="/ebo/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">依波表</a>
                
                      <a href="/victorinox/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">维氏</a>
                
                      <a href="/archimede/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">阿基米德</a>
                
                      <a href="/alpina/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">艾沛勒</a>
                
                      <a href="/bulova/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宝路华</a>
                
                      <a href="/fortis/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">富利斯</a>
                
                      <a href="/junghans/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">荣汉斯</a>
                
                      <a href="/stowa/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">司多娃</a>
                
                      <a href="/laco/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">LACO朗坤</a>
                                
               </p>
               
               	         
                </div>
                              
                <div class="item_sub_list gray_line">
                  <h2 class="orang_title">时尚奢华</h2>
                  <p> 
                                
                
                      <a href="/ck/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">CK</a>
                
                      <a href="/lv/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">LV</a>
                
                      <a href="/armani/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">阿玛尼</a>
                
                      <a href="/hermes/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">爱马仕</a>
                
                      <a href="/boucheron/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宝诗龙</a>
                
                      <a href="/burberry/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">博柏利</a>
                
                      <a href="/chaumet/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">绰美</a>
                
                      <a href="/dior/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">迪奥</a>
                
                      <a href="/gucci/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">古驰</a>
                
                      <a href="/swarovski/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">施华洛世奇</a>
                
                      <a href="/chanel/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">香奈儿</a>
                
                      <a href="/eta/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">ETA</a>
                
                      <a href="/sellita/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">Sellita</a>
                
                      <a href="/ronda/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">瑞士朗达</a>
                
                      <a href="/balmain/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">宝曼</a>
                
                      <a href="/sarcar/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">豪门世家</a>
                
                      <a href="/guess/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">guess手表</a>
                
                      <a href="/koncise/ks0-jg0-lx0-xz0-cz0-bd0-ys0-gn0-xl0-pp0.html">孔氏珐琅表</a>
                                
               </p>
               
               	         
                </div>
                                         </div>
            </div>';

$parent_arr = selector::select($str, "//div[contains(@class, 'item_sub_list')]");
$parents = [];
foreach ($parent_arr as $k => $p)
{
    $sub_href = selector::select($p, "//a/@href");
    $sub_text = selector::select($p, "//a");
    foreach ($sub_text as $tk=>$t)
    {
        $parents[] = array(
            'pid' => $k+1,
            'title' => $t,
            'url' => 'http://www.iwatch365.com/'.$sub_href[$tk],
            'created_at' => time()
        );
    }
}
db::insert_batch("spider_watch", $parents);

print_r($parents);
