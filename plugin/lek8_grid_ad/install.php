<?php

/*
	ZYC 插件实例：横幅格子广告插件 安装
	乐客吧www.lek8.com制作 QQ:804772778
*/

!defined('DEBUG') AND exit('Forbidden');

$tablepre = $db->tablepre;
$sql = "CREATE TABLE IF NOT EXISTS {$tablepre}grid_ad (
  ad_id bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  user char(32) NOT NULL DEFAULT '' COMMENT '发布人',
  type smallint(11) NOT NULL DEFAULT '0',
  rank smallint(11) NOT NULL DEFAULT '0',
  create_date int(11) unsigned NOT NULL DEFAULT '0',
  name char(32) NOT NULL DEFAULT '',
  url char(64) NOT NULL DEFAULT '',
  img char(128) NOT NULL DEFAULT '',
  PRIMARY KEY (ad_id),
  KEY type (type)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
";

$r = db_exec($sql);
//$r === FALSE AND message(-1, '创建图片格子广告表结构失败');
if($r === FALSE){
	message(-1, '创建广告表结构失败');
	return;
}
$sql = "INSERT INTO {$tablepre}grid_ad VALUES ('1', 'admin', '0', '1', '1474546560', '乐客吧_BBS', 'http://www.lek8.com/', '/plugin/lek8_grid_ad/lek8.png')";
$r = db_exec($sql);
//$r === FALSE AND message(-1, '插入图片格子广告数据失败');
if($r === FALSE){
	message(-1, '插入广告数据失败');
	return;
}

$sql = "CREATE TABLE IF NOT EXISTS {$tablepre}grid_ad_set (
  id bigint(11) unsigned NOT NULL AUTO_INCREMENT,
  s_zq smallint(11) NOT NULL DEFAULT '0',
  s_gold smallint(11) NOT NULL DEFAULT '0',
  s_grid smallint(11) NOT NULL DEFAULT '0',
  s_img char(128) NOT NULL DEFAULT '',
  PRIMARY KEY (id)
) ENGINE=MyISAM AUTO_INCREMENT=0 DEFAULT CHARSET=utf8
";

$r = db_exec($sql);
if($r === FALSE){
	message(-1, '创建配置表结构失败');
	return;
}

$sql = "INSERT INTO `{$tablepre}grid_ad_set` VALUES ('1', '30', '300', '8', 'plugin/lek8_grid_ad/ad.png')";
$r = db_exec($sql);
if($r === FALSE){
	message(-1, '插入配置数据失败');
	return;
}

?>