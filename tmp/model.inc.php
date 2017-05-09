<?php

!defined('DEBUG') AND exit('Forbidden');

// 可以合并成一个文件，加快速度
// merge to one file.



$include_model_files = array (
	APP_PATH.'model/kv.func.php',
	APP_PATH.'model/queue.func.php',
	APP_PATH.'model/group.func.php',
	APP_PATH.'model/user.func.php',
	APP_PATH.'model/forum.func.php',
	APP_PATH.'model/forum_access.func.php',
	APP_PATH.'model/thread.func.php',
	APP_PATH.'model/thread_top.func.php',
	APP_PATH.'model/post.func.php',
	APP_PATH.'model/attach.func.php',
	APP_PATH.'model/check.func.php',
	APP_PATH.'model/mythread.func.php',
	APP_PATH.'model/runtime.func.php',
	APP_PATH.'model/table_day.func.php',
	APP_PATH.'model/cron.func.php',
	APP_PATH.'model/form.func.php',
	APP_PATH.'model/misc.func.php',
	APP_PATH.'model/session.func.php',
	
	APP_PATH.'plugin/xn_pm/model/pm.func.php',
APP_PATH.'plugin/xn_search/model/search.func.php',
APP_PATH.'plugin/xn_tag/model/tag_cate.func.php',
APP_PATH.'plugin/xn_tag/model/tag.func.php',
APP_PATH.'plugin/xn_tag/model/tag_thread.func.php',
	
);



if(DEBUG) {
	foreach ($include_model_files as $model_files) {
		include _include($model_files);
	}
} else {
	
	$model_min_file = $conf['tmp_path'].'model.min.php';
	$isfile = is_file($model_min_file);
	if(!$isfile) {
		$s = '';
		foreach($include_model_files as $model_files) {
			$s .= php_strip_whitespace(_include($model_files));
		}
		$r = file_put_contents($model_min_file, $s);
		unset($s);
	}
	include $model_min_file;
}

include 	APP_PATH.'plugin/qt_markdown/model/markdown.func.php';

	include _include(APP_PATH.'plugin/xn_digest/model/thread_digest.func.php');










/*
function xn_php_strip_whitespace($file) {
	$s = php_strip_whitespace($file);
	if(substr($s, 0, 5) == '<?php') {
		$s = substr($s, 5);
	}
	if(substr($s, -2) == '?>') {
		$s = substr($s, 0, -2);
	}
	return $s;
}*/

?>