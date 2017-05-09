<?php
/*
	ZYC 插件实例：图片格子广告插件 设置
	乐客吧www.lek8.com制作 QQ:804772778
*/


!defined('DEBUG') AND exit('Access Denied.');
 
//include APP_PATH.'plugin/lek8_grid_ad/view/lek8_my_ad.htm';
 
 $action = param(3);

if(empty($action)) {
	$set_list = db_find('grid_ad_set', array('id'=>1));	
	$maxid = db_maxid('grid_ad', 'ad_id') + 1;
	$s_jb = $set_list [0]['s_gold'];
	$s_zq = $set_list [0]['s_zq'];
	//$s_grid = $set_list [0]['s_grid'];
	if(empty($user['username'])){
		include _include(APP_PATH.'plugin/lek8_grid_ad/view/lek8_nb_ad.htm');
		return;
	}
	if($method == 'GET') {
		include _include(APP_PATH.'plugin/lek8_grid_ad/view/lek8_my_ad.htm');
	} else {
		$rowidarr = $maxid;
		$namearr = post_brief($_POST['name']);
		$urlarr = post_brief($_POST['url']);
		$imgarr = post_brief($_POST['img']);
		$rankarr = $maxid;
		$user_n = $user['username'];
		$gold = $user['golds'];
		//$s_jb = 300;//需扣减的金币
		
		if($gold < $s_jb){
			message(0, '金币不足'.$s_jb.'个，请补充后再试！你拥有金币：'.$gold.'个');
			return;
		}
		
		if($namearr == ""){
			message(0, '【名称】选项为空或非法提交');
			return;
		}
		if($urlarr == ""){
			message(0, '【链接】选项为空或非法提交');
			return;
		}
		if($imgarr == ""){
			message(0, '【图片】选项为空或非法提交');
			return;
		}
		
		if($rowidarr) {
			$arr = array(
				'ad_id'=>$rowidarr,
				'name'=>$namearr,
				'url'=>$urlarr,
				'img'=>$imgarr,
				'rank'=>$rankarr,
				'create_date'=>time(),
				'user'=>$user_n,
			);
			db_create('grid_ad', $arr);
			//扣减 金币
			$arr = array('golds' => $gold - $s_jb,);
			db_update('user', array('uid'=>$user['uid']), $arr);	//表名（无需加bbs_），需更新的ID，更新的数据

		}
		
		message(0, '发表成功');
	}
}

?>