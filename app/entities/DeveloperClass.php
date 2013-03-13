<?php

class Developer extends MakiaveloEntity {
	private $id; //type: integer
private $created_at; //type: datetime
private $updated_at; //type: datetime
private $name; //type: string
private $avatar_url; //type: string
private $github_url; //type: string


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