<?php

class SecurityLayer {
	private $roles;


	public function __construct() {
		global $__SECURITY;
		$this->roles = array_flip($__SECURITY['roles']);
	}

	public function currentRole() {
		if(isset($_SESSION['makiavelo'])) {
			if(isset($_SESSION['makiavelo']['current_session'])) {
				if(isset($_SESSION['makiavelo']['current_session']['role'])) {
					return $_SESSION['makiavelo']['current_session']['role'] ;
				}
			}
		}
		return $this->getLowestRole();
	}


	public function currentUser() {
		if(isset($_SESSION['makiavelo'])) {
			if(isset($_SESSION['makiavelo']['current_session'])) {
				if(isset($_SESSION['makiavelo']['current_session']['user'])) {
					return $_SESSION['makiavelo']['current_session']['user'] ;
				}
			}
		}
		return null;
	}

	public function logInUser($usr) {
		if(!isset($_SESSION['makiavelo']))	 {
			$_SESSION['makiavelo'] = array();
		}

		if(!isset($_SESSION['makiavelo']['current_session'])) {
			$_SESSION['makiavelo']['current_session'] = array();
		}

		$_SESSION['makiavelo']['current_session']['user'] = $usr;
		$_SESSION['makiavelo']['current_session']['role'] = ($usr->role) ? $usr->role : "default";

	}

	public function logOutUser() {
		unset($_SESSION['makiavelo']['current_session']['user']);
		unset($_SESSION['makiavelo']['current_session']['role']);
		setcookie(session_id(), "");
	}

	public function isUserLoggedIn() {
		Makiavelo::info(print_r($_SESSION, true));
		if(isset($_SESSION['makiavelo'])) {
			if(isset($_SESSION['makiavelo']['current_session'])) {
				if(isset($_SESSION['makiavelo']['current_session']['role'])) {
					Makiavelo::info("USER IS LOGGED IN!");
					return true;
				}
			}
		}
					Makiavelo::info("USER NOT LOGGED IN!");
		return false;
	}

	public function isUserAllowed($role) {
		Makiavelo::info("Checking if user can access url...");
		Makiavelo::info("User role: " . $this->currentRole());
		Makiavelo::info("Route minimun role: " . $role);
		Makiavelo::info("List of roles: " . print_r($this->roles, true));
		if($role == "" || $this->currentRole() == "") {
			return true;
		}
		if(!$this->isUserLoggedIn()) {
			if ($this->roles[$role] > 0) {
				return false;
			} else {
				return true;
			}
		}

		if($this->isUserLoggedIn()) {
			return ($this->roles[$this->currentRole()] >= $this->roles[$role]);
		}
	}

	public function getLowestRole() {
		$roles = array_keys($this->roles);
		return $roles[0];
	}

}


?>