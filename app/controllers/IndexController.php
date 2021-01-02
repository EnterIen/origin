<?php
namespace controller;

use core\Controller;

Class IndexController extends Controller
{
	public function getOne()
	{
		return $this->renderFile('index');
	}

	public function getAll()
	{
		$data = [
			'firstName' => 'W',
			'lastName' => 'Y'
		];
		
		return $this->render('index', $data);
	}
}

