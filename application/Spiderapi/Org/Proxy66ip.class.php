<?php

use Components\Http;
use Components\FetchHtml;

/**
* 66ip.cn代理ip抓取
*/
class Proxy66ip
{
	//代理表模型
	private $ProxyipModel = null;
	
	//采集页面url
	private $cjUrl = array();
	
	//延时 毫秒
	public $delay = 1000;
	
	public $pageMax = 32;
	
	public $logfile = '';
	
	//域名
	public $domain = 'http://www.66ip.cn';
	
	//采集规则
	private $param = array(
		'node' => array(
			'element' => 'div#footer',
			'index' => 0,
		),
		'items' => array(
			'ip' => array(
				'element' => 'tr>td',
				'node' => 'all',
				'index' => 0,
			),
			'port' => array(
				'element' => 'tr>td',
				'node' => 'all',
				'index' => 1,
			),
			'proxy_type' => array(
				'element' => 'tr>td',
				'node' => 'all',
				'index' => 3,
			),
			'verifytime' => array(
				'element' => 'tr>td',
				'node' => 'all',
				'index' => 4,
			),
		),
	);
	
	public function __construct(){}
	
	public function init()
	{
		$this->ProxyipModel = D('Proxyip');
		
		for($i=1; $i<=$this->pageMax; $i++) {
			$this->cjUrl[] = $this->domain."/areaindex_{$i}/1.html";
		}
	}
	
	public function cjProxy()
	{
		if($this->cjUrl) {
			
			for($i=1;$i<=count($this->cjUrl); $i++) {
				$pageurl = $this->cjUrl[$i];
				$header = $this->getHearder($pageurl);
				$html = Http::curl_http($pageurl, $header, '', true);
				$html = mb_convert_encoding($html['content'], 'UTF-8', 'GBK');
				if($html) {
					$fetch = new FetchHtml('', $html);
                    $res = $fetch->getNodeAttribute($this->param);
					if($res) {
						if($data = $this->pareProxyData($res)) {
							$insert_ids = $this->addProxy($data, $this->ProxyipModel);
							if($insert_ids) {
								$this->writeLog(" insert {$insert_ids}");
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
	}
	
	private function pareProxyData($res)
	{
		$data = array();
		if($res['ip']) {
			for($i=0; $i<count($res['ip']); $i++) {
				
				if(!empty($res['port'][$i]) && preg_match('/\d+$/iUs',$res['port'][$i])) {
					$data[$i]['ip'] = $res['ip'][$i];
					$data[$i]['port'] = (int)$res['port'][$i];
					if($res['proxy_type'][$i] === '高匿代理') {
						$data[$i]['proxy_type'] = 1;
					}else {
						$data[$i]['proxy_type'] = 0;
					}
					$data[$i]['http_type'] = 'http';
					$data[$i]['isp'] = '';
					$data[$i]['speed'] = $data[$i]['speed']? str_replace(array('秒', ' '),'', $data[$i]['speed']): 0;
					$data[$i]['connect'] = $data[$i]['connect']? str_replace(array('秒', ' '),'', $data[$i]['connect']): 0;
					$data[$i]['verifytime'] = str_replace(array(' 验证','验证'), '', $res['verifytime'][$i]);
					$data[$i]['addtime'] = time();
					$data[$i]['updatetime'] = time();
				}
			}
		}
		return $data;
	}
	
	public function getHearder($pageurl = '')
	{
		$header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"; 
		$header[] = "AAccept-Encoding: gzip, deflate"; 
		$header[] = "Accept-Language: zh-CN,zh;q=0.8"; 
		$header[] = "Cache-Control: max-age=0"; 
		$header[] = "Connection: keep-alive"; 
		$header[] = "Host: www.66ip.cn"; 
		if($pageurl) {
			$header[] = "Referer: {$pageurl}"; 
		}
		$header[] = "Upgrade-Insecure-Requests: 1"; 
		$header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0";
	
		return $header;
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
	* 插入数据
	* @param $model 模型实例
	* @param $data 插入的数据
	*/
	public function addProxy($data, $model)
	{
		if(empty($data) || !is_object($model)) return false;

		$_insert_ids = '';
		foreach($data as $val) {
			if($insert_id = $this->_add($val, $model, array('ip'=>(int)$val['ip']))) {
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