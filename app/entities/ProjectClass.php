<?php

class Project extends MakiaveloEntity {
	private $id; //type: integer
	private $created_at; //type: datetime
	private $updated_at; //type: datetime
	private $name; //type: string
	private $url; //type: string
	private $description; //type: text
	private $owner_id; //type: integer
	private $stars; //type: integer
	private $forks; //type: integer
	private $last_update; //type: datetime
	private $language;
	private $published; //type: integer
	private $open_issues; //type: integer
	private $closed_issues; //type: integer
	private $readme; //type: text

	static public $validations = array();
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

	public function owner() {
		return load_developer($this->owner_id);
	}

	public function url() {
		$url = $this->url;
		$url = str_replace("https://", "", $url);
		$url = str_replace("http://", "", $url);
		return "http://" . $url;
	}

	public function language() {
		return ($this->language != "") ? $this->language : "N/A";
	}

	public function saveInitStats() {
		Makiavelo::info("===== Saving initial deltas");
		$pd = new ProjectDelta();
		$pd->forks = $this->forks;
		$pd->delta_forks = 0;

		$pd->open_issues = $this->open_issues;
		$pd->closed_issues = $this->closed_issues;

		$pd->stars = $this->stars;
		$pd->delta_stars = 0;

		$pd->project_id = $this->id;
		$pd->sample_date = date("Y-m-d H:i:s");

		if(save_project_delta($pd)) {
			Makiavelo::info("===== Delta saved! ");
		} else {
			Makiavelo::info("===== ERROR saving delta");
		}
	}

	public function getStats($init = null, $end = null) {
		$deltas = list_project_delta("sample_date",null, "project_id = " .$this->id);
		return $deltas;	
	}

	public function grabHistoricData() {
		$proj_name = $this->name;
		$usr_name  = $this->owner()->name;		
		
		Makiavelo::info("==== Querying for $usr_name/$proj_name");
		$g_data = GithubAPI::queryProjectData($usr_name, $proj_name);

		$this->readme = $g_data->readme;
		Makiavelo::puts("Updating project...");
		save_project($this);

		$data = array();
		foreach($g_data->commits 	as $commit) {
			$commit_date = $commit->commit->committer->date;
			$commit_date = explode("T", $commit_date);
			$commit_date = $commit_date[0];
			$date_idx = intval(str_replace("-", "", $commit_date));
			if(!isset($data[$date_idx]) || !isset($data[$date_idx]['commits'])) {
				$data[$date_idx] = array("commits" => 1);
 			} else {
				$data[$date_idx]['commits']++;
 			}

			$pc = load_project_commit_where("sha = '".$commit->sha."'");
			if($pc == null) { //We make sure we haven't yet saved this commit
				$project_commit = new ProjectCommit();
				$project_commit->project_id = $this->id;
				$project_commit->committer  = $commit->committer->login;
				$project_commit->commit_message = $commit->commit->message;
				$project_commit->sha = $commit->sha;
				$project_commit->commit_date = $commit_date;

				save_project_commit($project_commit);
			}
		}

		foreach($g_data->pulls as $pull) {
			$created_data = explode("T", $pull->created_at);
			$closed_data = explode("T", $pull->closed_at);
			$merged_data = explode("T", $pull->merged_at);

			$created_idx = intval(str_replace("-", "", $created_data[0]));
			$merged_idx = intval(str_replace("-", "", $merged_data[0]));
			$closed_idx = intval(str_replace("-", "", $closed_data[0]));

			if(!isset($data[$created_idx]) || !isset($data[$created_idx]['new_pulls'])) {
				$data[$created_idx]['new_pulls'] = 1;
			} else {
				$data[$created_idx]['new_pulls']++;
			}

			if($merged_idx != 0) {
				if(!isset($data[$merged_idx]) || !isset($data[$merged_idx]['merged_pulls'])) {
					$data[$merged_idx]['merged_pulls'] = 1;
				} else {
					$data[$merged_idx]['merged_pulls']++;
				}
			}

			if($closed_idx != 0) {
				if(!isset($data[$closed_idx]) || !isset($data[$closed_idx]['closed_pulls'])) {
					$data[$closed_idx]['closed_pulls'] = 1;
				} else {
					$data[$closed_idx]['closed_pulls']++;
				}
			}
		}

		foreach($data as $date => $stats) {
			$year = substr($date, 0, 4);
			$month = substr($date, 4,2);
			$day = substr($date, 6,2);
			$str_date = $year . "-" . $month . "-" . $day;
 			$pd = new ProjectDelta();
			$pd->forks = -99;
			$pd->delta_forks = -99;
			$pd->stars = -99;
			$pd->delta_stars = -99;

			$pd->project_id 	= $this->id;
			$pd->commits_count 	= isset($stats['commits']) ? $stats['commits'] : 0;
			$pd->new_pulls 		= isset($stats['new_pulls']) ? $stats['new_pulls'] : 0;
			$pd->closed_pulls 	= isset($stats['closed_pulls']) ? $stats['closed_pulls'] : 0;
			$pd->merged_pulls 	= isset($stats['merged_pulls']) ? $stats['merged_pulls'] : 0;
			$pd->sample_date 	= $str_date;
			
			if(save_project_delta($pd)) {
				Makiavelo::info("===== Delta saved! ");
			} else {
				Makiavelo::info("===== ERROR saving delta::" . mysql_error());
			}
		}

		foreach($g_data->open_issues_list as $issue) {

			$iss = new Issue();
			$iss->title = $issue->title;
			$iss->body = MarkdownExtra::defaultTransform($issue->body);
			$iss->created_at = $issue->created_at;
			$iss->updated_at = $issue->updated_at;
			$iss->url = $issue->html_url;
			$iss->number = $issue->number;
			$iss->project_id = $this->id;

			if(save_issue($iss)) {
				Makiavelo::info("===== Issue saved! ");
			} else {
				Makiavelo::info("===== ERROR saving issue::" . mysql_error());
			}

		}
	}
}