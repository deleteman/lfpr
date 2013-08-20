
<?= form_for($this->faq, "create", array("id" => "new_faq"))?>
	<?php if(count($this->faq->errors) > 0) { ?>
    <div id="error_explanation">
      <h2>Error saving FAQ: </h2>

      <ul>
        <?php foreach($this->faq->errors as $msg) { ?>
              <?php foreach($msg as $err_msg) { ?>
                  <li><?= $err_msg ?></li>
        <?php } 
      } ?>
      </ul>
    </div>
    <?php } ?>

	<?=hidden_field($this->faq, "project_id")?>

	<?=text_field($this->faq, "question", "", array("class" => "question_txt", "placeholder" => "Question text here..."))?>

	<?=textarea_field($this->faq, "answer", "", array("class" => "answer_txt", "placeholder" => "The answer goes here..."))?>

	<input  type ="submit" value="Save question" class="btn btn-primary"/>
	
<?=form_end_tag()?>
