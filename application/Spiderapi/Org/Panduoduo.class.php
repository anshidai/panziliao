<?php

use Components\Http;
use Components\FetchHtml;

/**
* 盘多多数据抓取
*/
class Panduoduo
{
	//用户表模型
	private $userModel = null;
	
	//资源表模型
	private $resourceModel = null;
	
	//配置表模型
	private $configModel = null;
	
	//请求header头
	private $headers = array();
	
	//请求url
	private $urls = array();
	
	//请求用户主页url
	private $homeUrls = array();
	
	//列表页最大页码
	public $pageMax = 0;
	
	//总共请求次数
	public $total = 100;
	
	//每次请求并发数, 且请求次数不能小于并发数
	public $thread = 10;
	
	//延时 毫秒
	public $delay = 1000;
	
	public $logfile = '';
	
	//连续请求出错最大次数
    public $errorNum = 5;
    
	//当前累计错误次数
    private $currError = 0;
	
	public $domain = 'http://www.panduoduo.net';
	
	public $UserListParam = array(
		'node' => array(
			'element' => 'ul.u-list',
			'index' => 0,
		),
		'items' => array(
			'url' => array(
				'element' => 'div.user>a.left-',
				'node' => 'all',
				'index' => 0,
				'attr' => 'href',
			),
			'uname' => array(
				'element' => 'div.info>a',
				'node' => 'all',
				'index' => 1,
			),
			'avatar' => array(
				'element' => 'div.user>img.avatar',
				'node' => 'all',
				'index' => 0,
				'attr' => 'src',
			),
			'share_count' => array(
				'element' => 'p.status>b',
				'node' => 'all',
				'index' => 0,
			),
			'follow_count' => array(
				'element' => 'p.status>b',
				'node' => 'all',
				'index' => 1,
			),
			'fans_count' => array(
				'element' => 'p.status>b',
				'node' => 'all',
				'index' => 2,
			),
			'intro' => array(
				'element' => 'div.desc',
				'node' => 'all',
				'index' => 0,
			),
		),
	);
	
	
	public function __construct(){}
	
	public function init()
	{
		if($this->total < $this->thread) {
			$this->total = $this->thread;
		}
		
		$this->resourceModel = D('Resource');
		$this->userModel = D('ResourceUser');
		$this->configModel = D('Config');
	}
	
	public function test()
	{
		
	}
	
	/**
	* 采集用户列表页
	*/
	public function cjUserList()
	{
		$urlFormat = $this->domain.'/u/bd/%d';
		
		$pageMax = $this->getMaxPage(sprintf($urlFormat, 1));
		for($page=$pageMax+1,$i=0; $page<=$pageMax + $this->thread; $page++, $i++) {
			if($this->currError > $this->errorNum) {
				$this->writeLog('请求过快强制退出');
				exit;        
			}
			if($i > $this->thread) {
				$this->writeLog('超过最大请求页总数强制退出');
				exit; 
			}
			
			$url = sprintf($urlFormat, $page);
			$html = Http::curl_http(sprintf($urlFormat, $page), '', '', true);
			if($html['content']) {
				$fetch = new FetchHtml('', $html['content']);
				$res = $fetch->getNodeAttribute($this->UserListParam);
				if($res) {
					$users = $this->parseUserData($res);
					$insert_ids = $this->addUser($users, $this->userModel);
					$this->writeLog(" insert {$insert_ids}");
				}
			}else {
				$this->currError += 1;   
				$this->writeLog("错误信息 {$html}");
				continue;
			}
			
			unset($html);
			$this->configModel->setValue('USERMAXPAGE', (int)$page);  
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
	* 获取最大页码
	*/
	private function getMaxPage($url = '')
	{
		if($this->pageMax) {
			return $this->pageMax;
		}
		
		if($this->pageMax = $this->configModel->getValue('USERMAXPAGE')) {
			return (int)$this->pageMax;
		}
		
		$html = Http::curl_http($url, '', '', true);
		if(empty($html['content'])) {
			$this->pageMax = 1;
		}else {
			if(preg_match('/<span class=\"pcount\">(.*)<\/span>/iUs', $html['content'], $match)) {
				$this->pageMax = str_replace(array('&nbsp;','共','页'), '', strip_tags($match[1]));
			}
			unset($html);
		}
		return max((int)$this->pageMax, 1);
	}
	
	/**
	* 解析分享详情页数据
	*/
	private function parseShartData($data, $uid, $uname)
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
		unset($data);
		
		return $res;
	}
	
	/**
	* 解析用户数据
	*/
	private function parseUserData($res)
	{
		$data = array();
		if($res['url']) {
			for($i=0; $i<count($res['url']); $i++) {
				$data[$i]['uid'] = $this->getUid($res['url'][$i]);;
				$data[$i]['uname'] = $res['uname'][$i]? $res['uname'][$i]: '';
				$data[$i]['avatar'] = $res['avatar'][$i]? $res['avatar'][$i]: '';
				$data[$i]['intro'] = str_replace(array('说明：暂无说明','说明：'), '', $res['intro'][$i]);
				$data[$i]['share_count'] = str_replace(array('--', '-'), '', $res['share_count'][$i]);
				$data[$i]['share_count'] = $data[$i]['share_count']? (int)$data[$i]['share_count']: 0;
				
				$data[$i]['fans_count'] = str_replace(array('--', '-'), '', $res['fans_count'][$i]);
				$data[$i]['fans_count'] = $data[$i]['fans_count']? (int)$data[$i]['fans_count']: 0;
				
				$data[$i]['follow_count'] = str_replace(array('--', '-'), '', $res['follow_count'][$i]);
				$data[$i]['follow_count'] = $data[$i]['follow_count']? (int)$data[$i]['follow_count']: 0;
				
				$data[$i]['source'] = 'baidu';
				$data[$i]['hits'] = 0;
				$data[$i]['addtime'] = time();
				$data[$i]['cj_url'] = 'http://www.panduoduo.net/u/bd-'.$data[$i]['uid'];
				$data[$i]['cj_status'] = 2;
			}
		}
		return $data;
	}
	
	private function getUid($url)
	{
		$arr = explode('-', $url);
		return $arr[1]? (int)$arr[1]: 0;
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
	
	public function writeLog($msg = '')
    {
        $msg = date('Y-m-d H:i:s')." {$msg}\n";
		if($this->logfile) {
			file_put_contents($this->logfile, $msg, FILE_APPEND);    
		}else {
			echo $msg;
		}
    }
	
	
	
	
	
}