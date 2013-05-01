<?php

class LoadAllDataTask {

	public function updatePublishedProjects() {
		$projs = list_project();
		foreach($projs as $proj) {
			Makiavelo::puts("Updating project: " . $proj->id . " -- " . $proj->name);
			$proj->published = 1;
			save_project($proj);
		}
	}

	public function loadGitalyticsData() {
		$proj = load_project(12);
		Makiavelo::puts("Grabing data for project: " . $proj->name);
		$proj->grabHistoricData();
		Makiavelo::puts("Done!");
	}

	public function load() {

 		$projects = list_project();
 		Makiavelo::info("=== Starting old stats process ===");
 		foreach($projects as $proj) {
 			$proj_name = $proj->name;
 			$usr_name  = $proj->owner()->name;		
 			
 			Makiavelo::puts("==== Querying for $usr_name/$proj_name");
 			$g_data = GithubAPI::queryProjectData($usr_name, $proj_name);

 			//Calculate the commits for today
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

			//Makiavelo::puts(print_r($data, true));
			//exit;
			foreach($data as $date => $stats) {
				$year = substr($date, 0, 4);
				$month = substr($date, 4,2);
				$day = substr($date, 6,2);
				$str_date = $year . "-" . $month . "-" . $day;
	 			Makiavelo::info("==== Delta found, saving...");
	 			Makiavelo::puts("Saving delta for date: $str_date");
	 			$pd = new ProjectDelta();
				$pd->forks = -99;
				$pd->delta_forks = -99;
				$pd->stars = -99;
				$pd->delta_stars = -99;

				$pd->project_id 	= $proj->id;
				$pd->commits_count 	= isset($stats['commits']) ? $stats['commits'] : 0;
				$pd->new_pulls 		= isset($stats['new_pulls']) ? $stats['new_pulls'] : 0;
				$pd->closed_pulls 	= isset($stats['closed_pulls']) ? $stats['closed_pulls'] : 0;
				$pd->merged_pulls 	= isset($stats['merged_pulls']) ? $stats['merged_pulls'] : 0;
				$pd->sample_date 	= $str_date; 
				
				if(save_project_delta($pd)) {
					Makiavelo::puts("===== Delta saved! ");
				} else {
					Makiavelo::puts("===== ERROR saving delta::" . mysql_error());
				}
			}

			delete_issues_by_project_id($proj->id);

 			foreach($data->open_issues_list as $issue) {

			$iss = new Issue();
			$iss->title = $issue->title;
			$iss->body = $issue->body;
			$iss->created_at = $issue->created_at;
			$iss->updated_at = $issue->updated_at;
			$iss->url = $issue->url;
			$iss->number = $issue->number;
			$iss->project_id = $proj->id;

			if(save_issue($iss)) {
				Makiavelo::info("===== Issue saved! ");
			} else {
				Makiavelo::info("===== ERROR saving issue::" . mysql_error());
			}
		}
	}

}


?>