<?php

function save_[UC_NAME]($entity) {
	if(!$entity->is_new()) {
		return update_[UC_NAME]($entity);
	} else {
			
		if($entity->validate()) {
			global $__db_conn;	

			$sql = "[CREATE_SQL]";

			$sql = str_replace(":created_at:", Date("Y-m-d"), $sql);
			$sql = str_replace(":updated_at:", Date("Y-m-d"), $sql);

			preg_match_all("/:([a-zA-Z_0-9]*):/", $sql, $matches);
			foreach($matches[1] as $attr) {
				$sql = str_replace(":$attr:", $entity->$attr, $sql);
			}
			mysql_query($sql, $__db_conn);
			$entity->id = mysql_insert_id($__db_conn);
			return true;
		} else {
			return false;
		}
	}

}


function update_[UC_NAME]($en) {
	if($en->validate()) {
		global $__db_conn;	

		$sql = str_replace(":id:", $en->id, "[UPDATE_SQL]"); #'UPDATE tipo_buque set name="' . $en->name .'" WHERE id=' . $en->id;

		$sql = str_replace(":updated_at:", Date("Y-m-d"), $sql);


		preg_match_all("/:([a-zA-Z_0-9]*):/", $sql, $matches);
		foreach($matches[1] as $attr) {
			$sql = str_replace(":$attr:", $en->$attr, $sql);
		}
		mysql_query($sql, $__db_conn);
		return true;
	} else {
		return false;
	}


}

function delete_[UC_NAME]($entity_id) {
	global $__db_conn;
	$sql = str_replace(":id:", $entity_id, "[DELETE_SQL]"); #DELETE FROM tipo_buque WHERE id = " . $entity_id;

	if(!mysql_query($sql, $__db_conn)) {
		echo mysql_error();
	}
}

function load_[UC_NAME]($id) {
	return load_[UC_NAME]_where("id = $id");
}

function load_[UC_NAME]_where($where) {
	global $__db_conn;

	$sql =  "SELECT * FROM [UC_NAME] WHERE $where"; #SELECT * FROM tipo_buque WHERE id = " . $id;

	$result = mysql_query($sql, $__db_conn);
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_assoc($result);
		$new = new [NAME]();
		$new->load_from_array($row);
		return $new;
	} else {
		return null;
	}
}

/** 
Retrieves a list of [NAME]
@order = Optional, can be an array of keys or just a single key to order by the results
@limit = Optional
*/
function list_[UC_NAME]($order = null, $limit = null) {
	global $__db_conn;	

	$sql = "[LIST_SQL]";
	if($order != null) {
		$order_str = $order;
		if(is_array($order)) {
			$order_str = implode(",", $order);
		}
		$sql .= " order by $order_str";
	}

	if($limit != null) {
		$sql .= " limit $limit";
	}

	$result = mysql_query($sql, $__db_conn);
	$results = array();

	while($row = mysql_fetch_assoc($result)) {
		$tmp = new [NAME]();
		$tmp->load_from_array($row);
		$results[] = $tmp;
	}

	return $results;

}


?>