<?php

Route::match(
	['get', 'post', 'put', 'delete'],
	'storage/{path}',
	'StorageController@handle'
)->where('path', '.*');
