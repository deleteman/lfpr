<?php

function save_developer($entity) {
  if(!$entity->is_new()) {
    return update_developer($entity);
  } else {
      
    if($entity->validate()) {
      global $__db_conn;  

      $sql = "INSERT INTO developer(created_at,updated_at,name,avatar_url,github_url) values (':created_at:',':updated_at:',':name:',':avatar_url:',':github_url:')";

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


function update_developer($en) {
  if($en->validate()) {
    global $__db_conn;  

    $sql = str_replace(":id:", $en->id, "UPDATE developer SET id=':id:',created_at=':created_at:',updated_at=':updated_at:',name=':name:',avatar_url=':avatar_url:',github_url=':github_url:' WHERE id = :id:"); #'UPDATE tipo_buque set name="' . $en->name .'" WHERE id=' . $en->id;

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

function delete_developer($entity_id) {
  global $__db_conn;
  $sql = str_replace(":id:", $entity_id, "DELETE FROM developer WHERE id = :id:"); #DELETE FROM tipo_buque WHERE id = " . $entity_id;

  if(!mysql_query($sql, $__db_conn)) {
    echo mysql_error();
  }
}

function load_developer($id) {
  return load_developer_where("id = $id");
}

function load_developer_where($where) {
  global $__db_conn;

  $sql =  "SELECT * FROM developer WHERE $where"; #SELECT * FROM tipo_buque WHERE id = " . $id;

  $result = mysql_query($sql, $__db_conn);
  if(!$result) {
    Makiavelo::info("ERROR MYSQL:: " . __FILE__ . "::" . mysql_error());
    return null;
  } else {
    if(mysql_num_rows($result) > 0) {
      $row = mysql_fetch_assoc($result);
      $new = new Developer();
      $new->load_from_array($row);
      return $new;
    } else {
      return null;
    }
  }
}

/** 
Retrieves a list of Developer
@order = Optional, can be an array of keys or just a single key to order by the results
@limit = Optional
*/
function list_developer($order = null, $limit = null) {
  global $__db_conn;  

  $sql = "SELECT * FROM developer";
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
    $tmp = new Developer();
    $tmp->load_from_array($row);
    $results[] = $tmp;
  }

  return $results;

}


?>
