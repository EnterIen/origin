<?php
namespace dispatcher;

# 定义抽象类
abstract Class Dispatcher
{
	# 定义抽象方法获取子类实例
	abstract function getInstance();

	# 不可见静态方法的入口
	public static function __callStatic($method, $args)
	{
		$instance = static::getInstance();
        
		# 回调不可见静态函数
		return call_user_func_array([$instance,$method], $args);
	}

	# 实例化子类 （注：和获取子类实例是不同的额）
	public static function newObject()
	{
		return new static;
	}

	#  获取子类实例
	public static  function register()
	{
		return static::getInstance();
	}


	# 反射+依赖注入
	protected function call($method, $args = null)
    {
    	# 调用类的类名
        $object = get_called_class();
        # 反射类 获取类的相关信息
        $reflect = new \ReflectionMethod($object, $method);	

        $params = [];
        # 遍历类方法的参数数组
        foreach ($reflect->getParameters() as $need) {
            # 依赖注入  判断参数类型 若为对象则实例化并装载
            if ($obj = $need->getClass()->name) {
                $params[$need->name] = $obj::register();
            # 默认参数
            } else {
                if (!$need->isDefaultValueAvailable()
                && !isset($args[$need->name])) {
                    Throw new \Exception('action [ '.$method.' ] needs params [ $'.$need->name.' ]');
                }
                # 装载默认的
                $params[$need->name] = $args[$need->name] ?? $need->getDefaultValue();
            }
        }
        return $reflect->invokeArgs($object::register(), $params);
    }

    # 保留原有实例操作
    // public function __call($method, $args)
    // {
    //     return call_user_func_array([static::getInstance(),$method],$args);
    // }

} 


