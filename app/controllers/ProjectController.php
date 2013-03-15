<?php

 class ProjectController extends ApplicationController {
 	private $per_page = 12;

 	public function newAction() {
		$entity = new Project();
		$this->render(array("entity" => $entity));
	}

	public function deleteAction() {
		delete_project($this->request->getParam("id"));
		$this->flash->success("Delete successfull!");
		$this->redirect_to(project_list_path());
	}

	public function editAction() {
		$tb = load_project($this->request->getParam("id"));

		$this->render(array("entity" => $tb));
	}

	public function showAction() {
		$id = $this->request->getParam("id");
		$ent = load_project($id);
		$this->render(array("project" => $ent));
	}


	public function createAction() {
		$entity = new Project();
		$proj = $this->request->getParam("project");

		if(!load_project_where("url = '".$proj['url']."'")) {
			$dev = load_developer_where('name = "' . $this->request->getParam("owner_name").'"');
			if($dev == null) { //Create the developer if it's not on our database already
				$dev = new Developer();
				$dev->name = $this->request->getParam("owner_name");
				save_developer($dev);
			}
			$proj['owner_id'] = $dev->id;
			$entity->load_from_array($proj);
			if(save_project($entity)) {
				//Create the first set of stats 
				$entity->saveInitStats();
				$this->flash->success("The project was added correctly, thanks!");
				$this->redirect_to(project_list_path());
			} else {
				$this->render(array("entity" => $entity), "new");
			}
		} else {
			$this->flash->error("This project has already been submited");
			$this->render(array("entity" => $entity), "new");

		}


		
	}

	public function indexAction() {
		$language = $this->request->getParam("language");
		$owner = $this->request->getParam("owner");
		$where = " 1 ";
		if($language != "" && $language != "All") {
			$where .= " and language = '" . $language ."'";
		}

		if($owner != "") {
			$dev = load_developer_where("name like '%".$owner."%'");
			$where .= " and owner_id = " . $dev->id;
		}

		$sort = str_replace("_", " ", $this->request->getParam("sort"));

		$curr_page = intVal($this->request->getParam("p"));
		$total = count_projects($where);
		$init = $curr_page * $this->per_page;
		$pages = ceil($total / $this->per_page);

		$entity_list = list_project($sort, $init . "," . $this->per_page, $where);
		$this->render(array(
						"entity_list" => $entity_list, 
						"pagination" => array(
											"current_page" => $curr_page,
											"total_pages" => $pages,
											"total_results" => $total),
						"search_crit" => array(
												"lang" => $language, 
												"owner" => $owner,
												"sort" => $this->request->getParam("sort"))));
	}

	private function queryGithub($usr, $repo) {
		return GithubAPI::queryProjectData($usr, $repo);
	}

	public function grab_dataAction() {
		$url = $this->request->getParam("url");
		$url_parts = explode("/", $url);

		$max = count($url_parts) - 1;

		$data = $this->queryGithub($url_parts[$max -1], $url_parts[$max]);
		Makiavelo::info("Data returned: " . print_r($data, true));

		if($data->message) {
			$json_array = $data;
		} else {

		$json_array = array(
						"name" => $data->{'name'},
						"raw" => print_r($data, true),
						"description" => $data->{'description'},
						"owner_name" => $data->{'owner'}->{'login'},
						"stars" => $data->{'watchers'},
						"forks" => $data->{'forks'},
						"last_update" => $data->{'updated_at'},
						"language" => $data->{'language'});
		}

		$this->render(array("json" => json_encode($json_array)));

	}

 }


?>