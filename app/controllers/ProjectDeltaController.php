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

 			Makiavelo::info("==== Querying for $usr_name/$proj_name");
 			$data = GithubAPI::queryProjectData($usr_name, $proj_name);

 			$delta_stars = $data->watchers - $proj->stars;
 			$delta_forks  = $data->forks - $proj->forks;

 			if($delta_stars != 0 || $delta_forks != 0) {
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

 				$pd->project_id = $proj->id;
 				$pd->sample_date = date("Y-m-d H:i:s");
 				if(save_project_delta($pd)) {
 					Makiavelo::info("===== Delta saved! ");
 				} else {
 					Makiavelo::info("===== ERROR saving delta");
 				}
 			}

 		}
 	}

 }


?>