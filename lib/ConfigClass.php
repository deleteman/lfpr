<?php

class Config {

	const CONFIG_FILE_PATH = "/config/config.yml";

	static private function load() {
		$parser = new YAMLParser();
		$config_path = ROOT_PATH . Config::CONFIG_FILE_PATH;
		Makiavelo::info("trying to load config file: " . $config_path);
		return $parser->parsePath($config_path);
	}

	static public function get($param_path) {
		Makiavelo::info("Getting configuration parameter: " . $param_path);
		$data = Config::load();
		Makiavelo::info("Data loaded: " . print_r($data, true));
		$keys = explode(".", $param_path);
		$current = $data;
		foreach($keys as $key) {
			if($current[$key]) {
				$current = $current[$key];
			}
		}
		Makiavelo::info("Value found: " . $current);
		return $current;
	}
}


?>