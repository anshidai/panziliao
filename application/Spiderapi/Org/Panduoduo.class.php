<?php


use Components\Http;

/**
* 盘多多数据抓取
*/
class Panduoduo
{
	//用户表模型
	private $userModel = null;
	
	//资源表模型
	private $resourceModel = null;
	
	//请求header头
	private $headers = array();
	
	//请求url
	private $urls = array();
	
	//请求用户主页url
	private $homeUrls = array();
	
	//列表页最大页码
	private $pageMax = 1;
	
	//总共请求次数
	public $total = 100;
	
	//每次请求并发数, 且请求次数不能小于并发数
	public $thread = 10;
	
	//延时 毫秒
	public $delay = 1000;
	
	public $domain = 'http://www.panduoduo.net';
	
	public function __construct()
	{
		$this->resourceModel = D('Resource');
		
		if($this->total < $this->thread) {
			$this->total = $this->thread;
		}
	}
	
	public function run()
	{
		
		$url = $this->domain.'/u/bd/%d';
		$this->getListMaxPage(sprintf($url, 1));
		for($i=1; $i<=$this->pageMax; $i++) {
			$userUrls[] = sprintf($url, $i);
		}
		
		$this->getUserHomeUrl($userUrls);
		
		/*
		$this->_reset();
		$url = $this->domain.'/u/bd-%d';
		$this->getListMaxPage(sprintf($url, 1));
		for($i=1; $i<=$this->pageMax; $i++) {
			$listUrls[] = sprintf($url, $i);
		}
		$this->getListUrl($listUrls);
		if(!empty($this->urls)) {
			$loop = array_chunk($this->urls, $this->thread);
			for($i = 0; $i<count($loop); $i++) {
				$data = Http::curl_multi($loop[$i], '', '', true);
				if($res = $this->_parseShartData($data)) {
					//var_dump(count($res));
					file_put_contents(__ROOT__.'/dds.txt',var_export($res,true), FILE_APPEND);
				}
				unset($data);
			}
		}
		*/
	}
	
	
	private function getUserHomeUrl($urls)
	{
		if(empty($urls)) {
			return false;
		}
		
		$loop = array_chunk($urls, $this->thread);
		for($i = 0; $i<count($loop); $i++) {
			
			$this->homeUrls = array();
			
			$data = Http::curl_multi($loop[$i], '', '', true);
			if($data) {
				foreach($data as $key=>$val) {
					if(empty($val['results'])) {
						continue;
					}
					$content = $val['results'];
					if(preg_match_all('/<div class=\"info\"><a href=\"(.*)\" target=\"_blank\" class=\"goto-uk-page\">进入主页<\/a>/iUs', $content, $url_match)) {
						foreach($url_match[1] as $url) {
							if(strpos($url, '/u/bd-') !== false) {
								$this->homeUrls[$url] = $this->domain.$url;
							}
						}
					}
				}
			}
			unset($data);
			if($this->delay) {
				usleep($this->delay * 1000);
			}
		}
	}
	
	
	/**
	* 获取详情页链接
	*/
	private function getListUrl($urls)
	{
		if(empty($urls)) {
			return false;
		}

		foreach($urls as $url) {
			$html = Http::curl_http($url, '', '', true);
			if(empty($html['content'])) {
				continue;
			}
			if(preg_match('/<table class=\"list-resource\">(.*)<\/table>/iUs', $html['content'], $match)) {
				if(preg_match_all('/<a class=\"blue\" target=\"_blank\" title=\".*\" href=\"(.*)\">/iUs', $match[1], $url_match)) {
					foreach($url_match[1] as $val) {
						if(strpos($val, '/r/') !== false) {
							$this->urls[$val] = $this->domain.$val;
						}
					}
				}
			}
			unset($html);
			if($this->delay) {
				usleep($this->delay * 1000);
			}
		}
	}
	
	/**
	* 获取列表页最大页码
	*/
	private function getListMaxPage($url)
	{
		$html = Http::curl_http($url, '', '', true);
		if(empty($html['content'])) {
			return false;
		}
		
		if(preg_match('/<span class=\"pcount\">(.*)<\/span>/iUs', $html['content'], $match)) {
			$maxpage = str_replace(array('&nbsp;','共','页'), '', strip_tags($match[1]));
		}
		unset($html);
		
		$this->pageMax = max((int)$maxpage, 1);
	}
	
	/**
	* 解析用户主页数据
	*/
	private function _parseHomeData()
	{
		
	}
	
	/**
	* 解析分享详情页数据
	*/
	private function _parseShartData($data, $uid, $uname)
	{
		if(empty($data)) return false;
		
		foreach($data as $key=>$val) {
			if(empty($val['results'])) {
				continue;
			}
			$content = $val['results'];
			
			if(preg_match('/<h1 class=\"center\">(.*)<\/h1>/', $content, $title_match)) {
				$res[$key]['title'] = $title_match[1];
				$res[$key]['filetype'] = getFileExt($res[$key]['title']);
			}
			if(preg_match('/<dd>文件大小： <b>(.*)<\/b><\/dd>/iUs', $content, $size_match)) {
				$res[$key]['filesize'] = str_replace(array('--', '-'), '', $size_match[1]);
			}
			if(preg_match('/<dd>资源分类：<a target=\"_blank\" href=\"(.*)\">.*<\/a><\/dd><dd>/iUs', $content, $category_match)) {
				$res[$key]['catid'] = getFileCategory(str_replace(array('/c/', 'c/'), '', $category_match[1]));
			}
			if(preg_match('/<dd>发布日期：(.*)<\/dd><dd>/iUs', $content, $sharetime_match)) {
				$res[$key]['sharetime'] = strtotime($sharetime_match[1]);
			}
			if(preg_match('/<dd>浏览次数：(.*)次<\/dd><dd>/iUs', $content, $hits_match)) {
				$res[$key]['hits'] = $hits_match[1];
			}
			if(preg_match('/<dd>其它：(.*)<\/dd><\/dl>/iUs', $content, $other_match)) {
				$other = str_replace(array('次下载','次保存'), '', $other_match[1]);
				list($res[$key]['down_num'], $res[$key]['save_num']) = explode('/', $other);
			}
			if(preg_match('/<a target=\"_blank\" class=\"dbutton2\" href=\"(.*)\" rel=\"nofollow\">/iUs', $content, $share_match)) {
				$urladder = pathinfo(urldecode($share_match[1]));
				$urladder = convertUrlQuery(str_replace(array('link?','link'),'', $urladder['filename']));
				
				$res[$key]['source_id'] = $urladder['shareid'];
				$res[$key]['fs_id'] = $urladder['fid'];
			}
			
			$res[$key]['cj_url'] = $key;
			$res[$key]['uid'] = $uid;
			$res[$key]['uname'] = $uname;
		}
		//file_put_contents(__ROOT__.'/dd.txt',var_export($data,true));
		unset($data);
		
		return $res;
	}
	
	
	private function _reset()
	{
		$this->urls = array();
		$this->pageMax = 1;
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