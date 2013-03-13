<?php

class EmailValidator extends Validator{

	public function validate($val) {
		if(filter_var($val, FILTER_VALIDATE_EMAIL) === false) {
			if(class_exists("I18n")) {
				$err_msg = I18n::t("makiavelo.entities.errors.email");
			} else {
				$err_msg = " doesn't have a valid e-mail format";
			}
			$this->setErrorMsg($err_msg);			
			return false;
		} else {
			return true;
		}
	}
}

?>