<?php
function wx_login_link($return_url) {
	$wxlogin = kv_get('wx_login');
	$appid = $wxlogin['appid'];
	$appkey = $wxlogin['appkey'];
	$return_url = urlencode($return_url);
	//$scope = "get_user_info,add_share,list_album,add_album,upload_pic,add_topic,add_one_blog,add_weibo";
	$scope = "snsapi_login";
	$state = md5(uniqid(rand(), TRUE)); //CSRF protection
	$login_url = "https://open.weixin.qq.com/connect/qrconnect?appid=".$appid."&redirect_uri=".$return_url."&response_type=code&scope=$scope&state=$state#wechat_redirect";
	return $login_url;
}

function wx_login_get_token($appid, $appkey, $code, $return_url) {
	$return_url = urlencode($return_url);
	$get_token_url	=	"https://api.weixin.qq.com/sns/oauth2/access_token?appid=".$appid."&secret=".$appkey."&code=".$code."&grant_type=authorization_code";
	$s = https_get($get_token_url);
	$arr_access_token = xn_json_decode($s);
	//刷新
	$refresh_token = $arr_access_token['refresh_token'];
	$get_refresh_token_url	=	"https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$appid."&grant_type=refresh_token&refresh_token=".$refresh_token."";
	$s_refresh_token = https_get($get_refresh_token_url);
	$token = xn_json_decode($s);
	if(empty($token["access_token"])) return xn_error(-1, 'access_token 解码出错。'.$s);
	return $token;
}

function wx_login_get_openid_by_token($token,$appid) {
	$access_token	=	$token['refresh_token'];
	$get_openid_url = "https://api.weixin.qq.com/sns/oauth2/refresh_token?appid=".$appid."&grant_type=refresh_token&refresh_token=".$access_token."";
	$s  = https_get($get_openid_url);
	$arr = xn_json_decode($s);
	if (isset($arr['error'])) {
		$error = $arr['error'].'<br />'.$arr['error_description'];
		return xn_error(-1, $error);
	}
	return $arr['openid'];
}

function wx_login_get_user_by_openid($openid, $token) {
	$token	=	$token['access_token'];
	$get_user_info_url = "https://api.weixin.qq.com/sns/userinfo?access_token=".$token."&openid=".$openid."&lang=zh_CN";
	$s = https_get($get_user_info_url);
	$arr = xn_json_decode($s, true);
	return $arr;
}

function wx_login_read_user_by_openid($openid) {
	$arr = db_find_one('user_open_plat', array('openid'=>$openid));
	if($arr) {
		$arr2 = user_read($arr['uid']);
		if($arr2) {
			$arr = array_merge($arr, $arr2);
		} else {
			db_delete('user_open_plat', array('openid'=>$openid));
			return FALSE;
		}
	}
	return $arr;
}

function wx_login_create_user($username, $avatar_url_2, $openid) {
	global $conf, $time, $longip;
	$arr = wx_login_read_user_by_openid($openid);
	if($arr) return xn_error(-2, '已经注册');
	$r = user_read_by_username($username);
	if($r) {
		// 特殊字符过滤
		$username = xn_substr($username.'_'.$time, 0, 31);
		$r = user_read_by_username($username);
		if($r) return xn_error(-1, '用户名被占用。');
	}
	$email = "wx_$time@qq.com";
	$r = user_read_by_email($email);
	if($r) return xn_error(-1, 'Email 被占用');
	$password = md5(rand(1000000000, 9999999999).$time);
	$user = array(
		'username'=>$username,
		'email'=>$email,
		'password'=>$password,
		'gid'=>101,
		'salt'=>rand(100000, 999999),
		'create_date'=>$time,
		'create_ip'=>$longip,
		'avatar'=>0,
		'logins' => 1,
		'login_date' => $time,
		'login_ip' => $longip,
	);
	$uid = user_create($user);
	if(empty($uid)) return xn_error(-1, '注册失败');
	$user = user_read($uid);
	$r = db_insert('user_open_plat', array('uid'=>$uid, 'platid'=>1, 'openid'=>$openid));
	if(empty($uid)) return xn_error(-1, '注册失败');
	runtime_set('users+', '1');
	runtime_set('todayusers+', '1');
	if($avatar_url_2) {
		$filename = "$uid.png";
		$dir = substr(sprintf("%09d", $uid), 0, 3).'/';
		$path = $conf['upload_path'].'avatar/'.$dir;
		!is_dir($path) AND mkdir($path, 0777, TRUE);

		$data = file_get_contents($avatar_url_2);
		file_put_contents($path.$filename, $data);
		user_update($uid, array('avatar'=>$time));
	}
	return $user;
}

?>
