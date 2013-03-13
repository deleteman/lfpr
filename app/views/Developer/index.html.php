<div class="page-header">
<h1>
	List of Developer
</h1>

</div>
<table class="table table-striped">
	<tr>
		<th>id</th>
		<th>created_at</th>
		<th>updated_at</th>
		<th>name</th>
		<th>avatar_url</th>
		<th>github_url</th>

		<th></th>
	</tr>
	<?php foreach($this->entity_list as $idx => $entity) { ?>
		<tr>
			<td><?=$entity->id?></td>
			<td><?=$entity->created_at?></td>
			<td><?=$entity->updated_at?></td>
			<td><?=$entity->name?></td>
			<td><?=$entity->avatar_url?></td>
			<td><?=$entity->github_url?></td>

			<td>
				<?=link_to(developer_update_path($entity),
							'<i class="icon-edit icon-white"></i> Edit',
							array("class" => "btn btn-warning"))?>
				<?=link_to(developer_delete_path($entity),
							'<i class="icon-remove-sign icon-white"></i> Delete',
							array("class" => "btn btn-danger"))?>
			</td>
		</tr>
	<?php } ?>
</table>
<a href="<?=developer_new_path() ?>">Add new one</a>
