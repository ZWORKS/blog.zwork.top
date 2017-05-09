<?php
/*
	ZYC 插件实例：图片格子广告插件 设置
	乐客吧www.lek8.com制作 QQ:804772778
*/

!defined('DEBUG') AND exit('Access Denied.');

$action = param(3);
empty($action) AND $action = 'list';

if(empty($user['username'])){
	message(0, '请登录后再试');
	return;
}

if($action == 'list') {
	
	$linklist = db_find('grid_ad', array(), array('rank'=>-1), 1, 1000, 'ad_id');
	$maxid = db_maxid('grid_ad', 'ad_id');
	
	if($method == 'GET') {
		include _include(APP_PATH.'plugin/lek8_grid_ad/setting.htm');
	} else {

		//列表
		$rowidarr = param('rowid', array(0));
		$namearr = param('name', array(''));
		$urlarr = param('url', array(''));
		$imgarr = param('img', array(''));
		$rankarr = param('rank', array(0));
		$cr_date = param('cr_date', array(0));
		$user_n = param('user_name', array(''));
		unset($rowidarr[0]);
		unset($namearr[0]);
		unset($urlarr[0]);
		unset($imgarr[0]);
		unset($rankarr[0]);
		unset($cr_date[0]);
		unset($user_n[0]);
		$arrlist = array();
		
		
		if(!empty($rowidarr)){
			foreach($rowidarr as $k=>$v) {
				$arr = array(
					'ad_id'=>$k,
					'name'=>$namearr[$k],
					'url'=>$urlarr[$k],
					'img'=>$imgarr[$k],
					'rank'=>$rankarr[$k],
					'create_date'=>strtotime($cr_date[$k]),
					'user'=>$user_n[$k],
				);
				if(!isset($linklist[$k])) {
					db_create('grid_ad', $arr);
				} else {
					db_update('grid_ad', array('ad_id'=>$k), $arr);
				}
			}
		
			// 删除
			$deletearr = array_diff_key($linklist, $rowidarr);
			foreach($deletearr as $k=>$v) {
				db_delete('grid_ad', array('ad_id'=>$k));
			}
		
			message(0, '保存成功');
			
		}elseif(!empty($_POST['s_zq'])){
			//设置
			$s_zq = post_brief($_POST['s_zq']);
			$s_gold = post_brief($_POST['s_gold']);
			$s_grid = post_brief($_POST['s_grid']);
			$s_img = post_brief($_POST['s_img']);
			if($s_zq == ""){
				message(0, '【展期】选项为空或非法提交');
				return;
			}
			if($s_gold == ""){
				message(0, '【金币】选项为空或非法提交');
				return;
			}
			if($s_grid == ""){
				message(0, '【格子数】选项为空或非法提交');
				return;
			}
			if($s_img == ""){
				message(0, '【图片】选项为空或非法提交');
				return;
			}
			
			$arr = array(
				'id'=>1,
				's_zq'=>$s_zq,
				's_gold'=>$s_gold,
				's_grid'=>$s_grid,
				's_img'=>$s_img,
			);
			db_update('grid_ad_set', array('id'=>1), $arr);
			message(0, '保存成功');			
		}else{
			message(0, '数据为空，此处功能尚未开发');
		}

	}		
		

}elseif($action == 'set'){
	$set_list = db_find('grid_ad_set', array('id'=>1));	
	if($method == 'GET') {
		
		include _include(APP_PATH.'plugin/lek8_grid_ad/view/setting_set.htm');
		
	} else {
		message(0, '此处功能尚未开发');
	}	
		
}
?>