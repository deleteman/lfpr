<?php

class IntegerValidator extends Validator {

	public function validate($val) {
		Makiavelo::info("Validating if '$val' is numeric...");
		if(!is_numeric($val)) {
			if(class_exists("I18n")) {
				$err_msg = I18n::t("makiavelo.entities.errors.integer");
			} else {
				$err_msg = " must be an integer";
			}
			$this->setErrorMsg($err_msg);
			return false;
		}

		return true;
	}
}

?>