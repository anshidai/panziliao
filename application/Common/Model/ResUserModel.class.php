<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ResUserModel extends MongoModel 
{
    protected $pk = 'id';
    protected $tablePrefix = 'pzl_';
    protected $_idType = self::TYPE_INT;
    protected $_autoinc = true;
    
    /*
     * 开启字段检测功能, 一旦开启字段检测功能后，
     * 系统会自动查找当前数据表的第一条记录来获取字段列表
    */
    protected $autoCheckFields = false;
    
    protected $fields = array(
        'id',
        'userid', //用户id'
        'username', //用户名称
        'avatar', //用户头像
        'intro', //简介
        'source', //来源 baidu weipan
        'share_count', //资源总数
        'fans_count', //粉丝数
        'follow_count', //关注数
        'hits', //浏览次数
        'addtime',  //创建时间 时间戳
        'adddate',  //创建时间 日期
        'data', //保存数据集合
        'cj_url', //采集url
        'cj_url_index', //采集url表_id
        'status', //审核状态 1-未审核 2-已审核
    );
    const counterskey = 'res_user';
    
    
    public function _add($param = array())
    {
        $data = array(     
            'userid' => (int)$param['userid'],      
            'username' => $param['username'], 
            'avatar' => $param['avatar']? $param['avatar']: '',       
            'intro' => $param['intro']? $param['intro']: '',  
            'source' => $param['source']? $param['source']: '',   
            'share_count' => (int)$param['share_count'],                    
            'fans_count' => (int)$param['fans_count'],                    
            'follow_count' => (int)$param['follow_count'],                    
            'hits' => $param['hits']? (int)$param['hits']: 0,                    
            'addtime' => time(),                    
            'adddate' => date('Y-m-d H:i:s'),                    
            'data' => $param['data']? $param['data']: '',                    
            'cj_url' => $param['cj_url'],                    
            'cj_url_index' => (int)$param['cj_url_index'],
        );
        
        if(empty($data['userid']) || $this->getRowExists($data['userid'])) {
            return false;    
        }
        $data['id'] = getSequenceValue(self::counterskey);
        
        return $this->add($data)? $data['id']: false;
    }
    
    
    /**
    * 查找记录是否存在 
    * @param string $url url
    */ 
    public function getRowExists($userid)
    {
        $res = $this->where(array('userid'=>$userid))->find(); 
        return $res['id']? $res['id']: '';   
    }
    
    
}
