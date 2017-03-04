<?php 

namespace Home\Model\BU;

use Components\helper\UrlHelper;

class BUResDetail
{
    
    /**
    * 获取某个分类下分享资源总数
    * @param int $catid 分类id 
    */
    public static function getCatDetailTotal($catid)
    {
        $resDetailModel = D('ResDetail');
        
        return $resDetailModel->where(array('catid'=>$catid,'status'=>2))->count();        
    }
    
    /**
    * 获取最新资源 
    * @param int $pagesize 取记录条数 
    */
    public static function getLatestDetail($pagesize = 10)
    {
        $resDetailModel = D('ResDetail');
        
        return $resDetailModel->where(array('status'=>2))->order('id desc')->limit($pagesize)->select();       
    }
    
    /**
    * 获取某个分类下分享资源数据 
    * @param int $catid 分类id 
    * @param int $num 取记录条数 
    * @param string $sort 排序 
    */
    public static function getCatDetailList($catid, $num = 10, $field = '', $sort = 'id desc')
    {
        $resDetailModel = D('ResDetail');
        
        $obj = $resDetailModel->field('')->where(array('catid'=>$catid,'status'=>2))->order($sort);
        if($num) {
            $obj = $obj->limit($num);    
        }
        $res = $obj->select();
        if($res) {
            foreach($res as $key=>$val) {
                $res[$key]['linkurl'] = UrlHelper::url('share_detail', $val['id']);
            }                
        }
        return $res;    
    }    
    
}