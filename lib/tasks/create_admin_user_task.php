<?php

class CreateAdminUserTask {

	public function run() {
		$usr = new User();
		$usr->username = "admin";
		$usr->password = "admin";
		$usr->role = "admin";
		save_user($usr);
	}
}


?>