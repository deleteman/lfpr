<?php
class GeneratorAction extends Action {
	private $action_mappings = array(
		"crud" => "CRUDGenerator",
		"controller" => "ControllerGenerator",
		"migration" => "MigrationGenerator"
		);

	public function execute($params) {
		$action_code = $params[0];
		unset($params[0]);
		$specific_action = new $this->action_mappings[$action_code];
		return $specific_action->execute(array_values($params));
	}
}
?>
