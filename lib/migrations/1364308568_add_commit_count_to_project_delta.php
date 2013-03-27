<?php 
 class AddCommitCountToProjectDelta extends Migration {
			   public function up() {
			   	$this->alter_table("project_delta", array("add_field" => array("commits_count" => "integer")));

			   }  

			   public function down() {
			   	$this->alter_table("project_delta", array("drop_field" => "commits_count"));
			   } 

			}  
?>