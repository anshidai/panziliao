<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ResourceUserModel extends MongoModel 
{
	protected $pk = 'id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	protected $autoCheckFields = false; //开启字段检测功能, 一旦开启字段检测功能后，系统会自动查找当前数据表的第一条记录来获取字段列表
	
	protected $fields = array('id','uid','uname','avatar','intro','source','share_count','fans_count','follow_count','hits','addtime');
	
}
