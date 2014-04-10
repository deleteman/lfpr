<?php

function formatDate($date, $format) {
    if($date == "0000-00-00") return "N/A";
    $dt = new DateTime($date);
    return $dt->format($format);
}

function truncate_string($str, $limit) {
  if(strlen($str) > $limit) {
    return '<span class="has-tooltip" data-title="'.$str.'">' . substr($str, 0, $limit) . '...</span>';
  } else {
    return $str;
  }
}

?>
