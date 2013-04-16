<?php

function count_project_commit($where = null) {
	global $__db_conn;	
	$sql = "SELECT count(*) as cant from project_commit";

	if($where != null) {
		$sql .= " WHERE " . $where;
	}

	$result = mysql_query($sql, $__db_conn);
	if(!$result) {
		Makiavelo::info("ERROR MYSQL :: " . mysql_error(). "::" . $sql);
		return 0;
	} else {
		$row = mysql_fetch_assoc($result);
		return $row['cant'];
	}
}

function save_project_commit($entity) {
	if(!$entity->is_new()) {
		return update_project_commit($entity);
	} else {
			
		if($entity->validate()) {
			global $__db_conn;	

			$sql = "INSERT INTO project_commit(created_at,updated_at,project_id,committer,commit_message,sha, commit_date) values (':created_at:',':updated_at:',':project_id:',':committer:',':commit_message:',':sha:', ':commit_date:')";

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


function update_project_commit($en) {
	if($en->validate()) {
		global $__db_conn;	

		$sql = str_replace(":id:", $en->id, "UPDATE project_commit SET id=':id:',created_at=':created_at:',updated_at=':updated_at:',project_id=':project_id:',committer=':committer:',commit_message=':commit_message:',sha=':sha:', commit_date=':commit_date:' WHERE id = :id:"); 

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

function delete_project_commit($entity_id) {
	global $__db_conn;
	$sql = str_replace(":id:", $entity_id, "DELETE FROM project_commit WHERE id = :id:"); #DELETE FROM tipo_buque WHERE id = " . $entity_id;

	if(!mysql_query($sql, $__db_conn)) {
		echo mysql_error();
	}
}

function load_project_commit($id) {
	return load_project_commit_where("id = $id");
}

function load_project_commit_where($where) {
	global $__db_conn;

	$sql =  "SELECT * FROM project_commit WHERE $where"; #SELECT * FROM tipo_buque WHERE id = " . $id;

	$result = mysql_query($sql, $__db_conn);
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_assoc($result);
		$new = new ProjectCommit();
		$new->load_from_array($row);
		return $new;
	} else {
		return null;
	}
}

/** 
Retrieves a list of ProjectCommit
@order = Optional, can be an array of keys or just a single key to order by the results
@limit = Optional
*/
function list_project_commit($order = null, $limit = null) {
	global $__db_conn;	

	$sql = "SELECT * FROM project_commit";
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
		$tmp = new ProjectCommit();
		$tmp->load_from_array($row);
		$results[] = $tmp;
	}

	return $results;

}


?>