<?php

 class WebSiteMessageController extends ApplicationController {

  public function newAction() {
    $entity = new WebSiteMessage();
    $this->render(array("entity" => $entity));
  }

  public function deleteAction() {
    delete_web_site_message($this->request->getParam("id"));
    $this->flash->success("Delete successfull!");
    $this->redirect_to(web_site_message_list_path());
  }

  public function editAction() {
    $tb = load_web_site_message($this->request->getParam("id"));

    $this->render(array("entity" => $tb));
  }

  public function showAction() {
    $id = $this->request->getParam("id");
    $ent = load_web_site_message($id);
    $this->render(array("entity" => $ent));
  }

  public function createAction() {
    $entity = new WebSiteMessage();
    $entity->load_from_array($this->request->getParam("web_site_message"));
    if(save_web_site_message($entity)) {
      $this->flash->setSuccess("Message sent! Thanks for getting in touch!");
      $this->redirect_to(home_root_path_path());
    } else {
      $this->flash->setError("Both fields are required, make sure you fill them! And the email must be  valid one.");
      $this->redirect_to(home_root_path_path());
    }
  }

  public function indexAction() {
    $entity_list = list_web_site_message();
    $this->render(array("entity_list" => $entity_list));
  }


 }


?>
