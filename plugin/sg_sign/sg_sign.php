<?php

!defined('DEBUG') AND exit('Access Denied.');
$active = 'default';
$kv = kv_get('sg_sign');
	if($method == 'GET') {
		$page = param(2, 1);
		$pagesize = 20;
		$uidarr = explode(',',runtime_get('sg_sign_top'));
	    $sg_signlist = db_find('sg_sign', array('uid'=>$uidarr),  array('id'=>1), 1, 10, 'uid');
		$pagination = pagination(url("sg_sign-{page}"), runtime_get('sg_signnum'), $page, $pagesize);
		include _include(APP_PATH.'plugin/sg_sign/sg_sign.htm');
	} elseif($method == 'POST') {
		empty($uid) AND message(0, '请登录后再签到!');
		$today = strtotime('today');
		$yesterday = strtotime(date('Y-m-d',strtotime('-1 day')));
		$tomorrow = strtotime(date('Y-m-d',strtotime('+1 day')));
		$userinfo = db_find_one('sg_sign', array('uid'=>$uid));
		$kv = kv_get('sg_sign');
		$Credit = $kv['sign1'];
		$number = runtime_get('sg_sign');
		$number += 1;
		if($number == 1){
			$Credit += $kv['sign5'];
			runtime_set('sg_sign_top', $uid);
			runtime_set('sg_sign_one', $user['username']);
		}else if($number >= 2 && $number <= 5) {
			$Credit += $kv['sign6'];
		}
			if($userinfo){
			if($userinfo['stime'] > $today){
				message(-1, '今天已经签过啦！');
			}else{
				if($userinfo['stime'] > $yesterday && $userinfo['stime'] < $today){
					if($userinfo['keepdays'] == 3){
						$Credit += $kv['sign2'];
						$message = '连续签到3天额外奖励'.$kv['sign2'].'积分，';
					}else if($userinfo['keepdays'] == 6){
						$Credit += $kv['sign3'];
						$message = '连续签到7天额外奖励'.$kv['sign3'].'积分，';
					}else if($userinfo['keepdays'] == 14) {
						$Credit += $kv['sign4'];
						$message = '连续签到15天额外奖励'.$kv['sign4'].'积分，';
					}else if($userinfo['keepdays'] >= 15){
						$Credit += rand($kv['sign7'], $kv['sign8']);
						$message = '连续签到超过15天额外奖励'.rand($kv['sign7'], $kv['sign8']).'积分，';
					}else{
						$message = '';
					}
					db_update('sg_sign',array('uid'=>$uid),array('id'=>$number,'stime'=>time(),'counts+'=>1,'credits+'=>$Credit,'todaycredits'=>$Credit,'keepdays+'=>1 ));
					db_update('user',array('uid'=>$uid),array('credits+'=>$Credit ));
				}else{
					$message = '';
					db_update('sg_sign',array('uid'=>$uid),array('id'=>$number,'stime'=>time(),'counts+'=>1,'credits+'=>$Credit,'todaycredits'=>$Credit,'keepdays'=>1 ));
					db_update('user',array('uid'=>$uid),array('credits+'=>$Credit ));
				}
				runtime_set('sg_sign+', 1);
				runtime_set('sg_signnum+', 1);
		        $number >= 2 && $number <= 10 AND  runtime_set('sg_sign_top', runtime_get('sg_sign_top').','.$uid);
				message(0, "成功签到！今日排名{$number}，{$message}总奖励{$Credit}积分！");
			}
		}else{
			db_insert('sg_sign', array('id'=>$number, 'uid'=>$uid, 'stime'=>time(), 'counts'=>1, 'credits'=>$Credit, 'todaycredits'=>$Credit, 'keepdays'=>1, 'user'=>$user['username'] ));
			db_update('user',array('uid'=>$uid),array('credits+'=>$Credit ));
			runtime_set('sg_sign+', 1);
			runtime_set('sg_signnum+', 1);
		    $number >= 2 && $number <= 10 AND  runtime_set('sg_sign_top', runtime_get('sg_sign_top').','.$uid);
			message(0, "首次签到，今日排名{$number}，额外奖励{$Credit}积分！");
		}
	}
?>