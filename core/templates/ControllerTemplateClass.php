<?php

 class [NAME]Controller extends ApplicationController {

 	public function newAction() {
		$entity = new [NAME]();
		$this->render(array("entity" => $entity));
	}

	public function deleteAction() {
		delete_[UC_NAME]($this->request->getParam("id"));
		$this->flash->success("Delete successfull!");
		$this->redirect_to([UC_NAME]_list_path());
	}

	public function editAction() {
		$tb = load_[UC_NAME]($this->request->getParam("id"));

		$this->render(array("entity" => $tb));
	}

	public function showAction() {
		$id = $this->request->getParam("id");
		$ent = load_[UC_NAME]($id);
		$this->render(array("entity" => $ent));
	}

	public function createAction() {
		$entity = new [NAME]();
		$entity->load_from_array($this->request->getParam("[UC_NAME]"));
		if(save_[UC_NAME]($entity)) {
			$this->flash->success("New [NAME] added!");
			$this->redirect_to([UC_NAME]_list_path());
		} else {
			$this->render(array("entity" => $entity), "new");
		}
	}

	public function indexAction() {
		$entity_list = list_[UC_NAME]();
		$this->render(array("entity_list" => $entity_list));
	}


 }


?>