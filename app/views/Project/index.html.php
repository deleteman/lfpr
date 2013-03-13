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
<div class="well">
	<h3>Filters</h3>
	<?php
	$opts = array();
	foreach(t("filters.languages") as $k => $v) {
		$opts[] = array($k, $v);
	}
	?>
	<label>Language</label>
	<?=select_field_tag("language", "lang", array("options" => $opts), array("class" => "span4"))?>
</div>
</div><!-- ¿? -->
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
			<?=link_to($entity->url, "See more", array("class" => "goto-github btn btn-primary btn-large", "target" => "_blank", "data-title" => "Go to Github's page"))?>
		</div>
	</div>

	<?php } ?>
</div>