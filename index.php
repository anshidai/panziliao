<?php

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

defined('MODEL_ENV') or define('MODEL_ENV', 'online'); //dev-开发&&测试环境  online-线上环境

define('__ROOT__', substr(__FILE__, 0 , -10));

// 定义应用目录
define('APP_PATH', __ROOT__.'/application/');

/* 绑定访问Front模块  当绑定了后其他模块就不能直接访问 */
//define('BIND_MODULE', 'Home');

define('RUNTIME_PATH', __ROOT__.'/runtime/');

// 引入ThinkPHP入口文件
require __ROOT__.'/thinkphp/ThinkPHP.php';
