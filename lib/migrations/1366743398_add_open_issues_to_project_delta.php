<?php 
 class AddOpenIssuesToProjectDelta extends Migration {
			   public function up() {
          $this->alter_table("project_delta", array("add_field" => array("open_issues" => "integer")));
         }  

         public function down() {
          $this->alter_table("project_delta", array("drop_field" => "open_issues"));
         } 

			}  
?>