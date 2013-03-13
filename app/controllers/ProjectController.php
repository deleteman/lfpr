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
		$entity->load_from_array($this->request->getParam("project"));
		if(save_project($entity)) {
			$this->flash->success("New Project added!");
			$this->redirect_to(project_list_path());
		} else {
			$this->render(array("entity" => $entity), "new");
		}
	}

	public function indexAction() {
		$entity_list = list_project();
		$this->render(array("entity_list" => $entity_list));
	}


 }


?>