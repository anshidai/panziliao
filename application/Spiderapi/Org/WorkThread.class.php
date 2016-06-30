<?php 

//namespace Spiderapi\Org; 

/**
 * 多线程抓取对象
 */
class Workthread extends \Thread {
	public $url;
	public $data;
	public $func;
	public function __construct($url, $func) 
	{
		$this->url = $url;
		$this->func = $func;
	}
	public function run() 
	{
		if(($url = $this->url)) {
			//线程执行方法
			//$this->data = PanInfoGet($url);
			$this->data = call_user_func($this->func, $url);
		}
	}
}