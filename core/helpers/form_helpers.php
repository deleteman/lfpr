<?php

/** Adds the opening tag for a form and 2 hidden fields:
- id
- created_at
*/
function form_for($en, $http_action = "create", $html_attrs = array()) {

	$html_opts = "";
	if(count($html_attrs) > 0) {
		foreach($html_attrs as $attr => $val) {
			$html_opts .= $attr . '="' . $val . '" ';
		}
	}

	$path_func_name = $en->__get_entity_name() . "_".$http_action."_path";
	$html = '<form action="' . $path_func_name() . '" method="post" ' . $html_opts . ' >';
	$html .= hidden_field($en, "id");
	$html .= hidden_field($en, "created_at");
	return $html;
}


function form_tag($url, $method, $html = array()) {

	$html_opts = "";
	if(count($html) > 0) {
		foreach($html as $attr => $val) {
			$html_opts .= $attr . '="' . $val . '" ';
		}
	}
	return $html = '<form action="' . $url . '" method="'.$method.'" '.$html_opts.'>';
}

function form_end_tag() {
	return '</form>';
}

function file_field($en, $attr,$label, $html_attrs = array()) {
	$html_opts = "";
	if(count($html_attrs) > 0) {
		foreach($html_attrs as $attr => $val) {
			$html_opts .= $attr . '="' . $val . '" ';
		}
	}
	$error = (isset($en->errors[$attr])) ? "validation-error" : "";
	$html = '<div class="form-field ' .$error . '">';
	$label_text = $label;
	if($label == null) {
		$label_text = Makiavelo::titlelize($attr);
	}
	$html .= '<label for="'.$attr.'">'.$label_text.'</label>';
	$html .= '<input type="file" 
				name="' . $en->__get_entity_name() . '['.$attr.']" 
				id="' . $en->__get_entity_name() . '_' .$attr.'" 
				 '. $html_opts . ' />';
	$html .= "</div>";

	return $html;
}


function select_field_tag($name, $id, $options, $html_attrs = array()) {
	$elems = $options['options'];
	$value_field = (isset($options['value_field'])) ? $options['value_field'] : "";
	$text_field = (isset($options['text_field'])) ? $options['text_field'] : "";
	$selected_value = (isset($options['selected'])) ? $options['selected'] : "";


	$html = "";
	if(isset($html_attrs['no-container']) && !$html_attrs['no-container'] || !isset($html_attrs['no-container'])) {
		$html = '<div class="form-field ">';
	}

	$html_extra_attrs = "";
	foreach($html_attrs as $attr => $value) {
		$html_extra_attrs .= ' '.$attr.'="'.$value.'"';
	}

	$html .= '<select 
				name="' . $name .'" 
				id="' . $id .'" 
				'.$html_extra_attrs.' 
				 >';
	foreach($elems as $elem) {
		$value = is_array($elem) ? $elem[0] : $elem->$value_field;
		$text  = is_array($elem) ? $elem[1] : $elem->$text_field;
		$selected = ($value == $selected_value) ? "selected" : "";

		$html .= '<option value="' . $value . '" ' . $selected . '>' . $text . '</option>';
	}
	$html .= "</select>";
	if(isset($html_attrs['no-container']) && !$html_attrs['no-container'] || !isset($html_attrs['no-container'])) {
		$html .= "</div>";
	}

	return $html;
}

function select_field($en, $attr, $label, $options) {
	$elems = $options['options'];
	$value_field = (isset($options['value_field'])) ? $options['value_field'] : "";
	$text_field = (isset($options['text_field'])) ? $options['text_field'] : "";

	$error = (isset($en->errors[$attr]) ) ? "validation-error" : "";
	$html = '<div class="form-field '.$error.'">';
	$label_text = $label;
	if($label == null) {
		$label_text = Makiavelo::titlelize($attr);
	}
	$html .= '<label for="'.$attr.'">'.$label_text.'</label>';
	$html .= '<select 
				name="' . $en->__get_entity_name() . '['.$attr.']" 
				id="' . $en->__get_entity_name() . '_' .$attr.'" 
				>';
	foreach($elems as $elem) {
		$selected = "";
		$value = is_array($elem) ? $elem[0] : $elem->$value_field;
		$text  = is_array($elem) ? $elem[1] : $elem->$text_field;
		if ( $en->$attr == $value) {
			$selected = "selected";
		}
		$html .= '<option value="' . $value . '" ' . $selected . '>' . $text . '</option>';
	}
	$html .= "</select>";
	$html .= "</div>";

	return $html;
}


function boolean_field($en, $attr, $label = null) {

	$html = '<div class="form-field">';
	$label_text = $label;
	if($label == null) {
		$label_text = Makiavelo::titlelize($attr);
	}
	$checked = ($en->$attr == 1) ? "checked" : "";
	$html .= '<label for="'.$attr.'">'.$label_text.'</label>';
	$html .= '<input type="checkbox" 
				' . $checked . '
				name="' . $en->__get_entity_name() . '['.$attr.']" 
				id="' . $en->__get_entity_name() . '_' .$attr.'" 
				 />';
	$html .= "</div>";

	return $html;
}

function email_field($en, $attr, $label = null, $attrs = array()) {
	return text_field($en, $attr, $label, $attrs);
}

function text_field($en, $attr, $label = null, $html_attr = array()) {

	$html_opts = "";

	if(count($html_attr) > 0) {
		foreach($html_attr as $prop => $val) {
			$html_opts .= $prop. '="' . $val . '" ';
		}
	}
	$html = "";
	$error = (isset($en->errors[$attr])) ? "validation-error" : "";
	if(!isset($html_attr["no-container"])) {
		$html = '<div class="form-field '.$error.'">';
	}
	$label_text = "";
	if($label !== false) {
		$label_text = $label;
		if($label === null) {
			$label_text = Makiavelo::titlelize($attr);
		}
	}
	$html .= '<label for="'.$attr.'">'.$label_text.'</label>';
	$html .= '<input type="text" 
				name="' . $en->__get_entity_name() . '['.$attr.']" 
				id="' . $en->__get_entity_name() . '_' .$attr.'" 
				value="' . $en->$attr . '" 
				'.$html_opts.' />';
	if(!isset($html_attr["no-container"])) {
		$html .= "</div>";
	}


	return $html;
}


function text_field_tag($name, $id, $label = null, $html = array()) {
	$html_opts = "";
	if(count($html) > 0) {
		foreach($html as $attr => $val) {
			$html_opts .= $attr . '="' . $val . '" ';
		}
	}

	$html_code = "";
	if(isset($html['no-container']) && !$html['no-container'] || !isset($html['no-container'])) {
		$html_code = '<div class="form-field">';
	} 	
	if($label !== null) {
		$html_code .= '<label for="'.$id.'">' . $label.'</label>';
	}
	$html_code .= '<input type="text" 
				name="' . $name . '"
				id="' . $id . '"
				'.$html_opts. ' />';
	if( (isset($html['no-container']) && !$html['no-container']) || !isset($html['no-container'])){
		$html_code .= "</div>";
	}

	return $html_code;
}


function password_field($en, $attr, $label = null) {

	$error = (isset($en->errors[$attr])) ? "validation-error" : "";
	$html = '<div class="form-field '.$error.'">';
	$label_text = $label;
	if($label == null) {
		$label_text = Makiavelo::titlelize($attr);
	}
	$html .= '<label for="'.$attr.'">'.$label_text.'</label>';
	$html .= '<input type="password" 
				name="' . $en->__get_entity_name() . '['.$attr.']" 
				id="' . $en->__get_entity_name() . '_' .$attr.'" 
				value="' . $en->$attr . '" />';
	$html .= "</div>";

	return $html;
}

function password_field_tag($name, $id, $html = array()) {
	$html_opts = "";
	if(count($html) > 0) {
		foreach($html as $attr => $val) {
			$html_opts .= $attr . '="' . $val . '" ';
		}
	}
	$html_code = "";
	if(!isset($html['no-container'])) {
		$html_code = '<div class="form-field">';
	} 
	$html_code .= '<input type="password" 
				name="' . $name . '"
				id="' . $id . '"
				'.$html_opts. ' />';
	if(!isset($html['no-container'])) {
		$html_code .= "</div>";
	}

	return $html_code;
}

function datetime_field($en, $attr) {
	$html = '<div class="form-field">';
	$html .= '<label for="">'.$attr.'</label>';
	$html .= '<input type="text" name="" id="" value="' . $en->$attr . '" /> / ';
	$html .= '<input type="text" name="" id="" value="' . $en->$attr . '" /> / ';
	$html .= '<input type="text" name="" id="" value="' . $en->$attr . '" />';
	$html .= "</div>";

	return $html;
}

function hidden_field($en, $attr) {
	$html = '<input type="hidden" 
				name="' . $en->__get_entity_name() . '[' . $attr . ']" 
				value="' . $en->$attr . '"
				id="' . $en->__get_entity_name() . '_' . $attr . '"  />';
	return $html;
}


function hidden_field_tag($name, $id, $html = array()) {
	$html_opts = "";
	if(count($html) > 0) {
		foreach($html as $attr => $val) {
			$html_opts .= $attr . '="' . $val . '" ';
		}
	}
	$html = '<input type="hidden" 
				name="' . $name .'"
				id="' . $id . '" 
				'.$html_opts.' />';
	return $html;
}
function textarea_field($en, $attr, $label = null, $html_attrs = array()) {

	$html_opts = "";
	if(count($html_attrs) > 0) {
		foreach($html_attrs as $htmlattr => $val) {
			$html_opts .= $htmlattr . '="' . $val . '" ';
		}
	}


	$error = (isset($en->errors[$attr])) ? "validation-error" : "";
	$html = '<div class="form-field '.$error.'">';
	$label_text = $label;
	if($label === null) {
		$label_text = Makiavelo::titlelize($attr);
	}
	$html .= '<label for="'.$attr.'">'.$label_text.'</label>';
	$html .= '<textarea 
				name="' . $en->__get_entity_name() . '['.$attr.']" 
				id="' . $en->__get_entity_name() . '_' .$attr.'" 
				' . $html_opts .' >'. $en->$attr . '</textarea>';
	$html .= "</div>";

	return $html;
}


function date_field_tag($name, $id, $label = null, $html_attrs = array()) {

	$html_opts = "";
	if(count($html_attrs) > 0) {
		foreach($html_attrs as $htmlattr => $val) {
			$html_opts .= $htmlattr . '="' . $val . '" ';
		}
	}
	$html = "";
	if(isset($html_opts['no-container']) && !$html_opts['no-container']) {
		$html = '<div class="form-field '.$error.'">';
	}
	if($label != null) {
		$label_text = $label;
	} else {
		$label_text = Makiavelo::titlelize($id);
	}
	
	$html .= '<label for="'.$name.'">'.$label_text.'</label>';
	$html .= '<input type="text"
				class="date-field"
				name="' . $name .'"
				id="' . $id .'" 
				'. $html_opts .'
				/>';
	if(isset($html_opts['no-container']) && !$html_opts['no-container']) {
		$html .= "</div>";
	}

	return $html;
}

function date_field($en, $attr, $label = null) {

	$error = (isset($en->errors[$attr])) ? "validation-error" : "";
	$html = '<div class="form-field '.$error.'">';
	$label_text = $label;
	if($label == null) {
		$label_text = Makiavelo::titlelize($attr);
	}
	$html .= '<label for="'.$attr.'">'.$label_text.'</label>';
	$html .= '<input type="text"
				class="date-field"
				name="' . $en->__get_entity_name() . '['.$attr.']" 
				id="' . $en->__get_entity_name() . '_' .$attr.'" 
				value="' . $en->$attr . '" />';
	$html .= "</div>";

	return $html;
}

function time_field($en, $attr, $label = null) {

	$error = (isset($en->errors[$attr])) ? "validation-error" : "";
	$html = '<div class="form-field '.$error.'">';
	$label_text = $label;
	if($label == null) {
		$label_text = Makiavelo::titlelize($attr);
	}
	$html .= '<label for="'.$attr.'">'.$label_text.'</label>';
	$html .= '<input type="text"
				class="time-field"
				name="' . $en->__get_entity_name() . '['.$attr.']" 
				id="' . $en->__get_entity_name() . '_' .$attr.'" 
				value="' . $en->$attr . '" />';
	$html .= "</div>";

	return $html;
}

function link_to($url, $text, $html_code = "") {
	global $rHandler;

	$html_attrs = "";
	if($html_code != "" ) {
		foreach ($html_code as $key => $value) {
			$html_attrs .= ' ' . $key . '="' . $value . '"';
		}
	}

	if($rHandler->find_method_for($url) == Makiavelo::VIA_POST) {
		$html = form_tag($url, "post");
		$html .= '<a href="#" type="submit" '.$html_attrs.'>'.$text.'</a>';
		$html .= form_end_tag();
	} else {
		$html = '<a href="' . $url . '" ' . $html_attrs . '>' . $text . '</a>';
	}
	return $html;
}

function submit($text, $options = array()) {
	$html_attrs = "";
	if(count($options) > 0 ) {
		foreach ($options as $key => $value) {
			$html_attrs .= ' ' . $key . '="' . $value . '"';
		}
	}

	$html = '<input type="submit" value="'.$text.'" '.$html_attrs.' />';
	return $html;
}

function image_tag($path, $html_options = array()) {
	$html = '<img src="'.$path.'"';
	$attrs = array();
	foreach($html_options as $key => $value) {
		$attrs[] = $key . '="' . $value . '"';
	} 
	$html .= implode(" ", $attrs) . ' />';
	return $html;
}

function print_error($txt) {
	return '<div class="alert alert-error">
		<button type="button" class="close" data-dismiss="alert">×</button>
		<strong>Atención!</strong>' . $txt.'
	</div>';
}
?>