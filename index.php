<?php

// 开启调试模式 建议开发阶段开启 部署阶段注释或者设为false
define('APP_DEBUG', true);

// 定义应用目录
define('APP_PATH','./application/');

/* 绑定访问Front模块  当绑定了后其他模块就不能直接访问 */
//define('BIND_MODULE', 'Front');

define('RUNTIME_PATH', './runtime/');

// 引入ThinkPHP入口文件
require './thinkphp/ThinkPHP.php';
