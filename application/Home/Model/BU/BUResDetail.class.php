<?php 

namespace Home\Model\BU;

use Components\helper\UrlHelper;

class BUResDetail
{
    /**
    * 获取某个分类下分享资源数据 
    * @param int $catid 分类id 
    * @param int $num 取记录条数 
    * @param string $sort 排序 
    */
    public static function getCatDetailList($catid, $num = 10, $field = '*', $sort = 'id desc')
    {
        $resDetailModel = D('ResDetail');
        
        $obj = $resDetailModel->field($field)->where(array('catid'=>$catid))->order($sort);
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