<?php 

namespace Spiderapi\Controller;

use Think\Controller;

class BaidupanController extends Controller {
	
	public function libaoan()
    {
        
        declare(ticks=1);
        $bWaitFlag = false; //是否等待进程结束
        $intNum = 3; //进程总数
        $pids = array(); //进程PID数组
        for($i = 0; $i < $intNum; $i++) {
            $pids[$i] = pcntl_fork(); //产生子进程，而且从当前行之下开试运行代码，而且不继承父进程的数据信息   
        
            //子进程得到的是0
            if(!$pids[$i]) {
                //里面执行子进程代码
                $uk = rand(1080322, 2008322);
                $timestamp = getTimestamp(13);
                $url = "http://pan.baidu.com/pcloud/user/getinfo?bdstoken=null&query_uk=108322&t={$timestamp}&channel=chunlei&clienttype=0&web=1";;
                
                $data[$i] = getPanBDShareInfo($url);
                var_dump($data[$i]);
                
                sleep(1);
                //exit();    
            }
        }
        
        if($bWaitFlag) {
            for($i = 0; $i < $intNum; $i++) {
                pcntl_waitpid($pids[$i], $status, WUNTRACED); //等待或返回fork的子进程状态
                echo "wait $i -> {$status} " . time() . "\n";
            }    
        }
        
    }
    
    public function test()
	{
		
		$timestamp = getTimestamp(13);
		$timestamp2 = getTimestamp(13).'0';
		
		$rand = randStr(16, 'number');
		$hash = base64_encode($timestamp2.'.'.$rand);
		
		$header[] = "Accept: */*"; 
		$header[] = "Accept-Encoding: gzip, deflate, sdch"; 
		$header[] = "Accept-Language: zh-CN,zh;q=0.8"; 
		$header[] = "Cache-Control: max-age=0"; 
		$header[] = "Connection: keep-alive"; 
		$header[] = "Host: pan.baidu.com";  
		$header[] = "X-Requested-With: XMLHttpRequest";  
		$header[] = "Referer: http://pan.baidu.com/share/home?uk=".rand(1000, 10000);  
		$header[] = "User-Agent: Mozilla/5.0 (Windows NT 6.1; WOW64; rv:40.0) Gecko/20100101 Firefox/40.0";
		
		$url = "http://pan.baidu.com/pcloud/feed/getsharelist?t={$timestamp}&category=0&auth_type=1&request_location=share_home&start=0&limit=60&query_uk=556952615&channel=chunlei&clienttype=0&web=1&logid={$hash}&bdstoken=null";
		$html = curl_http($url, $header,'', true);
		$content = $html['content'];
		
		file_put_contents(__ROOT__.'/dd.txt', var_export(json_decode($content,true), true));
		
		unset($html);
		
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
			
			echo "insert {$nextId}";
		}
	}
	
	
	
}