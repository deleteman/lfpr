<?php

class SQLGenerator {
	private $name;
	private $attributes;
	private $sql_types_mapping = array("string" => "varchar(255)",
										"text" => "text",
										"boolean" => "tinyint",
										"integer" => "int",
										"datetime" => "datetime",
										"time" => "time", 
										"date" => "date",
										"float" => "float");

	public function __construct($name, $attrs) {
		$this->name = $name;
		$this->attributes = $attrs;
		$this->table_name = $this->sanitize_string($this->name);
	}

	private function sanitize_string($str) {
		return strtolower(str_replace(" ", "_", Makiavelo::camel_to_underscore($str)));
	}

	public function create_database_sql() {
		global $__APP_NAME;
		return "CREATE DATABASE `" . $this->sanitize_string($__APP_NAME) . "`";
	}

	public function create_sql() {
		$sql = "CREATE TABLE `" . $this->table_name . "` (";
		$attrs = array();
		foreach($this->attributes as $attr_name => $attr_type) {
			$extras = "";
			if(strtolower($attr_name) == "id") {
				$extras = " auto_increment";
			}
			$attrs[] = "`" . $attr_name . "` {$this->sql_types_mapping[$attr_type]} $extras";
		}
		$sql .= implode(",\n", $attrs);
		$sql .= ",\n PRIMARY KEY (id)";
		$sql .= ' );';

		return $sql;

	}

	static private function mark_attribute($attr) {
		return "':" . $attr . ":'";
	}
	static private function attribute_and_value($attr) {
		return $attr . "=':" . $attr. ":'";
	}

	public function crud_sql() {

		$relevant_attrs = $this->attributes;
		unset($relevant_attrs['id']);
		return array(
			"list" => "SELECT * FROM " . $this->table_name,
			"create" => "INSERT INTO " . $this->table_name . "(" . implode(",", array_keys($relevant_attrs)) . ") values (" . 
				implode(",", array_map('SQLGenerator::mark_attribute', array_keys($relevant_attrs))) . ")",
			"retrieve" => "SELECT * FROM " . $this->table_name . " WHERE id = :id:",
			"update" => "UPDATE " . $this->table_name . " SET " . 
				implode(",", array_map("SQLGenerator::attribute_and_value", array_keys($this->attributes))) . 
				" WHERE id = :id:",
			"delete" => "DELETE FROM " . $this->table_name . " WHERE id = :id:"

			);
	}
}


?>