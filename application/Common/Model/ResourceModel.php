<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ResourceModel extends MongoModel 
{
	protected $pk = 'id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	
	
	public function add($data = array())
	{
		$this->add($data);
		return $this->getMongoNextId('id');
	}
	
	
}
