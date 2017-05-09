<?php
return array (
  'db' => 
  array (
    'type' => 'pdo_mysql',
    'mysql' => 
    array (
      'master' => 
      array (
        'host' => '127.0.0.1',
        'user' => 'zzz',
        'password' => '123123',
        'name' => 'blog',
        'tablepre' => 'bbs_',
        'charset' => 'utf8',
        'engine' => 'innodb',
      ),
      'slaves' => 
      array (
      ),
    ),
    'pdo_mysql' => 
    array (
      'master' => 
      array (
        'host' => '127.0.0.1',
        'user' => 'zzz',
        'password' => '123123',
        'name' => 'blog',
        'tablepre' => 'bbs_',
        'charset' => 'utf8',
        'engine' => 'innodb',
      ),
      'slaves' => 
      array (
      ),
    ),
  ),
  'cache' => 
  array (
    'enable' => true,
    'type' => 'mysql',
    'memcached' => 
    array (
      'host' => 'localhost',
      'port' => '11211',
      'cachepre' => 'bbs_',
    ),
    'redis' => 
    array (
      'host' => 'localhost',
      'port' => '6379',
      'cachepre' => 'bbs_',
    ),
    'xcache' => 
    array (
      'cachepre' => 'bbs_',
    ),
    'yac' => 
    array (
      'cachepre' => 'bbs_',
    ),
    'apc' => 
    array (
      'cachepre' => 'bbs_',
    ),
    'mysql' => 
    array (
      'cachepre' => 'bbs_',
    ),
  ),
  'tmp_path' => './tmp/',
  'log_path' => './log/',
  'view_url' => 'view/',
  'upload_url' => 'upload/',
  'upload_path' => './upload/',
  'sitename' => '周洋醇的博客',
  'sitebrief' => 'me',
  'timezone' => 'Asia/Shanghai',
  'lang' => 'zh-cn',
  'runlevel' => 4,
  'runlevel_reason' => 'The site is under maintenance, please visit later.',
  'cookie_domain' => '',
  'cookie_path' => '',
  'auth_key' => '6asd0jc370rnodsfxnn9yh2h6x8xlmvznm8gcj0p5gfkzz6ec6j08o6s2kbjufpn',
  'pagesize' => 20,
  'postlist_pagesize' => 1000,
  'cache_thread_list_pages' => 10,
  'online_update_span' => 120,
  'online_hold_time' => 3600,
  'session_delay_update' => 0,
  'upload_image_width' => 927,
  'order_default' => 'lastpid',
  'update_views_on' => 1,
  'user_create_email_on' => 0,
  'user_resetpw_on' => 0,
  'admin_bind_ip' => 1,
  'cdn_on' => 0,
  'url_rewrite_on' => 0,
  'version' => '4.0',
  'static_version' => '?1.0',
  'installed' => 1,
);
?>