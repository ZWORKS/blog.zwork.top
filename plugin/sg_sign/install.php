<?php

/*
	Xiuno BBS 4.0 每日签到安装
*/

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
$sql = "CREATE TABLE IF NOT EXISTS {$tablepre}sg_sign (
  `id` int(11) unsigned NOT NULL COMMENT 'ID',
  `uid` int(11) unsigned NOT NULL COMMENT '用户ID',
  `stime` int(11) unsigned DEFAULT NULL COMMENT '最后签到时间',
  `credits` int(11) unsigned DEFAULT NULL COMMENT '签到积分',
  `todaycredits` int(11) unsigned DEFAULT NULL COMMENT '今日积分',
  `counts` int(11) unsigned DEFAULT NULL COMMENT '签到天数',
  `keepdays` int(11) unsigned DEFAULT NULL COMMENT '连续签到',
  `user` varchar(32) COLLATE utf8_unicode_ci DEFAULT NULL COMMENT '签到用户',
  PRIMARY KEY (`uid`),
  KEY (`stime`)
  
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci
";

$r = db_exec($sql);
$r === FALSE AND message(-1, '创建签到表结构失败');

// 初始化
runtime_set('sg_sign', 0);
runtime_set('sg_signnum', 0);
runtime_set('sg_sign_one', 0);
runtime_set('sg_sign_top', 0);
$kv = kv_get('sg_sign');
if(!$kv) {
	$kv = array('sign1'=>'2', 'sign2'=>'3', 'sign3'=>'5', 'sign4'=>'8', 'sign5'=>'10', 'sign6'=>'5', 'sign7'=>'5', 'sign8'=>'20');
	kv_set('sg_hide', $kv);
}
?>