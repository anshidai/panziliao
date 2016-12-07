<?php 

namespace Home\Model\BU;

use Components\helper\UrlHelper;

class BUUserDetail 
{
    
    /***
    * 获取用户分享资源总数
    * @param int $userid 用户id
    * @return int 
    */
    public static function getUserDetailTotal($userid)
    {
        $resDetailModel = D('ResDetail');
        $total = $resDetailModel->where(array('userid'=>$userid))->count();
        return $total;    
    }
    
    /***
    * 获取用户分享资源
    * @param int $userid 用户id
    * @param int $num 记录数
    * @param string $sort 排序 desc-倒序 asc-升序
    * @return array 
    */
    public static function getUserDetailList($userid, $num = 0, $sort = 'id desc')
    {
        $resDetailModel = D('ResDetail');
        
        $obj = $resDetailModel->where(array('userid'=>$userid))->order($sort);
        if($num) {
            $obj = $obj->limit($num);    
        }
        $res = $obj->select();
        if($res) {
            foreach($res as $key=>$val) {
                $res[$key]['linkurl'] = UrlHelper::url('share_detail', $val['id']);
                $res[$key]['home_linkurl'] = UrlHelper::url('share_home', $val['userid']);
            }                
        }
        return $res;    
    }
    
    /***
    * 获取详细信息
    * @param int $id 
    * @return array
    */
    public static function getDetail($id)
    {
        $resDetailModel = D('ResDetail');
        $res = $resDetailModel->where(array('id'=>$id))->find();
        if($res) {
            $res['linkurl'] = UrlHelper::url('share_detail', $res['id']);
            $res['home_linkurl'] = UrlHelper::url('share_home', $res['userid']);
        }
        return $res;    
    }
    
                
    
}