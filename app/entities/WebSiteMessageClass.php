<?php

class WebSiteMessage extends MakiaveloEntity {
  private $id; //type: integer
  private $created_at; //type: datetime
  private $updated_at; //type: datetime
  private $email; //type: string
  private $body; //type: text


  static public $validations = array("email"=> array('presence','email'), "body" => array("presence"));

  public function __set($name, $val) {
    $this->$name = $val;
  }

  public function __get($name) {
    if(isset($this->$name)) {
      return $this->$name;
    } else {
      return null;
    }
  }
}
