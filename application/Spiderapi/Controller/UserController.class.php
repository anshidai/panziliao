<?php 

namespace Spiderapi\Controller;

use Think\Controller;

class UserController extends Controller {
	
	public function _initialize()
	{
		$this->userModel = D('ResourceUser');
		$this->nextId = 1;
	}
	
	public function index()
	{
		
		$this->userModel = D('ResourceUser');	
	}
	
	public function add()
	{
		
		$startId = 100001;
		$step = 50;
		
		for($i=$startId; $i<$startId+$step; $i++) {

			$uid = $i;
			
			//$header[] = "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8"; 
			$header[] = "Accept: */*"; 
			$header[] = "Accept-Encoding: gzip, deflate, sdch"; 
			$header[] = "Accept-Language: zh-CN,zh;q=0.8"; 
			$header[] = "Cache-Control: max-age=0"; 
			$header[] = "Connection: keep-alive"; 
			$header[] = "Host: pan.baidu.com";  
			$header[] = "X-Requested-With: XMLHttpRequest";  
			$header[] = "Referer: http://pan.baidu.com/share/home?uk={$uid}";  
			$header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0";
			
			$timestamp = getTimestamp(13);
			$url = "http://pan.baidu.com/pcloud/user/getinfo?bdstoken=null&query_uk={$uid}&t={$timestamp}&channel=chunlei&clienttype=0&web=1";
			$htmlPage = curl_http($url, $header,'', true);
			if($htmlPage['content']) {
				$content = $htmlPage['content'];
				$content = json_decode($content, true);
				if($content['errno'] == 0) {
					$result = $this->userModel->field('id')->order('id desc')->limit(1)->select();
					if(!empty($result)) {
						$result = array_values($result);
						$nextId = $result[0]['id'];
					}
					$nextId = $nextId? $nextId+1: 1;
		
					$this->userModel->create();
					$this->userModel->id = $nextId;
					$this->userModel->uid = $uid;
 					$this->userModel->uname = $content['user_info']['uname']? $content['user_info']['uname']: '';
					$this->userModel->avatar = $content['user_info']['avatar_url']? $content['user_info']['avatar_url']: '';
					$this->userModel->intro = $content['user_info']['intro']? $content['user_info']['intro']: '';
					$this->userModel->source = 'baidu';
					$this->userModel->share_count = $content['user_info']['pubshare_count']? $content['user_info']['pubshare_count']: 0;
					$this->userModel->fans_count = $content['user_info']['fans_count']? $content['user_info']['fans_count']: 0;
					$this->userModel->follow_count = $content['user_info']['follow_count']? $content['user_info']['follow_count']: 0;
					$this->userModel->hits = 0;
					$this->userModel->addtime = time(); 
					
					$this->userModel->add();
					echo "next id ".$nextId."\r\n";
				}
				unset($htmlPage, $content);
			}
			sleep(1);
		}
	}
	
	
	
}