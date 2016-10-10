<?php

namespace Home\Controller;

use Home\Model\BU\BUUser;
use Home\Model\BU\BUUserDetail;

class IndexController extends CommonController {
    
    public function index()
    {
        //$res = BUUser::getLastestUser();
        $res = BUUserDetail::getUserDetailList(1899046725, 2);
        dump($res);
    }
}