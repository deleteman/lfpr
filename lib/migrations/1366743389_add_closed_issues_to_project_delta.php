<?php 
 class AddClosedIssuesToProjectDelta extends Migration {
			   public function up() {
          $this->alter_table("project_delta", array("add_field" => array("closed_issues" => "integer")));
         }  

         public function down() {
          $this->alter_table("project_delta", array("drop_field" => "closed_issues"));
         } 

			}  
?>