<?php

class Validator {
	protected $error_msg;

	public function setErrorMsg($txt) {
		$this->error_msg = $txt;
	}

	public function errorMsg() {
		return $this->error_msg;
	}
}

?>