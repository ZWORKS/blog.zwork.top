<?php

/*
* Copyright (C) 2015 xiuno.com
*/

!defined('DEBUG') AND exit('Access Denied.');



$page = param(1, 1);
$order = $conf['order_default'];
$order != 'tid' AND $order = 'lastpid';
$pagesize = $conf['pagesize'];
$active = 'default';

$pagination = pagination(url("index-{page}"), $runtime['threads'], $page, $pagesize);

$toplist = thread_top_find_cache();

// 从默认的地方读取主题列表
$thread_list_from_default = 1;


$digest = param(2, 0);
if($digest == 1) {
	$thread_list_from_default = 0;
	$active = 'digest';
	$digests = thread_digest_count($fid);
	$pagination = pagination(url("index-{page}-1"), $digests, $page, $pagesize);
	$threadlist = thread_digest_find_by_fid($fid, $page, $pagesize);
}

if($thread_list_from_default) {
	$threadlist = thread_find_by_fid($fid, $page, $pagesize, $order);
}
//$conf['order_default'] == $order AND $threadlist = $toplist + $threadlist;

// 过滤没有权限访问的主题 / filter no permission thread
thread_list_access_filter($threadlist, $gid);

// SEO
$header['title'] = $conf['sitename']; 				// site title
$header['keywords'] = ''; 					// site keyword
$header['description'] = $conf['sitebrief']; 			// site description
$_SESSION['fid'] = 0;



include _include(APP_PATH.'view/htm/index.htm');

?>