<?php
namespace core;

use dispatcher\Container;
use core\Config;

Class Application extends Container
{
	public $boot;	#存储启动项的实例

	public function __construct()
	{
	#	$this->boot =  \app\Bootstrap::register();	 #实例化启动项
	}

	protected function sayHi()
	{
		echo "hi";
	}

	protected function reflectionTest(Config $config)
	{
		return $config::get();
	}

	public function run()
	{
        ////////////////////////[Config]////////////////////////////
		// print_r(Config::get());die;

		////////////////////////[Bootrap]////////////////////////////
		
		// # 遍历由类的方法名组成的数组 
		// foreach (get_class_methods($this->boot) as $func) {
		// 	# 如果方法名由 'init' 开头，则回调这个函数
		// 	if (0 === strpos($func, 'init')) {
		// 		# 执行回调
		// 		call_user_func([$this->boot,$func]);
		// 	}
		// }
		// exit;

		////////////////////////[Router]////////////////////////////
		$router = Router::start();
		// print_r($router);die;
		$controller = 'controller\\' . $router['controller'] ;
		$action = $router['action'];
		$args = $router['args'];
		
		////////////////////////[Reflection]////////////////////////////
		$this->displayOfGlobal($controller::call($action,$args));
		// call_user_func_array([$controller::register(),$action],$args);		




		# 测试
		// echo Config::get('cache');
		// print_r(Router::start());
		// View::display();
	}
}






