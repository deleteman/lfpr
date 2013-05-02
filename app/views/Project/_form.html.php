<ul class="breadcrumb">
  <li><a href="<?=home_root_path_path()?>">Home</a> <span class="divider">/</span></li>
  <li><a href="<?=project_list_path()?>">List of projects</a> <span class="divider">/</span></li>
  <li class="active">Publish your project</li>

</ul>
<?= form_for($this->entity)?>

	<div class="alert alert-error" id="error-box">

	</div>

	<?php if(count($this->entity->errors) > 0) { ?>
    <div id="error_explanation">
      <h2>Error saving entity: </h2>

      <ul>
        <?php foreach($this->entity->errors as $msg) { ?>
              <?php foreach($msg as $err_msg) { ?>
                  <li><?= $err_msg ?></li>
        <?php } 
      } ?>
      </ul>
    </div>
    <?php } ?>

    <div class="palette palette-info">
		<?=text_field($this->entity, "url", "Project's Github url", array("class" => "span10", "id" => "project-url-field", "no-container" => true))?>
		<?=link_to("#", "Query", array("class" => "btn btn-primary", "id" => "query-btn"))?>
		<br/>
		<span class="">Enter the url for your project and the information below will be gathered using
			GitHub's API</span>
	</div>

	<div class="palette palette-info-dark">
	<?=hidden_field_tag("owner_avatar", "owner_avatar")?>
	<?=text_field($this->entity, "name", "Project's Name", array("class" => "span6"))?>

	<?=textarea_field($this->entity, "description", "Project's description", array("class" => "span10"))?>

	<div class="one-line-form-elements">
		<?=text_field_tag("owner_name", "project_owner_name", "Project Owner")?>

		<?=text_field($this->entity, "language")?>
    <?=text_field($this->entity, "closed_issues", false, array("class" => "hidden"))?>
    <?=text_field($this->entity, "open_issues", false, array("class" => "hidden"))?>
		<?=text_field($this->entity, "stars", false, array("class" => "hidden"))?>
		<?=text_field($this->entity, "forks", false, array("class" => "hidden"))?>
		<?=text_field($this->entity, "last_update", false, array("class" => "hidden"))?>
	</div>
	<div class="one-line-form-elements fake-fields">
    <div class="form-field row">
      <label>Closed issues</label>
      <div class="span2 field-replacement" id="closed_issues_field" ></div>
    </div>
    <div class="form-field row">
      <label>Open issues</label>
      <div class="span2 field-replacement" id="open_issues_field" ></div>
    </div>
		<div class="form-field row">
			<label>Stars</label>
			<div class="span1 field-replacement" id="stars_field" ></div>
		</div>
		<div class="form-field row">
			<label>Forks</label>
			<div class="span1 field-replacement" id="forks_field" ></div>
		</div>
		<div class="form-field row">
			<label>Last Update</label>
			<div class="span3 field-replacement" id="last_update_field" ></div>
		</div>
	</div>
	<input  type ="submit" value="Send info" disabled class="btn btn-primary btn-large pull-right disabled" id="save-project-btn"/>
	<div class="clearfix"></div>
</div>
	<?//=link_to(project_list_path(), "Go back to the list", array("class" => "btn btn-large"))?>
<?=form_end_tag()?>
