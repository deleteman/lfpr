<?php

class Makiavelo {

	const LOG_FILE = "/tmp/logs.txt";
	//System paths
	const MAPPINGS_FOLDER = "/mappings/";

	const NOTFOUND_PAGE_PATH = "/public/404.html";

	const ENTITITES_FOLDER = "/app/entities/";
	const CONTROLLERS_FOLDER = "/app/controllers/";
	const VIEWS_FOLDER = "/app/views/";
	const SQL_CREATE_TABLES_FOLDER = "/app/sql/creates/";
	const SQL_HELPERS_FOLDER = "/app/sql/helpers/";
	const APP_CONFIG_FOLDER = "/config/";
	const CODE_HELPERS_FOLDER = "/app/helpers/";
	const UPLOADED_FILES_FOLDER = "/public/uploads/";

	const DATABASE_CONFIGURATION = "/config/database.yml";

	const MIGRATIONS_FOLDER = "/lib/migrations";

	const TEMPLATES_FOLDER =  "/core/templates/";
	const ABM_VIEWS_TEMPLATE_FOLDER = "/core/templates/abm_views/";
	const CONTROLLER_TEMPLATE_FILE = "/core/templates/ControllerTemplateClass.php";

	const VIA_GET = "get";
	const VIA_POST = "post";

	const DEBUG_LEVEL_NONE = 0;
	const DEBUG_LEVEL_INFO = 1;
	const DEBUG_LEVEL_FULL = 99;

	const RESPONSE_CODE_OK = "200";
	const RESPONSE_CODE_NOT_FOUND = "404";
	const RESPONSE_CODE_FORBIDDEN = "401";

	private static $debug_level = Makiavelo::DEBUG_LEVEL_FULL;

	public static function getDebugLevel() {
		return self::$debug_level;
	}
	public static function setDebugLevel($lvl) {
		self::$debug_level = $lvl;
	}

	public static function info($txt) {
		self::debug($txt, Makiavelo::DEBUG_LEVEL_INFO);
	}

	public static function puts($txt) {
		echo "\n" . $txt;
	}

	public static function debug($txt, $lvl) {
		if(self::getDebugLevel() >= $lvl) {
			$log_str = "\n" . date("d-m-Y H:i:s")	 . " :: " .$txt . "\n";
			$fp = fopen(ROOT_PATH . "/" . Makiavelo::LOG_FILE, "a");
			if ($fp) {
				fwrite($fp, $log_str);
				fclose($fp);
			} else {
				echo $log_str;
			}
		}
	}
	public function getAction($action_code) {
		$af = ActionFactory::getInstance();
		return $af->getAction($action_code);
	}

	public static function camel_to_underscore($txt) {
		return strtolower(preg_replace('/([a-z])([A-Z])/', '$1_$2', $txt));
	}

	public static function underscore_to_camel($txt) {
		return str_replace(" ", "", ucwords(str_replace("_", " ", strtolower($txt))));
	}

	public static function titlelize($txt) {
		return ucwords(str_replace("_", " ", $txt));
	}

	public static function getCurrentEnv() {
		$env = getenv("makiavelo_env");
		return ($env == "") ? "development" : $env;
	}
}

?>