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
		$cj->total = 100;
		$cj->thread = 10;
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
		import('Spiderapi.Org.Panduoduo');
		$cj = new \Panduoduo();
		$cj->run();
	}
	
}