<?php

namespace core;

use dispatcher\Container;

Class Controller extends Container
{
	private function display(string $file, array $data, bool $layouts = true)
	{
		$controller = get_called_class();
		$folder = substr($controller, strripos($controller, '\\')+1, -10);
		return View::display(strtolower($folder), $file, $data, $layouts);
	}

	public function render(string $file, array $data = [])
	{
		return $this->display($file,$data);
	}

	public function renderFile(string $file, array $data = [])
	{
		return $this->display($file,$data,false);
	}

	
}