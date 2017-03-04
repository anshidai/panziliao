<?php

namespace Home\Controller;

use Home\Model\BU\BUUser;
use Home\Model\BU\BUUserDetail;

class ShareController extends CommonController {
    
    private $pagesize = 10;
    
    public function home()
    {
        $id = I('get.id', 0, 'intval');
        $p = I('get.p', 1, 'intval');
        
        $data['userinfo'] = BUUser::getUserDetail($id);
        $data['list'] = BUUserDetail::getUserDetailList($data['userinfo']['userid'], $this->pagesize);
        
        $this->assign('data', $data);
        $this->display();
    }
    
    public function detail()
    {
        $id = I('get.id', 0, 'intval'); 
        
        $data['detail'] = BUUserDetail::getDetail($id);
        $data['detail']['title'] = str_replace('.'.$data['detail']['filetype'],'', $data['detail']['title']); 
        $data['userinfo'] = BUUser::getUserDetail($data['detail']['userid']);
        $data['list'] = BUUserDetail::getUserDetailList($data['userinfo']['userid'], 10);
        
        $this->assign('data', $data);
        $this->display();
    }
    
    public function lists()
    {
        
    }
    
    
    
    
}
