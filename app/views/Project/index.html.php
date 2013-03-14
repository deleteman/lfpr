<div class="page-header">
<h1>
	List of published projects

	<?=link_to(project_new_path(), "Publish yours", array("class" => "pull-right btn btn-large"))?>
</h1>
</div>
<ul class="breadcrumb">
  <li><a href="<?=home_root_path_path()?>">Home</a> <span class="divider">/</span></li>
  <li class="active">List of projects</li>
</ul>
<div class="well project-filters">
	<form method="get" action="">
		<h3>Filters</h3>
		<?php
		$opts = array();
		foreach(get_valid_languages() as $v) {
			$opts[] = array($v, $v)	;
		}
		?>
		<div class="pull-left">
			<label>Language</label>
			<?=select_field_tag("language", "lang", array("options" => $opts, "selected" => $this->search_crit['lang']), array("class" => "span4"))?>
		</div>

		<div class="pull-left">
			<?=text_field_tag("owner", "owner", "Owner",	 array("placeholder" => "Project owner", "value" => $this->search_crit['owner']))?>
		</div>
		<?=submit("Filter", array("class" => "btn btn-large btn-primary pull-left"))?>
	</form>
	<div class="clearfix"></div>
</div>
<div class="row">
<?php foreach($this->entity_list as $idx => $entity) { ?>
	<div class="span3 text-center">
		<div class="project-spotlight">
			<h3><?=$entity->name?></h3>
			<ul class="simple-stats">
				<li><span class="fui-menu-24"></span> <?=$entity->forks?> forks</li>
				<li><span class="fui-heart-24"></span> <?=$entity->stars?> stars</li>
			</ul>	
			<p><?=$entity->description?></p>
			<p>By <a href="#" class="dev-link" data-title="Click here to see all of this users repos"><?=$entity->owner()->name?></a></p>

			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$entity->url?>" data-text="Checkout this cool project on @Github! " data-via="lookingfor_pr">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			<?=link_to($entity->url, "See more", array("class" => "goto-github btn btn-primary btn-large", "target" => "_blank", "data-title" => "Go to Github's page"))?>
		</div>
	</div>

	<?php } ?>
</div>