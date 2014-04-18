<?php

 class DeveloperController extends ApplicationController {

  private function getCurrentDev() {
    $username = $this->request->getParam("name");
    $dev = load_developer_where("name = '" . mysql_real_escape_string($username) . "'");
    return $dev;
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

 }


?>
