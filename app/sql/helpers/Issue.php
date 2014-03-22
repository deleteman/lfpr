<?php

function save_issue($entity) {
  
  if(!$entity->is_new()) {
    return update_issue($entity);
  } else {
      
    if($entity->validate()) {
      global $__db_conn;  

      $sql = "INSERT INTO issue(created_at,updated_at,title,body,num,url,project_id) values (':created_at:',':updated_at:',':title:',':body:',':number:',':url:',':project_id:');";

      $sql = str_replace(":created_at:", Date("Y-m-d"), $sql);
      $sql = str_replace(":updated_at:", Date("Y-m-d"), $sql);

      preg_match_all("/:([a-zA-Z_0-9]*):/", $sql, $matches);
      foreach($matches[1] as $attr) {
        $sql = str_replace(":$attr:", mysql_real_escape_string($entity->$attr), $sql);
      }
      mysql_query($sql, $__db_conn);
      $entity->id = mysql_insert_id($__db_conn);
      return true;

    }
  }
}

function update_issue($en) {

}

function delete_issues_by_project_id($project_id) {
  global $__db_conn;
  $sql = str_replace(":id:", $project_id, "DELETE FROM issue WHERE project_id = :id:");

  if(!mysql_query($sql, $__db_conn)) {
    echo mysql_error();
  }
}

function count_issue($where) {
  global $__db_conn;

  $sql =  "SELECT count(*) as cant FROM issue WHERE $where";

  $result = mysql_query($sql, $__db_conn);
  if(mysql_num_rows($result) > 0) {
    $row = mysql_fetch_assoc($result);
    return $row['cant'];
  } else {
    return 0;
  }
}

function random_issue($project_id) {
  global $__db_conn;

  $sql =  "SELECT * FROM issue WHERE project_id = " . $project_id . " ORDER BY rand() LIMIT 1";

  $result = mysql_query($sql, $__db_conn);
  if(mysql_num_rows($result) > 0) {
    $row = mysql_fetch_assoc($result);
    $new = new Issue();
    $new->load_from_array($row);
    return $new;
  } else {
    return null;
  }
}

function load_issue($id) {
  return load_issue_where("id = $id");
}

function load_issue_where($where) {
  global $__db_conn;

  $sql =  "SELECT * FROM issue WHERE $where";

  $result = mysql_query($sql, $__db_conn);
  if(mysql_num_rows($result) > 0) {
    $row = mysql_fetch_assoc($result);
    $new = new Issue();
    $new->load_from_array($row);
    return $new;
  } else {
    return null;
  }
}

function list_issue($order = null, $limit = null, $where = null) {
  global $__db_conn;  

  $sql = "SELECT * FROM issue";
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
    $tmp = new Issue();
    $tmp->load_from_array($row);
    $results[] = $tmp;
  }

  return $results;

}
?>
