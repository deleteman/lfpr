<?php

class MigrationGenerator {

	public function execute($params) {
		$migration_name = $params[0];
		$filename = Makiavelo::camel_to_underscore($migration_name);	
		$timestamp = time();

		$migration_fname = $timestamp."_".$filename.".php";
		Makiavelo::puts("Generating migration file...$migration_fname\n");
		$migration_folder = ROOT_PATH . "/" .Makiavelo::MIGRATIONS_FOLDER;
		if(!is_dir($migration_folder))  {
			Makiavelo::puts("Migrations folder not found, creating it...");
			mkdir($migration_folder);
		}
		$fp = @fopen( $migration_folder . "/" . $migration_fname, "w");
		if($fp) {
			$class_name = Makiavelo::underscore_to_camel($migration_name);
			fwrite($fp, "<?php \n class $class_name extends Migration {
			   public function up() {}  \n
			   public function down() {} \n
			}  \n?>");
			fclose($fp);
		}
	}

}



?>