<?php

namespace Home\Controller;

use Home\Controller\BaseController;
use Home\Model\BU\BUUser;
use Home\Model\BU\BUUserDetail;

class IndexController extends BaseController {
    
    public function index()
    {
        $data['bestUser'] = BUUser::getLastestUser(24);
        
        $this->assign('data', $data);
        $this->display();
    }
}