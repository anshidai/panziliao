<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class CategoryModel extends MongoModel 
{
	protected $pk = 'catid';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	
	public function getCategory($cid = '')
	{
		$where = '';
		if($cid) {
			$where = array('catid'=>$cid);
		}
		return $this->where($where)->select();
	}
}
