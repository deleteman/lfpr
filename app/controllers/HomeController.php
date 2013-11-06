<?php

class HomeController extends ApplicationController {

	public function indexAction() {
		$latest = load_latest_projects();
		$this->render(array("new_projects" => $latest, "suscriptor" => new Suscriptor()));
	}

	public function decPromoAction() {
		$this->render();
	}
}

?>