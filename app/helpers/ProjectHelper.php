<?php

function get_valid_languages() {
	global $__db_conn;
	$sql = "SELECT distinct language from project where language != '' and published = 1 order by language";

	$langs = array("All");
	if($rs = mysql_query($sql, $__db_conn)) {
		while($data = mysql_fetch_assoc($rs)) {
			$langs[] = $data['language'];
		}
		return $langs;
	} else {
		return array();
	}
}

?>