<?php

/**
 * 系统配置文件,数据库表
 */
return array(
    //解析标签
    'TMPL_L_DELIM'       => '{',
    'TMPL_R_DELIM'       => '}',
	'DB_DEPLOY_TYPE'	=> 0,
	
    'DB_TYPE'             => 'mongo', //数据库类型
    'DB_HOST'             => '172.100.22.100', //数据库地址
    'DB_NAME'             => 'panziliao', //数据库名称
    'DB_USER'             => '', //数据库账号
    'DB_PWD'             => '', //数据库密码
    'DB_PORT'             => '27017', //数据库端口号
    'DB_PREFIX'             => 'pre_', //数据库表前缀
    //'DB_DEBUG'             => true,	

);

