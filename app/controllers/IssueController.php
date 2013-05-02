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
		$page = $this->request->getParam("p");
		$project_id = $this->request->getParam("pid");
		$per_page = 5;
		$init = $page * $per_page;
		$items = list_issue("num desc", $init . "," . $per_page, "project_id = " . $project_id);
		$total_results = count_issue("project_id = " . $project_id);
		$total_pages = ceil($total_results / $per_page);
		$this->render(array("issues" => $items, 
							"pid" => $project_id,
							"pagination" => array("total_pages" => $total_pages,
												  "total_results" => $total_results,
												  "current_page" => $page)));
	}

 }
?>