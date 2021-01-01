<?php

use \core\Router;

// Router::get('/', function () {
// 	echo 'that\'s colsure';
// });

Router::get('unit/list', 'RouterController@getUnitList');