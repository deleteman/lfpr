<?php 
 class AddProjectIdToDelta extends Migration {
			   public function up() {
			   	$this->alter_table("project_delta", array("add_field" => array("project_id" => "integer")));

			   }  

			   public function down() {
			   	$this->alter_table("project_delta", array("drop_field" => "project_id"));
			   } 

			}  
?>