<?php

if(MODEL_ENV == 'online') {
	//生产环境
	
	require COMMON_PATH.'Conf/constant.php';
	
	//数据库配置
	$dbconfig = array(
		'DB_TYPE' => 'mongo', //数据库类型
		'DB_HOST' => '10.28.119.136', //数据库地址
		'DB_NAME' => 'panziliao', //数据库名称
		'DB_USER' => '', //数据库账号
		'DB_PWD' => '', //数据库密码
		'DB_PORT' => '28018', //数据库端口号
		'DB_PREFIX' => 'pre_', //数据库表前缀
		//'DB_DEBUG' => true,
	);
	
}else {
	//开发环境
	
	require COMMON_PATH.'Conf/constant-local.php';
	
	//数据库配置
	$dbconfig = array(
		'DB_TYPE' => 'mongo', //数据库类型
		'DB_HOST' => '101.201.73.106', //数据库地址
		'DB_NAME' => 'panziliao', //数据库名称
		'DB_USER' => '', //数据库账号
		'DB_PWD' => '', //数据库密码
		'DB_PORT' => '28018', //数据库端口号
		'DB_PREFIX' => 'pre_', //数据库表前缀
		'DB_DEBUG' => true,
	);
}

//系统配置文件
$config = array(
	//解析标签
    'TMPL_L_DELIM'       => '{',
    'TMPL_R_DELIM'       => '}',
	'DB_DEPLOY_TYPE'	=> 0,
	
	//允许模块
	'MODULE_ALLOW_LIST' => array('Home', 'Spiderapi', 'Console'),	
); 

return array_merge($dbconfig, $config); 

