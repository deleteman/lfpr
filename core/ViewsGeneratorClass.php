<?php

class ViewsGenerator {

	private $type_mapping = array(
		"string" => "text",
		"integer" => "text", 
		"boolean" => "boolean",
		"datetime" => "datetime",
		"date" => "date",
		"time" => "time",
		"text" => "textarea",
		"float" => "text"
		);



	public function execute() {
		//@todo: generate from commend line
	}

	public function generateCRUDViewsFor($name, $attributes = array()) {
		Makiavelo::info("Generating VIEWS...");
		$views_folder = ROOT_PATH . Makiavelo::VIEWS_FOLDER . $name;
		@mkdir($views_folder);

		//Index file:
		$index_file_path = ROOT_PATH . Makiavelo::ABM_VIEWS_TEMPLATE_FOLDER . "index.html.php";

		$template_lines = file($index_file_path);
		if (count($template_lines) > 0) {
			foreach($template_lines as $i => $template_code) {
				#$template_code = fread($fp, filesize($index_file_path));

				$template_lines[$i] = str_replace("[NAME]", $name, $template_code);

				//Headers for the list
				preg_match("/\[HEADERS: (.*)\]/", $template_code, $match);
				if(isset($match[0]) && $match[0] != "") {
					$headers_template_tag = $match[0];
					$header_tag = $match[1];
					$html_headers = "";
					foreach ($attributes as $attr_name => $type) {
						$html_headers .= str_replace("{attr_name}", $attr_name, "$header_tag") . "\n";
					}
					$html_headers = str_replace("\\t", "\t", $html_headers);
					$template_code = preg_replace("/(\t+)\[/", "[", $template_code);
					$template_lines[$i] = str_replace($headers_template_tag, $html_headers, $template_code);
				}

				#Values for the list
				preg_match("/\[ATTRIBUTE_VALUE: (.*)\]/", $template_code, $match);
				if(isset($match[0]) && $match[0] != "") {
					$headers_template_tag = $match[0];
					$header_tag = $match[1];
					$html_headers = "";
					foreach ($attributes as $attr_name => $type) {
						$html_headers .= str_replace("{attr_name}", $attr_name, "$header_tag") . "\n";
					}
					$html_headers = str_replace("\\t", "\t", $html_headers);
					$template_code = preg_replace("/(\t+)\[/", "[", $template_code);
					$template_lines[$i] = str_replace($headers_template_tag, $html_headers, $template_code);
				}

				$template_lines[$i] = str_replace("[UC_NAME]", Makiavelo::camel_to_underscore($name), $template_lines[$i]);
				
			}			
			$destination_index = $views_folder . "/index.html.php";
			if(file_exists($destination_index)) {
				Makiavelo::info("view/index file already exists, not saving...");
			} else {
				$fp = fopen($destination_index, "w");
				if($fp) {
					//Save the file
					fwrite($fp, implode("", $template_lines));
					fclose($fp);
				}
			}
			
		}

		//New file:
		$new_file_path = ROOT_PATH . Makiavelo::ABM_VIEWS_TEMPLATE_FOLDER . "new.html.php";
		$fp = fopen($new_file_path, "r");
		if($fp) {
			$template_code = fread($fp, filesize($new_file_path));
			$template_code = str_replace("[NAME]", $name, $template_code);
		}
		$destination_new = $views_folder . "/new.html.php";
		if(file_exists($destination_new)) {
			Makiavelo::info("view/new file already exists, not saving...");
		} else {
			$fp = fopen($destination_new, "w");
			if($fp) {
				//Save the file
				fwrite($fp, $template_code);
				fclose($fp);
			}
		}

		//Show file:
		$show_file_path = ROOT_PATH . Makiavelo::ABM_VIEWS_TEMPLATE_FOLDER . "show.html.php";
		$fp = fopen($show_file_path, "r");
		if($fp) {
			$template_code = fread($fp, filesize($show_file_path));
			$template_code = str_replace("[NAME]", $name, $template_code);

			#Values for the list
			preg_match("/\[ATTRIBUTE: (.*)\]/", $template_code, $match);
			if(isset($match[0]) && $match[0] != "") {
				$attribute_template_tag = $match[0];
				$attribute_tag = $match[1];
				$attribute_list = "";
				foreach ($attributes as $attr_name => $type) {
					$attribute_list .= str_replace("{attr_name}", $attr_name, "$attribute_tag") . "\n";
				}
				$template_code = str_replace($attribute_template_tag, $attribute_list, $template_code);
			}

		}
		$destination_show = $views_folder . "/show.html.php";
		if(file_exists($destination_show))  {
			Makiavelo::info("view/show file already exists, not saving...");
		} else {
			$fp = fopen($destination_show, "w");
			if($fp) {
				//Save the file
				fwrite($fp, $template_code);
				fclose($fp);
			}
		}
		
		//Edit file:
		$edit_file_path = ROOT_PATH . Makiavelo::ABM_VIEWS_TEMPLATE_FOLDER . "edit.html.php";
		$fp = fopen($edit_file_path, "r");
		if($fp) {
			$template_code = fread($fp, filesize($edit_file_path));
			$template_code = str_replace("[NAME]", $name, $template_code);
		}
		$destination_edit = $views_folder . "/edit.html.php";
		if(file_exists($destination_edit))  {
			Makiavelo::info("view/edit file already exists, not saving...");
		} else {
			$fp = fopen($destination_edit, "w");
			if($fp) {
				//Save the file
				fwrite($fp, $template_code);
				fclose($fp);
			}
		}

		//_form file:
		$form_file_path = ROOT_PATH . Makiavelo::ABM_VIEWS_TEMPLATE_FOLDER . "_form.html.php";
		$form_file_lines = file($form_file_path);
		Makiavelo::info("Reading '$form_file_path'...");
		if(count($form_file_lines) > 0) {
			Makiavelo::info(count($form_file_lines) . " lines ...");
			foreach($form_file_lines as $i => $template_code) {
				//List of form fields

				preg_match("/\[FORM_FIELD: (.*)\]/", $template_code, $match);
				if(isset($match[0]) && $match[0] != "") {
					$form_template_tag = $match[0];
					$form_tag = $match[1];
					$html_form_fields= "";
					foreach ($attributes as $attr_name => $type) {
						$tmp = str_replace("{type}", $this->type_mapping[$type], "$form_tag") . "\n";
						$tmp = str_replace("{attr_name}", $attr_name, "$tmp") . "\n";
						$html_form_fields .= $tmp;
					}
					$html_form_fields = str_replace("\\t", "\t", $html_form_fields );
					$template_code = preg_replace("/(\t+)\[/", "[", $template_code);
					$form_file_lines[$i] = str_replace($form_template_tag, $html_form_fields, $template_code);
				}
			}
			
		}

		$destination_form = $views_folder . "/_form.html.php";
		if(file_exists($destination_form)) {
			Makiavelo::info("view/_form file already exists, not saving...");
		} else {
			$fp = fopen($destination_form, "w");
			if($fp) {
				//Save the file
				fwrite($fp, implode("", $form_file_lines));
				fclose($fp);
			}
		}
		
	}
}

?>