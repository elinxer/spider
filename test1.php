<?php


/*



SELECT
	COUNT(1)
FROM
	`member_index_tbl`
WHERE
	user_type != 99
AND reg_time BETWEEN 1481241600
AND 1482278399

25497


SELECT
	COUNT(1)
FROM
	`member_index_tbl`
WHERE
	user_type != 99
AND reg_time BETWEEN 1478649600
AND 1479686399

15538





*/



echo strtotime('2016-01-01');
echo '<br>';
echo strtotime('2016-11-30')+86399;


