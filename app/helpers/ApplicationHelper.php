<?php

function truncate_string($str, $limit) {
	if(strlen($str) > $limit) {
		return substr($str, 0, $limit) . '<span class="has-tooltip" data-title="'.$str.'">...</span>';
	} else {
		return $str;
	}
}

?>