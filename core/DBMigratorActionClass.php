<?php

class DBMigratorAction extends Action {

	public function execute($params) {
		$this->runMigrations();
	}

	protected function rollback() {
		$latest_migration = $this->getLatestMigration();
		Makiavelo::puts("Latest migration ran: " . $latest_migration);

		$m_folder = ROOT_PATH . "/" . Makiavelo::MIGRATIONS_FOLDER;
		$d = dir($m_folder);
		while( ($entry = $d->read()) !== false) {
			if($entry[0] != ".") {
				$name_parts = explode("_", $entry);
				if(intval($name_parts[0]) == intval($latest_migration) ) {
					$this->rollbackMigration($entry);	
				}
						
			}
		}
	}

	private function runMigrations() {
		$latest_migration = $this->getLatestMigration();
		Makiavelo::puts("Latest migration ran: " . $latest_migration);
		$last_migration = "";
		$m_folder = ROOT_PATH . "/" . Makiavelo::MIGRATIONS_FOLDER;
		$d = dir($m_folder);
		while( ($entry = $d->read()) !== false) {
			if($entry[0] != ".") {
				$name_parts = explode("_", $entry);
				if(intval($name_parts[0]) > intval($latest_migration) ) {
					$this->runMigration($entry);	
					$last_migration = $name_parts[0];
				}
						
			}
		}
	}

	private function getLatestMigration() {
		$sql = "SELECT migration from migrations order by migration desc limit 1";
		$res = DBLayer::select($sql);
		$r = mysql_fetch_assoc($res);
		if($r['migration'] == "") {
			return 0;
		} else {
			return $r['migration'];
		}
	}

	private function runMigration($f_name) {
		include(ROOT_PATH . "/" . Makiavelo::MIGRATIONS_FOLDER . "/" . $f_name );
		$parts = explode("_", $f_name);
		$migration_number = $parts[0];
		unset($parts[0]);
		$className = Makiavelo::underscore_to_camel(str_replace(".php", "", implode("_", $parts)));
		$migration = new $className;
		Makiavelo::puts("Running migration $f_name ...");
		$migration->up();

		$this->updateMigrationsTable($migration_number);
	}

	private function rollbackMigration($f_name) {
		include(ROOT_PATH . "/" . Makiavelo::MIGRATIONS_FOLDER . "/" . $f_name );
		$parts = explode("_", $f_name);
		$migration_number = $parts[0];
		unset($parts[0]);
		$className = Makiavelo::underscore_to_camel(str_replace(".php", "", implode("_", $parts)));
		$migration = new $className;
		Makiavelo::puts("Rolling back migration $f_name ...");
		$migration->down();

		$this->removeMigrationFromTable($migration_number);
	}

	private function removeMigrationFromTable($m_number) {
		$sql = "DELETE FROM migrations WHERE migration = $m_number";	
		Makiavelo::info("Updating migrations table:: $sql");
		DBLayer::query($sql);
	}
	
	private function updateMigrationsTable($migration_number) {
		$sql = "INSERT INTO migrations (migration) values (".$migration_number.")";
		Makiavelo::info("Updating migrations table:: $sql");
		DBLayer::query($sql);
	}
}


?>