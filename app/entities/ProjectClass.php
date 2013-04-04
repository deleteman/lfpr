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
	}
}