<?php 

/**
* redis配置
*/
if(MODEL_ENV == 'online') {
	return array(
		'host' => '10.28.119.136',
		'port' => 7379,
		'prefix' => 'pzl_',
		'timeout' => 3000,
	);
}else {
	return array(
		'host' => '127.0.0.1',
		'port' => 6379,
		'prefix' => 'pzl_',
		'timeout' => 3000,
	);
}