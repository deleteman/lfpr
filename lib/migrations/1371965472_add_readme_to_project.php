<?php 
 class AddReadmeToProject extends Migration {
   public function up() {
      $this->alter_table("project", array("add_field" => array("readme" => "text")));
   }  

   public function down() {
      $this->alter_table("project", array("drop_field" => "readme"));
   } 

}  
?>