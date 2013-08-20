<?php

class RoutesHandler {

	private $controller;
	private $action;
	private $via;
	private $params;

	private function routeMatches($route, $query_url, $method) {
		$route_parts = preg_split("/\//", $route['url']);
		$route_parts = array_values(array_filter($route_parts, 'strlen'));

		$query_parts = preg_split("/\//", $query_url . "/");
		$query_parts = array_values(array_filter($query_parts, 'strlen'));

		$route_method = (isset($route['via'])) ? $route['via'] : "GET";

		$params = array();

		Makiavelo::info(count($route_parts). " != " . count($query_parts));
		if(count($route_parts) != count($query_parts)) {
			Makiavelo::info("Returning false!");
			return false;
		}


		Makiavelo::info("Query parts: " . print_r($query_parts, true));
		Makiavelo::info("Route parts: " . print_r($route_parts, true));
		foreach($route_parts as $i => $uri_token) {
			Makiavelo::info("Comparing routes: {$uri_token} == {$query_parts[$i]}");
			if($uri_token[0] != ":" && $uri_token != $query_parts[$i]) {
				Makiavelo::info("Returning false, not the same token!");
				return false;
			}

			if($uri_token[0] == ":") { //we collect all parameters
				$params[str_replace(":", "", $uri_token)] = $query_parts[$i];
			}
		}

		Makiavelo::info("Comparing method: " . $method . " != " . $route_method);
		if($method != null) {
			if(strtolower($method) != strtolower($route_method)) {
				return false;
			}
		}

		Makiavelo::info("Route matched! :: " . print_r($route, true));
		$this->params = $params;
		return true;
	}

	public function checkRoute($querystring, $server, $security) {
		$general_routes_file = ROOT_PATH. Makiavelo::APP_CONFIG_FOLDER . "/routes.php";
		Makiavelo::info("Including " . $general_routes_file . "...");
		include($general_routes_file);
		$request_uri = $querystring;
		$method 	 = $server['REQUEST_METHOD'];

		foreach($_ROUTES as $entity_routes) {
			foreach ($entity_routes as $action => $data) {
				$data['role'] = (isset($data['role'])) ? $data['role'] : $security->getLowestRole();
				if($this->routeMatches($data, $request_uri, $method)) {
					if($security->isUserAllowed($data['role'])) { 
						$this->controller = $data['controller'];
						$this->action = $data['action'];
						$this->via = (isset($data['via'])) ? $data['via'] : Makiavelo::VIA_GET;
						return Makiavelo::RESPONSE_CODE_OK;
					} else {
						return Makiavelo::RESPONSE_CODE_FORBIDDEN;
					}
				}
			}
		}
		return Makiavelo::RESPONSE_CODE_NOT_FOUND;
	}

	public function find_method_for($url) {
		$general_routes_file = ROOT_PATH. Makiavelo::APP_CONFIG_FOLDER . "/routes.php";
		include($general_routes_file);

		foreach($_ROUTES as $entity_routes) {
			foreach ($entity_routes as $action => $data) {
				if($this->routeMatches($data, $url, null)) {
					return (isset($data['via'])) ? $data['via'] : Makiavelo::VIA_GET;
				}
			}
		}
		return false;
	}

	private function getNamedPath($name) {
		$general_routes_file = ROOT_PATH. Makiavelo::APP_CONFIG_FOLDER . "/routes.php";
		include($general_routes_file);

		foreach ($_ROUTES as $route) {
			if(array_key_exists($name, $route)) {
				Makiavelo::info("Returning $name path: " . print_r($route, true));
				return $route[$name]['url'];
			}
		}
		return null;
	}

	public function getLoginPath() {
		return $this->getNamedPath("login");
	}

	public function getRootPath() {
		return $this->getNamedPath("root");
	}

	public function getController() {
		return $this->controller;
	}

	public function getActionName() {
		return $this->action;
	}

	public function getHTTPMethod() {
		return $this->via;
	}

	public function getNamedParams() {
		return $this->params;
	}


	//--- Static --
	public static function generateRoutesHelpers() {
		$general_routes_file = ROOT_PATH. Makiavelo::APP_CONFIG_FOLDER . "/routes.php";
		include($general_routes_file);	

		foreach ($_ROUTES as $entity_routes) {
			foreach($entity_routes as $action => $data) {
				Makiavelo::info("Creating function helper: " . Makiavelo::camel_to_underscore($data['controller']) . "_" . $action . '_path ()');
				$fnc = 'function ' . Makiavelo::camel_to_underscore($data['controller']) . "_" . $action . '_path ($params = array()) {

						$url = "'.$data['url'].'";
						$args = func_get_args();

						preg_match_all("/:([a-zA-Z_0-9]*)/", $url, $matches);
						foreach($matches[1] as $i => $attr) {
							if(is_object($args[$i])) {
								$value = $args[$i]->$attr;
							} else {
								$value = $args[$i];
							}
							$url = str_replace(":$attr", $value, $url);
						}

							if(is_array($params) && count($params) > 0) {
								$url .= "?";
								$query_params = array();
								foreach($params as $k => $v) {
									$query_params[] = $k . "=" . $v;
								}
								$url .= implode("&", $query_params);
							}
							return $url;
						}';

				eval($fnc);
			}
		}
	}

}

?>