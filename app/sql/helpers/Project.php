<?php

function search_projects($term, $init, $limit) {
  global $__db_conn;

  $terms = explode(" ", urldecode($term));

  $where = "published=1 and (";
  $search_term_conditions = array();
  foreach($terms as $term) {
    $term = mysql_real_escape_string($term);
    $search_term_conditions[] = " (name like '%$term%' or description like '%$term%' or language like '%$term%')  ";
  } 
  $where .= implode(" or ", $search_term_conditions) . ")";

  $sql = "SELECT * FROM project where  $where LIMIT $init , $limit";

  $rs = mysql_query($sql, $__db_conn);
  $results = array();
  while($row = mysql_fetch_assoc($rs)) {
    $tmp = new Project();
    $tmp->load_from_array($row);
    $results[] = $tmp;
  }

  $return = array("results" => $results, "total" => 0);

  if(count($results) > 0) {
    $sql_count = "SELECT count(*) as total from project WHERE $where";
    $rs = mysql_query($sql_count, $__db_conn);
    if(!$rs) {
      Makiavelo::info("SQL Error:: " . mysql_error() . "::" . $sql_count);
    } else {
      $row = mysql_fetch_assoc($rs);
      $return['total'] = $row['total'];
    }
  }
  return $return;
  
}

function get_languages_by_ranking() {
  $sql = "SELECT COUNT(*) as total, language from project 
      WHERE published = 1
      GROUP BY language 
      ORDER BY total desc";
  global $__db_conn;

  $langs = array();
  if($rs = mysql_query($sql, $__db_conn)) {
    while($data = mysql_fetch_assoc($rs)) {
      $langs[] = array("lang" => $data['language'], "total" => intval($data['total']));
    }
  } else {
    Makiavelo::info("SQL Error::" . mysql_error() . "::" . $sql);
  }
  return $langs;
}

function get_developer_contributions($id, $username) {
  $sql = "SELECT COUNT( * ) as total , project_id, p.name as project_name, p.language as lang
      FROM  `project_commit` pc
      INNER JOIN project p ON p.id = pc.project_id
      WHERE owner_id != " . $id . "
      AND committer =  '" . $username . "'
      GROUP BY project_id";
  global $__db_conn;

  $projects = array();
  if($rs = mysql_query($sql, $__db_conn)) {
    while($data = mysql_fetch_assoc($rs)) {
      $projects[] = array("project" => $data['project_name'], "contribs" => intval($data['total']), "language" => $data['lang']);
    }
  }
  return $projects;
}

function load_latest_projects($order = "rand()") {
  global $__db_conn;
  $sql = "SELECT * from project where published = 1 order by $order limit 3";

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

      $sql = "INSERT INTO project(created_at,updated_at,name,url,description,owner_id,stars,forks,last_update,language,published,open_issues,closed_issues, readme, pr_acceptance_rate) values (':created_at:',':updated_at:',':name:',':url:',':description:',':owner_id:',':stars:',':forks:',':last_update:', ':language:', ':published:', ':open_issues:', ':closed_issues:', ':readme:', ':pr_acceptance_rate:')";

      $sql = str_replace(":created_at:", Date("Y-m-d"), $sql);
      $sql = str_replace(":updated_at:", Date("Y-m-d"), $sql);

      preg_match_all("/:([a-zA-Z_0-9]*):/", $sql, $matches);
      foreach($matches[1] as $attr) {
        $sql = str_replace(":$attr:", mysql_real_escape_string($entity->$attr), $sql);
        Makiavelo::info("QUERY =>". $sql);
      }
      if(mysql_query($sql, $__db_conn)) {
        $entity->id = mysql_insert_id($__db_conn);
        return true;
      } else {
        Makiavelo::info("Error mysql::" . mysql_error());
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

    $sql = str_replace(":id:", $en->id, "UPDATE project SET id=':id:',created_at=':created_at:',updated_at=':updated_at:',name=':name:',url=':url:',description=':description:',owner_id=':owner_id:',stars=':stars:',forks=':forks:',last_update=':last_update:', language=':language:', published = ':published:', readme=':readme:', pr_acceptance_rate=':pr_acceptance_rate:', open_issues=':open_issues:' WHERE id = :id:"); 

    $sql = str_replace(":updated_at:", Date("Y-m-d"), $sql);


    preg_match_all("/:([a-zA-Z_0-9]*):/", $sql, $matches);
    foreach($matches[1] as $attr) {
      $sql = str_replace(":$attr:", mysql_real_escape_string($en->$attr), $sql);
    }
    mysql_query($sql, $__db_conn);
    return true;
  } else {
    Makiavelo::info("MYSQL ERROR: " . mysql_error() . " :: " . $sql);
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
  if(!$result) {
    Makiavelo::info("MYSQL ERROR: " . mysql_error() . "::" . $sql1);
  }
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
  if(!$result) {
    Makiavelo::info("ERROR MYSQL: " . mysql_error() . " - " . $sql);
    return 0;
  }
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
  if(!$result) {
    Makiavelo::info("ERROR MYSQL: " . mysql_error() . " - " . $sql);
    return array();
  }
  $results = array();

  while($row = mysql_fetch_assoc($result)) {
    $tmp = new Project();
    $tmp->load_from_array($row);
    $results[] = $tmp;
  }

  return $results;

}


?>
