<?php

 class ProjectDeltaController extends ApplicationController {

 	/**
 		Loads all projects and queries Githubs API for new data
 		on stars and forks
 		*/
 	public function generateAction() {
 		$projects = list_project();
 		Makiavelo::info("=== Starting stats process ===");
 		foreach($projects as $proj) {
 			$proj_name = $proj->name;
 			$usr_name  = $proj->owner()->name;		
 			$dev = $proj->owner();
 			if($proj->updated_at == date("Y-m-d") . "00:00:00") {
 				continue; //Avoid duplicated entries
 			}

 			Makiavelo::info("==== Querying for $usr_name/$proj_name");
 			$data = GithubAPI::queryProjectData($usr_name, $proj_name);

 			//We update the dev's avatar if needed
 			if($data->owner->avatar_url != $dev->avatar_url) {
 				$dev->avatar_url = $data->owner->avatar_url;
 				save_developer($dev);
 			}

 			//Calculate the commits for today
			$commits_today = 0;
			$today = date("Y-m-d");
			foreach($data->commits as $commit) {
				$commit_date = $commit->commit->committer->date;
				$commit_date = explode("T", $commit_date);
				$commit_date = $commit_date[0];
				if($commit_date == $today) {
					$commits_today++;	
				}
			}

			$new_pulls_today = $closed_pulls_today = $merged_pulls_today = 0;
			foreach($data->pulls as $pull) {
				$created_data = explode("T", $pull->created_at);
				$closed_data = explode("T", $pull->closed_at);
				$merged_data = explode("T", $pull->merged_at);


				if($created_at[0] == $today) {
					$new_pulls_today++;
				}
				if($closed_at[0] == $today) {
					$closed_pulls_today++;
				}
				if($merged_data[0] == $today) {
					$merged_pulls_today++;
				}

			}

 			$delta_stars = $data->watchers - $proj->stars;
 			$delta_forks  = $data->forks - $proj->forks;

			//if($delta_stars != 0 || $delta_forks != 0) {
 			Makiavelo::info("==== Delta found, saving...");
 			//We also update the project
 			$proj->stars = $data->watchers;
 			$proj->forks = $data->forks;
 			save_project($proj);

			$pd = new ProjectDelta();
			$pd->forks = $data->forks;
			$pd->delta_forks = $delta_forks;

			$pd->stars = $data->watchers;
			$pd->delta_stars = $delta_stars;

			$pd->project_id 	= $proj->id;
			$pd->commits_count 	= $commits_today;
			$pd->new_pulls 		= $new_pulls_today;
			$pd->closed_pulls 	= $closed_pulls_today;
			$pd->merged_pulls 	= $merged_pulls_today;
			$pd->sample_date 	= date("Y-m-d H:i:s");
			
			if(save_project_delta($pd)) {
				Makiavelo::info("===== Delta saved! ");
			} else {
				Makiavelo::info("===== ERROR saving delta");
			}
 			//}

 		}
 	}

 }


?>