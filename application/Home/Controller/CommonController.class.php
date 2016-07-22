<?php 

namespace Home\Controller;

use Think\Controller;

class CommonController extends Controller {
    
    protected function _initialize()
    {
        header('Content-Type:text/html; charset="utf-8"');
    }
    
    
}