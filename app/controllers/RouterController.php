<?php
namespace controller;

use core\Controller;
use core\Config;

Class RouterController extends Controller
{
	public  function getConfig(Config $config)
	{
		return $config::get();
	}

	public function getUnitList(int $page, int $pageSize, string $default = 'need->default()')
	{
		return [$page, $pageSize, $default];
	}
}

