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
		'shorturl', //短hash url 例：http://pan.baidu.com/s/1cyYf0M
		'dynamicurl', //动态url 例：http://pan.baidu.com/share/link?shareid=23781188&uk=422894618
		'source_id', //分享id
		'fs_id', //分享fid
		'fid', //分享fid
		'album_id', //附件id
		'down_num', //下次次数
		'save_num', //保存次数
		'sharetime', //分享时间
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
