<?php

 class LoginController extends ApplicationController {

 	public function githubLoginAction() {
 		$code = $this->request->getParam("code");
 		GithubAPI::requestWebAuth($code);
 		$current_dev = GithubAPI::getCurrentUser();
 		$result = login_github_user($current_dev);
 		GithubAPI::getUserRepos($current_dev['username']);

 		if($result['ok'] === true) {
 			$this->redirect_to(home_root_path_path());
 		} else {
 			$this->flash->error("Error authenticating against Github");
 			$this->redirect_to(home_root_path_path());
 		}
 	}

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