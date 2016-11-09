<?php 

return array(
    'SHOW_PAGE_TRACE' => false, //是否开启追踪Trace模式 生产环境请勿开启
    
    'URL_HTML_SUFFIX' => 'html', //URL伪静态后缀设置

    'TMPL_TEMPLATE_SUFFIX' => '.tpl', // 默认模板文件后缀
    
    'VIEW_PATH' => THEMES_PATH, //前台页面模板目录
    
    'URL_MODEL' => 3, //URL访问模式 默认为PATHINFO 模式 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  
    'URL_ROUTER_ON' => false, //开启路由
    
    //路由规则
    'URL_ROUTE_RULES' =>  array(),
    
	//模板相关配置, 在模板中直接使用 如: __STATIC__ 则页面显示 /skin/images
    'TMPL_PARSE_STRING' => array(
		'__STATIC__' => __ROOT__.'/skin',
        '__IMG__'    => __ROOT__ . '/skin/'. SKIN_NAME .'/images',
        '__CSS__'    => __ROOT__ . '/skin/'. SKIN_NAME .'/css',
        '__JS__'     => __ROOT__ . '/skin/'. SKIN_NAME .'/js',
    ),
    
    
);