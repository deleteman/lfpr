<?php

 class FaqController extends ApplicationController {

 	public function newAction() {
		$entity = new Faq();
		$this->render(array("entity" => $entity));
	}

	public function deleteAction() {
		delete_faq($this->request->getParam("id"));
		$this->flash->success("Delete successfull!");
		$this->redirect_to(faq_list_path());
	}

	public function editAction() {
		$tb = load_faq($this->request->getParam("id"));

		$this->render(array("entity" => $tb));
	}

	public function showAction() {
		$id = $this->request->getParam("id");
		$ent = load_faq($id);
		$this->render(array("entity" => $ent));
	}

	public function createAction() {
		$entity = new Faq();
		$entity->load_from_array($this->request->getParam("faq"));
		if(save_faq($entity)) {
			$this->flash->setSuccess("New Question added successfully!");
			$this->redirect_to(project_show_path($entity->project_id));
		} else {
			$this->flash->setError("There was an error saving the question. Both fields are required.");
			//$this->render(array("entity" => $entity), "new");
			$this->redirect_to(project_show_path($entity->project_id));
		}
	}

	public function indexAction() {
		$entity_list = list_faq();
		$this->render(array("entity_list" => $entity_list));
	}


 }


?>