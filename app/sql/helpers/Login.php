<?php

function login_github_user($usr) {
	global $sLayer;
	$dev = load_developer_where("name = '" . $usr['username']."'");
	if($dev != null) {
		$sLayer->loginUser($dev);			
		return array("ok" => true, "user" => $dev);
	} else {
		$dev = new Developer();
		$dev->name = $usr['username'];
		$dev->avatar_url = $usr['avatar_url'];
		if(save_developer($dev)) {
			$sLayer->logInUser($dev);
			return array("ok" => true, "user" => $dev);
		} else {
			return array("ok" => false);
		}
	}
}

function login_user($u, $p) {
	global $__db_conn;
	global $__SECURITY;
	global $sLayer;

	$className = $__SECURITY['class_name'];
	$tmpEntity = new $className(); 
	$table_name = $tmpEntity->__get_entity_name();

	$func = "load_" . $table_name . "_where";
	$usuario = $func($__SECURITY['username_field'] . "='" . $u . "' and " . $__SECURITY['password_field'] . " = '" . $p . "'");
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