<?php

class CRUDGenerator {
	private $en_name;
	private $en_attributes;
	private $validations_str;
	//These will be added to all entities if they're not already on the list
	private $default_entity_attributes = array(
		"id" => "integer",
		"created_at" => "datetime",
		"updated_at" => "datetime"
		);
	public function execute($params) {
			$parser = new YAMLParser();	
			$yaml_configuration = $parser->parse($params[0]);
			print_r($yaml_configuration);
			if(!isset($yaml_configuration['crud'])) {
				Makiavelo::info("**** ERROR ****");
				Makiavelo::info("CRUD key not found on yaml file, cancelling generation...");
			} else {
				$crud_info = $yaml_configuration['crud'];
				$this->en_name = $crud_info['entity']['name'];
				if(isset($crud_info['entity']['validations'])) {
					$this->generateValidations($crud_info['entity']['validations']);
				}
				$this->generateEntity($crud_info['entity']);
				$this->generateViews($crud_info['entity']);
				$this->generateSQL();
				$this->generateRoutes();

				$contGen = new ControllerGenerator();
				$contGen->execute(array($this->en_name, "no-views"));
			}
	}

	public function generateValidations($validations) {
		Makiavelo::info("Generating validations...");
		$this->validations_str = "";
		foreach($validations as $attr => $validation_types) {
			Makiavelo::info("- Validations for {$attr} - " . print_r($validation_types, true));
			$this->validations_str .= '"'.$attr .'"'. "=> array('" . implode("','", $validation_types) . "'),\n";
		}

	}

	public function generateRoutes() {
		$r_gen = new RoutesGenerator($this->en_name);
		$r_gen->generateCRUDRoutes();
	}

	public function generateSQL() {

		Makiavelo::info("Generating SQL statements...");
		$sql_gen = new SQLGenerator($this->en_name, $this->en_attributes);

		$create_database_sql = $sql_gen->create_database_sql();
		$create_db_file = ROOT_PATH . Makiavelo::SQL_CREATE_TABLES_FOLDER . "/create_db.sql"; 
		if(!file_exists($create_db_file)) {
			$fp = fopen($create_db_file, "w");
			if($fp) {
				Makiavelo::info("Create db saved");
				fwrite($fp, $create_database_sql);
				fclose($fp);
			}
		}
		
		$create_sql = $sql_gen->create_sql();

		$create_table_file = ROOT_PATH . Makiavelo::SQL_CREATE_TABLES_FOLDER . "/" . $this->en_name . ".sql";
		if(!file_exists($create_table_file)) {
			$fp = fopen($create_table_file, "w");
			if($fp) {
				Makiavelo::info("Create table saved");
				fwrite($fp, $create_sql);
				fclose($fp);
			}
		}
		

		$crud_sql = $sql_gen->crud_sql();
		Makiavelo::info("Generating SQL CRUD: ");
		Makiavelo::info(print_r($crud_sql, true));
		
		$template_helper_file = ROOT_PATH . Makiavelo::TEMPLATES_FOLDER . "/sql_helper_templates.php";
		$fp = fopen($template_helper_file, "r");
		if($fp) {
			$template_code = fread($fp, filesize($template_helper_file));
			$code = str_replace("[NAME]", $this->en_name, $template_code);
			$code = str_replace("[UC_NAME]", Makiavelo::camel_to_underscore($this->en_name), $code);
			foreach ($crud_sql as $action => $query) {
				$code = str_replace("[".strtoupper($action)."_SQL]", $query, $code);
			}

			fclose($fp);
			//Saving template
			$helper_file = ROOT_PATH . Makiavelo::SQL_HELPERS_FOLDER . "/" . $this->en_name . ".php";
			if(!file_exists($helper_file)) {
				$fp = fopen($helper_file, "w");
				if($fp) {
					Makiavelo::info("Helper sql file saved");
					fwrite($fp, $code);
					fclose($fp);
				}
			}
			
		}
		Makiavelo::info("CRUD SQL created: " . print_r($crud_sql, true));
	}

	public function generateEntity($data) {
		Makiavelo::debug("Generating entity '" . $data['name']  . "'...",Makiavelo::DEBUG_LEVEL_INFO);
		$en_name = $this->en_name;
		$this->en_attributes = array_merge($this->default_entity_attributes, $data['fields']);

		//Grab the template for the class and replace the fields
		$entity_template = ROOT_PATH . Makiavelo::TEMPLATES_FOLDER . "EntityTemplateClass.php";
		Makiavelo::debug("Opening entity template: $entity_template",Makiavelo::DEBUG_LEVEL_INFO);
		$fp = fopen($entity_template, "r");
		$entity_code = "";
		if($fp ) {
			$entity_code = fread($fp, filesize($entity_template));
			$entity_code = str_replace("[NAME]", $en_name, $entity_code);
			$attr_code = "";
			foreach($this->en_attributes as $att_name => $att_type) {
				Makiavelo::debug("Adding attribute: $att_name => $att_type",Makiavelo::DEBUG_LEVEL_INFO);
				$attr_code .= "private $" . $att_name . "; //type: " . $att_type . "\n"; 
			} 
			$entity_code = str_replace("[ATTRIBUTES]", $attr_code, $entity_code); fclose($fp);
			$entity_code = str_replace("[VALIDATIONS]", $this->validations_str, $entity_code);
		}


		//Save the template into the new entity
		$entity_destination_file = ROOT_PATH . Makiavelo::ENTITITES_FOLDER . $en_name . "Class.php";
		Makiavelo::debug("Saving entity in: $entity_destination_file",Makiavelo::DEBUG_LEVEL_INFO);
		if(file_exists($entity_destination_file)) {
			Makiavelo::info("Entity class already exists, not saving...");
			return;
		} else {
			$fp = fopen($entity_destination_file, "w");
			if($fp) {
				fwrite($fp, $entity_code);
				fclose($fp);
			}
		}
	}
	public function generateViews($data) {
		$vGen = new ViewsGenerator();
		$vGen->generateCRUDViewsFor($this->en_name, $this->en_attributes);
	}

}

?>
