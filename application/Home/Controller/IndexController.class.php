<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Home\Model\BU\BUUser;
use Home\Model\BU\BUUserDetail;
use Home\Model\BU\BUResDetail;
use Components\helper\ArrayHelper;

class IndexController extends BaseController {
    
    public function index()
    {
        $data['bestUser'] = BUUser::getLastestUser(24,'id,userid,avatar,username');
        $data['bestVideo'] = BUResDetail::getCatDetailList(1, 30, 'id,title');
        $data['bestVideo'] = ArrayHelper::chunkArr($data['bestVideo'], 10);
        
        //var_dump($data['bestVideo']);
        
        $this->assign('data', $data);
        $this->display();
    }
}