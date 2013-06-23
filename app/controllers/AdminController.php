<?php

class AdminController extends ApplicationController {

	public function indexAction() {
		$most_commits = get_projects_by_commits();
		$most_pr = get_projects_by_pr();
		$latest = load_latest_projects("id desc");
		$suscribers = list_suscriptor("id ");
		$this->render(array("commits" => $most_commits, 
							"most_pr" => $most_pr,
							"latest" => $latest,
							"suscribers" => $suscribers)); 
	}
}


?>