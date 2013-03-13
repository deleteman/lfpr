<?php

 class ProjectController extends ApplicationController {

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
		$this->render(array("entity" => $ent));
	}

	public function createAction() {
		$entity = new Project();
		$proj = $this->request->getParam("project");
		$dev = load_developer_where('name = "' . $this->request->getParam("owner_name").'"');
		if($dev == null) {
			$dev = new Developer();
			$dev->name = $this->request->getParam("owner_name");
			save_developer($dev);
		}
		$proj['owner_id'] = $dev->id;
		$entity->load_from_array($proj);
		if(save_project($entity)) {
			$this->flash->success("The project was added correctly, thanks!");
			$this->redirect_to(project_list_path());
		} else {
			$this->render(array("entity" => $entity), "new");
		}
	}

	public function indexAction() {
		$entity_list = list_project();
		$this->render(array("entity_list" => $entity_list));
	}

	private function queryGithub($usr, $repo) {

		Makiavelo::debug("Querying URL: https://api.github.com/repos/".$usr."/".$repo );
		$repo = str_replace(".git", "", $repo);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/".$usr."/".$repo);
		curl_setopt($ch, CURLOPT_USERPWD, "deleteman:doglio23");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		return json_decode(curl_exec($ch));
	}

	public function grab_dataAction() {
		$url = $this->request->getParam("url");
		$url_parts = explode("/", $url);

		$max = count($url_parts) - 1;

		$data = $this->queryGithub($url_parts[$max -1], $url_parts[$max]);

		if($data->message) {
			$json_array = $data;
		} else {

		$json_array = array(
						"name" => $data->{'name'},
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