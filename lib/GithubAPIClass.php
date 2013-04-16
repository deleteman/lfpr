<?php

class GithubAPI {
	private static $CLIENT_ID = "6e3725492d7fffb516a1";
	private static $SECRET = "19fe6d8cd8c2a573b302ae96f46d46453cf9b26f";
	private static $TOKEN = null;
	private static $LOGIN_URL = "https://github.com/login/oauth/authorize";


	public static function login_url() {
		return self::$LOGIN_URL."?client_id=" . self::$CLIENT_ID . "&redirect_uri=http://www.lookingforpullrequests.com/github_cb/login";
	}

	private static function sendRequest($url, $method = "GET", $params = "", $http_creds = array(), $raw_response = false) {
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
		Makiavelo::info("Inside the sendRequest methdo:: " . print_r($value, true));
		if(!$value) {
			$error = curl_error($ch);
			Makiavelo::info("CURL ERROR :: " . $error);
			return json_decode('{"message": "'.$error.'"}');
		} else {
			if($raw_response) {
				return $value;
			} else {
				return json_decode($value);
			}
		}
	}

	public static function getUserRepos($user) {
		$url = "https://api.github.com/users/$user/repos?access_token=" . self::$TOKEN;
		$repo_list = self::sendRequest($url);
		Makiavelo::info("=== Getting list of repos :: " . print_r($repo_list, true));
		$projects = array();
		$dev = load_developer_where("name = '" . $user . "'");
		foreach($repo_list as $repo) {
			$proj = load_project_where("owner_id = " . $dev->id . " and name ='".$repo->name."'");
			if($proj == null) {
				$proj = new Project();
				$proj->name = $repo->name;
				$proj->url  = $repo->url;
				$proj->owner_id = $dev->id;
				$proj->description = $repo->description;
				$proj->stars = $repo->watchers;
				$proj->forks = $repo->forks;
				$proj->language = $repo->language;
				$proj->published = 0;
				if(save_project($proj)) {
					$projects[] = $proj;
				}
			} else {
				$projects[] = $proj;
			}
		}
		return $projects;
	}

	public static function requestWebAuth($code) {
		$url = "https://github.com/login/oauth/access_token";
		$params = 'client_id='.self::$CLIENT_ID.'&client_secret='.self::$SECRET.'&code='.$code;

		Makiavelo::info("Requesting WEB auth token to Github :: " . $params);
		$response = self::sendRequest($url, "POST", $params, array("deleteman", "doglio23"), true);
		$response = explode("&", $response);
		$response = $response[0];
		$response = explode("=", $response);
		$response_token = $response[1];
		Makiavelo::info("Response obtained :: " . print_r($response, true));
		self::$TOKEN = $response_token;
		return self::$TOKEN;
	}

	public static function getCurrentUser() {
		$url = "https://api.github.com/user?access_token=" . self::$TOKEN;
		$usr_data = self::sendRequest($url);
		Makiavelo::info("=== Getting current user:: " . print_r($usr_data, true));
		return array("username" => $usr_data->login, "avatar_url" => $usr_data->avatar_url);
	}

	private static function requestAuth() {
		$url = "https://api.github.com/authorizations";
		$params = '{"client_id": "'.self::$CLIENT_ID.'", "client_secret": "'.self::$SECRET.'"}';
		Makiavelo::info("Requesting auth token to Github :: " . $params);
		$response = self::sendRequest($url, "POST", $params, array("deleteman", "doglio23"));
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
