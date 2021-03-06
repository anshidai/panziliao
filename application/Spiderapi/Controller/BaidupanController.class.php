<?php 

namespace Spiderapi\Controller;

use Think\Controller;
use Components\Http;
use Components\HttpProxy;

class BaidupanController extends Controller 
{
    private $configModel = null;
    private $proxyModel = null;
	private $activeProxyModel = null;
	
	protected function _initialize()
    {
        header('Content-Type:text/html; charset="utf-8"');
		
        $this->configModel = D('Config');
        $this->proxyModel = D('Proxyip');
		$this->activeProxyModel = D('ActiveProxyip');
    } 
	
	public function cjPanduoduoUser()
	{
		if($this->configModel->getValue('CJUSERLOCK') == '1') {
			die(date('Y-m-d H:i:s')." 当前进程还未结束\n");
		}
		
		require_once MODULE_PATH.'Org/simple_html_dom.php';
		import('Spiderapi.Org.Panduoduo');
		
		$cj = new \Panduoduo('user');
		$cj->logfile = !IS_WIN? "/home/libaoan/panduoduo_user".date('Ym').".txt": "./panduoduo_user".date('Ym').".txt";
        $cj->thread = IS_WIN? 50: 20; //采集多少页
        $cj->delay = 2000;
		$cj->pageMax = 20;
		$cj->proxyMaxRequestNum = 1000;
		$cj->init();
		$cj->allowProxy = true;
		if($cj->allowProxy) {
			//$cj->proxyIP = getBestProxyIp($this->proxyModel, 50);
            
            $proxy_ip_1 = HttpProxy::cj_xicidaili_ip(1);
            $cj->proxyIP = $proxy_ip_1;
			$cj->proxyIP = reverseProxyIp($cj->proxyIP);
            
            //$cj->proxyIP = ip3366ProxyIp(10);  
		
			//$proxy = $this->configModel->getValue('PROXYIP');
			//$proxy = explode(':', $proxy);
			//$cj->proxyIP = array($proxy[0]=>array('ip'=>$proxy[0],'port'=>$proxy[1],'userpwd'=>'lba8610:9rg4cjuf'));   
			//$cj->proxyIP = array($proxy[0]=>array('ip'=>$proxy[0],'port'=>$proxy[1]));   
		}
        $cj->writeLog("/**************** 采集开始start ****************/");
		//$cj->cjUserPage();
        $cj->cjUserList();
        $cj->writeLog("/**************** 采集结束end ****************/");
	}
	
	public function cjPanduoduoDetail()
	{
		if($this->configModel->getValue('CJSHARTLOCK') == '1') {
			die(date('Y-m-d H:i:s')." 当前进程还未结束\n");
		}
		
		require_once MODULE_PATH.'Org/simple_html_dom.php';
		import('Spiderapi.Org.Panduoduo');
		
		$cj = new \Panduoduo('share');
		$cj->logfile = !IS_WIN? "/home/libaoan/panduoduo_detail".date('Ym').".txt": "./panduoduo_detail".date('Ym').".txt";
        $cj->total = IS_WIN? 100: 2000;
        $cj->thread = 100;
        $cj->ListThread = 3;
		$cj->delay = 500;
		$cj->proxyMaxRequestNum = 1000;
		$cj->init();
		$cj->allowProxy = true;
		if($cj->allowProxy) {
            $proxy_ip_1 = HttpProxy::cj_xicidaili_ip(1);
            //$proxy_ip_2 = HttpProxy::cj_66ip_ip(2);
            //$proxy_ip = array_merge($proxy_ip_1, $proxy_ip_2);
            //$proxy_ip = HttpProxy::filter_proxy_ips($proxy_ip, 2);
            $cj->proxyIP = $proxy_ip_1;
            $cj->proxyIP = reverseProxyIp($cj->proxyIP);

			//$datetime = strtotime('-1 days', time());
			//$datetime = strtotime(date('Ymd'));
			//$cj->proxyIP = getRandProxyIp($this->proxyModel, 10, array('addtime'=>array('$gte'=>$datetime)));
			//$cj->proxyIP = getBestProxyIp($this->proxyModel, 100);
            
            //$cj->proxyIP = getBestProxyIp($this->activeProxyModel, 50);
            
            //$cj->proxyIP = ip3366ProxyIp(10);  
		
			//$proxy = $this->configModel->getValue('PROXYIP');
			//$proxy = explode(':', $proxy);
			//$cj->proxyIP = array($proxy[0]=>array('ip'=>$proxy[0],'port'=>$proxy[1],'userpwd'=>'lba8610:9rg4cjuf')); 
			//$cj->proxyIP = array($proxy[0]=>array('ip'=>$proxy[0],'port'=>$proxy[1])); 
		}
        $cj->writeLog("/**************** 采集开始start ****************/");
		$cj->cjShareDetail();
        $cj->writeLog("/**************** 采集结束end ****************/");
	}
    
    public function cjPanduoduoDetail2()
    {
        if($this->configModel->getValue('CJSHARTLOCK2') == '1') {
            die(date('Y-m-d H:i:s')." 当前进程还未结束\n");
        }
        
        require_once MODULE_PATH.'Org/simple_html_dom.php';
        import('Spiderapi.Org.Panduoduo2');
        
        $cj = new \Panduoduo2('share');
        $cj->logfile = !IS_WIN? "/home/libaoan/panduoduo_detail_2_".date('Ym').".txt": "./panduoduo_detail_2_".date('Ym').".txt";
        $cj->total = IS_WIN? 100: 2000;
        $cj->thread = 100;
        $cj->ListThread = 3;
        $cj->delay = 500;
        $cj->proxyMaxRequestNum = 1000;
        $cj->init();
        $cj->allowProxy = true;
        if($cj->allowProxy) {
            //$proxy = $this->configModel->getValue('PROXYIP');
			//$proxy = explode(':', $proxy);
			//$cj->proxyIP = array($proxy[0]=>array('ip'=>$proxy[0],'port'=>$proxy[1],'userpwd'=>'lba8610:9rg4cjuf'));
			//$cj->proxyIP = array($proxy[0]=>array('ip'=>$proxy[0],'port'=>$proxy[1]));

			$proxy_ip_1 = HttpProxy::cj_xicidaili_ip(1);
            $cj->proxyIP = $proxy_ip_1;
			$cj->proxyIP = reverseProxyIp($cj->proxyIP);
		}
        $cj->writeLog("/**************** 采集开始start ****************/");
        $cj->cjShareDetail();
        $cj->writeLog("/**************** 采集结束end ****************/");
    }
    
    public function cjPanduoduoDetail3()
    {
        if($this->configModel->getValue('CJSHARTLOCK3') == '1') {
            die(date('Y-m-d H:i:s')." 当前进程还未结束\n");
        }
        
        require_once MODULE_PATH.'Org/simple_html_dom.php';
        import('Spiderapi.Org.Panduoduo3');
        
        $cj = new \Panduoduo3('share');
        $cj->logfile = !IS_WIN? "/home/libaoan/panduoduo_detail_3_".date('Ym').".txt": "./panduoduo_detail_3_".date('Ym').".txt";
        $cj->total = IS_WIN? 100: 2000;
        $cj->thread = 100;
        $cj->ListThread = 3;
        $cj->delay = 500;
        $cj->proxyMaxRequestNum = 1000;
        $cj->init();
        $cj->allowProxy = true;
        if($cj->allowProxy) {
			//$proxy = $this->configModel->getValue('PROXYIP');
			//$proxy = explode(':', $proxy);
			//$cj->proxyIP = array($proxy[0]=>array('ip'=>$proxy[0],'port'=>$proxy[1],'userpwd'=>'lba8610:9rg4cjuf'));
			//$cj->proxyIP = array($proxy[0]=>array('ip'=>$proxy[0],'port'=>$proxy[1]));

			$proxy_ip_1 = HttpProxy::cj_xicidaili_ip(1);
            $cj->proxyIP = $proxy_ip_1;
			$cj->proxyIP = reverseProxyIp($cj->proxyIP);
		}
        $cj->writeLog("/**************** 采集开始start ****************/");
        $cj->cjShareDetail();
        $cj->writeLog("/**************** 采集结束end ****************/");
    }
	
	public function cjBaiduPanUser()
	{
		if($this->configModel->getValue('CJUSERLOCK') == '1') {
			die(date('Y-m-d H:i:s')." 当前进程还未结束\n");
		}
		
		import('Spiderapi.Org.BaiduPan');
		
		$cj = new \BaiduPan();
		$cj->logfile = IS_WIN? "./baidupan_user".date('Ym').".txt": "/home/libaoan/baidupan_user".date('Ym').".txt";
		$cj->total = 10000;
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
        $cj->init();
		$cj->run();
	}

	public function test()
	{

		
	}
	
}
