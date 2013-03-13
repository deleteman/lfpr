<div class="page-header">
<h1>
	List of published projects
</h1>
</div>
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
<table class="table table-striped">
	<tr>
		<th>id</th>
		<th>created_at</th>
		<th>updated_at</th>
		<th>name</th>
		<th>url</th>
		<th>description</th>
		<th>owner_id</th>
		<th>stars</th>
		<th>forks</th>
		<th>last_update</th>

		<th></th>
	</tr>
	<?php foreach($this->entity_list as $idx => $entity) { ?>
		<tr>
			<td><?=$entity->id?></td>
			<td><?=$entity->created_at?></td>
			<td><?=$entity->updated_at?></td>
			<td><?=$entity->name?></td>
			<td><?=$entity->url?></td>
			<td><?=$entity->description?></td>
			<td><?=$entity->owner_id?></td>
			<td><?=$entity->stars?></td>
			<td><?=$entity->forks?></td>
			<td><?=$entity->last_update?></td>

			<td>
				<?=link_to(project_update_path($entity),
							'<i class="icon-edit icon-white"></i> Edit',
							array("class" => "btn btn-warning"))?>
				<?=link_to(project_delete_path($entity),
							'<i class="icon-remove-sign icon-white"></i> Delete',
							array("class" => "btn btn-danger"))?>
			</td>
		</tr>
	<?php } ?>
</table>
<a href="<?=project_new_path() ?>">Add new one</a>
