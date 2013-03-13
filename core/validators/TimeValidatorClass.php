<?php

class TimeValidator extends Validator{

	public function validate($val) {
		$regExp = "/^([0-9]{1,2}):([0-9]{2})(?(::[0-9]{2}))$/";
		if(preg_match($regExp, $val) === 0) {
			if(class_exists("I18n")) {
				$err_msg = I18n::t("makiavelo.entities.errors.time");
			} else {
				$err_msg = " doesn't have a valid time format";
			}
			$this->setErrorMsg($err_msg);			
			return false;
		} else {
			return true;
		}
	}
}

?>