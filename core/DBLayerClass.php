<?php

class DBLayer {

	public static $config = array();

	public static function connect() {
		Makiavelo::info("Connecting to database...");
		DBLayer::loadConfiguration();
		$__db_conn = null;
		if(DBLayer::$config['name'] != "") {
			$__db_conn = mysql_connect(DBLayer::$config['host'], DBLayer::$config['user'], DBLayer::$config['pwd']);
			if(!$__db_conn) {
				Makiavelo::error("Can't connect to database, using configuration: ");
				Makiavelo::error(print_r(DBLayer::$config, true));
				Makiavelo::error("MYSQL ERROR::" . mysql_error());
			}
		}
		DBLayer::selectDB();
		return $__db_conn;
	}

	public static function selectDB() {
		if(!mysql_select_db(DBLayer::$config['name'])) {
			Makiavelo::info("Error selecting database: ");
			Makiavelo::info(mysql_error());
		}
	}

	public static function disconnect($conn) {
		Makiavelo::info("Closing database connection...");
		mysql_close($conn);
	}

	public static function getDBName() {
		return DBLayer::$config['name'];
	}

	public static function describeTable($tname) {
		Makiavelo::info("Doing a describe table for '".DBLayer::getDBName()." - $tname'");
		$db = DBLayer::connect();
		$res = mysql_query("show columns from $tname", $db);
		$return = array();
		if(!$res) {
			Makiavelo::info("Error:: " . mysql_error());
		} else {
			while($data = mysql_fetch_assoc($res)) {
				Makiavelo::info("Field data: " . print_r($data, true));
				$return[$data['Field']] = $data['Type'];
			}

		}
		//DBLayer::disconnect($db);
		Makiavelo::info("table description: " . print_r($return, true));
		return $return;
	}

	private static function loadConfiguration() {
		Makiavelo::info("Loading database configuration file: ");
		$database_yml = ROOT_PATH . Makiavelo::DATABASE_CONFIGURATION;

		Makiavelo::info($database_yml);
		$parser = new YAMLParser();
		$config = $parser->parsePath($database_yml);
		Makiavelo::info("Configuration loaded: ");
		Makiavelo::info(print_r($config, true));
		DBLayer::$config = $config[Makiavelo::getCurrentEnv()];
	}

	public static function select($sql) {
		$db = DBLayer::connect();
		$return = mysql_query($sql, $db);
		return $return;
	}

	public static function query($sql) {
		$db = DBLayer::connect();
		$return = mysql_query($sql, $db);
		if(!$return) {
			Makiavelo::info("Error on MYSQL Query:: " . mysql_error());
		}
		return $return;
	}

}


?>