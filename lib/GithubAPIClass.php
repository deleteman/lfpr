<?php

class GithubAPI {

	public static function queryProjectData($usr, $repo) {

		Makiavelo::info("Querying URL: https://api.github.com/repos/".$usr."/".$repo );
		$repo = str_replace(".git", "", $repo);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://api.github.com/repos/".$usr."/".$repo);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$value = curl_exec($ch);
		if(!$value) {
			$error = curl_error($ch);
			Makiavelo::info("CURL ERROR :: " . $error);
			return json_decode('{"message": "'.$error.'"}');
		} else {
			return json_decode($value);
		}
	}
}

?>