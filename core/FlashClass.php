<?php

class Flash {


	public function __call($method, $text) {
		$method_type = Makiavelo::camel_to_underscore($method);
		$method_parts = explode("_", $method_type);
		if($method_parts[0] == "get") {
			$key = '__flash_' . $method_parts[1];
			if(isset($_SESSION[$key])) {
				$txt = $_SESSION[$key];
				unset($_SESSION[$key]);
			} else {
				$txt = "";
			}
			return $txt;
		} else {
			$_SESSION["__flash_" . $method_type] = $text[0];
		}
	}

}


?>