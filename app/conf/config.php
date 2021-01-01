<?php
return [
	'debug' => true,
	'cache' => 'redis',

	'default' => [
		'controller' => 'index',
		'action' => 'index',
		'router' => 'querystring',
	],
	'view' => [
		'dir' => 'layouts',
		'file' => 'base'
	],
];

