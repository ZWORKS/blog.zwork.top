<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);

$user = user_read($uid);
user_login_check();



$header['mobile_title'] = $user['username'];
$header['mobile_linke'] = url("my");

if(empty($action)) {
	
	$header['title'] = lang('my_home');
	include _include(APP_PATH.'view/htm/my.htm');
	
} elseif($action == 'profile') {
	
	include _include(APP_PATH.'view/htm/my_profile.htm');

} elseif($action == 'password') {
	
	if($method == 'GET') {
		
		
		
		include _include(APP_PATH.'view/htm/my_password.htm');
		
	} elseif($method == 'POST') {
		
		
		
		$password_old = param('password_old');
		$password_new = param('password_new');
		$password_new_repeat = param('password_new_repeat');
		$password_new_repeat != $password_new AND message(-1, lang('repeat_password_incorrect'));
		md5($password_old.$user['salt']) != $user['password'] AND message('password_old', lang('old_password_incorrect'));
		$password_new = md5($password_new.$user['salt']);
		$r = user_update($uid, array('password'=>$password_new));
		$r === FALSE AND message(-1, lang('password_modify_failed'));
		
		
		message(0, lang('password_modify_successfully'));
		
	}
	
} elseif($action == 'thread') {

	
	
	$page = param(2, 1);
	$pagesize = 20;
	$totalnum = $user['threads'];
	$thread_list_from_default = 1;
	$active = 'default';
	
	
$digest = param(3, 0);
if($digest == 1) {
	$thread_list_from_default = 0;
	$active = 'digest';
	$digests = $user['digests'];
	$pagination = pagination(url("user-$uid-{page}-1"), $digests, $page, $pagesize);
	$threadlist = thread_digest_find_by_uid($uid, $page, $pagesize);
}
	
	if($thread_list_from_default) {
		$pagination = pagination(url('my-thread-{page}'), $totalnum, $page, $pagesize);
		$threadlist = mythread_find_by_uid($uid, $page, $pagesize);
	}
	
	
	
	include _include(APP_PATH.'view/htm/my_thread.htm');

} elseif($action == 'avatar') {
	
	if($method == 'GET') {
		
		
		
		include _include(APP_PATH.'view/htm/my_avatar.htm');
	
	} else {
		
		
		
		$width = param('width');
		$height = param('height');
		$data = param('data', '', FALSE);
		
		empty($data) AND message(-1, lang('data_is_empty'));
		$data = base64_decode_file_data($data);
		$size = strlen($data);
		$size > 2048000 AND message(-1, lang('filesize_too_large', array('maxsize'=>'2M', 'size'=>$size)));
		
		$filename = "$uid.png";
		$dir = substr(sprintf("%09d", $uid), 0, 3).'/';
		$path = $conf['upload_path'].'avatar/'.$dir;
		$url = $conf['upload_url'].'avatar/'.$dir.$filename;
		!is_dir($path) AND (mkdir($path, 0777, TRUE) OR message(-2, lang('directory_create_failed')));
		
		

	$dir = xn_set_dir($uid, $conf['upload_path'].'avatar/').'/';
	$path = $conf['upload_path'].'avatar/'.$dir;
	$url = $conf['upload_url'].'avatar/'.$dir.$filename;
	

		file_put_contents($path.$filename, $data) OR message(-1, lang('write_to_file_failed'));
		
		user_update($uid, array('avatar'=>$time));
		
		
		
		message(0, array('url'=>$url));
		
	}
}

else if($action=='favorite'){

	if($method=='POST'){

		$action = param('action','add');
		$tid = param('tid');
		if(!$user) message(0,'请先登录');
		$thread = thread_read($tid);
		empty($thread) AND message(0, lang('thread_not_exists'));
		if($action=='add'){
			db_insert('gg_favorite_thread',array('tid'=>$tid,'uid'=>$user['uid']));
			message(1,'添加成功！');
		}else if($action=='del'){
			db_delete('gg_favorite_thread',array('tid'=>$tid,'uid'=>$user['uid']));
			message(1,'删除成功！');
		}

	}else{
		
		include _include(APP_PATH.'plugin/GG_thread_favorite/model/func.php');
		$page = 1;
		$threads = GG_favorite_my();
		$threadlist = array();
		if(!empty($threads)){
			foreach($threads as $thread){
				$threadlist[] = thread_read($thread['tid']);
			}
		}
		include _include(APP_PATH.'plugin/GG_thread_favorite/model/my_favorite.htm');
	
	}

}

?>