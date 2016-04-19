<?php
	ini_set("display_errors","1");
	error_reporting(65535);
	define('DEBUG', 1);
	define('WEBROOT', dirname(__FILE__));
	define('HOST', $_SERVER['HTTP_HOST']);
	define('SALT', 'tRureaphedAcaCHe4');
	session_start();
	date_default_timezone_set("Asia/Taipei");

//	$_SESSION['last_access_token'] = @$_SESSION['access_token'];
//	$_SESSION['access_token'] = md5(time() . SALT);
	require_once(WEBROOT . "/function.php");
	require_once('config/database.inc.php');
	$time_start = microtime(true);

	//Database
	$db = new PDO($config['database']['dsn'], $config['database']['username'], $config['database']['password']);
	$db->exec("set names utf8");

	//Dispatcher
	$URI = $_SERVER['REQUEST_URI'];
	$q = explode("/", explode("?", $URI)[0]);
	$nil = array_shift($q);
	$controllerName = camelize(array_shift($q));
	if ($controllerName == "") $controllerName = "base";
	$controllerClass = "{$controllerName}Controller";
	$action = array_shift($q);
	$arg = $q;

	if (!class_exists($controllerClass)){
		$controller = new BaseController($db);
		$controller->before();
		$controller->error(404);
	} else {
		$controller = new $controllerClass($db);
		$controller->setMethod($_SERVER['REQUEST_METHOD']);
		$controller->loadModel($controllerName);
		$controller->before();
		if (!method_exists($controller, $action)){
			call_user_func_array(array($controller, "index"), array_merge((array)$action, $arg));
		} else {
			call_user_func_array(array($controller, $action), $arg);
		}
		$controller->after();
	}
	$time_end = microtime(true);
?>