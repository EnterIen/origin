<?php

use \core\Router;

Router::get('/welcome', function () {
	echo 'that\'s colsure';
});

Router::get('/unit/list', 'RouterController@getUnitList');