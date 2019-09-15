<?php
define('PROJECT_ROOT', __DIR__ . '/');

spl_autoload_register(function($className) {
	include_once PROJECT_ROOT . $className . '.php';
});