<?php

use Components\Http;
use Components\FetchHtml;

/**
* 盘多多数据抓取
*/
class Panduoduo
{
	//采集资源 user-用户  share-分享
	public $sourceType;
	
	//用户表模型
	private $userModel = null;
	
	//资源表模型
	private $resourceModel = null;
	
	//配置表模型
	private $configModel = null;
	
	//请求url
	private $urls = array();
	
	//列表页最大页码
	public $pageMax = 0;
	
	//总共请求次数
	public $total = 100;
	
	//每次请求并发数, 且请求次数不能小于并发数
	public $thread = 10;
	
	//列表页请求并发数
	public $ListThread = 5;
	
	public $pagesize = 100;
	
	//延时 毫秒
	public $delay = 1000;
	
	public $logfile = '';
	
	//连续请求出错最大次数
    public $errorNum = 10;
    
	//当前累计错误次数
    private $currError = 0;
	
	public $domain = 'http://www.panduoduo.net';
	
	//是否开启代理 默认不开启	
    public $allowProxy = false; 
	
	//代理ip集合
	public $proxyIP = array(); 
	
	//当前正在使用代理ip
    private $currProxyIp = ''; 
	
	//一个代理ip最多请求多少URL
	public $proxyMaxRequestNum = 100;
	
	//当前代理ip已请求url数量
	private $proxyCurrRequestNum = 0;
	
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
	
	public function __construct($sourceType){
		$this->sourceType = $sourceType;
	}
	
	public function init()
	{
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
		try{
			$this->configModel->setValue('CJUSERLOCK', 1);
			$urlFormat = $this->domain.'/u/bd/{$page}';
			$pageMax = $this->pageMax;
			for($page=$pageMax+1,$i=0; $page<=$pageMax + $this->thread; $page++, $i++) {
				if($this->currError > $this->errorNum) {
					$this->writeLog('连续请求出错超过最大次数限制强制退出');
					$this->configModel->setValue('CJUSERLOCK', 2);
					exit;        
				}
				if($i > $this->thread) {
					$this->writeLog('超过最大请求页总数强制退出');
					$this->configModel->setValue('CJUSERLOCK', 2);
					exit; 
				}
				
				$url = str_replace('{$page}', $page, $urlFormat);
				$html = $this->http($url);
				if($html['content']) {
					$fetch = new FetchHtml('', $html['content']);
					$res = $fetch->getNodeAttribute($this->UserListParam);
					if($res) {
						$users = $this->parseUserData($res);
						$insert_ids = $this->addUser($users, $this->userModel);
						if($insert_ids) {
							$this->writeLog(" insert {$insert_ids}");
						}
					}
				}else {
					$this->currError += 1;   
					$this->writeLog("错误信息 ".var_export($html,true));
					continue;
				}
				unset($html);  
				if($this->delay) {
					usleep($this->delay * 1000);
				}
			}
			$this->configModel->setValue('CJUSERLOCK', 2);
		}
		catch(Exception $e) {
			$this->writeLog("【异常】 ".$e->getMessage());
			$this->configModel->setValue('CJUSERLOCK', 2);
		}
	}
    
    /**
    * 采集用户列表页
    */
    public function cjUserPage()
    {
        try{
			
            $this->configModel->setValue('CJUSERLOCK', 1);
            $urlFormat = $this->domain.'/u/bd/{$page}';
            $pageMax = $this->getMaxPage('USERMAXPAGE');
            for($page=$pageMax+1,$i=0; $page<=$pageMax + $this->thread; $page++, $i++) {
                if($this->currError > $this->errorNum) {
                    $this->writeLog('连续请求出错超过最大次数限制强制退出');
                    $this->configModel->setValue('CJUSERLOCK', 2);
                    exit;        
                }
                if($i > $this->thread) {
                    $this->writeLog('超过最大请求页总数强制退出');
                    $this->configModel->setValue('CJUSERLOCK', 2);
                    exit; 
                }

                $url = str_replace('{$page}', $page, $urlFormat);
				$html = $this->http($url);
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
                    $this->writeLog("错误信息 ".var_export($html,true));
                    continue;
                }
                
                unset($html);
                $this->configModel->setValue('USERMAXPAGE', (int)$page);  
                if($this->delay) {
                    usleep($this->delay * 1000);
                }
            }
            $this->configModel->setValue('CJUSERLOCK', 2);
        }
        catch(Exception $e) {
            $this->writeLog("【异常】 ".$e->getMessage());
            $this->configModel->setValue('CJUSERLOCK', 2);
        }
    }
	
	/**
	* 采集分享信息
	*/
	public function cjShareDetail()
	{
		try {
			$this->configModel->setValue('CJSHARTLOCK', 1);
		
			$currid = $this->configModel->getValue('CJUSERID');
			if(!$currid) {
				$currid = 0;
			}
			$map = array('id'=>array('$gt'=>(int)$currid));
			$total = $this->userModel->where($map)->count();
			
			if($total < $this->total) {
				$this->total = $total;
			}
			
			$pagemax = ceil($this->total/$this->pagesize);
			for($page=1; $page<=$pagemax; $page++) {
				$res = $this->userModel->field('id,uid,uname')->where($map)
						->order('id')->limit(($page-1)*$this->pagesize, $this->pagesize)->select();
				$this->writeLog("查询记录 ".count($res)." 执行语句 ".$this->userModel->_sql());
				if($res) {
					foreach($res as $key=>$val) {
						$url = str_replace('{$uid}', $val['uid'], $this->domain.'/u/bd-{$uid}');
						$pagecontent = $this->http($url);
						if($pagecontent['httpcode'] == 0 && empty($pagecontent['content'])) {
							$this->writeLog("{$url} ".var_export($pagecontent,true));
							continue;
						}elseif($pagecontent['httpcode'] != 200 || strpos($pagecontent['error'], 'Failed to connect') !== false) {
							$this->writeLog("{$url} ".var_export($pagecontent,true));
							continue;
						}
						
						$this->configModel->setValue('CJUSERID', $val['id']);
						if(strpos($pagecontent['content'], '找不到这个页面') !== false) {
							$this->writeLog("页面不存在 {$url}");
							continue;
						}
						if(strpos($pagecontent['content'], '该用户还没有分享的资源') !== false) {
							$this->writeLog("该用户还没有分享的资源 {$val['id']} {$val['uid']} {$val['uname']} {$url}");
							continue;
						}
						$listpageMax = $this->cjPageMax($pagecontent['content']);
						
						//有分页
						if($listpageMax) {
							for($listpage=1; $listpage<=$listpageMax; $listpage++) {
								$urls[] = $url.'/'.$listpage;
							}
							$detailUrls = $this->getDetailUrlMulti($urls);
						}else {
							$detailUrls = $this->getDetailUrl($pagecontent['content']);
						}
						unset($pagecontent);
						
						if(empty($detailUrls)) {
							continue;
						}
						
						$loop = array_chunk($detailUrls, $this->thread);
						foreach($loop as $urlArr) {
							$html = $this->http($urlArr);
							$data = $this->parseDetailData($html, $val['uid'], $val['uname']);
							if($data) {
								$insert_ids = $this->addShare($data, $this->resourceModel);
								if($insert_ids) {
									$this->writeLog(" uid:{$val['uid']} insert {$insert_ids}");
								}
							}
							unset($html,$data);
						}
					}
				}
			}
			$this->configModel->setValue('CJSHARTLOCK', 2);
			
		}catch(Exception $e) {
			$this->writeLog("【异常】 ".$e->getMessage());
			$this->configModel->setValue('CJSHARTLOCK', 2);
		}
	}
	
	/**
	* 获取详情页链接 - 单页
	*/
	private function getDetailUrl($content = '')
	{
		$this->urls = array();
		if(preg_match('/<table class=\"list-resource\">(.*)<\/table>/iUs', $content, $match)) {
			if(preg_match_all('/<a class=\"blue\" target=\"_blank\" title=\".*\" href=\"(.*)\">/iUs', $match[1], $url_match)) {
				foreach($url_match[1] as $val) {
					if(strpos($val, '/r/') !== false) {
						$this->urls[$val] = $this->domain.$val;
					}
				}
			}
		}
		return $this->urls;
	}
	
	/**
	* 获取详情页链接 - 多分页
	*/
	private function getDetailUrlMulti($urls)
	{
		if(empty($urls)) {
			return false;
		}
		$this->urls = array();
		
		$loop = array_chunk($urls, $this->ListThread);
		foreach($loop as $urlArr) {
			$html = $this->http($urlArr);
			if(empty($html)) return false;
			
			foreach($html as $key=>$val) {
				if(empty($val['results'])) {
					$this->writeLog("错误信息 ".var_export($val,true));
					continue;
				}
				if(preg_match('/<table class=\"list-resource\">(.*)<\/table>/iUs', $val['results'], $match)) {
					if(preg_match_all('/<a class=\"blue\" target=\"_blank\" title=\".*\" href=\"(.*)\">/iUs', $match[1], $url_match)) {
						foreach($url_match[1] as $url) {
							if(strpos($url, '/r/') !== false) {
								$this->urls[$url] = $this->domain.$url;
							}
						}
					}
				}
			}
			unset($html);
		}
		return $this->urls;
	}
	
	/**
	* 获取最大页码
	*/
	private function getMaxPage($name)
	{
		if($this->pageMax = $this->configModel->getValue($name)) {
			return (int)$this->pageMax;
		}
	}
		
	private function cjPageMax($content = '')
	{
		if(preg_match('/<span class=\"pcount\">(.*)<\/span>/iUs', $content, $match)) {
			$pageMax = str_replace(array('&nbsp;','共','页'), '', strip_tags($match[1]));
		}
		$this->pageMax = (int)$pageMax;
		return $this->pageMax? $this->pageMax: 0;
	}
	
	/**
	* 解析分享详情页数据
	*/
	private function parseDetailData($data, $uid, $uname)
	{
		if(empty($data)) return false;
		foreach($data as $key=>$val) {
			if(empty($val['results'])) {
				continue;
			}
			$content = $val['results'];
			$row = array();
			$urlQuery = array();
			if(preg_match('/<h1 class=\"center\">(.*)<\/h1>/', $content, $title_match)) {
				$row['title'] = $title_match[1];
				$row['filetype'] = getFileExt($row['title']);
			}
			if(preg_match('/<dd>文件大小： <b>(.*)<\/b><\/dd>/iUs', $content, $size_match)) {
				$row['filesize'] = str_replace(array('--', '-'), '', $size_match[1]);
			}
			if(preg_match('/<dd>资源分类：<a target=\"_blank\" href=\"(.*)\">.*<\/a><\/dd><dd>/iUs', $content, $category_match)) {
				$row['catid'] = getFileCategory(str_replace(array('/c/', 'c/'), '', $category_match[1]));
			}
			if(preg_match('/<dd>发布日期：(.*)<\/dd><dd>/iUs', $content, $sharetime_match)) {
				$row['sharetime'] = strtotime($sharetime_match[1]);
			}
			if(preg_match('/<dd>浏览次数：(.*)次<\/dd><dd>/iUs', $content, $hits_match)) {
				$row['hits'] = (int)$hits_match[1];
			}
			if(preg_match('/<dd>其它：(.*)<\/dd><\/dl>/iUs', $content, $other_match)) {
				$other = str_replace(array('次下载','次保存'), '', $other_match[1]);
				list($row['down_num'], $row['save_num']) = explode('/', $other);
				$row['down_num'] =  (int)$row['down_num'];
				$row['save_num'] =  (int)$row['save_num'];
			}
			if(preg_match('/<a target=\"_blank\" class=\"dbutton2\" href=\"(.*)\" rel=\"nofollow\">/iUs', $content, $share_match)) {
				$urlparams = getUrlQuery($share_match[1]);

				$row['source_id'] = $urlparams['shareid']? (int)$urlparams['shareid']: 0;
				$row['album_id'] = $urlparams['album_id']? (int)$urlparams['album_id']: 0;
				$row['fs_id'] = $urlparams['fsid']? (int)$urlparams['fsid']: 0;
				$row['fid'] = $urlparams['fid']? (int)$urlparams['fid']: 0;
			}
			
			$link = 'share/link?';
			if($row['source_id']) {
				$urlQuery[] = "shareid={$row['source_id']}";
			}
			if($row['album_id']) {
				$urlQuery[] = "album_id={$row['album_id']}";
				$link = empty($row['filetype'])? 'pcloud/album/info?': 'pcloud/album/file?';
			}
			if($row['fs_id']) {
				$urlQuery[] = "fsid={$row['fs_id']}";
			}
			if($row['fid']) {
				$urlQuery[] = "fid={$row['fid']}";
			}
			if(empty($urlQuery)) {
				continue;
			}
			$urlQuery[] = "uk={$uid}";

			$row['cj_status'] = 2;
			$row['cj_url'] = $key;
			$row['addtime'] = time();
			$row['shorturl'] = '';
			$row['dynamicurl'] = "http://pan.baidu.com/{$link}".implode('&', $urlQuery);
			$row['source'] = 'baidu';
			$row['addtime'] = time();
			$row['uid'] = (int)$uid;
			$row['uname'] = $uname;
			
			$res[] = $row;
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
	*/
	public function addUser($data, $model)
	{
		if(empty($data) || !is_object($model)) return false;

		$_insert_ids = '';
		foreach($data as $val) {
			if($insert_id = $this->_add($val, $model, array('uid'=>(int)$val['uid']))) {
				$_insert_ids[] = $insert_id;
			}
		}
		return $_insert_ids? implode(', ', $_insert_ids): false;
	}
	
	/**
	* 插入分享数据
	* @param $model 模型实例
	* @param $data 插入的数据
	*/
	public function addShare($data, $model)
	{
		if(empty($data) || !is_object($model)) return false;

		$_insert_ids = '';
		foreach($data as $val) {
			if($insert_id = $this->_add($val, $model, array('source_id'=>(int)$val['source_id'],'fs_id'=>(int)$val['fs_id']))) {
				$_insert_ids[] = $insert_id;
			}
		}
		return $_insert_ids? implode(', ', $_insert_ids): false;
	}
	
	public function writeLog($msg = '', $exit = false)
    {
        $msg = date('Y-m-d H:i:s')." {$msg}\n";
		if($this->logfile) {
			file_put_contents($this->logfile, $msg, FILE_APPEND);    
		}else {
			echo $msg;
		}
		if($exit) exit;
    }
	
	private function http($url, $gzip = true, $header = array())
	{
		$proxy = array();
		
		if($this->allowProxy) {
			if(!$this->proxyCurrRequestNum || $this->proxyCurrRequestNum > $this->proxyMaxRequestNum) {
				$proxy = $this->changeProxy();
				if(empty($proxy)) {
					if($this->sourceType === 'user') {
						$this->configModel->setValue('CJUSERLOCK', 2);
					}else if($this->sourceType === 'share') {
						$this->configModel->setValue('CJSHARTLOCK', 2);
					}
					$this->writeLog('当前没有可用的代理ip退出', true);
				}
				$this->proxyCurrRequestNum = 0;
			}
			$this->proxyCurrRequestNum += is_array($url)? count($url): 1;
		}
		
		if(is_array($url)) {
			$html = Http::curl_multi($url, $header, $gzip, $proxy);
		}else {
			$html = Http::curl_http($url, $header, $proxy, $gzip); 
		}
		return $html;
	}
	
	/**
    * 每次获取一个代理ip
    */
    private function changeProxy()
    {
        //如果有代理ip则删除当前，取下一个
        if($this->currProxyIp) unset($this->proxyIP[$this->currProxyIp]);
		
        if(empty($this->proxyIP)) return '';
        
		$data = array();
        foreach($this->proxyIP as $val) {
            $data['ip'] = $val['ip'];
            $data['port'] = $val['port'];
			
			$this->currProxyIp = $val['ip'];
            break;
        }
		$this->writeLog("当前代理ip ".implode(':', $data)."  剩余代理IP数量：".count($this->proxyIP));
		
		return !empty($data)? $data: '';
    }
	
	
	
	
	
}