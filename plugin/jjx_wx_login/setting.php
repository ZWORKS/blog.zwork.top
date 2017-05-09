<?php
/*
	微信登陆插件设置
*/
!defined('DEBUG') AND exit('Access Denied.');

if($method == 'GET') {
	$kv = kv_get('wx_login');
	$input = array();
	//$input['meta'] = form_text('meta', $kv['meta']);
	$input['appid'] = form_text('appid', $kv['appid']);
	$input['appkey'] = form_text('appkey', $kv['appkey']);
	include _include(APP_PATH.'plugin/jjx_wx_login/setting.htm');
	
} else {
	$kv = array();
	//$kv['meta'] = param('meta');
	$kv['appid'] = param('appid');
	$kv['appkey'] = param('appkey');
	kv_set('wx_login', $kv);
	message(1, '修改成功');
}
?>