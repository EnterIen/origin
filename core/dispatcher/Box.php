<?php
namespace dispatcher;

# 定义盒子 继承分发器
Class Box extends Dispatcher
{
	# 实现父类抽象方法
	public static function getInstance()
	{
		# 不存放实例 每次生成新的实例
		return self::newObject(); 
	}
}