<?php 

namespace Spiderapi\Controller;

use Think\Controller;

class BaidupanController extends Controller {
	
    public $threadNum = 3; //并发数
	private $startId; //开始id
    private $userUrls = array(); //用户信息请求url 
    private $userShareUrl = array(); //用户分享信息请求url 
    private $headers = array(); //请求头信息
	
    public function init()
    {
		
		
		/*
        $this->createUserUrl();
        
        if($this->userUrls) {
            $data = curl_multi($this->userUrls, $this->headers, true);
            if($users = getPanBDUserInfo($data)) {
                //var_dump($users);exit;
                
                $this->insertUser($users);    
            }  
        }
		*/
        
    }
	
	
	public function getMaxId()
	{
		$userModel = D('ResourceUser');
		
		//$res = $userModel->getMongoNextId('uid');
		var_dump($userModel);
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
		$this->_insertUser($userdata);
	}
	
	protected function _insertUser($data = array())
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