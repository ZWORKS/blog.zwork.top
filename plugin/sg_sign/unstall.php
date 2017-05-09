<?php

/*
	Xiuno BBS 4.0 每日签到卸载
*/

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
$sql = "DROP TABLE IF EXISTS {$tablepre}sg_sign;";

$r = db_exec($sql);
$r === FALSE AND message(-1, '卸载邀请码表失败');

?>