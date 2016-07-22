<?php

namespace Home\Controller;

class IndexController extends CommonController {
    
    public function index()
    {
    
        //$res =  D("Category")->getCategory(5);
        
        $model = D('Test');
        
        //var_dump($model->select());
        
        $model->create();
        //$model->id = 1;
        $model->name = 'lianbaoan';
        
        $res = $model->add();
        
        //$res = $model->add(array('id'=>1, 'name'=>'libaoan'));
        
        //dump($res);
        dump($model->getMongoNextId('id'));
        
        
        //$this->display();
    }
}