<?php 

namespace Console\Controller;

use Think\Controller;

/**
* 执行脚本 php index.php Console/ResetStatus/xxx 
*/
class ResetStatusController extends Controller
{
    protected function _initialize()
    {
        
    }
	
	public function updateStatus()
	{
		$this->updateRestUserStatus();
		$this->updateResetDetailStatus();
	}
	
	/**
	* 定时审核通过用户信息 status=2
	*/
    public function updateRestUserStatus()
	{
		$pagesize = 60;
		$model = D('ResUser');
		$model->field('id,status')->where(array('status'=>1))->order('id asc')->limit($pagesize)->save(array('status'=>2));
		
		echo "updateRestUserStatus complete\n";
	}
	
	
	 /**
	* 定时审核通过分享信息 status=2
	*/
    public function updateResetDetailStatus()
    {
        $pagesize = 30;
		$model = D('ResDetail');
		$model->where(array('status'=>1))->order('id asc')->limit($pagesize)->save(array('status'=>2));
        
        echo "updateResetDetailStatus complete\n";
    }  
	
	/**
	* 定时审核通过用户和分享信息 status=2
	*/
    public function updateRestStatus()
	{
		$userids = array();
		$pagesize = 60; //用户每次审核通过数量
		
		$model = D('ResUser');
		$list = $model->field('id,status')->where(array('status'=>1))->order('id asc')->limit($pagesize)->select();
		if($list) {
			foreach($list as $val) {
				$userids[$val['id']] = $val['id'];
				$model->where(array('id'=>$val['id']))->save(array('status'=>2));
			}
		}
		
		if(!empty($userids)) {
			$model = D('ResDetail');
			foreach($userids as $uid) {
				$model->where(array('res_user_id'=>$uid))->save(array('status'=>2));
			}
		}
	}
	
	
    /**
	* 批量更新字段值
	*/
    public function resetDetail()
    {
        $resDetailModel = D('ResDetail');
        $resUserModel = D('ResUser');
        $pagesize = 500;
        $map['id'] = array('$gt' => 163000);
        $total = $resDetailModel->where($map)->count();
        //var_dump($total,$resDetailModel->_sql());exit;
        $maxPage = ceil($total/$pagesize);
        for($page=1; $page<=$maxPage; $page++) {
            $limit = ($page - 1) * $pagesize . ','. $pagesize;
            $list = $resDetailModel->field('id,userid')->where($map)->order("id asc")->limit($limit)->select();
            if($list) {
                foreach($list as $val) {
                    if(empty($val['userid'])) {
                        continue;
                    } 
                    $user = $resUserModel->field('id,userid')->where(array('userid'=>$val['userid']))->find();
                    if($user) {
                        $resDetailModel->where(array('id'=>$val['id']))->save(array('res_user_id'=>$user['id']));
                        echo "{$val['id']} ".date('Y-m-d H:i:s')."\n";    
                    }   
                }
		unset($user);
            }
            unset($list);
        }
        
        echo "complete\n";
    }    
    
}
