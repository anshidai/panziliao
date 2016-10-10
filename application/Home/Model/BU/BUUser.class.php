<?php 
namespace Home\Model\BU;

class BUUser 
{
    
    /***
    * 获取最新用户信息
    * @param int $num 记录条数
    * @param array
    */
    public static function getLastestUser($num = 10)
    {
        $resUserModel = D('ResUser');
        $res = $resUserModel->order('id desc')->limit($num)->select();
        if($res) {
            foreach($res as $key=>$val) {
                $res[$key]['linkurl'] = build_url('home', array('userid'=>$val['userid']));       
            }    
        }
        return $res;
    }
    
    /***
    * 获取用户详细信息
    * @param int $userid 用户id 
    */
    public static function getUserDetail($userid)
    {
        $resUserModel = D('ResUser');
        $res = $resUserModel->where(array('userid'=>$userid))->find();
        if($res) {
            
        }
        return $res;    
    }
    
                
    
}