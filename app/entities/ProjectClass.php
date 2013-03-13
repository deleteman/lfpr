<?php

class Project extends MakiaveloEntity {
	private $id; //type: integer
private $created_at; //type: datetime
private $updated_at; //type: datetime
private $name; //type: string
private $url; //type: string
private $description; //type: text
private $owner_id; //type: integer
private $stars; //type: integer
private $forks; //type: integer
private $last_update; //type: datetime
private $language;


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