<?php 
 class AddLanguageToProject extends Migration {
			   public function up() {
			   	$this->alter_table("project", array("add_field" => array("language" => "string")));
			   }  

			   public function down() {
			   	$this->alter_table("project", array("drop_field" => "language"));
			   } 

			}  
?>