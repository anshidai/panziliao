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
        //最新用户
        $data['bestUser'] = BUUser::getLastestUser(24,'id,userid,avatar,username');
        
        //视频
        $data['bestVideo'] = BUResDetail::getCatDetailList(1, 30, 'id,title');
        $data['bestVideo'] = ArrayHelper::chunkArr($data['bestVideo'], 10);
        
        //音乐
        $data['bestDocument'] = BUResDetail::getCatDetailList(2, 30, 'id,title');
        $data['bestDocument'] = ArrayHelper::chunkArr($data['bestDocument'], 10);
        
        //图片
        $data['bestPicture'] = BUResDetail::getCatDetailList(3, 30, 'id,title');
        $data['bestPicture'] = ArrayHelper::chunkArr($data['bestPicture'], 10);
        
        //专辑
        $data['bestSpecial'] = BUResDetail::getCatDetailList(4, 30, 'id,title');
        $data['bestSpecial'] = ArrayHelper::chunkArr($data['bestSpecial'], 10);
        
        //其他
        $data['bestOther'] = BUResDetail::getCatDetailList(5, 30, 'id,title');
        $data['bestOther'] = ArrayHelper::chunkArr($data['bestOther'], 10);
        
        $this->assign('data', $data);
        $this->display();
    }
}