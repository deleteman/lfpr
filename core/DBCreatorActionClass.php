<?php

class DBCreatorAction extends Action {

	public function execute($params) {
		Makiavelo::info("Creating Database...");
		$sql_folder_path = ROOT_PATH . Makiavelo::SQL_CREATE_TABLES_FOLDER;

		Makiavelo::puts("Creating database...");
		$conn = DBLayer::connect();
		$db_name = DBLayer::getDBName();
		$sql = "CREATE DATABASE `$db_name`";
		if(!mysql_query($sql, $conn)) {
			Makiavelo::info("ERROR creating db: " . mysql_error());
		}
		//We also have to create the migrations table
		$sql_migrations = "CREATE TABLE migrations ( migration INT PRIMARY KEY);";
		mysql_select_db($db_name);
		if(!mysql_query($sql_migrations, $conn)) {
			Makiavelo::info("ERROR creating migrations table:: " . mysql_error());
		}
		DBLayer::disconnect($conn);
	}
}

?>