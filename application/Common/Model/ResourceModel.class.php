<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ResourceModel extends MongoModel 
{
	protected $pk = 'id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	
	/*
	 * 开启字段检测功能, 一旦开启字段检测功能后，
	 * 系统会自动查找当前数据表的第一条记录来获取字段列表
	*/
	protected $autoCheckFields = false;
	
	protected $fields = array(
		'id', //自增id
		'title', //标题
		'catid', //所属分类
		'hits', //浏览次数
		'filetype', //文件类型 mp3 pdf flv doc等
		'filesize', //文件大小
		'source', //来源 baidu weipan
		'uid', //用户id
		'uname', //用户名称
		'shorturl', //短hash url标识
		'source_id', //分享id
		'fs_id', //分享fid
		'down_num', //下次次数
		'save_num', //保存次数
		'sharetime', //分享时间
		'addtime', //分享时间
	);
	
	/**
	* 获取下一条记录id值
	*/
	public function getNextId()
	{
		$maxid = $this->getMongoNextId($this->pk);
		return max($maxid, 1);
	}
	
	
}
