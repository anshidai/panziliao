<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ConfigModel extends MongoModel 
{
	protected $pk = 'name';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	protected $autoCheckFields = false;
	protected $fields = array(
		'name', //配置名称
		'value', //值
		'desc', //描述
	);
	
	public function getValue($name)
	{
		$res = $this->field('value')->where(array('name'=>$name))->find();
		return $res? $res['value']: '';
	}
	
	public function setValue($name, $value)
	{
		return $this->where(array('name'=>$name))->save(array('value'=>$value));
	}
}
