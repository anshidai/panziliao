<?php 

namespace Home\Model\BU;

use Components\helper\UrlHelper;
use Components\PZL;

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
    * @param int $page 页码  
    * @param string $sort 排序
    * @param string $field 显示字段
    */
    public static function getCatDetailList($catid, $num = 10, $page = 1, $sort = 'id desc', $field = '')
    {
        $resDetailModel = D('ResDetail');
        
        $obj = $resDetailModel->field($field)->where(array('catid'=>$catid,'status'=>2))->order($sort);
        $obj = $obj->limit(($page - 1) * $num. ','.$num);
        $res = $obj->select();
        
        return $res;    
    } 
    
    /**
    * 获取指定分类下最新资源 
    */
    public static function getCategoryBestDetail($cid, $num = 0, $sort = 'id desc')
    {
        $resDetailModel = D('ResDetail');
        
        $obj = $resDetailModel->where(array('catid'=>$cid, 'status'=>2))->order($sort);
        if($num) {
            $obj = $obj->limit($num);    
        }
        $res = $obj->select();
        
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

        return $res;    
    }
    
    /***
    * 获取用户分享资源总数
    * @param int $userid 用户id
    * @return int 
    */
    public static function getUserDetailTotal($userid)
    {
        $resDetailModel = D('ResDetail');
        $total = $resDetailModel->where(array('res_user_id'=>$userid,'status'=>2))->count();
		
        return $total;    
    }
    
    /***
    * 获取用户分享资源
    * @param int $userid 用户id
    * @param int $num 记录数
    * @param int $page 页码
    * @param string $sort 排序 desc-倒序 asc-升序
    * @param string $field 显示字段
    * @return array 
    */
    public static function getUserDetailList($userid, $num = 10, $page = 1, $sort = 'id desc', $field = '')
    {
        $resDetailModel = D('ResDetail');
        
        $obj = $resDetailModel->where(array('res_user_id'=>$userid, 'status'=>2))->order($sort);
        $obj = $obj->limit(($page - 1) * $num. ','.$num);
        $res = $obj->select();

        return $res;    
    }
    
    /**
    * 获取类似数据 
    */
    public static function getLikeRes($keyword, $distinctId = 0)
    {
        $resDetailModel = D('ResDetail');
        
        $map = array();
        if($distinctId) {
            $map['id'] = array('neq', $distinctId);        
        }
        
        $res = $resDetailModel->where(array('id'=>$id))->find();
    }  
	
	/**
	* 获取随机
	*/
	public static function getRandShareRes($cid, $pagesize = 10)
	{
		$key = PZL::getCachekey('category_detail_rand', $cid);
		$data = PZL::cache('redis')->get($key);
		if(!$data) {
			$data = D('ResDetail')->field('id,title')->where(array('res_user_id'=>$userid, 'status'=>2))
					->order("rand()")->limit($pagesize)->select();
			PZL::cache('redis')->set($key, $data, 86400*30);
		}
		
		return $data;
	}
    
}