<?php

abstract class Migration {

	abstract public function up();
	abstract public function down();

	/**
	Allows the dev to modify the structure of a table:
	Supported operations:

	- add_field
	- drop_field
	*/
	protected function alter_table($tname, $params) {
		global $__db_conn;
		foreach($params as $operation => $parms) {
			switch($operation) {
				case "add_field":
					$keys = array_keys($parms);
					$new_field = $keys[0];
					$type = $parms[$new_field];
					$sql = "ALTER TABLE $tname ADD COLUMN $new_field $type";
				break;
				case "drop_field":
					$new_field = $parms;
					$sql = "ALTER TABLE $tname drop column $new_field ";
				break;
				default:
				break;
			}
			Makiavelo::info("Altering table :: " . $sql);
			DBLayer::query($sql);

		}	
	}
}


?>