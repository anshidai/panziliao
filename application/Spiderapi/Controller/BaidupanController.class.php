<?php 

namespace Spiderapi\Controller;

use Think\Controller;

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
		$cj->total = 10000;
        $cj->thread = 5;
		$cj->delay = 2000;
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
	}
	
}