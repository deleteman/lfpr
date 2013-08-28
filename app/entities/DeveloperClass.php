<?php

class Developer extends MakiaveloEntity {
	private $id; //type: integer
private $created_at; //type: datetime
private $updated_at; //type: datetime
private $name; //type: string
private $avatar_url; //type: string
private $github_url; //type: string
private $role; //not in DB

	static public $validations = array();

	public function __construct() {
		$this->role = "user";
	}

	public function __set($name, $val) {
		$this->$name = $val;
	}

	public function __get($name) {
		if(isset($this->$name)) {
			return $this->$name;
		} else {
			return null;
		}
	}

	public function avatar() {
		if($this->avatar_url == "") {
			return "/img/no-avatar.png";
		} else {
			return $this->avatar_url;
		}
	}

	public function getProjects($onlyPublished = false) {
		$wherecond = "owner_id = " . $this->id;
		if($onlyPublished) {
			$wherecond .= " and published = 1";
		}
		return list_project(null, null, $wherecond);
	}

	public function commitCount() {
		return count_project_commit("committer = '".$this->name."'");
	}

	public function ownsProject($p) {
		return $p->owner_id == $this->id;
	}

	public function gatherStats() {
		$db_projects = $this->getProjects(true);
		$langs = array();
		$projects = array();
		foreach($db_projects as $project) {
			$langs[] = $project->language;

			$projects[] = array(
				"name" => $project->name,
				"commits" => $project->countCommits(),
				"pr_acceptance_rate" => $project->pr_acceptance_rate,
				"stars" => $project->stars,
				"forks" => $project->forks,
				"faq_count" => count($project->getQuestions()),
				"total_pull_requests" => $project->getTotalPullRequests()
				);
		}
		$stats = array("languages" => array_unique($langs),
						"projects" => $projects);
		return $stats;
	}

}