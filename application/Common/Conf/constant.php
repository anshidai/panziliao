<?php 

/**
* 生产环境
*/

/**
* 定义系统常量
* 【注意事项】：
	1、定义code状态码 以CODE_开头
	2、如果是域名 需http://或https://开头， 结尾不带斜线
	3、如果是目录 结尾不带斜线 如: /data/xxx/ruturn
*/

//域名
define('DOMAIN', 'http://test.panziliao.com');

//风格模板
define('SKIN_NAME', 'default');

//网站名称
define('SITE_NAME', '盘资料');

//模板主题目录
define('THEMES_PATH', __ROOT__.'/template/'. SKIN_NAME. '/');

