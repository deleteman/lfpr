<?php

class RoutesGenerator {

	private $en_name;

	public function __construct($name) {
		$this->en_name = $name;
	}

	public function generateCRUDRoutes() {

		$template = '$_ROUTES[] = array(
	"list" => array("url" => "/:uc_name:/", "controller" => "[NAME]", "action" => "index"),
	"create" => array("url" => "/:uc_name:/create", "controller" => "[NAME]", "action" => "create", "via" => "post"),
	"new" => array("url" => "/:uc_name:/new", "controller" => "[NAME]", "action" => "new"),
	"retrieve" => array("url" => "/:uc_name:/:id", "controller" => "[NAME]", "action" => "show", "via" => "get"),
	"update" => array("url" => "/:uc_name:/:id/edit", "controller" => "[NAME]", "action" => "edit"),
	"delete" => array("url" => "/:uc_name:/:id/delete", "controller" => "[NAME]", "action" => "delete", "via" => "post")
	); ?>';

		$template = str_replace(":uc_name:", Makiavelo::camel_to_underscore($this->en_name), $template);
		$template = str_replace("[NAME]", $this->en_name, $template);

		$routes_file = ROOT_PATH . Makiavelo::APP_CONFIG_FOLDER . "/routes.php";

		$fp = fopen($routes_file, "r");
		if($fp) {
			$routes = fread($fp, filesize($routes_file));
			$routes = str_replace("?>", $template, $routes);		
			fclose($fp);

			$fp  = fopen($routes_file, "w");
			if($fp) {
				fwrite($fp, $routes);
				fclose($fp);
			}
		}
		
	}

}


?>