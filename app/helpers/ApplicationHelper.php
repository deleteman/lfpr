<?php

function truncate_string($str, $limit) {
  if(strlen($str) > $limit) {
    return '<span class="has-tooltip" data-title="'.$str.'">' . substr($str, 0, $limit) . '...</span>';
  } else {
    return $str;
  }
}

?>
