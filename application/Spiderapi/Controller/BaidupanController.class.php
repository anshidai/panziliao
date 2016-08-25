<?php 

namespace Spiderapi\Controller;

use Think\Controller;
use Components\Http;
use Components\HttpProxy;

class BaidupanController extends Controller 
{
	private $configModel = null;
	
	protected function _initialize()
    {
        header('Content-Type:text/html; charset="utf-8"');
		
		$this->configModel = D('Config');
    } 
	
	public function cjPanduoduoUser()
	{
		if($this->configModel->getValue('CJUSERLOCK') == '1') {
			die('当前进程还未结束');
		}
		
		require_once MODULE_PATH.'Org/simple_html_dom.php';
		
		import('Spiderapi.Org.Panduoduo');
		$cj = new \Panduoduo();
		$cj->logfile = IS_WIN? "./panduoduo_user".date('Ymd').".txt": "/home/libaoan/panduoduo_user".date('Ymd').".txt";
        $cj->thread = IS_WIN? 50: 300; //采集多少页
		$cj->delay = 2000;
		$cj->init();
        $cj->writeLog("采集开始start");
		$cj->cjUserList();
        $cj->writeLog("采集结束end");
	}
	
	public function cjPanduoduoDetail()
	{
		if($this->configModel->getValue('CJSHARTLOCK') == '1') {
			die('当前进程还未结束');
		}
		
		import('Spiderapi.Org.Panduoduo');
		$cj = new \Panduoduo();
		$cj->logfile = IS_WIN? "./panduoduo_detail".date('Ymd').".txt": "/home/libaoan/panduoduo_detail".date('Ymd').".txt";
        $cj->total = IS_WIN? 100: 10000;
        $cj->thread = 5;
		$cj->delay = 1000;
		$cj->init();
        $cj->writeLog("采集开始start");
		$cj->cjShareDetail();
        $cj->writeLog("采集结束end");
	}
	
	public function cjBaiduPanUser()
	{
		if($this->configModel->getValue('CJUSERLOCK') == '1') {
			die('当前进程还未结束');
		}
		
		import('Spiderapi.Org.BaiduPan');
		
		$cj = new \BaiduPan();
		$cj->logfile = "/home/libaoan/baiduPan_".date('Ymd').".txt";
		//$cj->logfile = "./baiduPan_".date('Ymd').".txt";
		$cj->total = 10000;
        $cj->thread = 20;
		$cj->delay = 2000;
		$cj->allowProxy = true;
		if($cj->allowProxy) {
			$proxy_ip_1 = HttpProxy::cj_xicidaili_ip(2);
			$proxy_ip_2 = HttpProxy::cj_66ip_ip(2);
			$proxy_ip = array_merge($proxy_ip_1, $proxy_ip_2);
			$proxy_ip = HttpProxy::filter_proxy_ips($proxy_ip, 2);
			
			$cj->writeLog("采集代理ip完成 记录总数: ".count($proxy_ip));
		}
		$cj->proxyIP = $proxy_ip? $proxy_ip: array();
        $cj->init();
		$cj->run();
	}

	public function test()
	{
		
		
	}
	
}
