<?php

class GithubAPI {
	private static $CLIENT_ID = "";
	private static $SECRET = "";
	private static $TOKEN = null;

	private static function sendRequest($url, $method = "GET", $params = "", $http_creds = array()) {
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		if($method != "GET") {
			switch($method) {
				case "POST":
					curl_setopt($ch, CURLOPT_POST, 1);
				break;
			}
		}

		if(count($http_creds) > 0) {
			curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
			curl_setopt($ch, CURLOPT_USERPWD, implode(":", $http_creds));
		}

		if($params != "" && $method == "POST") {
			curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		}

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

	private static function requestAuth() {
		$url = "https://api.github.com/authorizations";
		$params = '{"client_id": "'.self::$CLIENT_ID.'", "client_secret": "'.self::$SECRET.'"}';
		Makiavelo::info("Requesting auth token to Github :: " . $params);
		$response = self::sendRequest($url, "POST", $params, array("xxxx", "yyyy"));
		Makiavelo::info("Response obtained :: " . print_r($response, true));
		return $response->token;
	}

	public static function queryProjectData($usr, $repo) {

		if(self::$TOKEN == null) {
			self::$TOKEN = self::requestAuth();
		}
		$repo = str_replace(".git", "", $repo);
		$repo_url = "https://api.github.com/repos/".$usr."/".$repo;
		Makiavelo::info("Querying URL: " . $repo_url);
		$data = self::sendRequest($repo_url);	

		$commits_url = $repo_url . "/commits?access_token=" . self::$TOKEN;
		$commits_data = self::sendRequest($commits_url);
		$data->commits = $commits_data;

		$pull_url = $repo_url . "/pulls?access_token=" . self::$TOKEN;
		$pull_data = self::sendRequest($pull_url);

		$pull_url = $repo_url . "/pulls?state=closed?access_token=" . self::$TOKEN;
		$closed_pulls = self::sendRequest($pull_url);
		if(is_array($closed_pulls)) {
			$pull_data = array_merge($closed_pulls, $pull_data);
		}
		$data->pulls = $pull_data;

		return $data;
	}
}

?>