<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class IndexUrlModel extends MongoModel 
{
    protected $pk = 'id';
    protected $tablePrefix = 'pzl_';
    protected $_idType = self::TYPE_INT;
    protected $_autoinc = true;
    protected $autoCheckFields = false;
    protected $fields = array(
        'id', 
        'url',  //采集url
        'resid',  //资源id 如：用户id 资源详情id
        'source',  //采集网站 panduoduo:盘多多
        'status',  //采集状态 -1:只采集网址 0:采集内容成功 >0采集错误(状态200除外)
        'addtime',  //创建时间 时间戳
        'adddate',  //创建时间 日期
        'type',  //分类 user:用户 detail:详情
        'extend',  //扩展字段
    ); 
    const counterskey = 'index_url';
    
    public function _add($param = array())
    {
        $data = array(   
            'url' => $param['url'],       
            'resid' => $param['resid'],      
            'source' => $param['source']? $param['source']: '',       
            'status' => (int)$param['status'],       
            'addtime' => time(),   
            'adddate' => date('Y-m-d H:i:s'),   
            'type' => 'user',      
            'extend' => $param['extend']? $param['extend']: '',                    
        );
        if(empty($data['url']) || $this->getRowExists($data['url'])) {
            return false;    
        }
        $data['id'] = getSequenceValue(self::counterskey);

        return $this->add($data)? $data['id']: false;
    }
    
    
    /**
    * 查找记录是否存在 
    * @param string $url url
    */ 
    public function getRowExists($url)
    {
        $res = $this->where(array('url'=>$url))->find(); 
        return $res['id']? $res['id']: '';   
    }
    
       
}