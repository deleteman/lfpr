<?php

 class LoginController extends ApplicationController {

 	public function signInAction() {

 		$usuario = $this->request->getParam('email');
 		$pwd = $this->request->getParam('pwd');

 		$result = login_user($usuario, $pwd);
 		if($result['ok'] === true) {
 			$this->redirect_to(home_root_path_path());
 		} else {
 			$this->flash->error("Invalid username/password ");
 			$this->render(null, 'index');
 		}
 	}

 	public function signOutAction() {
 		logout_user();
 		$this->redirect_to(home_root_path_path());
 	}

	public function indexAction() {
		$this->render();
	}


 }


?>