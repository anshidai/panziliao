<?php 

namespace Spiderapi\Controller;

use Think\Controller;

class BaidupanController extends Controller {
	
    public $threadNum = 3; //并发数
    private $userUrls = array(); //用户信息请求url 
    private $userShareUrl = array(); //用户分享信息请求url 
    private $headers = array(); //请求头信息 
    
    public function init()
    {

        $this->createUserUrl();
        
        if($this->userUrls) {
            $data = curl_multi($this->userUrls, $this->headers, true);
            if($users = getPanBDUserInfo($data)) {
                //var_dump($users);exit;
                
                $this->insertUser($users);    
            }  
        }
        
    }
    
    private function createUserUrl()
    {
        $startid = 1;
        
        for($uid = $startid; $uid<=$this->threadNum; $uid++) {
            $timestamp = getTimestamp(13);
            $this->userUrls[$uid] = "http://pan.baidu.com/pcloud/user/getinfo?bdstoken=null&query_uk={$uid}&t={$timestamp}&channel=chunlei&clienttype=0&web=1";
            
            $header = array();
            $header[] = "Accept: */*"; 
            $header[] = "Accept-Encoding: gzip, deflate, sdch"; 
            $header[] = "Accept-Language: zh-CN,zh;q=0.8"; 
            $header[] = "Cache-Control: max-age=0"; 
            $header[] = "Connection: keep-alive"; 
            $header[] = "Host: pan.baidu.com";  
            $header[] = "X-Requested-With: XMLHttpRequest";  
            $header[] = "Referer: http://pan.baidu.com/share/home?uk=".$uid+rand(1000, 10000);  
            $header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0";
            $this->headers[$uid] = $header;
        }    
    }
    
	public function addshareinfo()
	{
		
	}
	
	public function adduser()
	{
		$userdata = $this->getUserInfo(656039880, 656039880);
		$this->insertUser($userdata);
	}
	
	protected function getUserInfo($start_uid, $end_uid)
	{
		$data = array();
		
		if($end_uid < $start_uid) {
			$end_uid = $start_uid;
		}
		
		import('Spiderapi.Org.WorkThread');
		for($i = $start_uid; $i <=$end_uid; $i++) {
			$timestamp = getTimestamp(13);
			$url = "http://pan.baidu.com/pcloud/user/getinfo?bdstoken=null&query_uk={$i}&t={$timestamp}&channel=chunlei&clienttype=0&web=1";;
			$thread_array[$i] = new \WorkThread($url, 'getPanBDUserInfo');
			$thread_array[$i]->start();
		}
		
		//检查线程是否执行结束
		foreach($thread_array as $thread_array_key => $thread_array_value) {
			while($thread_array[$thread_array_key]->isRunning()) {
				usleep(5000);
			}
			//如果执行结束，取出结果
			if($thread_array[$thread_array_key]->join()) {
				$temp = $thread_array[$thread_array_key]->data;
				if(!empty($temp)) {
					$data[] = $temp;
				}
				$thread_array[$thread_array_key]->kill();
			}
		}
		return $data;
	}
	
	
	protected function insertUser($data = array())
	{
		if(empty($data)) return false;
		
		$userModel = D('ResourceUser');
		
		foreach($data as $val) {
            
            $count = $userModel->where("uid={$val['uid']}")->count();
            var_dump($userModel->_sql());
            
            var_dump($count,$val['uid'], 2222);exit;
            
			$result = $userModel->field('id')->order('id desc')->limit(1)->select();
			if(!empty($result)) {
				$result = array_values($result);
				$nextId = $result[0]['id'];
			}
			$nextId = $nextId? $nextId+1: 1;

			$userModel->create();
			$userModel->id = $nextId;
			$userModel->uid = $val['uid'];
			$userModel->uname = $val['uname'];
			$userModel->avatar = $val['avatar'];
			$userModel->intro = $val['intro'];
			$userModel->source = 'baidu';
			$userModel->share_count = $val['share_count'];
			$userModel->fans_count = $val['fans_count'];
			$userModel->follow_count = $val['follow_count'];
			$userModel->cj_url = $val['cj_url'];
			$userModel->hits = 0;
			$userModel->addtime = time(); 
			$userModel->add();
			
			echo "insert {$nextId}\r\n";
		}
	}
	
	
	
}