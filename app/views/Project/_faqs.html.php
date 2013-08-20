<?php
$active_user = current_user();
$owner = false;

if($active_user != null) { //User logged in
	$owner = $active_user->ownsProject($this->project);
}
?>
<?php
if($owner) {?>
<div class="accordion" id="accordion2">
  <div class="accordion-group">
    <div class="accordion-heading">
      <a class="accordion-toggle" data-toggle="collapse" data-parent="#accordion2" href="#collapseOne">
		<h2>Add a new question... </h2>
      </a>
    </div>
    <div id="collapseOne" class="accordion-body collapse ">
      <div class="accordion-inner">
      <?php
		if($owner) {
			$this->renderView("../Faq/_form");
		}
		?>
      </div>
    </div>
  </div>
</div>
<?php } ?>
<div class="well">
	<?php
	if(count($this->faqs_list) > 0) { ?>
	<h2>List of Frequently Asked Questions</h2>
	<ul class="faq_list">
	<?php
	foreach($this->faqs_list as $faq) {
		?>
		<li>
			<h3><?=htmlentities($faq->question)?></h3>
			<div class="answer">
				<?=htmlentities($faq->answer)?>
			</div>
		</li>
	<?php
	}
	?>
	</ul>
	<?php
} else { ?>
	<div class="alert alert-info">
	  <h3>No FAQ here!</h3> <br/> 
	  It looks like the owner of this repo hasn't created one yet.
	</div>
<?php } ?>
</div>
