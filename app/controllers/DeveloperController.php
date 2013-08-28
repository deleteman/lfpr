<?php

 class DeveloperController extends ApplicationController {

 	public function newAction() {
		$entity = new Developer();
		$this->render(array("entity" => $entity));
	}

	public function getStatsAction() {
		$this->layout = null;
		$username = $this->request->getParam("usrname");
		$dev = load_developer_where("name = '" . $username . "'");
		$stats = "";
		if(!$dev) {
			$stats = array("error" => "User not found");
			$this->statusCode = Makiavelo::RESPONSE_CODE_NOT_FOUND;
		} else {
			$stats = $dev->gatherStats();
		}
		$this->render(array("stats" => $stats));
	}

	public function deleteAction() {
		delete_developer($this->request->getParam("id"));
		$this->flash->success("Delete successfull!");
		$this->redirect_to(developer_list_path());
	}

	public function editAction() {
		$tb = load_developer($this->request->getParam("id"));

		$this->render(array("entity" => $tb));
	}

	public function showAction() {
		$id = $this->request->getParam("id");
		$ent = load_developer($id);
		$this->render(array("entity" => $ent));
	}

	public function createAction() {
		$entity = new Developer();
		$entity->load_from_array($this->request->getParam("developer"));
		if(save_developer($entity)) {
			$this->flash->success("New Developer added!");
			$this->redirect_to(developer_list_path());
		} else {
			$this->render(array("entity" => $entity), "new");
		}
	}

	public function indexAction() {
		$entity_list = list_developer();
		$this->render(array("entity_list" => $entity_list));
	}


 }


?>