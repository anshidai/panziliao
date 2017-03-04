<?php 
namespace Common\Model;

use Think\Model\MongoModel;

class ResDetailModel extends MongoModel 
{
    protected $pk = 'id';
    protected $tablePrefix = 'pzl_';
    protected $_idType = self::TYPE_INT;
    protected $_autoinc = true;
    
    /*
     * 开启字段检测功能, 一旦开启字段检测功能后，
     * 系统会自动查找当前数据表的第一条记录来获取字段列表
    */
    protected $autoCheckFields = false;
    
    protected $fields = array(
        'id',
        'title', //标题
        'catid', //所属分类
        'hits', //浏览次数
        'filetype', //文件类型 mp3 pdf flv doc等
        'filesize', //文件大小
        'source', //来源 baidu weipan
        'userid', //用户id
        'username', //用户名称
        'shorturl', //短hash url 例：http://pan.baidu.com/s/1cyYf0M
        'dynamicurl', //动态url 例：http://pan.baidu.com/share/link?shareid=23781188&uk=422894618
        'source_id', //分享id
        'fs_id', //分享fid
        'fid', //分享fid
        'album_id', //附件id
        'down_num', //下次次数
        'save_num', //保存次数
        'sharetime', //分享时间
        'addtime',  //创建时间 时间戳
        'adddate',  //创建时间 日期
        'data', //保存数据集合
        'cj_url', //采集url
        'cj_url_index', //采集url表_id
        'status', //审核状态 1-未审核 2-已审核
    );
    const counterskey = 'res_detail';
    
    public function _add($param = array())
    {
        $data = array(   
            'title' => $param['title'],       
            'catid' => (int)$param['catid'],      
            'hits' => $param['hits']? (int)$param['hits']: 0,       
            'filetype' => $param['filetype']? $param['filetype']: '',       
            'filesize' => $param['filesize']? $param['filesize']: '',  
            'source' => $param['source']? $param['source']: '',   
            'userid' => (int)$param['userid'],      
            'username' => $param['username']? $param['username']: '',                    
            'shorturl' => $param['shorturl']? $param['shorturl']: '',                    
            'dynamicurl' => $param['dynamicurl']? $param['dynamicurl']: '',                    
            'source_id' => (int)$param['source_id'],                    
            'fs_id' => (int)$param['fs_id'],                    
            'fid' => (int)$param['fid'],                    
            'album_id' => (int)$param['album_id'],                    
            'down_num' => (int)$param['down_num'],                    
            'save_num' => (int)$param['save_num'],                    
            'sharetime' => $param['sharetime'],                    
            'addtime' => time(),                    
            'adddate' => date('Y-m-d H:i:s'),                    
            'data' => $param['data']? $param['data']: '',                    
            'cj_url' => $param['cj_url']? $param['cj_url']: '',                    
            'cj_url_index' => (int)$param['cj_url_index'],
        );
        
        if(empty($data['userid']) || (empty($data['source_id']) && empty($data['album_id'])) || $this->getRowExists($data['userid'], $data['source_id'],$data['album_id'])) {
            return false;
        }
        $data['id'] = getSequenceValue(self::counterskey);
        
        return $this->add($data)? $data['id']: false; 
    }
    
    
    /**
    * 查找记录是否存在 
    * @param string $url url
    */ 
    public function getRowExists($userid, $source_id = '', $album_id = '')
    {
        $map['userid'] = $userid;
        
        if($source_id) {
            $map['source_id'] = $source_id;     
        }elseif($album_id) {
            $map['album_id'] = $album_id;     
        }
        
        $res = $this->where($map)->find(); 
        return $res['id']? $res['id']: '';   
    }
    
      
}
