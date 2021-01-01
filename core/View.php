<?php

namespace core;

use dispatcher\Container;

# 定义视图类 继承分发器
Class View extends Container
{
	public function display(string $folder, string $filename, array $data = [], bool $layouts = true)
	{
		$path  = APP_PATH . '/app/views/';
		$file = $path . $folder . '/' .$filename . '.php';

		# 通过 `extract` 将数组键值转换为变量名获取键值
		if (!empty($data)) {
			extract($data);
		}

		# 启动缓存
		ob_start();
		require $file;
		# 销毁缓存 并将缓存内容赋给变量
		$content = ob_get_clean();

		# 加载公共视图
		if ($layouts) {
			$config = Config::get('view');
			$common = $path . ($config['dir'] ?? 'layouts') . '/' . ($config['file'] ?? 'base') . '.php';
			
			ob_start();
			require $common;
			$content =  ob_get_clean();
		}
		# 返回所有加载视图
		return $content;
	}
}