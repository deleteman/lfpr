<?php 
 class AddPrAcceptanceRateToProject extends Migration {
   public function up() {
      $this->alter_table("project", array("add_field" => array("pr_acceptance_rate" => "integer")));
   }  

   public function down() {
   		$this->alter_table("project", array("drop_field" => "pr_acceptance_rate"));
   } 

}  
?>