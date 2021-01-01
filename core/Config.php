<?php

namespace core;

use dispatcher\Container;

# 定义配置类 TODO：
  # 加载配置文件
  # 解析配置参数
Class Config extends Container
{
	private $config;

	public function __construct()
	{
		$path = APP_PATH . '/app/conf/';
		$conf = [];
		# 遍历配置文件根目录
		foreach (scandir($path) as $file) {
			# 过滤掉隐藏文件
			if (substr($file, -4) != '.php') {
				continue;
			}
			# 拼接完整的文件路径
			$filename = $path . $file;
			# 加载默认配置文件
			if ($file == 'config.php') {
				$conf += require $filename;
			} else {
				# 将文件名作为k装载到数组
				$k = explode('.', $file)[0];
				$conf[$k] = require $filename;
			}
		}
		# 返回所有配置文件
		$this->config = $conf;

	}


	protected function get($key = null, $default = null)
	{
		if (empty($key)) {
			return $this->config;
		}
		# 如果有多维参数
		if (strpos($key, '.')) {
			$conf = $this->config;	#这里为什么非要一个赋值操作呢？
			foreach (explode('.', $key) as $v) {
				$conf = $conf[$v] ?? $default;
			}
			return $conf;
		}
		# 返回一维参数
		return $this->config[$key] ?? $default;
	}


}