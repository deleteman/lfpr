<?php

 class ApplicationController extends MakiaveloController {


 	public function __construct() {
 		$this->layout = HTTPRequest::is_ajax_request() ? null : "layout";
 	}	

 }
?>