<?php
namespace dispatcher;

# 定义容器 继承分发器
Class Container extends Dispatcher
{
	# 容器 存储子类实例
	public static $container = []; #注意static的存在

	# 实现父类的抽象方法
	public function getInstance()
	{
		# 获取调用类的名称
		$class = get_called_class();

		// echo "调用类名称：" . $class .PHP_EOL;

		# 观察容器里面是否已存在这个调用类的实例
		if (!self::$container[$class] instanceof $class || !isset(self::$container[$class]) ){
			# 没有则调用父类方法 进行实例
			self::$container[$class] = self::newObject();
			
			// echo "容器中实例的类:" . PHP_EOL;
			// print_r(self::$container);
		}

		return self::$container[$class];
	}
}


