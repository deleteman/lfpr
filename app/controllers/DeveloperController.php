<?php

 class DeveloperController extends ApplicationController {

  private function getCurrentDev() {
    $username = $this->request->getParam("name");
    $dev = load_developer_where("name = '" . mysql_real_escape_string($username) . "'");
    return $dev;
  }

  public function newAction() {
    $entity = new Developer();
    $this->render(array("entity" => $entity));
  }

  public function getStatsAction() {
    $this->layout = null;
    $dev = $this->getCurrentDev();
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
    $ent = $this->getCurrentDev();
    if(!$ent) {
      $this->statusCode = Makiavelo::RESPONSE_CODE_NOT_FOUND;
      $this->flash->setError("Wrong name dude, the developer you're looking for does not exist.");
      $this->redirect_to(home_root_path_path());
    } else {
      $this->render(array("entity" => $ent));
    }
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
