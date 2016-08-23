<?php

/**
* 百度网盘数据抓取
*/

use Components\Http;

class BaiduPan
{
	//用户表模型
	private $userModel = null;
	
	//资源表模型
	private $resourceModel = null;
	
	//请求header头
	private $headers = array();
	
	//请求url
	private $urls = array();
	
	//总共请求次数
	public $total = 100;
	
	//每次请求并发数, 且请求次数不能小于并发数
	public $thread = 10;
	
	//延时 毫秒
	public $delay = 1000;
	
	public function __construct()
	{
		$this->userModel = D('ResourceUser');
		//$this->resourceModel = D('Resource');
		
		if($this->total < $this->thread) {
			$this->total = $this->thread;
		}
	}
	
	public function run()
	{
		$loop = ceil($this->total/$this->thread);
		for($i = 1; $i<=$loop; $i++) {
			$this->_createUserHeaderUrl();
			if($this->urls) {
				$data = Http::curl_multi($this->urls, $this->headers, true);
				if($users = $this->_parseUserData($data)) {
					if($res = $this->addUser($users, $this->userModel)) {
						echo "insert {$res}\n";
					}    
				}
				unset($data,$users);
				if($this->delay) {
					usleep($this->delay * 1000);
				}
			}
		}
	}
	
	/**
	* 用户信息 创建请求头和url
	*/
	private function _createUserHeaderUrl()
	{
		$this->headers = $this->urls = array();
		
		$nextid = $this->userModel->getNextId();
		for($uid = $nextid; $uid<=$nextid + $this->thread; $uid++) {
            $header = array();
            $header[] = "Accept: */*"; 
            $header[] = "Accept-Encoding: gzip, deflate, sdch"; 
            $header[] = "Accept-Language: zh-CN,zh;q=0.8"; 
            $header[] = "Cache-Control: max-age=0"; 
            $header[] = "Connection: keep-alive"; 
            $header[] = "Host: pan.baidu.com";  
            $header[] = "X-Requested-With: XMLHttpRequest";  
            $header[] = "Referer: http://pan.baidu.com/share/home?uk=".$uid+rand(1000, 10000);  
            $header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0";
            $this->headers[$uid] = $header;
			
			$timestamp = getTimestamp(13);
            $this->urls[$uid] = "http://pan.baidu.com/pcloud/user/getinfo?bdstoken=null&query_uk={$uid}&t={$timestamp}&channel=chunlei&clienttype=0&web=1";
        }  
	}
	
	/**
	* 分享信息 创建请求头和url
	*/
	private function _createDetailHeaderUrl()
	{
		$this->headers = $this->urls = array();
		
		
		$header[] = "Accept: */*"; 
		$header[] = "Accept-Encoding: gzip, deflate, sdch"; 
		$header[] = "Accept-Language: zh-CN,zh;q=0.8"; 
		$header[] = "Cache-Control: max-age=0"; 
		$header[] = "Connection: keep-alive"; 
		$header[] = "Host: pan.baidu.com";  
		$header[] = "X-Requested-With: XMLHttpRequest";  
		$header[] = "Referer: http://pan.baidu.com/share/home?uk=".$uid+rand(1000, 10000);  
		$header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0";
		
		
		$nextid = $this->userModel->getNextId();
		for($uid = $nextid; $uid<=$nextid + $this->thread; $uid++) {
            $header = array();
            $header[] = "Accept: */*"; 
            $header[] = "Accept-Encoding: gzip, deflate, sdch"; 
            $header[] = "Accept-Language: zh-CN,zh;q=0.8"; 
            $header[] = "Cache-Control: max-age=0"; 
            $header[] = "Connection: keep-alive"; 
            $header[] = "Host: pan.baidu.com";  
            $header[] = "X-Requested-With: XMLHttpRequest";  
            $header[] = "Referer: http://pan.baidu.com/share/home?uk=".$uid+rand(1000, 10000);  
            $header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0";
            $this->headers[$uid] = $header;
			
			$timestamp = getTimestamp(13);
            $this->urls[$uid] = "http://pan.baidu.com/pcloud/user/getinfo?bdstoken=null&query_uk={$uid}&t={$timestamp}&channel=chunlei&clienttype=0&web=1";
        }  
	}
	
	
	/**
	* 解析用户信息
	*/
	private function _parseUserData($data = array())
	{
		if(empty($data)) return false;
    
		foreach($data as $key=>$val) {
			$jsondata = json_decode($val['results'], true);
			if($jsondata['errno'] != '0' || empty($jsondata['user_info'])) {
				continue;
			}
			$userinfo = $jsondata['user_info'];
			$res[$key] = array(
				'uid' => $userinfo['uk'],
				'uname' => $userinfo['uname']? $userinfo['uname']: '',
				'avatar' => $userinfo['avatar_url']? $userinfo['avatar_url']: '',
				'intro' => $userinfo['intro']? $userinfo['intro']: '',
				'source' => 'baidu',
				'share_count' => $userinfo['pubshare_count']? $userinfo['pubshare_count']: 0,
				'fans_count' => $userinfo['fans_count']? $userinfo['fans_count']: 0,
				'follow_count' => $userinfo['follow_count']? $userinfo['follow_count']: 0,
				'hits' => 0,
				'addtime' => time(),
				'cj_url' => $key,
				'cj_status' => 2,
			);        
		}
		unset($data);
		return $res;
	}
	
	
	private function getPanBDShareInfo($url)
	{
		$data = array();
		
		//$header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"; 
		$header[] = "Accept: */*"; 
		$header[] = "Accept-Encoding: gzip, deflate, sdch"; 
		$header[] = "Accept-Language: zh-CN,zh;q=0.8"; 
		$header[] = "Cache-Control: max-age=0"; 
		$header[] = "Connection: keep-alive"; 
		$header[] = "Host: pan.baidu.com";  
		$header[] = "X-Requested-With: XMLHttpRequest";  
		$header[] = "Referer: http://pan.baidu.com/share/home?uk=".rand(1000, 10000);  
		$header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0";
		
		$html = Http::curl_http($url, $header,'', true);
		$content = $html['content'];
		if($content) {
			$content = json_decode($content, true);
			if($content['errno'] == 0 && $content['records']) {
				foreach($content['records'] as $val) {
					$data[$val['shareid']]['uid'] = $val['uk'];
					$data[$val['shareid']]['title'] = $val['title'];
					$data[$val['shareid']]['catid'] = parsePanCategory($val['category']);
					$data[$val['shareid']]['filetype'] = getFileType($val['title']);
					$data[$val['shareid']]['filesize'] = $val['filelist'][0]['size'];
					$data[$val['shareid']]['shareid'] = $val['shareid'];
					$data[$val['shareid']]['fs_id'] = $val['filelist'][0]['fs_id'];
					$data[$val['shareid']]['sharetime'] = $val['feed_time'];
					$data[$val['shareid']]['sharedown'] = $val['dCnt']? $val['dCnt']: 0; //下载次数
					$data[$val['shareid']]['sharesave'] = $val['tCnt']? $val['tCnt']: 0; //保存次数
					$data[$val['shareid']]['shareviews'] = $val['vCnt']? $val['vCnt']: 0; //查看次数
					$data[$val['shareid']]['addtime'] = time();
					$data[$val['shareid']]['hits'] = 0;
					$data[$val['shareid']]['source'] = 'baidu';
				}
			}
		}
		unset($html, $content);
		return $data;
	}
	
	
	/**
	* 插入一条数据
	* @param $model 模型实例
	* @param $data 插入的数据
	* @param $map where条件
	*/
	private function _add($data, $model, $map = array())
	{
		if(empty($data) || !is_object($model)) return false;
		
		if($map) {
			if($model->where($map)->count()) {
				return false;
			}
		}
		$data['id'] = $model->getNextId();
		return $model->add($data);
	}
	
	/**
	* 插入用户数据
	* @param $model 模型实例
	* @param $data 插入的数据
	* @param $map where条件
	*/
	public function addUser($data, $model)
	{
		if(empty($data) || !is_object($model)) return false;
		
		$_insert_ids = '';
		foreach($data as $val) {
			if($insert_id = $this->_add($val, $model, array('uid'=>$val['uid']))) {
				$_insert_ids[] = $insert_id;
			}
		}
		return $_insert_ids? implode(', ', $_insert_ids): false;
	}
	
	
}