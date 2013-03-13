<?php

function __autoload_core($class) {
	try {
		$path = dirname(__FILE__)."/../core/" . $class . "Class.php";
		if(file_exists($path)) {
			require_once($path);
		}
	} catch(Exception $e) {

	}
}

function __autoload_app($class) {
	$path = dirname(__FILE__)."/../app/entities/" . $class . "Class.php";
	if(file_exists($path)) {
		require_once($path);
	}
}

function __autoload_controller($class) {
	$path = dirname(__FILE__)."/../app/controllers/" . $class . ".php";
	if(file_exists($path)) {
		require_once($path);
	}
}

function __autoload_validator($class) {
	$path = dirname(__FILE__)."/../core/validators/" . $class . "Class.php";
	if(file_exists($path)) {
		require_once($path);
	}
}
spl_autoload_register('__autoload_core');
spl_autoload_register('__autoload_app');
spl_autoload_register('__autoload_controller');
spl_autoload_register('__autoload_validator');

session_start();

define("ROOT_PATH", dirname(__FILE__). "/../");

include_once(ROOT_PATH . "/core/helpers/url_helpers.php");
include_once(ROOT_PATH . "/core/helpers/form_helpers.php");
include_once(ROOT_PATH . "/core/spyc.php");
include_once(ROOT_PATH . "/core/I18nClass.php");
include_once(ROOT_PATH . "/config/config.php");

//Includes all sql helpers
$sql_helper_folder = ROOT_PATH . Makiavelo::SQL_HELPERS_FOLDER;
$d = dir($sql_helper_folder);
while(false !== ($entry = $d->read())) {
	if($entry[0] != ".") {
		include($sql_helper_folder . "/" . $entry);
	}
}


//Includes all generic helpers
$code_helper_folder = ROOT_PATH . Makiavelo::CODE_HELPERS_FOLDER;
Makiavelo::info("loading code helpers from: " . $code_helper_folder);
$d = dir($code_helper_folder);
while(false !== ($entry = $d->read())) {
	if($entry[0] != ".") {
		include($code_helper_folder . "/" . $entry);
	}
}

//DB connection... simple for now...
$__db_conn = DBLayer::connect();

Makiavelo::info("Initializing bootstrap script...");
RoutesHandler::generateRoutesHelpers();

$rHandler = new RoutesHandler();
$sLayer = new SecurityLayer();

$q = (isset($_GET['q'])) ? $_GET['q'] : "";

Makiavelo::info("Querystring: " .print_r($_GET, true));

$response_code = $rHandler->checkRoute($q, $_SERVER, $sLayer);

$mkCore = new MakiaveloCore($rHandler);
$mkCore->handleResponseCode($response_code);




?>