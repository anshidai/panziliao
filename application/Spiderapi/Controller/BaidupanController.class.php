<?php 

namespace Spiderapi\Controller;

use Think\Controller;
use Components\Http;
use Components\HttpProxy;

class BaidupanController extends Controller 
{
	protected function _initialize()
    {
        header('Content-Type:text/html; charset="utf-8"');
    } 
	
	public function cjUser()
	{
		import('Spiderapi.Org.BaiduPan');
		
		$cj = new \BaiduPan();
		$cj->logfile = "/home/libaoan/baiduPan_".date('Ymd').".txt";
		//$cj->logfile = "./baiduPan_".date('Ymd').".txt";
		$cj->total = 1000;
        $cj->thread = 20;
		$cj->delay = 1000;
		
		$cj->allowProxy = true;
		if($cj->allowProxy) {
			$proxy_ip_1 = HttpProxy::cj_xicidaili_ip(2);
			$proxy_ip_2 = HttpProxy::cj_66ip_ip(2);
			$proxy_ip = array_merge($proxy_ip_1, $proxy_ip_2);
			$proxy_ip = HttpProxy::filter_proxy_ips($proxy_ip, 2);
			
			$cj->writeLog("采集代理ip完成 记录总数: ".count($proxy_ip));
		}
		$cj->proxyIP = $proxy_ip? $proxy_ip: array();
		
		$cj->run();
	}
	
	public function cjDetail()
	{
		import('Spiderapi.Org.Panduoduo');
		$cj = new \Panduoduo();
		$cj->run();
	}
	
	public function test()
	{
		//import('Spiderapi.Org.Panduoduo');
		//$cj = new \Panduoduo();
		//$cj->run();
		
		$proxy_ip = array();

		$proxy_ip_1 = HttpProxy::cj_xicidaili_ip(2);
		var_dump($proxy_ip_1);

		
	}
	
}