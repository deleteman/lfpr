<?php

function show_path_for($entity) {
	$str_fnc = $entity->__get_entity_name() . "_retrieve_path";
	return $str_fnc($entity);
}

function edit_path_for($entity) {
	$str_fnc = $entity->__get_entity_name() . "_update_path";
	return $str_fnc($entity);
}

function delete_path_for($entity) {
	$str_fnc = $entity->__get_entity_name() . "_delete_path";
	return $str_fnc($entity);
}

?>