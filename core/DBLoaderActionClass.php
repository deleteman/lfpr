<?php


class DBLoaderAction extends Action{

	public function execute($params) {
		$this->loadEntities();
	}

	private function loadEntities() {
		$sql_folder_path = ROOT_PATH . Makiavelo::SQL_CREATE_TABLES_FOLDER;

		$d = dir($sql_folder_path);
		while(($item = $d->read()) != false) {
			if($item != "create_db.sql" && substr($item, 0, 1) != ".") {
				$file_path = $sql_folder_path . "/" . $item;
				$fp = fopen($file_path, "r");
				if($fp) {
					Makiavelo::puts("Loading entity: $item ...");
					$conn = DBLayer::connect();
					$sql = fread($fp, filesize($file_path));
					fclose($fp);
					$res = mysql_query($sql, $conn);
					if(!$res && mysql_errno($conn) == 1050) {
						Makiavelo::puts("---- Entity already loaded, ignoring");
					}
					DBLayer::disconnect($conn);
				}
			}
		}
	}
}

?>