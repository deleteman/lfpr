<?php

class Config {

	const CONFIG_FILE_PATH = "/config/config.yml";

	static private function load() {
		$parser = new YAMLParser();
		$config_path = ROOT_PATH . Config::CONFIG_FILE_PATH;
		return $parser->parsePath($config_path);
	}

	static public function get($param_path) {
		$data = Config::load();
		$keys = explode(".", $param_path);
		$current = $data;
		foreach($keys as $key) {
			if($current[$key]) {
				$current = $current[$key];
			}
		}
		return $current;
	}
}


?>