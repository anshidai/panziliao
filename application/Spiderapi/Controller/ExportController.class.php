<?php 

namespace Spiderapi\Controller;

use Think\Controller;

class ExportController extends Controller 
{
    private $countersModel = null;
    private $indexUrlModel = null;
    private $resUserModel = null;
    private $resDetailModel = null;
    
       
    protected function _initialize()
    {
        header('Content-Type:text/html; charset="utf-8"');
        $this->countersModel = D('Counters');
        $this->indexUrlModel = D('IndexUrl');
        $this->resUserModel = D('ResUser');
        $this->resDetailModel = D('ResDetail');
    }
    
    public function exportUser()
    {
        $model = D('ResourceUser');
        
        $pagesize = 1000;
        $total = $model->where(array('id'=>array('$gt'=>522406)))->count();
        $pageTotal = ceil($total/$pagesize);
        
        for($page = 1; $page<=$pageTotal; $page++) {
            $res = $model->where(array('id'=>array('$gt'=>522406)))->order('id')->limit(($page-1)*$pagesize.','.$pagesize)->select();
            if($res) {
                foreach($res as $val) {
                    $index = $user = array();
                    
                    if(strpos($val['cj_url'], 'panduoduo') !== false) {
                        $val['source'] = 'panduoduo';        
                    }
                    $index['url'] = $val['cj_url'];
                    $index['resid'] = $val['uid'];
                    $index['source'] = $val['source'];
                    $index['status'] = 0;
                    
                    if($indexId = $this->indexUrlModel->_add($index)) {
                        $user['userid'] = $val['uid'];    
                        $user['username'] = $val['uname'];    
                        $user['avatar'] = $val['avatar'];    
                        $user['intro'] = $val['intro'];    
                        $user['source'] = $val['source'];    
                        $user['share_count'] = $val['share_count'];    
                        $user['fans_count'] = $val['fans_count'];    
                        $user['follow_count'] = $val['follow_count'];    
                        $user['hits'] = $val['hits'];    
                        $user['cj_url'] = $val['cj_url'];    
                        $user['cj_url_index'] = $indexId; 
                        
                        $this->resUserModel->_add($user);
                    
                        echo date('Y-m-d H:i:s')." insert userid:{$user['userid']}\n";
                    }else {
                        echo date('Y-m-d H:i:s')." exists userid:{$val['uid']}\n";     
                    }      
                }
            }
            unset($res);
        } 
        echo "complete\n";    
    }
    
    
    public function exportDetail()
    {
        $model = D('Resource3');
        
        $pagesize = 1000;
        //$total = $model->count();
        $total = $model->where(array('id'=>array('$gt'=>63982)))->count();
        $pageTotal = ceil($total/$pagesize);  
        for($page = 1; $page<=$pageTotal; $page++) {
            //$res = $model->order('id')->limit(($page-1)*$pagesize.','.$pagesize)->select();
            $res = $model->where(array('id'=>array('$gt'=>63982)))->order('id')->limit(($page-1)*$pagesize.','.$pagesize)->select();
            if($res) {
                foreach($res as $val) {
                    $index = $detail = array();
                    //var_dump($val);exit;
                    if(empty($val['title']) || (empty($val['source_id']) && empty($val['album_id']))) {
                        echo date('Y-m-d H:i:s')."{$val['id']} empty userid:{$val['uid']} id:{$val['source_id']} - {$val['album_id']}\n";
                        exit();
                        continue;    
                    }
                    
                    $index['url'] = $val['cj_url'];
                    $index['resid'] = $this->parseDetailId($val['cj_url']);
                    $index['source'] = 'panduoduo';
                    $index['status'] = 0;
                    $index['extend'] = $val['uid'];
                    
                    if($indexId = $this->indexUrlModel->_add($index)) {
                        $detail['title'] = $val['title'];         
                        $detail['catid'] = $val['catid'];         
                        $detail['hits'] = $val['hits'];         
                        $detail['filetype'] = $val['filetype'];         
                        $detail['filesize'] = $val['filesize'];         
                        $detail['source'] = 'panduoduo';         
                        $detail['userid'] = $val['uid'];         
                        $detail['username'] = $val['uname'];         
                        $detail['shorturl'] = $val['shorturl'];         
                        $detail['dynamicurl'] = $val['dynamicurl'];         
                        $detail['source_id'] = $val['source_id'];         
                        $detail['album_id'] = $val['album_id'];         
                        $detail['fs_id'] = $val['fs_id'];         
                        $detail['fid'] = $val['fid'];         
                        $detail['save_num'] = $val['save_num'];         
                        $detail['down_num'] = $val['down_num'];         
                        $detail['sharetime'] = $val['sharetime'];                  
                        $detail['cj_url'] = $val['cj_url'];         
                        $detail['cj_url_index'] = $indexId;
                        
                        $this->resDetailModel->_add($detail);
                    
                        echo date('Y-m-d H:i:s')." insert userid:{$detail['userid']} id:{$detail['source_id']} - {$detail['album_id']}\n";
                    }else {
                        echo date('Y-m-d H:i:s')." exists userid:{$detail['userid']} id:{$detail['source_id']} - {$detail['album_id']}\n";     
                    } 
                }    
            }
            unset($res);
        }
        echo "complete\n";
    }
    
    private function parseUserId($url)
    {
        if(preg_match('/u\/bd-(\d+)$/', $url, $match)) {
            return $match[1];
        }               
    }
    
    private function parseDetailId($url)
    {
        if(preg_match('/r\/(\d+)$/', $url, $match)) {
            return $match[1];
        }               
    }
        
}