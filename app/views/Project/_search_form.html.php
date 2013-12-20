<h3>Search for projects.</h3>
<?=form_tag(project_search_path(), "GET") ?>
<?=text_field_tag("q", "q", false, array("placeholder" => "Enter your search here (i.e: project name, part of description, language)...", "value" => $this->q));?>
<?=submit("Search", array("class" => "btn btn-primary btn-large"))?>
<?=form_end_tag()?>
