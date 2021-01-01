<?php

namespace app;

use dispatcher\Container;

Class Bootstrap extends Container
{
	public function init1()
	{
		echo 'init1' . PHP_EOL; 
	}

	public function init2()
	{
		echo 'init2' . PHP_EOL;
	}
}