<?php

$_ROUTES = array();


$_ROUTES[] = array("root_path" => array("url" => "/", "controller" => "Home", "action" => "index"));
/*
$_ROUTES[] = array(
	"login" => array("url" => "/login", "controller" => "Login", "action" => "index"),
	"sign_in" => array("url" => "/login/sign_in", "controller" => "Login", "action" => "signIn", "via" => "post"),
	"sign_out" => array("url" => "/login/sign_out", "controller" => "Login", "action" => "signOut", "via" => "post")
	);
*/
$_ROUTES[] = array(
	"list" => array("url" => "/project/", "controller" => "Project", "action" => "index"),
	"create" => array("url" => "/project/create", "controller" => "Project", "action" => "create", "via" => "post"),
	"new" => array("url" => "/project/new", "controller" => "Project", "action" => "new"),
	"retrieve" => array("url" => "/project/:id", "controller" => "Project", "action" => "show", "via" => "get"),
	"update" => array("url" => "/project/:id/edit", "controller" => "Project", "action" => "edit"),
	"delete" => array("url" => "/project/:id/delete", "controller" => "Project", "action" => "delete", "via" => "post")
	); $_ROUTES[] = array(
	"list" => array("url" => "/developer/", "controller" => "Developer", "action" => "index"),
	"create" => array("url" => "/developer/create", "controller" => "Developer", "action" => "create", "via" => "post"),
	"new" => array("url" => "/developer/new", "controller" => "Developer", "action" => "new"),
	"retrieve" => array("url" => "/developer/:id", "controller" => "Developer", "action" => "show", "via" => "get"),
	"update" => array("url" => "/developer/:id/edit", "controller" => "Developer", "action" => "edit"),
	"delete" => array("url" => "/developer/:id/delete", "controller" => "Developer", "action" => "delete", "via" => "post")
	); ?>