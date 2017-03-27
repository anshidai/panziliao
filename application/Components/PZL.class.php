<?php

namespace Components;

class PZL
{
    /**
	 * 获取缓存实例对象
	 * @param string $type 缓存名 redis, memcache, file
	 * @return object
	 */
	public static function cache($type)
	{
		return \Components\helper\CacheHelper::instance($type);
	}
	
	
	/**
	 * 获取缓存key
	 * @param string $name 缓存键名
	 * @param string|int $params 键名需要替换参数
	 * @return string
	 */
	public static function getCachekey($name, $params = '')
	{
		$cachelist = require COMMON_PATH.'/Conf/cachekey.php';
		$key = $cachelist[$name];
		if($key && $params) {
			$key = sprintf($key, $params);
		}
		return $key;
	}  
}