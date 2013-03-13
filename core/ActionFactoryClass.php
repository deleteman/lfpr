<?php

class ActionFactory {

	private static $instance = null;

	public static function getInstance() {
		if(self::$instance == null) {
			self::$instance = new ActionFactory();
		}
		return self::$instance;
	}

	private $mapping = array(
		"g" => "GeneratorAction",
		"db:load" => "DBLoaderAction",
		"db:create" => "DBCreatorAction",
		"db:migrate" => "DBMigratorAction",
		"db:rollback" => "DBMigratorRollbackAction",
		"task" => "TaskRunnerAction"
		);

	public function getAction($action_code) {
		return new $this->mapping[$action_code];
	}
}

?>