<?php 

namespace Spiderapi\Controller;

use Think\Controller;

class BaidupanController extends Controller 
{
	protected function _initialize()
    {
        header('Content-Type:text/html; charset="utf-8"');
    } 
	
	public function test()
	{
		import('Spiderapi.Org.Panduoduo');
		$cj = new \Panduoduo();
		$cj->run();
	}
	
    public function init()
    {
		$act = $GLOBALS['argv'][2];
		//$act = 'baidupanUser';
		
		if($act == 'baidupanUser') {
			import('Spiderapi.Org.BaiduPan');
			
			$cj = new \BaiduPan();
			$cj->total = 1;
			$cj->thread = 1;
			$cj->run();
			
		} else if($act == 'baidupanDetail') {
			
			
			
		}
		
		
		
    }
	
}