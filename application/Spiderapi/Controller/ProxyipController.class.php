<?php 
namespace Spiderapi\Controller;

use Think\Controller;
use Components\Http;
use Components\HttpProxy;

class ProxyipController extends Controller 
{
	private $proxyipModel = null;
	
	protected function _initialize()
    {
        header('Content-Type:text/html; charset="utf-8"');
		
		$this->configModel = D('Config');
		
    } 
	
	public function cjProxy66ip()
	{
		if($this->configModel->getValue('CJPROXYLOCK') == '1') {
			die(date('Y-m-d H:i:s').' 当前进程还未结束');
		}
		
		require_once MODULE_PATH.'Org/simple_html_dom.php';
		
		import('Spiderapi.Org.Proxy66ip');
		
		$cj = new \Proxy66ip();
		$cj->logfile = IS_WIN? "./Proxy_66ip".date('Ym').".txt": "/home/libaoan/Proxy_66ip".date('Ym').".txt";
		$cj->delay = 2000;
		$cj->init();
		$cj->writeLog("采集开始start");
		$cj->cjProxy();
		$cj->writeLog("采集结束end");
	}
	
	public function cjProxyXicidaili()
	{
		if($this->configModel->getValue('CJPROXYLOCK') == '1') {
			die(date('Y-m-d H:i:s').' 当前进程还未结束');
		}
		
		require_once MODULE_PATH.'Org/simple_html_dom.php';
		
		import('Spiderapi.Org.ProxyXicidaili');
		
		$cj = new \ProxyXicidaili();
		$cj->logfile = IS_WIN? "./Proxy_xicidaili".date('Ym').".txt": "/home/libaoan/Proxy_xicidaili".date('Ym').".txt";
		$cj->delay = 2000;
		$cj->init();
		$cj->writeLog("采集开始start");
		$cj->cjProxy();
		$cj->writeLog("采集结束end");
	}
	
	public function test()
	{
	}
	
	
}