<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Home\Model\BU\BUUser;
use Home\Model\BU\BUUserDetail;
use Home\Model\BU\BUResDetail;
use Home\Model\BU\BUCommon;
use Components\helper\UrlHelper;

class ShareController extends BaseController 
{
    
    private $pagesize = 10;
    
    public function home()
    {
        $id = I('get.id', 0, 'intval');
        $page = I('get.p', 1, 'intval');
        
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
        
        $data['likeList'] = array();

        $this->assign('data', $data);
        $this->display();
    }
    
    public function lists()
    {
        $cid = I('get.cid', 0, 'intval');
        $page = I('get.p', 1, 'intval');
 
        $firstUrl = $baseUrl = UrlHelper::url('category_list', $cid);
        $baseUrl = rtrim($baseUrl, '/').'-p{$page}/';
        
        $data['catname'] = getCategoryName($cid);
        $data['page'] = $page;
        $data['total'] = BUResDetail::getCatDetailTotal($cid);
        $data['list'] = BUResDetail::getCatDetailList($cid, $this->pagesize);
        $data['pages'] = BUCommon::getPages($baseUrl, $firstUrl, $data['total'], $page, $this->pagesize);

        $this->assign('data', $data);
        $this->display();    
    }
    
    
    
    
}
