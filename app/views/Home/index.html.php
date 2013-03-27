<div class="hero-unit">
<h3>Welcome to </h3>
<h1> Looking for Pull Request!</h1>
<p>
	Use the site to find a project to contribute to, <em>OR</em> publish your projects and let the 
	world know you're looking for contributors
</p>
	<p class="pull-left">
	<?=link_to(project_list_path(), "Find projects", array("class" => "btn btn-primary btn-large"))?>
	<?=link_to(project_new_path(), "Publish your project", array("class" => "btn btn-large"))?>
	</p>
	<div class="well span4 pull-right">
		<p>Subscribe to get weekly updates on the site and new repos published.</p>
		<?=form_for($this->suscriptor) ?>
			<?=email_field($this->suscriptor, "email", false, array("placeholder" => "your@email.com"));?>
			<?=submit("Suscribe", array("class" => "btn btn-primary btn-large"))?>
		<?=form_end_tag()?>
			</div>
	<div class="clearfix"></div>
</div>

	<h3>Latest projects added</h3>
	<div class="row latest-projects">
		<?php
		foreach($this->new_projects as $proj) { ?> 
		<div class="span3 text-center">
			<div class="project-spotlight">
				<h3><?=link_to(project_show_path($proj), truncate_string($proj->name, 13), array("class" => "proj-name-link"))?></a></h3>
				<div class="">
					<ul class="simple-stats pull-left">
						<li><span class="fui-menu-24"></span> <?=$proj->forks?> forks</li>
						<li><span class="fui-heart-24"></span> <?=$proj->stars?> stars</li>
					</ul>
					<ul class="simple-stats pull-left">
						<li><span class="fui-settings-24"></span> <?=$proj->language()?> </li>
					<li ><span class="fui-man-24"></span> <?=link_to(project_list_path(array("language" => "All", "owner" => $proj->owner()->name)),
										$proj->owner()->name,
										array("class" => "dev-link", "data-title" => "See all projects by this user"))?></li>
					</ul>	
					<div class="clearfix"></div>
				</div>
				
				<p><?=truncate_string($proj->description, 80)?></p>
			
				<?=link_to(project_show_path($proj), '<span class="fui-eye-24"></span> View Stats', array("class" => "btn btn-primary btn-large"))?>
<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$proj->url()?>" data-text="Checkout this cool project on @Github! " data-via="lookingfor_pr" data-count="none">Tweet about it!</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			</div>
		</div>
		<?php } ?>
		<div class="span3 text-center">
			<div class="project-spotlight">
				<h3>Your project</h3>
				<p>
					<img src="/img/illustrations/clipboard.png">
				</p>
				<?=link_to(project_new_path(), "Publish now!", array("class" => "btn btn-success btn-large"))?>
			</div>
		</div>
	</div>
