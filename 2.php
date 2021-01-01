<?php

abstract class Dispatcher
{
#	abstract function getInstance();

	public static function __callStatic($method, $args) 
	{
	
#	$instance = static::getInstance();
		$instance = get_called_class();
		echo $instance;
	die;	
		call_user_func_array([$instance, $method], $args);
	}  

	public static function newObject() 
	{
		return new Static;
	}
	
}

Class Container extends Dispatcher
{
        # 容器 存储子类实例
        public static $container = []; #注意static的存在

        # 实现父类的抽象方法
        public function getInstance()
        {
                # 获取调用类的名称
                $class = get_called_class();

                # 观察容器里面是否已存在这个调用类的实例
                if (!self::$container[$class] instanceof $class || !isset(self::$container[$class]) ){
                        # 没有则调用父类方法 进行实例
                        self::$container[$class] = self::newObject();
                }

                return self::$container[$class];
        }
}

Class Test extends Dispatcher
{
	protected function A()
	{
		echo 'A';
	}

	public function B()
	{
		echo 'B';
	}

}

Test::A();
