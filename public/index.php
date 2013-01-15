<?php
//设定系统常量
error_reporting(E_ALL);//关闭警告参数为0，所有警告E_ALL
ini_set('display_errors','on');//显示错误
date_default_timezone_set('Asia/Shanghai');//设置时区
//两种时间表示（应用程序级常量）
define("NOW",date("Y-m-d H:i:s"));
define("DATE",date("Y-m-d"));


chdir(dirname(__DIR__));

// Setup autoloading
require 'init_autoloader.php';

// Run the application!
Zend\Mvc\Application::init(require 'config/application.config.php')->run();