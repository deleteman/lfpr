
<?= form_for($this->entity)?>
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
		<?=text_field($this->entity, "url", "Project's Github url", array("class" => "span10", "id" => "project-url-field"))?>
		<span class="">Enter the url for your project and the information below will be gathered using
			GitHub's API</span>
	</div>

	<div class="palette palette-info-dark">
	<?=text_field($this->entity, "name")?>

	<?=textarea_field($this->entity, "description")?>

	<?=text_field($this->entity, "owner_id")?>

	<?=text_field($this->entity, "stars")?>

	<?=text_field($this->entity, "forks")?>

	<?=datetime_field($this->entity, "last_update")?>
	<?=text_field($this->entity, "language")?>
</div>
	<input  type ="submit" value="Save" class="btn btn-primary btn-large pull-right" />
	<?=link_to(project_list_path(), "Go back to the list", array("class" => "btn btn-large"))?>
<?=form_end_tag()?>
