<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Home\Model\BU\BUUser;
use Home\Model\BU\BUUserDetail;
use Home\Model\BU\BUResDetail;
use Home\Model\BU\BUCommon;
use Components\helper\UrlHelper;

class LatestController extends BaseController 
{
    private $pagesize = 100;
    
    /**
    * 最新资源 
    */
    public function Latests()
    {
        $data['list'] = BUResDetail::getLatestDetail($this->pagesize);
        
        $this->assign('data', $data);
        $this->display();        
    }    
}