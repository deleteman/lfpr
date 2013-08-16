<?php

class UpdateProjectUrlTask {

	public function run() {
		Makiavelo::puts("Starting update of urls");
		$projects = list_project(null, null, "published =1");
 		foreach($projects as $proj) {
 			$proj_name = $proj->name;
 			$dev = $proj->owner();
 			$usr_name  = $dev->name;		

 			Makiavelo::puts("Querying Github for: $usr_name / $proj_name");
 			$data = GithubAPI::queryProjectData($usr_name, $proj_name);

 			if(!isset($data->message)) {
	 			$proj->url = isset($data->html_url) ? $data->html_url : $data->url;	
 			} else {
 				Makiavelo::puts("Project not found, unpublishing...");
 				$proj->published = 0;
 			}
 			
			Makiavelo::puts("Saving project...");
			save_project($proj);
		}
	}
}
?>