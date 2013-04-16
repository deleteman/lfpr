<?php

function load_latest_projects() {
	global $__db_conn;
	$sql = "SELECT * from project order by rand() limit 3";

	$projects = array();
	if($rs = mysql_query($sql, $__db_conn)) {
		while($data = mysql_fetch_assoc($rs)) {
			$dummy = new Project();
			$dummy->load_from_array($data);
			$projects[] = $dummy;
		}
		return $projects;
	} else {
		return array();
	}
}

function save_project($entity) {
	if(!$entity->is_new()) {
		return update_project($entity);
	} else {
			
		if($entity->validate()) {
			global $__db_conn;	

			$sql = "INSERT INTO project(created_at,updated_at,name,url,description,owner_id,stars,forks,last_update, language, published) values (':created_at:',':updated_at:',':name:',':url:',':description:',':owner_id:',':stars:',':forks:',':last_update:', ':language:', ':published:')";

			$sql = str_replace(":created_at:", Date("Y-m-d"), $sql);
			$sql = str_replace(":updated_at:", Date("Y-m-d"), $sql);

			preg_match_all("/:([a-zA-Z_0-9]*):/", $sql, $matches);
			foreach($matches[1] as $attr) {
				$sql = str_replace(":$attr:", $entity->$attr, $sql);
			}
			if(mysql_query($sql, $__db_conn)) {
				$entity->id = mysql_insert_id($__db_conn);
				return true;
			} else {
				Makiavelo::debug("Error mysql::" . mysql_error());
				return false;
			}

		} else {
			return false;
		}
	}

}


function update_project($en) {
	if($en->validate()) {
		global $__db_conn;	

		$sql = str_replace(":id:", $en->id, "UPDATE project SET id=':id:',created_at=':created_at:',updated_at=':updated_at:',name=':name:',url=':url:',description=':description:',owner_id=':owner_id:',stars=':stars:',forks=':forks:',last_update=':last_update:', language=':language:', published = ':published:' WHERE id = :id:"); 

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

function delete_project($entity_id) {
	global $__db_conn;
	$sql = str_replace(":id:", $entity_id, "DELETE FROM project WHERE id = :id:"); #DELETE FROM tipo_buque WHERE id = " . $entity_id;

	if(!mysql_query($sql, $__db_conn)) {
		echo mysql_error();
	}
}

function load_project($id) {
	return load_project_where("id = $id");
}

function load_project_where($where) {
	global $__db_conn;

	$sql =  "SELECT * FROM project WHERE $where"; #SELECT * FROM tipo_buque WHERE id = " . $id;

	$result = mysql_query($sql, $__db_conn);
	if(mysql_num_rows($result) > 0) {
		$row = mysql_fetch_assoc($result);
		$new = new Project();
		$new->load_from_array($row);
		return $new;
	} else {
		return null;
	}
}

function count_projects($where = null) {
	global $__db_conn;	

	$sql = "SELECT count(*) as cant FROM project";

	if($where != null) {
		$sql .= " WHERE $where ";
	}
	Makiavelo::info("== Counting projects:: " . $sql);
	
	$result = mysql_query($sql, $__db_conn);
	$results = array();

	$row = mysql_fetch_assoc($result);
	return $row['cant'];
}

/** 
Retrieves a list of Project
@order = Optional, can be an array of keys or just a single key to order by the results
@limit = Optional
*/
function list_project($order = null, $limit = null, $where = null) {
	global $__db_conn;	

	$sql = "SELECT * FROM project";

	if($where != null) {
		$sql .= " WHERE $where ";
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

	Makiavelo::info("== Loading projects:: " . $sql);
	$result = mysql_query($sql, $__db_conn);
	$results = array();

	while($row = mysql_fetch_assoc($result)) {
		$tmp = new Project();
		$tmp->load_from_array($row);
		$results[] = $tmp;
	}

	return $results;

}


?>