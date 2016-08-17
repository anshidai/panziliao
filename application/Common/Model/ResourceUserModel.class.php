<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ResourceUserModel extends MongoModel 
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
		'uid', //用户id'
		'uname', //用户名称
		'avatar', //用户头像
		'intro', //简介
		'source', //来源 baidu weipan
		'share_count', //资源总数
		'fans_count', //粉丝数
		'follow_count', //关注数
		'hits', //浏览次数
		'addtime', //添加时间
		'cj_status', //采集状态 0-默认 1-部分数据入库 2-采集完成
		'cj_url', //采集url
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
