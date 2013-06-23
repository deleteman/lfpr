<?php

class User extends MakiaveloEntity {
	private $id; //type: integer
private $created_at; //type: datetime
private $updated_at; //type: datetime
private $username; //type: string
private $password; //type: string
private $role; //type: string


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