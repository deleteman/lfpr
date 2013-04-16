<?php

 class ProjectCommitController extends ApplicationController {

 	public function newAction() {
		$entity = new ProjectCommit();
		$this->render(array("entity" => $entity));
	}

	public function deleteAction() {
		delete_project_commit($this->request->getParam("id"));
		$this->flash->success("Delete successfull!");
		$this->redirect_to(project_commit_list_path());
	}

	public function editAction() {
		$tb = load_project_commit($this->request->getParam("id"));

		$this->render(array("entity" => $tb));
	}

	public function showAction() {
		$id = $this->request->getParam("id");
		$ent = load_project_commit($id);
		$this->render(array("entity" => $ent));
	}

	public function createAction() {
		$entity = new ProjectCommit();
		$entity->load_from_array($this->request->getParam("project_commit"));
		if(save_project_commit($entity)) {
			$this->flash->success("New ProjectCommit added!");
			$this->redirect_to(project_commit_list_path());
		} else {
			$this->render(array("entity" => $entity), "new");
		}
	}

	public function indexAction() {
		$entity_list = list_project_commit();
		$this->render(array("entity_list" => $entity_list));
	}


 }


?>