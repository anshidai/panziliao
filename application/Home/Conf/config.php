<?php 

return array(

    'DEFAULT_MODULE' => 'Home',

    'SHOW_PAGE_TRACE' => false, //是否开启追踪Trace模式 生产环境请勿开启

    'URL_HTML_SUFFIX' => 'html', //URL伪静态后缀设置

    'TMPL_TEMPLATE_SUFFIX' => '.tpl', // 默认模板文件后缀
    
    'VIEW_PATH' => THEMES_PATH, //前台页面模板目录
    
    'URL_MODEL' => 2, //URL访问模式 默认为PATHINFO 模式 0 (普通模式); 1 (PATHINFO 模式); 2 (REWRITE  模式); 3 (兼容模式)  
    'URL_ROUTER_ON' => true, //开启路由
    'URL_ROUTE_RULES' => array(
        '/^u(\d+)-p(\d+)$/' => 'Share/home?id=:1&p=:2',
        '/^u(\d+)/' => 'Share/home?id=:1',
        '/^detail\/(\d+)/' => 'Share/detail?id=:1',
        '/^c(\d+)-p(\d+)$/' => 'Share/lists?cid=:1&p=:2',
        '/^c(\d+)$/' => 'Share/lists?cid=:1',
        '/^latests$/' => 'Latest/latests',
    ),    
    
	//模板相关配置, 在模板中直接使用 如: __STATIC__ 则页面显示 /skin/images
    'TMPL_PARSE_STRING' => array(
		'__STATIC__' => DOMAIN.'/skin',
        '__IMAGE__'    => DOMAIN . '/skin/'. SKIN_NAME .'/image',
        '__CSS__'    => DOMAIN . '/skin/'. SKIN_NAME .'/css',
        '__IMG__'    => DOMAIN . '/skin/'. SKIN_NAME .'/css/img',
        '__JS__'     => DOMAIN . '/skin/'. SKIN_NAME .'/js',
    ),
    
    
);