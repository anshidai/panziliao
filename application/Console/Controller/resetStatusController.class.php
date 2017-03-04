<?php 

namespace Console\Controller;

use Think\Controller;

class resetStatusController extends Controller
{
    public function resetUser()
    {
        $model = D('ResUser');
        
    }
    
    public function resetDetail()
    {
        $model = D('ResDetail');    
    }    
    
}