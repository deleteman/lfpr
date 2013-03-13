<?php

function login_user($u, $p) {
	global $__db_conn;
	global $__SECURITY;
	global $sLayer;

	$className = $__SECURITY['class_name'];
	$tmpEntity = new $className(); 
	$table_name = $tmpEntity->__get_entity_name();

	$func = "load_" . $table_name . "_where";
	$usuario = $func("email='" . $u . "' and password = '" . $p . "'");
	if($usuario != null) {
		$sLayer->logInUser($usuario);
		return array("ok" => true, "user" => $usuario);
	} else {
		return array("ok" => false);
	}
}

function logout_user() {
	global $sLayer;
	return $sLayer->logOutUser();
}

function current_role() {
	global $sLayer;
	return $sLayer->currentRole();
}

function current_user() {
	global $sLayer;
	return $sLayer->currentUser();
}

function user_logged_in() {
	global $sLayer;
	return $sLayer->isUserLoggedIn();
}


?>