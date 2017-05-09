<?php

/*
	Xiuno BBS 4.0 邀请码设置
*/

!defined('DEBUG') AND exit('Access Denied.');
if($method == 'GET') {
	
	$kv = kv_get('sg_sign');
	
	$input = array();
	$input['sign1'] = form_text('sign1', $kv['sign1']);
	$input['sign2'] = form_text('sign2', $kv['sign2']);
	$input['sign3'] = form_text('sign3', $kv['sign3']);
	$input['sign4'] = form_text('sign4', $kv['sign4']);
	$input['sign5'] = form_text('sign5', $kv['sign5']);
	$input['sign6'] = form_text('sign6', $kv['sign6']);
	$input['sign7'] = form_text('sign7', $kv['sign7']);
	$input['sign8'] = form_text('sign8', $kv['sign8']);
	
	include _include(APP_PATH.'plugin/sg_sign/setting.htm');
	
} else {

	$kv = array();
	$kv['sign1'] = param('sign1', 0);
	$kv['sign2'] = param('sign2', 0);
	$kv['sign3'] = param('sign3', 0);
	$kv['sign4'] = param('sign4', 0);
	$kv['sign5'] = param('sign5', 0);
	$kv['sign6'] = param('sign6', 0);
	$kv['sign7'] = param('sign7', 0);
	$kv['sign8'] = param('sign8', 0);
	
	kv_set('sg_sign', $kv);
	
	message(0, '修改成功');
}
?>