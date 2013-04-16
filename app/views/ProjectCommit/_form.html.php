
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
	<?=text_field($this->entity, "id")?>

	<?=datetime_field($this->entity, "created_at")?>

	<?=datetime_field($this->entity, "updated_at")?>

	<?=text_field($this->entity, "project_id")?>

	<?=text_field($this->entity, "committer")?>

	<?=textarea_field($this->entity, "commit_message")?>

	<?=text_field($this->entity, "sha")?>



	<input  type ="submit" value="Save" />
	
<?=form_end_tag()?>
