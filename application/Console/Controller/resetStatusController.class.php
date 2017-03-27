<?php 

namespace Console\Controller;

use Think\Controller;

class ResetStatusController extends Controller
{
    protected function _initialize()
    {
        
    }
    
    public function resetUser()
    {
        $model = D('ResUser');
        
    }
    
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
