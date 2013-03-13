<?php


class YAMLParser {

	public function parsePath($path) {
		return spyc_load_file($path);
	}

	public function parse($fname) {
		$filename = ROOT_PATH . "/" . Makiavelo::MAPPINGS_FOLDER . "/" . $fname;
		if($filename) {
			return spyc_load_file($filename);
		}
	}
}

?>