<?php

function save_project_delta($entity) {
	if(!$entity->is_new()) {
		return update_project_delta($entity);
	} else {
			
		if($entity->validate()) {
			global $__db_conn;	

			$sql = "INSERT INTO project_delta(created_at,updated_at,sample_date,stars,delta_stars,forks,delta_forks, project_id, commits_count, new_pulls, closed_pulls, merged_pulls, closed_issues, open_issues) 
					values (':created_at:',':updated_at:',':sample_date:',':stars:',':delta_stars:',':forks:',':delta_forks:', ':project_id:', ':commits_count:', ':new_pulls:',':closed_pulls:', ':merged_pulls:', ':closed_issues:', ':open_issues:')";

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


function update_project_delta($en) {
	if($en->validate()) {
		global $__db_conn;	

		$sql = str_replace(":id:", $en->id, "UPDATE project_delta SET id=':id:',created_at=':created_at:',updated_at=':updated_at:',sample_date=':sample_date:',stars=':stars:',delta_stars=':delta_stars:',forks=':forks:',delta_forks=':delta_forks:' , project_id= ':project_id:', commits_count = ':commits_count:', new_pulls=':new_pulls:', closed_pulls=':closed_pulls:', merged_pulls=':merged_pulls:' WHERE id = :id:"); 

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

function delete_project_delta($entity_id) {
	global $__db_conn;
	$sql = str_replace(":id:", $entity_id, "DELETE FROM project_delta WHERE id = :id:"); #DELETE FROM tipo_buque WHERE id = " . $entity_id;

	if(!mysql_query($sql, $__db_conn)) {
		echo mysql_error();
	}
}

function load_project_delta($id) {
	return load_project_delta_where("id = $id");
}

function load_project_delta_where($where) {
	global $__db_conn;

	$sql =  "SELECT * FROM project_delta WHERE $where"; #SELECT * FROM tipo_buque WHERE id = " . $id;

	$result = mysql_query($sql, $__db_conn);
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_assoc($result);
		$new = new ProjectDelta();
		$new->load_from_array($row);
		return $new;
	} else {
		return null;
	}
}

/** 
Retrieves a list of ProjectDelta
@order = Optional, can be an array of keys or just a single key to order by the results
@limit = Optional
*/
function list_project_delta($order = null, $limit = null, $where = null) {
	global $__db_conn;	

	$sql = "SELECT * FROM project_delta";

	if($where != null) {
		$sql .= " WHERE $where";
	}

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
		$tmp = new ProjectDelta();
		$tmp->load_from_array($row);
		$results[] = $tmp;
	}

	return $results;

}


?>