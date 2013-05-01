<?php

class Issue extends MakiaveloEntity {

  private $id; //type: integers
  private $title; //type: string
  private $body; //type: string
  private $create_at; //type: datetime
  private $updated_at; //type: datetime
  private $url; //type: string
  private $number; //type: integer
  private $project_id; //type: integer

  static public $validations = array();

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
