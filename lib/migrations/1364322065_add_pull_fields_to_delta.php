<?php 
 class AddPullFieldsToDelta extends Migration {
			   public function up() {
			   	$this->alter_table("project_delta", array("add_field" => array("new_pulls" => "integer")));
			   	$this->alter_table("project_delta", array("add_field" => array("closed_pulls" => "integer")));
			   	$this->alter_table("project_delta", array("add_field" => array("merged_pulls" => "integer")));
			   }  

			   public function down() {
			   	$this->alter_table("project_delta", array("drop_field" => "new_pulls"));
			   	$this->alter_table("project_delta", array("drop_field" => "closed_pulls"));
			   	$this->alter_table("project_delta", array("drop_field" => "merged_pulls"));
			   } 

			}  
?>