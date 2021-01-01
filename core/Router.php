<?php
namespace core;

use dispatcher\Container;

# 定义路由类 注意继承Container
Class Router extends Container
{
	public $uri;    #url 全路径
	public $path;   #url 域名后的路径参数
	public $method; #http请求方式
	public $controller; 
	public $action; 

	# 初始化装载
	public function __construct()
	{
		$this->uri  = $_SERVER['REQUEST_URI'];
		$this->path = $_SERVER['PATH_INFO'];
		$this->method = $_SERVER['REQUEST_METHOD'] ?? 'GET';

		// 引入自定义路由文件
		require_once APP_PATH . '/app/web.php';
	}

	//	先检查自定义路由
	public  function __call($method, $args)
	{
		if (!$method || !is_array($args) || count($args) < 2) {
			throw new Exception("lack of params of router", 1);
		}

		$method = strtoupper($method);
		if ($method === $this->method && $args[0] === $this->path) {
			if (is_callable($args[1])) {
				call_user_func($args[1]);
				exit;
			}
			[$this->controller, $this->action] = explode('@', $args[1]);
		}
	}


	# 路由入口
	protected function start()
	{
		# 默认路由规则
		$router = Config::get('default.router') ?? 'querystring';


		# 根据path|配置文件  解析控制器 方法名称
		$path = explode('/', trim($this->path, '/'));

		if (empty($path[0])) {
			$path[0] = Config::get('default.controller', 'index');
		}
		$controller = $this->controller ?? ucfirst($path[0]) . 'Controller'; 

		if (empty($path[1])) {
			$path[1] = Config::get('default.action', 'index');
		}
		$method = strtolower($this->method);
		$action = $this->action ?? $method .  ucfirst($path[1]); 

		# 调用默认路由方法 返回路由后的参数数组
		$args = [];
		if (method_exists($this, $router)) {
			$args = call_user_func_array([$this,$router], [$this->uri]);	
		}

		# return
		return ['controller' => $controller, 'action' => $action, 'args' => $args];
	}

	# 默认路由方法 
	# $url String url全路径 
	public function querystring($url)
	{
		
		$params = explode('?', $url)[1];
		if (empty($params)) {
			return [];
		}
		$params_arr = explode('&', $params);
		if (empty($params_arr)) {
			return [];
		}
		foreach ($params_arr as $value) {
			if (strpos($value, '=')) {
				list($k,$v) =  explode('=',$value);
				# 检查路由键值是否合法
				if(preg_match('/^[A-Za-z_][A-Za-z0-9_]*$/', $k)) {
					$arr[$k] = $v;
				}
			}
		}
		return $arr;
		
	}









}