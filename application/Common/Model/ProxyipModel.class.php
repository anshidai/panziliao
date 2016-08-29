<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ProxyipModel extends MongoModel 
{
	protected $pk = 'id';
	protected $_idType = self::TYPE_INT;
	protected $_autoinc = true;
	protected $autoCheckFields = false;
	protected $fields = array(
		'id', //自增id
		'ip', //地址
		'port', //端口
		'proxy_type', //代理类型 1-国内高匿名 2-国内普通代理 3-国外普通代理 4-socks代理
		'http_type', //http类型 1-http 2-https 3-qq代理 4-socks代理
		'isp', //运营商 1-中国电信 2-中国联通 3-中国移动 4-中国铁通
		'speed', //速度/秒
		'connect', //连接时间/秒
		'verifytime', //验证时间
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
