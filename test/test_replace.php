<?php

include '../xiunophp/xiunophp.php';

$s = '{
	"name" : "导航栏更多版块",
        "brief" : "导航比较多的时候，显示省略号，点击下拉菜单显示更多的版块。",
        "version" : "1.0",
        "bbs_version" : "4.0",
        "installed" : 0,
        "enable" : 0,
        "hooks_rank": {
        	"body_start.htm": 0,
        	"body_end.htm": 0
        }
        "dependencies": {
        	
        }
}';


$arr = xn_json_decode($s);

print_r($arr);
