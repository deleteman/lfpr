<?php 
 class AddCommitDateToProjectCommit extends Migration {
			   public function up() {
			   	$this->alter_table("project_commit", array("add_field" => array("commit_date" => "date")));
			   }  

			   public function down() {
			   	$this->alter_table("project_commit", array("drop_field" => "commit_date"));
			   } 

}  
?>