<?php

$_ROUTES = array();


$_ROUTES[] = array(
  "root_path" => array("url" => "/", "controller" => "Home", "action" => "index"),
  "contest" => array("url" => "contest", "controller" => "Home", "action" => "contest")

  );
$_ROUTES[] = array(
  "sign_out" => array("url" => "/login/sign_out", "controller" => "Login", "action" => "signOut", "via" => "post")
  );
$_ROUTES[] = array(
  "list" => array("url" => "/project/", "controller" => "Project", "action" => "index"),
  "create" => array("url" => "/project/create", "controller" => "Project", "action" => "create", "via" => "post"),
  "grab_data" => array("url" => "/project/grab_data", "controller" => "Project", "action" => "grab_data", "via" => "post"),
  "publish" => array("url" => "/project/publish/:id", "controller" => "Project", "action" => "publish"),
  "unpublish" => array("url" => "/project/unpublish/:id", "controller" => "Project", "action" => "unpublish", "via" => "post"),
  "search" => array("url" => "/project/search", "controller" => "Project", "action" => "search"),
//  "new" => array("url" => "/project/new", "controller" => "Project", "action" => "new","role" => "user"),
  "show" => array("url" => "/project/:id", "controller" => "Project", "action" => "show", "via" => "get"),
  "update" => array("url" => "/project/:id/edit", "controller" => "Project", "action" => "edit"),
  "delete" => array("url" => "/project/:id/delete", "controller" => "Project", "action" => "delete", "via" => "post")
  ); 
$_ROUTES[] = array(
  "list" => array("url" => "/developer/", "controller" => "Developer", "action" => "index"),
  "show" => array("url" => "/developer/:name", "controller" => "Developer", "action" => "show", "via" => "get"),
  "stats" => array("url" => "/developer/:name/stats", "controller" => "Developer", "action" => "getStats", "via" => "get")
  ); 
$_ROUTES[] = array(
  "list" => array("url" => "/suscriptor/", "controller" => "Suscriptor", "action" => "index"),
  "create" => array("url" => "/suscriptor/create", "controller" => "Suscriptor", "action" => "create", "via" => "post"),
  "delete" => array("url" => "/suscriptor/:id/delete", "controller" => "Suscriptor", "action" => "delete", "via" => "post")
  ); 

$_ROUTES[] = array(
  "github_login" => array("url" => "/github_cb/login", "controller" => "Login", "action" => "githubLogin")
  );
  
$_ROUTES[] = array(
  "generate" => array("url" => "/project_delta/gen", "controller" => "ProjectDelta", "action" => "generate"),
  /*
  "list" => array("url" => "/project_delta/", "controller" => "ProjectDelta", "action" => "index"),
  "create" => array("url" => "/project_delta/create", "controller" => "ProjectDelta", "action" => "create", "via" => "post"),
  "new" => array("url" => "/project_delta/new", "controller" => "ProjectDelta", "action" => "new"),
  "retrieve" => array("url" => "/project_delta/:id", "controller" => "ProjectDelta", "action" => "show", "via" => "get"),
  "update" => array("url" => "/project_delta/:id/edit", "controller" => "ProjectDelta", "action" => "edit"),
  "delete" => array("url" => "/project_delta/:id/delete", "controller" => "ProjectDelta", "action" => "delete", "via" => "post")
  */);

   $_ROUTES[] = array(
  "list" => array("url" => "/project_commit/", "controller" => "ProjectCommit", "action" => "index"),
  "create" => array("url" => "/project_commit/create", "controller" => "ProjectCommit", "action" => "create", "via" => "post"),
  "new" => array("url" => "/project_commit/new", "controller" => "ProjectCommit", "action" => "new"),
  "retrieve" => array("url" => "/project_commit/:id", "controller" => "ProjectCommit", "action" => "show", "via" => "get"),
  "update" => array("url" => "/project_commit/:id/edit", "controller" => "ProjectCommit", "action" => "edit"),
  "delete" => array("url" => "/project_commit/:id/delete", "controller" => "ProjectCommit", "action" => "delete", "via" => "post")
  ); 

   $_ROUTES[] = array(
  "list" => array("url" => "/issue/:pid", "controller" => "Issue", "action" => "index"),
  ); 
   $_ROUTES[] = array(
  "index" => array("url" => "/admin/", "controller" => "Admin", "action" => "index", "role" => "admin"),
  "login" => array("url" => "/admin/login", "controller" => "Login", "action" => "adminLogin"),
  "admin_login" => array("url" => "/admin/login", "controller" => "Login", "action" => "doAdminLogin", "via" => "post"),
  /*
  "create" => array("url" => "/user/create", "controller" => "User", "action" => "create", "via" => "post", "role" => "admin"),
  "new" => array("url" => "/user/new", "controller" => "User", "action" => "new", "role" => "admin"),
  "retrieve" => array("url" => "/user/:id", "controller" => "User", "action" => "show", "via" => "get", "role" => "admin"),
  "update" => array("url" => "/user/:id/edit", "controller" => "User", "action" => "edit", "role" => "admin"),
  "delete" => array("url" => "/user/:id/delete", "controller" => "User", "action" => "delete", "via" => "post", "role" => "admin")
  */
  );

 $_ROUTES[] = array(
  "list" => array("url" => "/faq/", "controller" => "Faq", "action" => "index"),
  "create" => array("url" => "/faq/create", "controller" => "Faq", "action" => "create", "via" => "post"),
  "new" => array("url" => "/faq/new", "controller" => "Faq", "action" => "new"),
  "retrieve" => array("url" => "/faq/:id", "controller" => "Faq", "action" => "show", "via" => "get"),
  "update" => array("url" => "/faq/:id/edit", "controller" => "Faq", "action" => "edit"),
  "delete" => array("url" => "/faq/:id/delete", "controller" => "Faq", "action" => "delete", "via" => "post")
  ); $_ROUTES[] = array(
	"list" => array("url" => "/web_site_message/", "controller" => "WebSiteMessage", "action" => "index", "role" => "admin"),
	"create" => array("url" => "/web_site_message/create", "controller" => "WebSiteMessage", "action" => "create", "via" => "post"),
	"new" => array("url" => "/web_site_message/new", "controller" => "WebSiteMessage", "action" => "new", "role" => "admin"),
	"retrieve" => array("url" => "/web_site_message/:id", "controller" => "WebSiteMessage", "action" => "show", "via" => "get", "role" => "admin"),
	"update" => array("url" => "/web_site_message/:id/edit", "controller" => "WebSiteMessage", "action" => "edit", "role" => "admin"),
	"delete" => array("url" => "/web_site_message/:id/delete", "controller" => "WebSiteMessage", "action" => "delete", "via" => "post", "role" => "admin")
	); ?>
