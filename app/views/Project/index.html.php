<div class="page-header">
<h1>
	List of published projects


</h1>
</div>
<ul class="breadcrumb">
  <li><a href="<?=home_root_path_path()?>">Home</a> <span class="divider">/</span></li>
  <li class="active">List of projects</li>
</ul>
<div class="well project-filters">
	<form method="get" action="">
		<div class="span8">
			<h3>Filters</h3>
			<?php
			$opts = array();
			foreach(get_valid_languages() as $v) {
				$opts[] = array($v, $v)	;
			}
			?>
			<div class="pull-left">
				<label>Language</label>
				<?=select_field_tag("language", "lang", array("options" => $opts, "selected" => $this->search_crit['lang']), array("class" => "span4 dropkick-select"))?>
			</div>

			<div class="pull-left">
				<?=text_field_tag("owner", "owner", "Owner", array("placeholder" => "Project owner", "value" => $this->search_crit['owner']))?>
			</div>
			<?=submit("Filter", array("class" => "btn btn-large btn-primary pull-left"))?>
		</div>
		<div class="span3">
			<h3>Sort by</h3>
			<?php
			$opts = array();
			foreach(t("sort_by") as $k => $v) {
				$opts[] = array($k , $v);
			}
			?>
			<?=select_field_tag("sort", "sort", array("options" => $opts, "selected" => $this->search_crit['sort']), array("class" => "dropkick-select"))?>
		</div>
	</form>
	<div class="clearfix"></div>
</div>
	<?php
if(count($this->entity_list) > 0) {  ?>
<div class="row">
<?php foreach($this->entity_list as $idx => $entity) { ?>
	<div class="span3 text-center">
		<div class="project-spotlight">
			<h3><?=link_to(project_show_path($entity), truncate_string($entity->name, 13), array("class" => "proj-name-link"))?></a></h3>
			<div class="">
				<ul class="simple-stats pull-left">
					<li><span class="fui-menu-24"></span> <?=$entity->forks?> forks</li>
					<li><span class="fui-heart-24"></span> <?=$entity->stars?> stars</li>
				</ul>
				<ul class="simple-stats pull-left">
					<li><span class="fui-settings-24"></span> <?=$entity->language()?> </li>
				<li ><span class="fui-man-24"></span> <?=link_to(developer_show_path($entity->owner()),
									$entity->owner()->name,
									array("class" => "dev-link", "data-title" => "See all projects by this user"))?></li>
				</ul>	
				<div class="clearfix"></div>
			</div>
			<p><?=truncate_string($entity->description, 70)?></p>

			<a href="https://twitter.com/share" class="twitter-share-button" data-url="<?=$entity->url?>" data-text="Checkout this cool project on @Github!" data-via="lookingfor_pr">Tweet</a>
<script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0];if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src="//platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
			<?=link_to(project_show_path($entity), 
					'<span class="fui-eye-24"></span> View Stats', array("class" => "btn btn-primary btn-large" ))?>
		</div>
	</div>
	<?php } ?>
</div>
	<?php
} else { ?>
	<div class="alert alert-warning">
		<h2>So... this is a bit embarrassing...</h2>
		We were unable to find results for your search, please try again using different parameters :)
	</div>
	<?php
}
?>
<?php
if($this->pagination['total_pages'] > 1) { 
	$page = $this->pagination['current_page'];
	$total_pages = $this->pagination['total_pages'];
?>
<div class="pagination">
	<ul>
		<?php if($page > 0) { ?>
		<li class="previous">
			<a href="<?=project_list_path(array("p" => $page - 1, 
												"language" => $this->search_crit['lang'], 
												"owner" => $this->search_crit['owner'],
												"sort" => $this->search_crit['sort']))?>">
				<img src="/img/pager/previous.png">
			</a>
		</li>
		<?php  } ?>
		<?php
		for($i = 0; $i < $total_pages; $i++) { ?>
			<li class="<?=($i == $page) ? "active":""?>">
				<a href="<?=project_list_path(array("p" => $i, 
													"language" => $this->search_crit['lang'], 
													"owner" => $this->search_crit['owner'],
													"sort" => $this->search_crit['sort']))?>">
					<?=($i+1)?>
				</a>
			</li>
		<?php } ?>
		<?php if($page < $total_pages - 1) { ?>
		<li class="next">
			<a href="<?=project_list_path(array("p" => $page + 1, 
												"language" => $this->search_crit['lang'], 
												"owner" => $this->search_crit['owner'],
												"sort" => $this->search_crit['sort']))?>">
				<img src="/img/pager/next.png">
			</a>
		</li>
		<?php  } ?>
	</ul>
</div>
<?php } ?>
