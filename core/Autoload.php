<?php
# 框架中命名空间和文件目录的映射关系
$path = [
    'core' => 'core',
    'app' => 'app',
    'controller' => 'app/controllers',
    'model' => 'app/models',
    'view' => 'app/views',
    'dispatcher' => 'core/dispatcher',
    'public' => 'public'
];

function helper($var)
{
	switch ($var) {
		case is_object($var):
			print_r($var);
			break;
		case is_array($var):
			print_r($var);
			break;
		case is_string($var):
			echo $var;
			break;
	}
	exit;
}

# 
spl_autoload_register( function($class) use($path) 
{
	// helper($class);
	# 获取最后出现的下标
	$position = strrpos($class, '\\');
	# 获取命名空间映射的目录
	$ns = substr($class, 0, $position);
	$dir = $path[$ns] ?? '';
	# 获取use类文件名
	$file = substr($class, $position+1);
	
	# 加载use类文件
	// echo APP_PATH . '/' .$dir . '/' . $file  . '.php' . PHP_EOL;
	require APP_PATH . '/' . $dir . '/' . $file  . '.php';
});
