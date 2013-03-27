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

	public function owner() {
		return load_developer($this->owner_id);
	}

	public function url() {
		$url = $this->url;
		$url = str_replace("https://", "", $url);
		$url = str_replace("http://", "", $url);
		return "http://" . $url;
	}

	public function language() {
		return ($this->language != "") ? $this->language : "N/A";
	}

	public function saveInitStats() {
		Makiavelo::info("===== Saving initial deltas");
		$pd = new ProjectDelta();
		$pd->forks = $this->forks;
		$pd->delta_forks = 0;

		$pd->stars = $this->stars;
		$pd->delta_stars = 0;

		$pd->project_id = $this->id;
		$pd->sample_date = date("Y-m-d H:i:s");

		if(save_project_delta($pd)) {
			Makiavelo::info("===== Delta saved! ");
		} else {
			Makiavelo::info("===== ERROR saving delta");
		}
	}

	public function getStats($init = null, $end = null) {
		$deltas = list_project_delta("sample_date",null, "project_id = " .$this->id);
		return $deltas;	
	}
}