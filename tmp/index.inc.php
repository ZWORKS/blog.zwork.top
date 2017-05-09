<?php

!defined('DEBUG') AND exit('Access Denied.');



$sid = sess_start();

// 语言 / Language
$lang = include _include(APP_PATH."lang/$conf[lang]/bbs.php");

// 支持 Token 接口（token 与 session 双重登陆机制，方便 REST 接口设计，也方便 $_SESSION 使用）
// Support Token interface (token and session dual match, to facilitate the design of the REST interface, but also to facilitate the use of $_SESSION)
$uid = intval(_SESSION('uid'));
empty($uid) AND $uid = user_token_get() AND $_SESSION['uid'] = $uid;
$user = user_read($uid);

// 用户组 / Group
$gid = empty($user) ? 0 : intval($user['gid']);
$grouplist = group_list_cache();
$group = isset($grouplist[$gid]) ? $grouplist[$gid] : $grouplist[0];

// 版块 / Forum
$fid = 0;
$forumlist = forum_list_cache();
$forumlist_show = forum_list_access_filter($forumlist, $gid);	// 有权限查看的板块 / filter no permission forum
$forumarr = arrlist_key_values($forumlist_show, 'fid', 'name');

// 头部 header.inc.htm 
$header = array(
	'title'=>$conf['sitename'],
	'mobile_title'=>'',
	'mobile_link'=>'./',
	'keywords'=>'', // 搜索引擎自行分析 keywords, 自己指定没用 / Search engine automatic analysis of key words, so keep it empty.
	'description'=>$conf['sitebrief'],
	'navs'=>array(),
);

// 运行时数据，存放于 cache_set() / runtime data
$runtime = runtime_init();

// 检测站点运行级别 / restricted access
check_runlevel();

// 全站的设置数据，站点名称，描述，关键词
// $setting = kv_get('setting');

$route = param(0, 'index');


//系统初始化

defined('DS') || define('DS', DIRECTORY_SEPARATOR);
defined('ROOT_PATH') || define('ROOT_PATH', dirname(dirname(__FILE__)) . DS);

if (!isset($_SERVER['HTTPS']))
    $_SERVER['HTTPS'] = 'off';

$_SERVER['PHP_SELF'] = htmlspecialchars($_SERVER['SCRIPT_NAME'] ? $_SERVER['SCRIPT_NAME'] : $_SERVER['PHP_SELF']);
$_SERVER['basefilename'] = basename($_SERVER['PHP_SELF']);

$conf['siteroot'] = substr($_SERVER['PHP_SELF'], 0, -strlen($_SERVER['basefilename']));
$conf['siteurl'] = strtolower(($_SERVER['HTTPS'] == 'on' ? 'https' : 'http') .
    '://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['PHP_SELF'], 0, strrpos($_SERVER['PHP_SELF'], '/') + 1));

$conf['upload_url'] = $conf['siteurl'] . 'upload/';
if (!function_exists('pre')) {

    /**
     * 格式化输出信息
     *
     * @param 字符串/数组 $array 要输出的信息
     * @param 逻辑值 $exit 是否需要退出
     */
    function pre($array, $exit = false) {
        if ($array) {
            if (is_string($array)) {
                echo '<br>';
                echo htmlspecialchars($array);
                echo '<br>';
            } else {
                echo "<div style='font-size:12px;line-height:14px;text-align:left;color:#000;background-color:#fff;'><pre>";
                print_r($array);
                echo "</pre></div>";
            }
        }
    }

}

if(!defined('SKIP_ROUTE')) {
	
	// 按照使用的频次排序，增加命中率，提高效率
	// According to the frequency of the use of sorting, increase the hit rate, improve efficiency
	switch ($route) {
		
		case 'index': 	include _include(APP_PATH.'route/index.php'); 	break;
		case 'thread':	include _include(APP_PATH.'route/thread.php'); 	break;
		case 'forum': 	include _include(APP_PATH.'route/forum.php'); 	break;
		case 'user': 	include _include(APP_PATH.'route/user.php'); 	break;
		case 'my': 	include _include(APP_PATH.'route/my.php'); 	break;
		case 'attach': 	include _include(APP_PATH.'route/attach.php'); 	break;
		case 'post': 	include _include(APP_PATH.'route/post.php'); 	break;
		case 'mod': 	include _include(APP_PATH.'route/mod.php'); 	break;
		case 'browser': include _include(APP_PATH.'route/browser.php'); break;
		
		case 'wx_login':		include _include(APP_PATH.'plugin/jjx_wx_login/route/wx_login.php'); 	break;case 'sg_sign': include _include(APP_PATH.'plugin/sg_sign/sg_sign.php'); break;case 'pm': include _include(APP_PATH.'plugin/xn_pm/route/pm.php'); break;
		case 'qq_login':		include _include(APP_PATH.'plugin/xn_qq_login/route/qq_login.php'); 	break;
		case 'search': include _include(APP_PATH.'plugin/xn_search/route/search.php'); break;
		default: 
			
			include _include(APP_PATH.'route/index.php'); 	break;
			//http_404();
			/*
			!is_word($route) AND http_404();
			$routefile = _include(APP_PATH."route/$route.php");
			!is_file($routefile) AND http_404();
			include $routefile;
			*/
	}
}



?>