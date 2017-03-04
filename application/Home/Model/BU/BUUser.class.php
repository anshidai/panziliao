<?php 

namespace Home\Model\BU;

use Components\helper\UrlHelper;

class BUUser 
{
    private static $models = null;
    
    public static function getInstance($modelname)
    {
        if(!isset(self::$models[$modelname])) {
            return self::$models[$modelname] = D($modelname);   
        }
        return self::$models[$modelname];               
    }
    
    /***
    * 获取最新用户信息
    * @param int $num 记录条数
    * @param array
    */
    public static function getLastestUser($num = 10, $field = '')
    {
        $resUserModel = self::getInstance('ResUser');
        $res = $resUserModel->field($field)->where(array('status'=>2))->order('id desc')->limit($num)->select();
        if($res) {
            foreach($res as $key=>$val) {
                $res[$key]['linkurl'] = UrlHelper::url('share_home', $val['userid']);
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
        $resUserModel = self::getInstance('ResUser');
        $res = $resUserModel->where(array('userid'=>$userid))->find();
        if($res) {
                    
        }
        return $res;    
    }
    
                
    
}