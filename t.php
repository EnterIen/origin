<?php

class Dispatcher
{
    public static $container = [];

    public function register()
    {
        $class =  get_called_class();
        if (!self::$container[$class] instanceof $class || !isset(self::$container[$class])) {
            self::$container[$class] = new Static;
        }
        var_dump(self::$container[$class]);
    }
}

// (new Dispatcher())->register();die;

Class Conf
{
    public function initConf()
    {
	echo "class conf";
    }
}

Class Plug
{
    public function initPlug()
    {
        echo "class plug";
    }
}


Class Reflect extends Dispatcher
{
    public function sample(Conf $config)
    {
 	  var_dump($config);	
    }
}


$instance = new Reflect();
// $instance->sample();die; # 直接调用原始方法使用依赖注入是不行的必须通过反射

# 反射Reflect类方法
$x = new ReflectionMethod('Reflect', 'sample'); # 反射的是某个具体方法哈
# 获取该方法参数信息 以数组形式返回
$y = $x->getParameters();
$parameters = [];
# 遍历参数数组
foreach($y as $k => $v){
   // echo $v->name . PHP_EOL;   # 输出参数变量名
   // echo $v->getClass()->name . PHP_EOL;   # 输出参数的提示类
    if ($object = $v->getClass()->name) {
        $parameters[$v->name] = new $object();
    }
    return $x->invokeArgs(new Reflect(), $parameters);

}
# 打印参数数组
// print_r($y);
