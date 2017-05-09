<?php



// ------------> 最原生的 CURD，无关联其他数据。

function tag_thread_create($tagid, $tid) {
	
	$arr = array('tagid'=>$tagid, 'tid'=>$tid);
	$r = db_create('tag_thread', $arr);
	
	return $r;
}

function tag_thread_delete($tagid, $tid) {
	
	$r = db_delete('tag_thread', array('tagid'=>$tagid, 'tid'=>$tid));
	
	return $r;
}

function tag_thread_find($cond = array(), $orderby = array(), $page = 1, $pagesize = 20) {
	
	$tag_threadlist = db_find('tag_thread', $cond, $orderby, $page, $pagesize);
	
	return $tag_threadlist;
}

function tag_thread_delete_by_tagid($tagid) {
	$r = db_delete('tag_thread', array('tagid'=>$tagid));
	return $r;
}

function tag_thread_find_tagid_by_tid($tid) {
	$tagids = array();
	$tagthreadlist = tag_thread_find(array('tid'=>$tid), array(), 1, 1000);
	$tagids = arrlist_values($tagthreadlist, 'tagid');
	return $tagids;
}



?>