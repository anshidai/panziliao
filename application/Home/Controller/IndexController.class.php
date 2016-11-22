<?php

namespace Home\Controller;

use Home\Model\BU\BUUser;
use Home\Model\BU\BUUserDetail;

class IndexController extends CommonController {
    
    public function index()
    {
        $data['bestUser'] = BUUser::getLastestUser(24);
        
        $this->assign('data', $data);
        $this->display();
    }
}