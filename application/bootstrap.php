<?php

defined ( 'SYSPATH' ) or die ( 'No direct script access.' );

// -- Environment setup --------------------------------------------------------

// Load the core Kohana class
require SYSPATH . 'classes/Kohana/Core' . EXT;

if (is_file ( APPPATH . 'classes/Kohana' . EXT )) {
	// Application extends the core
	require APPPATH . 'classes/Kohana' . EXT;
} else {
	// Load empty core extension
	require SYSPATH . 'classes/Kohana' . EXT;
}

/**
 * Set the default time zone.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/timezones
 */
date_default_timezone_set ( 'Asia/Shanghai' );

/**
 * Set the default locale.
 *
 * @link http://kohanaframework.org/guide/using.configuration
 * @link http://www.php.net/manual/function.setlocale
 */
setlocale ( LC_ALL, 'en_US.utf-8' );

/**
 * Enable the Kohana auto-loader.
 *
 * @link http://kohanaframework.org/guide/using.autoloading
 * @link http://www.php.net/manual/function.spl-autoload-register
 */
spl_autoload_register ( array (
		'Kohana',
		'auto_load' 
) );

/**
 * Optionally, you can enable a compatibility auto-loader for use with
 * older modules that have not been updated for PSR-0.
 *
 * It is recommended to not enable this unless absolutely necessary.
 */
// spl_autoload_register(array('Kohana', 'auto_load_lowercase'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @link http://www.php.net/manual/function.spl-autoload-call
 * @link http://www.php.net/manual/var.configuration#unserialize-callback-func
 */
ini_set ( 'unserialize_callback_func', 'spl_autoload_call' );

// -- Configuration and initialization -----------------------------------------

/**
 * Set the default language
 */
I18n::lang ( 'en-us' );

/**
 * Set Kohana::$environment if a 'KOHANA_ENV' environment variable has been supplied.
 *
 * Note: If you supply an invalid environment name, a PHP warning will be thrown
 * saying "Couldn't find constant Kohana::<INVALID_ENV_NAME>"
 */
if (isset ( $_SERVER ['KOHANA_ENV'] )) {
	Kohana::$environment = constant ( 'Kohana::' . strtoupper ( $_SERVER ['KOHANA_ENV'] ) );
}
// cookie salt设置

Cookie::$salt = "www.czzs.com";
/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string base_url path, and optionally domain, of your application NULL
 * - string index_file name of your index file, usually "index.php" index.php
 * - string charset internal character set used for input and output utf-8
 * - string cache_dir set the internal cache directory APPPATH/cache
 * - integer cache_life lifetime, in seconds, of items cached 60
 * - boolean errors enable or disable error handling TRUE
 * - boolean profile enable or disable internal profiling TRUE
 * - boolean caching enable or disable internal caching FALSE
 * - boolean expose set the X-Powered-By header FALSE
 */
Kohana::init ( array (
		'base_url' => '/',
		'index_file' => FALSE,
		'base_url' => '' 
) );

/**
 * Attach the file write to logging.
 * Multiple writers are supported.
 */
Kohana::$log->attach ( new Log_File ( APPPATH . 'logs' ) );

/**
 * Attach a file reader to config.
 * Multiple readers are supported.
 */
Kohana::$config->attach ( new Config_File () );

/**
 * Enable modules.
 * Modules are referenced by a relative or absolute path.
 */
Kohana::modules ( array (
		'auth' => MODPATH . 'auth', // Basic authentication
		'cache' => MODPATH . 'cache', // Caching with multiple backends
		'codebench' => MODPATH . 'codebench', // Benchmarking tool
		'database' => MODPATH . 'database', // Database access
		'image' => MODPATH . 'image', // Image manipulation
		'minion' => MODPATH . 'minion', // CLI Tasks
		'orm' => MODPATH . 'orm', // Object Relationship Mapping
		'unittest' => MODPATH . 'unittest', // Unit testing
		'userguide' => MODPATH . 'userguide', // User guide and API documentation
		'captcha' => MODPATH . 'captcha', // 验证码
		'pagination' => MODPATH . 'pagination', // 分页
		'swift' => MODPATH . 'swift', // 邮件服务
		'helper' => MODPATH . 'helper', // 助手modules
		'sphinx' => MODPATH . 'sphinx', // 助手modules
		'editor' => MODPATH . 'editor', // 编辑器
		'redis' => MODPATH . 'redis', // redis
		'qiniu' => MODPATH . 'qiniu'  // 上传服务
) );

/*
 * sso登录跳转
*/
Route::set ( 'userlogin', 'userlogin(/<action>)' )->defaults ( array (
'directory' => 'user',
'controller' => 'userlogin',
'action' => 'index'
) );
/*
 * ==============快速发布路由====================================
 */
Route::set ( 'quickPublish', 'quick(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'QuickPublish',
		'controller' => 'basic',
		'action' => 'index' 
) );
Route::set ( 'companyquick', 'company/quick(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'user/Company/QuickPublish',
		'controller' => 'basic',
		'action' => 'index' 
) );
Route::set ( 'personquick', 'person/quick(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'user/Person/QuickPublish',
		'controller' => 'basic',
		'action' => 'index' 
) );

/*
 * ==============用户中心路由开始==================================== 用户中心，公共部分，注册登录
 */
Route::set ( 'user', 'member(/<action>)' )->defaults ( array (
		'directory' => 'user',
		'controller' => 'user',
		'action' => 'index' 
) );

/*
 * 企业用户中心路由
 */
Route::set ( 'user/company', 'company/member(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'user/Company',
		'controller' => 'basic',
		'action' => 'index' 
) );
/*
 * 个人用户中心路由
 */
Route::set ( 'user/person', 'person/member(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'user/Person',
		'controller' => 'basic',
		'action' => 'index' 
) );
/* ===================上传图片flash接口============================== */
Route::set ( 'upload', 'upload(/<action>)' )->defaults ( array (
		'directory' => 'upload',
		'controller' => 'upload',
		'action' => 'index' 
) );
/*
 * ===================用户中心路由结束========================== //*=================== 供外部调用api 开始==============================
 */

Route::set ( 'sapi/user/company', 'sapi/user/company(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'sapi/User/Company',
		'controller' => 'user',
		'action' => 'index' 
) );
Route::set ( 'sapi/user/personal', 'sapi/user/personal(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'sapi/User/Personal',
		'controller' => 'user',
		'action' => 'index' 
) );
Route::set ( 'sapi/platform', 'sapi/platform(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'sapi/Platform',
		'controller' => 'tag',
		'action' => 'index' 
) );
Route::set ( 'sapi', 'sapi(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'sapi',
		'controller' => 'basic',
		'action' => 'index' 
) );

// *=================== 供外部调用api 结束==============================*/

/*
 * =================== 平台路由开始==============================
 */
Route::set ( 'user/platform', 'platform(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'platform',
		'controller' => 'index',
		'action' => 'index' 
) );

Route::set ( 'news', 'zixun(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'News',
		'controller' => 'index',
		'action' => 'index' 
) );

Route::set ( 'ask', 'ask(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'Ask',
		'controller' => 'ask',
		'action' => 'index' 
) );

Route::set ( 'business', 'business(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'Business',
		'controller' => 'business',
		'action' => 'index'
) );
/*
 * =================== 平台路由结束============================== /
 */
/*=================== 后台路由开始==============================*/
Route::set ( 'cms', 'cms(/<controller>(/<action>))' )->defaults ( array (
		'directory' => 'cms',
		'controller' => 'admin',
		'action' => 'index' 
) );
/* =================== 后台路由结束============================== */

/*=================== 自定义执行脚本路由开始==============================*/
Route::set ( 'regularscript', 'regularscript(/<action>)' )->defaults ( array (
		'directory' => 'regularscript',
		'controller' => 'regularscript',
		'action' => 'index' 
) );
/* =================== 自定义执行脚本路由开始============================== */
/**
 * Set the routes.
 * Each route must have a minimum of a name, a URI and a set of
 * defaults for the URI.
 */
Route::set ( 'default', '(<controller>(/<action>(/<id>)))' )->defaults ( array (
		'directory' => 'home',
		'controller' => 'index',
		'action' => 'index' 
) );