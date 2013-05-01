<?php 
 class AddOpenIssuesToProject extends Migration {
			   public function up() {
          $this->alter_table("project", array("add_field" => array("open_issues" => "integer")));
         }  

			   public function down() {
          $this->alter_table("project", array("drop_field" => "open_issues"));
         } 

			}  
?>