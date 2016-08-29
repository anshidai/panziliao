<?php 

function randStr($len=6, $format = 'all') 
{ 
	switch($format) { 
		case 'all':
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; break;
		case 'char':
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz-@#~'; break;
		case 'number':
			$chars = '0123456789'; 
			break;
		default :
			$chars = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789-@#~'; 
			break;
	}
	mt_srand((double)microtime()*1000000*getmypid()); 
	$password = '';
	while(strlen($password)<$len) {
		$password .= substr($chars,(mt_rand()%strlen($chars)), 1);
	}
	return $password;
} 

function getFileCategory($type = '')
{
	switch($type) {
		case 1: 
			$cid = 1;break; //视频
		case 2: 
			$cid = 2;break; //音乐
		case 3: 
			$cid = 3;break; //图片
		case 10: 
			$cid = 4;break; //专辑
		case 6: 
			$cid = 5;break; //其他
		default: 
			$cid = 5;break;
	}
	return $cid;
}


/**
* 
* 返回一定位数的时间戳，多少位由参数决定
* @param digits 多少位的时间戳
* @return 时间戳
 */
function getTimestamp($digits = false) {
	$digits = $digits > 10 ? $digits : 10;
	$digits = $digits - 10;
	if ((!$digits) || ($digits == 10)) {
		return time();
	} else {
		return number_format(microtime(true),$digits,'','');
	}
}

function pos_html($start_tag, $end_tag, $html = '', $addslashes = false)
{
    //$start_tag = str_replace('"', '\"', $start_tag);
    //$end_tag = str_replace('"', '\"', $end_tag);
    
    if($addslashes) {
        $start_tag = str_replace(array('"', '/', '(', ')'), array('\"', '\/', '\(', '\)'), $start_tag);
        $end_tag = str_replace(array('"', '/', '(', ')'), array('\"', '\/', '\(', '\)'), $end_tag);
        
        //$start_tag = addslashes($start_tag);
        //$end_tag = addslashes($end_tag);
    }
	
	$start_pos = strpos($html, $start_tag);
	if($start_pos) {
		$start_pos = $start_pos + strlen($start_tag);
	}
    $end_pos = strpos($html, $end_tag);

    return substr($html, $start_pos, $end_pos - $start_pos);
    
}

/**
* 获取文件扩展名
*/
function getFileExt($filename)
{
	$arr = pathinfo($filename);
	return $arr['extension']? $arr['extension']: '';
}

/**
* 转换字节数为其他单位
* @param   string $filesize  字节大小
* @return  string 返回大小
*/
function sizeToUnit($filesize) {
   if($filesize >= 1073741824) {
      $filesize = round($filesize / 1073741824 * 100) / 100 .' GB';
   } elseif ($filesize >= 1048576) {
      $filesize = round($filesize / 1048576 * 100) / 100 .' MB';
   } elseif($filesize >= 1024) {
      $filesize = round($filesize / 1024 * 100) / 100 . ' KB';
   } else {
      $filesize = $filesize.' Bytes';
   }
   return $filesize;
}

/**
* 解析url参数
* @param   string $url  待解析url
* @param   string $separator  分隔符
* @return  array
*/
function getUrlQuery($url, $separator = '&')
{
	$url = urldecode($url);
	$arrUrl = parse_url($url);
	if(strpos($arrUrl['query'], '?') !== false) {
		$arrUrl = parse_url($arrUrl['query']);
	}
	
	$queryParts = explode($separator, $arrUrl['query']);
	$params = array();
	foreach($queryParts as $param) {
		$item = explode('=', $param);
		$params[$item[0]] = $item[1];
	}
	return $params;
}

/**
* 获取最新的代理ip
* @param $num 数量
* @param $$field 显示数量
*/
function getBestProxyIp($num, $map = '')
{
	$res = D('Proxyip')->getBestProxy($num, $map);
	if($res) {
		foreach($res as $proxy) {
			$data[$proxy['ip']] = $proxy;
		}
	}
	return $data? $data: '';
}

function getRandProxyIp($num, $map = '')
{
	$res = D('Proxyip')->getRandProxy($num, $map);
	if($res) {
		foreach($res as $proxy) {
			$data[$proxy['ip']] = $proxy;
		}
	}
	return $data? $data: '';
}

/**
* 指定数值范围内生成一定数量的不重复随机数
* @param $min 最小值
* @param $$max 最大值
* @param $$num 生成数量
*/
function unique_rand($min, $max, $num) 
{
	$count = 0;
	$data = array();
	if($max-$min+1 <$num) {
		return $data;
	}
	while($count < $num) {
		$t = mt_rand($min,$max);
		if(!isset($data[$t])) {
			$data[$t] = $t;
			$count++;
		}
	}
	return $data;
}