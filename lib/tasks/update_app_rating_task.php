<?php

class UpdateAppRatingTask {

	public function run() {
		Makiavelo::puts("Starting update of approval ratings");
		$projects = list_project(null, null, "published = 1");
 		foreach($projects as $proj) {
 			$proj_name = $proj->name;
 			$usr_name  = $proj->owner()->name;		
 			$dev = $proj->owner();

 			Makiavelo::puts("Querying Github for: $usr_name / $proj_name");
 			$data = GithubAPI::queryProjectData($usr_name, $proj_name);

 			$total_pulls = 0;
			$total_merged_pulls = 0;
			
			foreach($data->pulls as $pull) {
				$total_pulls++;
				if($pull->merged_at) {
					$total_merged_pulls++;
				}
			}
			if($total_pulls > 0) {
				$proj->pr_acceptance_rate = ($total_merged_pulls / $total_pulls) * 100;
			} else {
				$proj->pr_acceptance_ratec = -1;
			}	
			Makiavelo::puts("Saving project...");
			save_project($proj);
		}
	}
}
?>