<?php
/*
	ZYC 插件实例：横幅格子广告插件 卸载
	乐客吧www.lek8.com制作 QQ:804772778
*/
!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
$sql = "DROP TABLE IF EXISTS {$tablepre}grid_ad;";

$r = db_exec($sql);
$r === FALSE AND message(-1, '卸载横幅格子广告表失败');



$sql = "DROP TABLE IF EXISTS {$tablepre}grid_ad_set;";

$r = db_exec($sql);
$r === FALSE AND message(-1, '卸载横幅格子广告配置表失败');

?>