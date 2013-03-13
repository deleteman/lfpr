<?php

class PresenceValidator extends Validator{

	public function validate($val) {
		if($val == "") {
			if(class_exists("I18n")) {
				$err_msg = I18n::t("makiavelo.entities.errors.blank");
			} else {
				$err_msg = " can't be blank";
			}
			$this->setErrorMsg($err_msg);
			return false;
		} else {
			return true;
		}
	}
}


?>