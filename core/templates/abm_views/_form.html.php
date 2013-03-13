
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
	[FORM_FIELD: \t<?={type}_field($this->entity, "{attr_name}")?>]

	<input  type ="submit" value="Save" />
	
<?=form_end_tag()?>
