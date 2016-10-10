<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class CountersModel extends MongoModel 
{
    protected $pk = '_id';
    protected $tablePrefix = 'pzl_';
    protected $_idType = self::TYPE_INT;
    protected $_autoinc = true;
    protected $autoCheckFields = false;
    protected $fields = array(
        'name', //自增key 表名(不包含表前缀)
        'value', //自增值
    );
    
    /**
    * 获取自增值
    * 
    * @param string $name 
    */
    public function getNextSequence($name)
    {
        $sequence = 1;
        
        $res = $this->where(array('name'=>$name))->find();
        if($res['value']) {
            $sequence = $res['value'];
        }
        $this->setNextSequence($name, $sequence + 1);
        return $sequence;    
    }
    
    /***
    * 设置自增值
    * 
    * @param string $name
    * @param int $sequence
    */
    public function setNextSequence($name, $sequence = 1)
    {
        return $this->where(array('name'=>$name))->save(array('value'=>$sequence));        
    }
    
        
}