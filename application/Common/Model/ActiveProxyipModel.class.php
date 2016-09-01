<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ActiveProxyipModel extends MongoModel 
{
    protected $pk = 'id';
    protected $_idType = self::TYPE_INT;
    protected $_autoinc = true;
    protected $autoCheckFields = false;
    protected $fields = array(
        'id', //自增id
        'ip', //ip
        'port', //端口
        'expires', //是否有效  1-有效 2-无效
        'addtime', //添加时间
        'updatetime', //更新时间
    );
    
    /**
    * 获取下一条记录id值
    */
    public function getNextId()
    {
        $maxid = $this->getMongoNextId($this->pk);
        return max($maxid, 1);
    }
    
    public function getBestProxy($num = 100, $map = array())
    {
        $res = $this->where($map)->order("{$this->pk} desc")->limit($num)->select();
        return $res;
    }
    
    /**
    * 随机获取
    */
    public function getRandProxy($num = 100, $map = array())
    {
        $minid = $this->field($this->pk)->where($map)->order("{$this->pk}")->find();
        $maxid = $this->field($this->pk)->where($map)->order("{$this->pk} desc")->find();
        
        $ids = unique_rand($minid['id'], $maxid['id'], $num);
        $map['id'] = array('$in'=>array_values($ids));

        $res = $this->where($map)->select();
        return $res;
    }
    
    
}
