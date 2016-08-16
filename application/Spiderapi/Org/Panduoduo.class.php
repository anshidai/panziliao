<?php

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
		//$this->_getDetailUrl();
		$this->_getDetailInfo('http://www.panduoduo.net/r/21055767');
		
		//var_dump($this->urls);
	}
	
	/**
	* 获取详情页链接
	*/
	private function _getDetailUrl($uid)
	{
		$html = curl_http('http://www.panduoduo.net/u/bd-'.$uid);
		
		if(empty($html['content'])) return false;
		
		if(preg_match('/<table class=\"list-resource\">(.*)<\/table>/iUs', $html['content'], $match)) {
			if(preg_match_all('/<a class=\"blue\" target=\"_blank\" title=\".*\" href=\"(.*)\">/iUs', $match[0], $url_match)) {
				foreach($url_match[1] as $url) {
					if(strpos($url, '/r/') !== false) {
						$this->urls[] = $this->domain.$url;
					}
				}
			}
		}
		unset($html);
	}
	
	
	private function _getDetailInfo($user = array(), $url)
	{
		$html = curl_http($url);
		
		if($html['content']) {
			if(preg_match('/<h1 class=\"center\">(.*)<\/h1>/', $html['content'], $title_match)) {
				$data['title'] = $title_match[1];
				$data['filetype'] = getFileExt($data['title']);
			}
			if(preg_match('/<dd>文件大小： <b>(.*)<\/b><\/dd>/iUs', $html['content'], $size_match)) {
				$data['filesize'] = $size_match[1];
			}
			if(preg_match('/<dd>发布日期：(.*)<\/dd><dd>/iUs', $html['content'], $sharetime_match)) {
				$data['sharetime'] = $sharetime_match[1];
			}
			if(preg_match('/<dd>浏览次数：(.*)次<\/dd><dd>/iUs', $html['content'], $hits_match)) {
				$data['hits'] = $hits_match[1];
			}
			if(preg_match('/<dd>其它：(.*)<\/dd><\/dl>/iUs', $html['content'], $other_match)) {
				$other = str_replace(array('次下载','次保存'), '', $other_match[1]);
				list($data['down_num'], $data['save_num']) = explode('/', $other);
			}
			if(preg_match('/<a target=\"_blank\" class=\"dbutton2\" href=\"(.*)\" rel=\"nofollow\">/iUs', $html['content'], $share_match)) {
				$urladder = pathinfo(urldecode($share_match[1]));
				$urladder = convertUrlQuery(str_replace(array('link?','link'),'', $urladder['filename']));
				
				$data['source_id'] = $urladder['shareid'];
				$data['fs_id'] = $urladder['fid'];
			}
			
			$data['uid'] = $user['uid'];
			$data['uname'] = $user['uname'];
		}
		//file_put_contents(__ROOT__.'/dd.txt',var_export($data,true));
		unset($html['content']);
		
		return $data;
	}
	
	
	private functin _parseShartData($data = array())
	{
		
	}
	
	
	
	
	
	
	
}