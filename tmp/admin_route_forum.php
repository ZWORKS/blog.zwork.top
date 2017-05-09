<?php

!defined('DEBUG') AND exit('Access Denied.');

$action = param(1);

// 不允许删除的版块 / system keeped forum
$system_forum = array(1);



if(empty($action) || $action == 'list') {
	
	
	
	if($method == 'GET') {
		
		
		
		$header['title']        = lang('forum_admin');
		$header['mobile_title'] = lang('forum_admin');
	
		$maxfid = forum_maxid();
		
		
		
		include _include(ADMIN_PATH."view/htm/forum_list.htm");
	
	} elseif($method == 'POST') {
		
		$fidarr = param('fid', array(0));
		$namearr = param('name', array(''));
		$rankarr = param('rank', array(0));
		$iconarr = param('icon', array(''));
		
		

$fuparr = param('fup', array(0));


		
		$arrlist = array();
		foreach($fidarr as $k=>$v) {
			$arr = array(
				'fid'=>$k,
				'name'=>array_value($namearr, $k),
				'rank'=>array_value($rankarr, $k)
			);
			
			if(!isset($forumlist[$k])) {
				

$arr['fup'] = array_value($fuparr, $k);


				forum_create($arr);
			} else {
				
				forum_update($k, $arr);
			}
			// icon
			if(!empty($iconarr[$k])) {
				
				$s = array_value($iconarr, $k);
				$data = substr($s, strpos($s, ',') + 1);
				$data = base64_decode($data);
				
				$iconfile = "../upload/forum/$k.png";
				file_put_contents($iconfile, $data);
				
				forum_update($k, array('icon'=>$time));
			}
			
			
		}
		
		// 删除 / delete
		$deletearr = array_diff_key($forumlist, $fidarr);
		foreach($deletearr as $k=>$v) {
			if(in_array($k, $system_forum)) continue;
			

forum_delete_sons($k);


			forum_delete($k);
			
		}
		
		forum_list_cache_delete();
		
		
		
		
		
		message(0, lang('save_successfully'));
	}

} elseif($action == 'update') {
	
	$_fid = param(2, 0);
	$_forum = forum_read($_fid);
	empty($_forum) AND message(-1, lang('forum_not_exists'));
	
	
	
	if($method == 'GET') {
		
		$header['title']        = lang('forum_edit');
		$header['mobile_title'] = lang('forum_edit');
	
		
		
		$accesslist = forum_access_find_by_fid($_fid);
		
		if(empty($accesslist)) {
			foreach($grouplist as $group) {
				$accesslist[$group['gid']] = $group; // 字段名相同，直接覆盖。 / same field, directly overwrite
			}
		} else {
			foreach($accesslist as &$access) {
				$access['name'] = $grouplist[$access['gid']]['name']; // 字段名相同，直接覆盖。 / same field, directly overwrite
			}
		}
		array_htmlspecialchars($_forum);
		
		$input = array();
		$input['name'] = form_text('name', $_forum['name']);
		$input['rank'] = form_text('rank', $_forum['rank']);
		$input['brief'] = form_textarea('brief', $_forum['brief'], '100%', 80);
		$input['announcement'] = form_textarea('announcement', $_forum['announcement'], '100%', 80);
		$input['accesson'] = form_checkbox('accesson', $_forum['accesson']);
		$input['moduids'] = form_text('moduids', $_forum['moduids']);
		
		
		
		$tag_maxid = tag_maxid();
		$tag_cate_maxid = tag_cate_maxid();
		$tagcatelist = tag_cate_find_by_fid($_fid);
		
		
		//print_r($_forum);exit;
		
		include _include(ADMIN_PATH."view/htm/forum_update.htm");
	
	} elseif($method == 'POST') {	
		
		$name = param('name');
		$rank = param('rank', 0);
		$brief = param('brief', '', FALSE);
		$announcement = param('announcement', '', FALSE);
		$moduids = param('moduids');
		$accesson = param('accesson', 0);
		$moduids = forum_filter_moduid($moduids);
		
		
		
		$arr = array (
			'name' => $name,
			'rank' => $rank,
			'brief' => $brief,
			'announcement' => $announcement,
			'moduids' => $moduids,
			'accesson' => $accesson,
		);
		forum_update($_fid, $arr);
		
		if($accesson) {
			$allowread = param('allowread', array(0));
			$allowthread = param('allowthread', array(0));
			$allowpost = param('allowpost', array(0));
			$allowattach = param('allowattach', array(0));
			$allowdown = param('allowdown', array(0));
			foreach($grouplist as $_gid=>$v) {
				$access = array (
					'allowread'=>array_value($allowread, $_gid, 0),
					'allowthread'=>array_value($allowthread, $_gid, 0),
					'allowpost'=>array_value($allowpost, $_gid, 0),
					'allowattach'=>array_value($allowattach, $_gid, 0),
					'allowdown'=>array_value($allowdown, $_gid, 0),
				);
				forum_access_replace($_fid, $_gid, $access);
			}
		} else {
			forum_access_delete_by_fid($_fid);
		}
		
		
		
		
		
		$tagcatelist = tag_cate_find_by_fid($_fid);
 		$tagcatelist = arrlist_change_key($tagcatelist, 'cateid');
		$cate_name_arr = param('cate_name', array(''));
		$cate_rank_arr = param('cate_rank', array(0));
		$cate_enable_arr = param('cate_enable', array(0));
		$cate_id_arr = array_keys($cate_name_arr);
		$cate_id_arr_old = arrlist_values($tagcatelist, 'cateid');
		
		$update = FALSE;
		// 新增 + 更新 / new + update
		foreach($cate_id_arr as $k) {
			$arr = array(
				'cateid'=>$k,
				'fid'=>$_fid,
				'name'=>$cate_name_arr[$k],
				'rank'=>$cate_rank_arr[$k],
				'enable'=>array_value($cate_enable_arr, $k),
			);
			if(isset($tagcatelist[$k])) {
				tag_cate_update($k, $arr);
			} else {
				if(!$arr['name']) continue;
				tag_cate_create($arr);
			}
			$update = TRUE;
		}
		// 删除 / delete
		$cate_id_delete = array_diff($cate_id_arr_old, $cate_id_arr);
		foreach($cate_id_delete as $k) {
			tag_cate_delete($k);
			$update = TRUE;
		}
		
		// tag
		$taglist = tag_fetch_from_catelist($tagcatelist);
		$taglist = arrlist_change_key($taglist, 'tagid');
		$tag_name_arr = param('tag_name', array(''));
		$tag_rank_arr = param('tag_rank', array(0));
		$tag_enable_arr = param('tag_enable', array(0));
		$tag_cate_id_arr = param('tag_cate_id', array(0));
		$tag_id_arr = array_keys($tag_name_arr);
		$tag_id_arr_old = arrlist_values($taglist, 'tagid');
		foreach($tag_id_arr as $k) {
			$arr = array(
				'tagid'=>$k,
				'cateid'=>array_value($tag_cate_id_arr, $k),
				'name'=>$tag_name_arr[$k],
				'rank'=>$tag_rank_arr[$k],
				'enable'=>array_value($tag_enable_arr, $k),
			);
			if(isset($taglist[$k])) {
				tag_update($k, $arr);
			} else {
				if(!$arr['name']) continue;
				tag_create($arr);
			}
			$update = TRUE;
		}
		$tag_id_delete = array_diff($tag_id_arr_old, $tag_id_arr);
		foreach($tag_id_delete as $k) {
			tag_delete($k);
			$update = TRUE;
		}
		
		$update AND setting_set('tag_update_time', $time);
		

		
		forum_list_cache_delete();
		
		message(0, lang('edit_sucessfully'));	
	}

} elseif($action == 'getname') {
	
	$uids = xn_urldecode(param(2));
	$arr = explode(',', $uids);
	$names = array();
	$err = '';
	
	
	
	foreach($arr as $_uid) {
		$_uid = intval($_uid);
		if(empty($_uid)) continue;
		$_user = user_read($_uid);
		if(empty($_user)) { $err .= lang('item_not_exists', array('item'=>$_uid)); continue; }
		if($_user['gid'] > 4) { $err .= lang('item_not_moderator', array('item'=>$_uid));  continue; }
		$names[] = $_user['username'];
	}
	$s = implode(',', $names);
	$err AND message(-1, $err);
	
	
	
	message(0, $s);
		
}



?>