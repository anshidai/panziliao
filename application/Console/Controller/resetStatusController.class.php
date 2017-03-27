<?php 

namespace Console\Controller;

use Think\Controller;

/**
* 执行脚本 php index.php Console/Reset/xxx
*/
class ResetStatusController extends Controller
{
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
	
    
}