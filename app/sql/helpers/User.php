<?php

function save_user($entity) {
  if(!$entity->is_new()) {
    return update_user($entity);
  } else {
      
    if($entity->validate()) {
      global $__db_conn;  

      $sql = "INSERT INTO user(created_at,updated_at,username,password,role) values (':created_at:',':updated_at:',':username:',':password:',':role:')";

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


function update_user($en) {
  if($en->validate()) {
    global $__db_conn;  

    $sql = str_replace(":id:", $en->id, "UPDATE user SET id=':id:',created_at=':created_at:',updated_at=':updated_at:',username=':username:',password=':password:',role=':role:' WHERE id = :id:"); #'UPDATE tipo_buque set name="' . $en->name .'" WHERE id=' . $en->id;

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

function delete_user($entity_id) {
  global $__db_conn;
  $sql = str_replace(":id:", $entity_id, "DELETE FROM user WHERE id = :id:"); #DELETE FROM tipo_buque WHERE id = " . $entity_id;

  if(!mysql_query($sql, $__db_conn)) {
    echo mysql_error();
  }
}

function load_user($id) {
  return load_user_where("id = $id");
}

function load_user_where($where) {
  global $__db_conn;

  $sql =  "SELECT * FROM user WHERE $where"; #SELECT * FROM tipo_buque WHERE id = " . $id;

  //Makiavelo::info("LOAD USER WHERE: " . $sql);
  $result = mysql_query($sql, $__db_conn);
  if(mysql_num_rows($result) > 0) {
    $row = mysql_fetch_assoc($result);
    $new = new User();
    $new->load_from_array($row);
    return $new;
  } else {
    return null;
  }
}

/** 
Retrieves a list of User
@order = Optional, can be an array of keys or just a single key to order by the results
@limit = Optional
*/
function list_user($order = null, $limit = null) {
  global $__db_conn;  

  $sql = "SELECT * FROM user";
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
    $tmp = new User();
    $tmp->load_from_array($row);
    $results[] = $tmp;
  }

  return $results;

}


?>
