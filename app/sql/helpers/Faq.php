<?php

function save_faq($entity) {
  if(!$entity->is_new()) {
    return update_faq($entity);
  } else {
      
    if($entity->validate()) {
      global $__db_conn;  

      $sql = "INSERT INTO faq(created_at,updated_at,project_id,question,answer,`order`) values (':created_at:',':updated_at:',':project_id:',':question:',':answer:',':order:')";

      $sql = str_replace(":created_at:", Date("Y-m-d"), $sql);
      $sql = str_replace(":updated_at:", Date("Y-m-d"), $sql);

      preg_match_all("/:([a-zA-Z_0-9]*):/", $sql, $matches);
      foreach($matches[1] as $attr) {
        $sql = str_replace(":$attr:", mysql_real_escape_string($entity->$attr), $sql);
      }
      if(!mysql_query($sql, $__db_conn)) {
        Makiavelo::info("ERROR SAVING FAQ: " . mysql_error() . "::" . $sql);
      }
      $entity->id = mysql_insert_id($__db_conn);
      return true;
    } else {
      return false;
    }
  }

}


function update_faq($en) {
  if($en->validate()) {
    global $__db_conn;  

    $sql = str_replace(":id:", $en->id, "UPDATE faq SET id=':id:',created_at=':created_at:',updated_at=':updated_at:',project_id=':project_id:',question=':question:',answer=':answer:',`order`=':order:' WHERE id = :id:"); #'UPDATE tipo_buque set name="' . $en->name .'" WHERE id=' . $en->id;

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

function delete_faq($entity_id) {
  global $__db_conn;
  $sql = str_replace(":id:", $entity_id, "DELETE FROM faq WHERE id = :id:"); #DELETE FROM tipo_buque WHERE id = " . $entity_id;

  if(!mysql_query($sql, $__db_conn)) {
    echo mysql_error();
  }
}

function load_faq($id) {
  return load_faq_where("id = $id");
}

function load_faq_where($where) {
  global $__db_conn;

  $sql =  "SELECT * FROM faq WHERE $where"; #SELECT * FROM tipo_buque WHERE id = " . $id;

  $result = mysql_query($sql, $__db_conn);
  if(mysql_num_rows($result) > 0) {
    $row = mysql_fetch_assoc($result);
    $new = new Faq();
    $new->load_from_array($row);
    return $new;
  } else {
    return null;
  }
}

/** 
Retrieves a list of Faq
@order = Optional, can be an array of keys or just a single key to order by the results
@limit = Optional
*/
function list_faq($order = null, $limit = null, $where = null) {
  global $__db_conn;  

  $sql = "SELECT * FROM faq";
  if($where != null) {
    $sql .= " WHERE " . $where;
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
  if(!$result) {
    Makiavelo::info("Mysql error: " . mysql_error() . "::" . $sql);
  }
  $results = array();

  while($row = mysql_fetch_assoc($result)) {
    $tmp = new Faq();
    $tmp->load_from_array($row);
    $results[] = $tmp;
  }

  return $results;

}


?>
