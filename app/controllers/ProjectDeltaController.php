<?php

 class ProjectDeltaController extends ApplicationController {

 	/**
 		Loads all projects and queries Githubs API for new data
 		on stars and forks
 		*/
 	public function generateAction() {
 		$projects = list_project(null, null, "published = 1");
 		Makiavelo::info("=== Starting stats process ===");
 		foreach($projects as $proj) {
 			$proj_name = $proj->name;
 			$usr_name  = $proj->owner()->name;		
 			$dev = $proj->owner();
 			Makiavelo::info("==== Querying for $usr_name/$proj_name");
 			Makiavelo::info("Last Update: " . $proj->updated_at);
 			if($proj->updated_at == date("Y-m-d") . " 00:00:00") {
 				Makiavelo::info("Skiping project");
 				continue; //Avoid duplicated entries
 			}

 			$data = GithubAPI::queryProjectData($usr_name, $proj_name);

 			//We update the dev's avatar if needed
 			if($data->owner->avatar_url != $dev->avatar_url) {
 				$dev->avatar_url = $data->owner->avatar_url;
 				save_developer($dev);
 			}

 			//Calculate the commits for today
			$commits_today = 0;
			$today = date("Y-m-d");
			/*
			Makiavelo::info("========================");
			Makiavelo::info(print_r($data->commits, true));
			Makiavelo::info("========================");
			exit();
			*/

			foreach($data->commits as $commit) {
				$commit_date = $commit->commit->committer->date;
				$commit_date = explode("T", $commit_date);
				$commit_date = $commit_date[0];

				$pc = load_project_commit_where("sha = '".$commit->sha."'");
				if($pc == null) { //We make sure we haven't yet saved this commit
					$project_commit = new ProjectCommit();
					$project_commit->project_id = $proj->id;
					$project_commit->committer  = $commit->committer->login;
					$project_commit->commit_message = $commit->commit->message;
					$project_commit->sha = $commit->sha;
					$project_commit->commit_date = $commit_date;

					save_project_commit($project_commit);
				}
				
				if($commit_date == $today) {
					$commits_today++;	
				}
			}

			$new_pulls_today = $closed_pulls_today = $merged_pulls_today = 0;
			$total_pulls = 0;
			$total_merged_pulls = 0;
			
			foreach($data->pulls as $pull) {
				$created_data = explode("T", $pull->created_at);
				$closed_data = explode("T", $pull->closed_at);
				$merged_data = explode("T", $pull->merged_at);

				$total_pulls++;
				if($pull->merged_at) {
					$total_merged_pulls++;
				}

				if($created_data[0] == $today) {
					$new_pulls_today++;
				}
				if($closed_data[0] == $today) {
					$closed_pulls_today++;
				}
				if($merged_data[0] == $today) {
					$merged_pulls_today++;
				}

			}
			if($total_pulls > 0) {
				$proj->pr_acceptance_rate = ($total_merged_pulls / $total_pulls) * 100;
			} else {
				$proj->pr_acceptance_ratec = -1;
			}

 			$delta_stars = $data->watchers - $proj->stars;
 			$delta_forks  = $data->forks - $proj->forks;

			//if($delta_stars != 0 || $delta_forks != 0) {
 			Makiavelo::info("==== Delta found, saving...");
 			//We also update the project
 			$proj->stars = $data->watchers;
 			$proj->forks = $data->forks;
 			$proj->readme = $data->readme;
 			save_project($proj);

			$pd = new ProjectDelta();
			$pd->forks = $data->forks;
			$pd->delta_forks = $delta_forks;

			$pd->open_issues = $data->open_issues;
			$pd->closed_issues = $data->closed_issues;

			$pd->stars = $data->watchers;
			$pd->delta_stars = $delta_stars;

			$pd->project_id 	= $proj->id;
			$pd->commits_count 	= $commits_today;
			$pd->new_pulls 		= $new_pulls_today;
			$pd->closed_pulls 	= $closed_pulls_today;
			$pd->merged_pulls 	= $merged_pulls_today;
			$pd->sample_date 	= date("Y-m-d H:i:s");

			if(save_project_delta($pd)) {
				Makiavelo::info("===== Delta saved! " );
				Makiavelo::info(print_r($pd, true));
			} else {
				Makiavelo::info("===== ERROR saving delta");
			}
 			//}

 			delete_issues_by_project_id($proj->id);

 			foreach($data->open_issues_list as $issue) {

			$iss = new Issue();
			$iss->title = $issue->title;
			$iss->body = MarkdownExtra::defaultTransform($issue->body);
			$iss->created_at = $issue->created_at;
			$iss->updated_at = $issue->updated_at;
			$iss->url = $issue->html_url;
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

 }


?>