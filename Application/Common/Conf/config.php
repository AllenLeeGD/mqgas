<?php
define('SITE_URL', 'app.youbanghulian.com');
define('PICSERVER_URL', 'http://app.youbanghulian.com');//插入图片的时候要存入这个地址+/Upload/+xxx
//define('PICSERVER_URL', 'http://192.168.1.116:8090');//插入图片的时候要存入这个地址+/Upload/+xxx
define('PIC_FOLDER', '../Upload/');
define('SESSION_TIME', 7200);
define('EDU_PREFIX',"");
return array(
	//'配置项'=>'配置值'
        'APP_DEBUG',True,
        //'SHOW_PAGE_TRACE' => True,
    
	//数据库配置信息
	'DB_TYPE'   => 'mysql', // 数据库类型
	'DB_HOST'   => '120.77.221.110', // 服务器地址
//	'DB_HOST'   => '172.18.78.113',
	'DB_NAME'   => 'mq', // 数据库名
//	'DB_USER'   => 'root', // 用户名
//	'DB_PWD'    => 'free', // 密码
	'DB_USER'   => 'mqdatabase', // 用户名
	'DB_PWD'    => 'newocean', // 密码
//	'DB_PWD'    => 'c46163e4df',
	'DB_PORT'   => 3306, // 端口
	'DB_PREFIX' => '', // 数据库表前缀 
	'DB_CHARSET'=> 'utf8', // 字符集
	'SESSION_AUTO_START'    => true, 
	'DB_DEBUG'  =>  FALSE, // 数据库调试模式 开启后可以记录SQL日志 3.2.3新增
	'URL_PARAMS_BIND'       =>  true,
	'DEFAULT_TIMEZONE'=>'Asia/Shanghai', // 设置默认时区
	// 配置邮件发送服务器
    'MAIL_SMTP'                     =>TRUE,
    'MAIL_HOST'                     =>'smtp.exmail.qq.com',//邮件发送SMTP服务器
    'MAIL_SMTPAUTH'                 =>TRUE,
    'MAIL_USERNAME'                 =>'system@youbanghulian.com',//SMTP服务器登陆用户名
    'MAIL_PASSWORD'                 =>'Ubangapp0922',//SMTP服务器登陆密码
    'MAIL_SECURE'                   =>'tls',
    'MAIL_CHARSET'                  =>'utf-8',
    'MAIL_ISHTML'                   =>TRUE
);
