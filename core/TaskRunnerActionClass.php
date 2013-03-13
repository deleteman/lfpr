<?php

class TaskRunnerAction extends Action {

	public function execute($params)  {
		$task = $params[0];
		$task_parts = explode(":", $task);
		$class_name = Makiavelo::underscore_to_camel($task_parts[0]) . "Task";
		$task_obj = new $class_name;
		$mname = $task_parts[1];
		$task_obj->$mname();
	}
}

?>