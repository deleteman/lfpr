<?php 
 class AddPublishedToProject extends Migration {
			   public function up() {
			   	$this->alter_table("project", array("add_field" => array("published" => "boolean")));
			   }  

			   public function down() {
			   	$this->alter_table("project", array("drop_field" => "published"));
			   } 

			}  
?>