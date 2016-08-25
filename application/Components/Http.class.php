<?php 

namespace Components;

class Http
{
	/**
	* curl 提交
	* $url 请求url地址
	* $header 请求头信息
	* $proxy 代理信息 ip=>代理ip, port=>代理端口, loginpwd=>代理密码
	* $gzip 是否需要gzip解压
	*/
	public static function curl_http($url, $header = array(), $proxy = array(), $gzip = false, $cookie = '')
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_HEADER, 0); //是否取得头信息 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //是否抓取跳转后的页面 
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); //设置超时 秒 
		//curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11');

		if($proxy['ip'] && $proxy['port']) {
			curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
			curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']); //设置代理ip
			curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']); //设置代理端口号
			
			if($proxy['loginpwd']) {
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['loginpwd']); //设置代理密码   
			}
		}

		if($gzip) {
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); //针对已gzip压缩过的进行解压，不然返回内容会是乱码
		}
		
		if(!empty($header)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //设置http请求头信息
		}

		//执行并获取HTML文档内容
		$data['content'] = curl_exec($ch);
		
		//正则匹配 Cookie
		/*
		if(preg_match_all('/Set-Cookie:(.*);/iU', $output, $cookie_match)) {
			foreach($cookie_match[1] as $val) {
				$data['cookie'][] = $val;
			}    
		}
		*/ 
		
		//提交cookie
		if($cookie) {
			curl_setopt($ch, CURLOPT_COOKIE, $cookie);
		}

		$data['httpcode'] = curl_getinfo($ch, CURLINFO_HTTP_CODE); //返回http_code状态码
		
		//释放curl句柄
		curl_close($ch);
		
		return $data;
	} 

	/*
	'header'=>"Host: xxx.com\r\n" . 
			"Accept-language: zh-cn\r\n" . 
			"User-Agent: Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 5.1; Trident/4.0; 4399Box.560; .NET4.0C; .NET4.0E)" .
			"Accept: *//*"
	*/ 
	public static function dfile_get_contents($url, $header = '', $timeout = 60)
	{
		$opts = array(
			'http' => array(
				'method' => "GET",
				'timeout' => $timeout, 
			)
		); 
		if(!empty($header)) {
			$opts['http']['header'] = $header;
		}
		
		$context = stream_context_create($opts);
		$content = @file_get_contents($url, false, $context);
		return trim($content);
	}
	
	/**
	* curl post提交数据
	*/
	public static function curl_post($url, $post = array(), $header = array(), $proxy = array(), $gzip = false)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
		curl_setopt($ch, CURLOPT_HEADER, 0); //是否取得头信息
		curl_setopt($ch, CURLOPT_TIMEOUT, 60); //设置超时 秒 
		
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $post);
		
		if($proxy['ip'] && $proxy['port']) {
			curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
			curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']); //设置代理ip
			curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']); //设置代理端口号
			
			if($proxy['loginpwd']) {
				curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['loginpwd']); //设置代理密码   
			}
		}
		
		if($gzip) {
			curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); //针对已gzip压缩过的进行解压，不然返回内容会是乱码
		}
		
		if(!empty($header)) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $header); //设置http请求头信息
		}
		
		//执行并获取HTML文档内容
		$data['content'] = curl_exec($ch);
		$data['httpcode'] = curl_getinfo($ch, CURLINFO_HTTP_CODE); //返回http_code状态码
		
		//释放curl句柄
		curl_close($ch);
		
		return $data;
		
	}

	/**
	* curl多线程
	*/
	public static function curl_multi($urls, $header = array(), $gzip = false, $proxy = array())
	{
		$queue = curl_multi_init();
		$map = array();
		foreach($urls as $key => $url) {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $url);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); //将curl_exec()获取的信息以文件流的形式返回，而不是直接输出
			curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1); //是否抓取跳转后的页面 
			curl_setopt($ch, CURLOPT_HEADER, 0); //是否取得头信息
			curl_setopt($ch, CURLOPT_TIMEOUT, 30); //设置超时 秒 
			//curl_setopt($ch, CURLOPT_NOSIGNAL, true);
			
			if($gzip) {
				curl_setopt($ch, CURLOPT_ENCODING, 'gzip'); //针对已gzip压缩过的进行解压，不然返回内容会是乱码
			}
			
			if(!empty($header[$key])) {
				curl_setopt($ch, CURLOPT_HTTPHEADER, $header[$key]); //设置http请求头信息
			}
			
			if($proxy['ip'] && $proxy['port']) {
				curl_setopt($ch, CURLOPT_PROXYTYPE, 'HTTP');
				curl_setopt($ch, CURLOPT_PROXY, $proxy['ip']); //设置代理ip
				curl_setopt($ch, CURLOPT_PROXYPORT, $proxy['port']); //设置代理端口号
				
				if($proxy['loginpwd']) {
					curl_setopt($ch, CURLOPT_PROXYUSERPWD, $proxy['loginpwd']); //设置代理密码   
				}
			}
			
			curl_multi_add_handle($queue, $ch);
			$map[(string) $ch] = $url;
		}
		$responses = array();
		do{
			while(($code = curl_multi_exec($queue, $active)) == CURLM_CALL_MULTI_PERFORM);
			if($code != CURLM_OK) {
				break; 
			}
			while($done = curl_multi_info_read($queue)) {
				$error = curl_error($done['handle']);
				$results = curl_multi_getcontent($done['handle']);
				$responses[$map[(string) $done['handle']]] = compact('error', 'results');
				curl_multi_remove_handle($queue, $done['handle']);
				curl_close($done['handle']);
				unset($results);
			}
			if($active > 0) {
				curl_multi_select($queue, 0.5);
			}
		}while($active);
		
		curl_multi_close($queue);
		return $responses;    
	}
	
	public static function pos_html($start_tag, $end_tag, $html = '', $addslashes = false)
	{
		//$start_tag = str_replace('"', '\"', $start_tag);
		//$end_tag = str_replace('"', '\"', $end_tag);
		
		if($addslashes) {
			$start_tag = str_replace(array('"', '/', '(', ')'), array('\"', '\/', '\(', '\)'), $start_tag);
			$end_tag = str_replace(array('"', '/', '(', ')'), array('\"', '\/', '\(', '\)'), $end_tag);
			
			//$start_tag = addslashes($start_tag);
			//$end_tag = addslashes($end_tag);
		}
		$start_pos = strpos($html, $start_tag) + strlen($start_tag);
		$end_pos = strpos($html, $end_tag);

		return substr($html, $start_pos, $end_pos - $start_pos);
		
	}
	
	
}