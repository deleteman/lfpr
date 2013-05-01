<?php

 class IssueController extends ApplicationController {

 	public function newAction() {
		$entity = new Issue();
		$this->render(array("entity" => $entity));
	}

	public function deleteAction() {
		
	}

	public function editAction() {

	}

	public function showAction() {
		$id = $this->request->getParam("id");
		$ent = load_issue($id);
		$this->render(array("entity" => $ent));
	}

	public function createAction() {

	}

	public function indexAction() {

	}

 }
?>