#!/usr/bin/php
<?php

define("ROOT_PATH", dirname(__FILE__));
//Includes necesarios
function __autoload_core ($classname) {
	$fname = dirname(__FILE__)."/core/" . $classname . "Class.php";
	if(file_exists($fname)) {
		include_once($fname);
	}
}


function __autoload_entities ($classname) {
	$fname = dirname(__FILE__)."/app/entities/" . $classname . "Class.php";
	if(file_exists($fname)) {

		//Includes the sql helpers for the entity
		//$sql_helper_folder = ROOT_PATH . Makiavelo::SQL_HELPERS_FOLDER;
		//include($sql_helper_folder . "/" . $classname . ".php");
		include_once($fname);
	}
}


function __autoload_validator($class) {
	$path = dirname(__FILE__)."/core/validators/" . $class . "Class.php";
	if(file_exists($path)) {
		require_once($path);
	}
}

//Includes necesarios
function __autoload_tasks ($classname) {
	$fname = dirname(__FILE__)."/lib/tasks/" . Makiavelo::camel_to_underscore($classname) . ".php";
	if(file_exists($fname)) {
		include_once($fname);
	}
}


function __autoload_lib($class) {
	$path = dirname(__FILE__)."/lib/" . $class . "Class.php";
	if(file_exists($path)) {
		require_once($path);
	}
}

spl_autoload_register('__autoload_core');
spl_autoload_register('__autoload_entities');
spl_autoload_register('__autoload_validator');
spl_autoload_register('__autoload_tasks');
spl_autoload_register('__autoload_lib');



include_once(ROOT_PATH . "/core/spyc.php");
include_once(ROOT_PATH . "/config/config.php");


//Includes all sql helpers
$sql_helper_folder = ROOT_PATH . Makiavelo::SQL_HELPERS_FOLDER;
$d = dir($sql_helper_folder);
while(false !== ($entry = $d->read())) {
	if($entry[0] != ".") {
		include($sql_helper_folder . "/" . $entry);
	}
}

//DB connection... simple for now...
$__db_conn = DBLayer::connect();


$parameters = $argv;

if(count($parameters) > 1) {
	$mk = new Makiavelo();

	$action = $mk->getAction($parameters[1]);
	unset($parameters[0]);
	unset($parameters[1]);

	$action->execute(array_values($parameters));
} else {
	echo "Welcome to Makiavelo command line utility";
	echo "\nUsage: makiavelo [COMMAND] [ATTRIBUTES] \n";
	echo "\nValid commands:";
	echo "\n  g: Generator command";
	echo "\n     Attributes:";
	echo "\n     crud: Generates a controller, an entity and a set of views for the CRUD operations. Needs a name for the entity";
	echo "\n     controller: Generates an empty controller. Needs a controller name as parameter";
	echo "\n     migration: Generates an empty migration class.";
	echo "\n  db:create: Creates the database.";
	echo "\n  db:load: Loads all sql files related to the entities";
	echo "\n  db:migrate: Runs all pending migrations";
	echo "\n  task: Runs a task on lib/tasks";
	echo "\n";
	//[yaml file inside mappings folder]

}

?>