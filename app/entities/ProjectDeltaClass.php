<?php

class ProjectDelta extends MakiaveloEntity {
	private $id; //type: integer
private $created_at; //type: datetime
private $updated_at; //type: datetime
private $sample_date; //type: datetime
private $stars; //type: integer
private $delta_stars; //type: integer
private $forks; //type: integer
private $delta_forks; //type: integer
private $commits_count;
private $new_pulls;
private $closed_pulls;
private $merged_pulls;


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