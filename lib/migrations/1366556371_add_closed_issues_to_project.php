<?php 
 class AddClosedIssuesToProject extends Migration {
			   public function up() {
          $this->alter_table("project", array("add_field" => array("closed_issues" => "integer")));
         }  

         public function down() {
          $this->alter_table("project", array("drop_field" => "closed_issues"));
         } 

			}  
?>