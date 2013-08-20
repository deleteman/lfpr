<?php

class Faq extends MakiaveloEntity {
	private $id; //type: integer
private $created_at; //type: datetime
private $updated_at; //type: datetime
private $project_id; //type: integer
private $question; //type: string
private $answer; //type: text
private $order; //type: integer


	static public $validations = array( "question" => array("presence"),
										"answer" => array("presence"));
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