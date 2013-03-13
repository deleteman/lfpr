<?php

 class SuscriptorController extends ApplicationController {

	public function deleteAction() {
		delete_suscriptor($this->request->getParam("id"));
		$this->flash->success("Delete successfull!");
		$this->redirect_to(suscriptor_list_path());
	}



	public function createAction() {
		$entity = new Suscriptor();
		$entity->load_from_array($this->request->getParam("suscriptor"));
		$same_email = load_suscriptor_where("email = '".$entity->email."'");
		if($same_email != null) {
			$this->flash->error("Dude! Chill, you're already suscribed, just relax and browse the list of repos...");
		} else {
			if(save_suscriptor($entity)) {
				$this->flash->success("Thanks for suscribing! Stay tuned!");
			} else {
				$this->flash->error("Please enter a valid email address and try again");
			}
		}
		$this->redirect_to(home_root_path_path());
	}

	public function indexAction() {
		$entity_list = list_suscriptor();
		$this->render(array("entity_list" => $entity_list));
	}


 }


?>