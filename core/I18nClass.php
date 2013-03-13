<?php

function t($key) {
	return I18n::t($key);
}

function l($key) {
	return I18n::l($key);
}

class I18nCantFindLocaleFiles extends Exception {

}

class I18n {

	static private $locale = "en"; //by default we use "en"
	const LOCALES_FOLDER = "/config/locales/";
	static private $date_format = "d/m/Y";


	static public function config($options) {
		I18n::$locale = $options['locale'];

		I18n::loadLocaleFiles();
	}

	static public function loadLocaleFiles() {
		$folder = ROOT_PATH . I18n::LOCALES_FOLDER . "/" . I18n::$locale;

		Makiavelo::info("Loading locale information...");
		$d = @dir($folder);
		if($d === false) {
			Makiavelo::info("**** ERROOR ***");
			Makiavelo::info("Can't open locales folder: $folder");
			throw new I18nCantFindLocaleFiles("Can't open locales folder: $folder");
		} else {
			$parser = new YAMLParser();	
			$full_data = array();
			while(false !== ($entry = $d->read())) {
				if($entry[0] != ".") {
					Makiavelo::info("-- Loading file: " . ($folder . "/" . $entry));
					$locale_data = $parser->parsePath($folder . "/" . $entry);
					$full_data = array_merge($full_data, $locale_data[I18n::$locale]);
					Makiavelo::info("-- Elements loaded: " . print_r($full_data, true));
				}
			}
			//Makiavelo::info("Translation data : " . print_r($full_data, true));
			I18n::setLocaleData($full_data);
		}
	}

	static public function setLocaleData($d) {
		$_SESSION['I18n'][I18n::$locale] = $d;
	}

	static public function translate($key) {
		$keys = explode(".", $key);
		$current = $_SESSION['I18n'][I18n::$locale];
		Makiavelo::info("session insde translate method: " . print_r($_SESSION['I18n'], true));
		if(!isset($_SESSION['I18n'][I18n::$locale])) {
			I18n::loadLocaleFiles();
		}
		foreach($keys as $k) {
			if(!isset($current[$k])) {
				$current = "missing.transaltion.for.$key";
				break;
			} else {
				$current = $current[$k];
			}
		}
		Makiavelo::info("Translating: " . $key . " - translation found: " . $current);
		return $current;
	}

	static public function localize($datetime) {
		Makiavelo::info("---------------------------------------");
		Makiavelo::info("Localizing the following date: $datetime");
		$dtime = new DateTime($datetime);
		$res = $dtime->format(I18n::$date_format);
		Makiavelo::info("Result:: $res");
		Makiavelo::info("---------------------------------------");
		return $res;
		/*
		if(trim($datetime) == "") {
			return;
		}
		$date_time = explode(" ", $datetime);
		$date = trim($date_time[0]);
		$time = null;
		if(isset($date_time[1])) {
			$time = trim($date_time[1]);
			$time = explode(":", $time);
		}
		$date = explode("-", $date);

		$current_format = I18n::$date_format;
		$current_format = str_replace("%m", $date[1], $current_format);
		$current_format = str_replace("%d", $date[2], $current_format);
		$current_format = str_replace("%y", $date[0], $current_format);

		if($time != null) {
			$current_format = str_replace("%H", $time[0], $current_format);
			$current_format = str_replace("%i", $time[1], $current_format);
			$current_format = str_replace("%s", $time[2], $current_format);
		}
		
		return $current_format;
		*/
	}

	//abbreviation
	static public function t($key) {
		return I18n::translate($key);
	}
	static public function l($datetime) {
		return I18n::localize($datetime);
	}
}


?>