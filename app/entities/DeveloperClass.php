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

	public function getMyContributions() {
		return get_developer_contributions($this->id, $this->name);
	}

	public function gatherStats() {
		$db_projects = $this->getProjects(true);
		$langs = array();
		$projects = array();
		foreach($db_projects as $project) {
			$projects[] = array(
				"language" => $project->language,
				"name" => $project->name,
				"commits" => $project->countCommits(),
				"pr_acceptance_rate" => intval($project->pr_acceptance_rate),
				"stars" => intval($project->stars),
				"forks" => intval($project->forks),
				"faq_count" => count($project->getQuestions()),
				"total_pull_requests" => $project->getTotalPullRequests()
				);
		}
		$stats = array("owned_projects" => $projects,
						"contributed_projects" => $this->getMyContributions());
		return $stats;
	}
}