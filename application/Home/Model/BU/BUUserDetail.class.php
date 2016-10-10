<?php 
namespace Home\Model\BU;

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
    public static function getUserDetailList($userid, $num = 0, $sort = 'desc')
    {
        $resDetailModel = D('ResDetail');
        
        $obj = $resDetailModel->where(array('userid'=>$userid))->order("id {$sort}");
        if($num) {
            $obj = $obj->limit($num);    
        }
        $res = $obj->select();
        
        //$res = $resDetailModel->where(array('userid'=>$userid))->order("id {$sort}")->select();
        if($res) {
            foreach($res as $key=>$val) {
                $res[$key]['linkurl'] = build_url('detail', array('detailid'=>$val['id']));
                $res[$key]['home_linkurl'] = build_url('home', array('userid'=>$val['userid']));
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
            $res['linkurl'] = build_url('detail', array('detailid'=>$res['id']));
            $res['home_linkurl'] = build_url('home', array('userid'=>$res['userid']));
        }
        return $res;    
    }
    
                
    
}