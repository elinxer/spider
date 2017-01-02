<?php

ini_set("memory_limit", "2048M");

set_time_limit(0);

error_reporting(E_ALL);

require dirname(__FILE__).'/phpspider/core/requests.php';
require dirname(__FILE__).'/phpspider/core/selector.php';
require dirname(__FILE__).'/phpspider/core/db.php';



$GLOBALS['config']['db'] = array(
    'host'		=>	'127.0.0.1',
    'port'		=>	3306,
    'user'		=>	'root',
    'pass'		=>	'',
    'name'		=>	'spider',
);



$result = db::get_one("SELECT * FROM `zh_question_answer` LIMIT 1;");

echo $html = selector::select($result['answer_html_content'], "//div[contains(@class, 'zm-editable-content')]");

print_r($result);
