<?php

class DBMigratorRollbackAction extends DBMigratorAction {

	public function execute($params) {
		Makiavelo::puts("Rolling back database state...");
		$this->rollback();
	}
}


?>