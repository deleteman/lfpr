<?php

class ControllerGenerator {

	public function execute($params) {
		$name = str_replace("Controller", "", $params[0]); //in case the dev adds the word...
		if(isset($params[1])) {
			$views_option = $params[1];
		} else {
			$views_option = "";
		}

		$this->controller_name = $name;
		//Make sure the name is rightly formatted
		$this->uc_controller_name = Makiavelo::camel_to_underscore($name);
		Makiavelo::info("Generating controller: {$this->controller_name}");

		$this->generateControllerFile();
		if($views_option != "no-views") {
			Makiavelo::info("With views...");
			$this->generateViewFiles();
		} else {
			Makiavelo::info("Without views...");
		}
	}

	public function generateViewFiles() {
		$vGen = new ViewsGenerator();
		$vGen->generateCRUDViewsFor($this->controller_name);
	}

	public function generateControllerFile() {
		$template_file = ROOT_PATH . Makiavelo::CONTROLLER_TEMPLATE_FILE; 
		$target_file = ROOT_PATH . Makiavelo::CONTROLLERS_FOLDER . "/" . $this->controller_name . "Controller.php";

		$fp = fopen($template_file, "r");
		if($fp) {
			$code = fread($fp, filesize($template_file));
			$code = str_replace("[NAME]", $this->controller_name, $code);
			$code = str_replace("[UC_NAME]", $this->uc_controller_name, $code);

			fclose($fp);

			if(file_exists($target_file)) {
				Makiavelo::info("Controller file already exists ($target_file), not saving!");
			} else {
				$fp = fopen($target_file, "w");
				if($fp) {
					fwrite($fp, $code);
					fclose($fp);
				}
			}
		}
	}
}

?>