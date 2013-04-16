<?php

class ProjectCommit extends MakiaveloEntity {
	private $id; //type: integer
private $created_at; //type: datetime
private $updated_at; //type: datetime
private $project_id; //type: integer
private $committer; //type: string
private $commit_message; //type: text
private $sha; //type: string
private $commit_date; //type: date


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