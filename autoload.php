<?php
define('PROJECT_ROOT', __DIR__  . DIRECTORY_SEPARATOR);

spl_autoload_register(function($className) {
	echo $className;
	include_once PROJECT_ROOT . $className . '.php';
});