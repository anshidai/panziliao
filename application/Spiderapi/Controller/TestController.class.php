<?php 

namespace Spiderapi\Controller;

use Think\Controller;
use Components\Http;
use Components\HttpProxy;

class TestController extends Controller 
{
    protected function _initialize()
    {
        header('Content-Type:text/html; charset="utf-8"');
    }
    
    public function init()
    {
        $url = 'http://www.panduoduo.net/u/bd-3608041317';
        $url = 'http://www.panduoduo.net/r/22813064';
        $url = array(
            'http://www.panduoduo.net/u/bd-1141090171',
            'http://www.panduoduo.net/u/bd-3390755623',
            'http://www.panduoduo.net/u/bd-808072626',
            'http://www.panduoduo.net/u/bd-1359690209',
            );
        $proxy = array('ip'=>'123.97.31.65', 'port'=>'8998');
        //$proxy = array();
        //$res = Http::curl_http($url, '', $proxy, true);
        $res = Http::curl_multi($url, '', true, $proxy);
        var_dump($res);
    } 
    
    
    
}