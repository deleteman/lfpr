<div class="page-header">
<h1>
	List of ProjectCommit
</h1>

</div>
<table class="table table-striped">
	<tr>
		<th>id</th>
		<th>created_at</th>
		<th>updated_at</th>
		<th>project_id</th>
		<th>committer</th>
		<th>commit_message</th>
		<th>sha</th>

		<th></th>
	</tr>
	<?php foreach($this->entity_list as $idx => $entity) { ?>
		<tr>
			<td><?=$entity->id?></td>
			<td><?=$entity->created_at?></td>
			<td><?=$entity->updated_at?></td>
			<td><?=$entity->project_id?></td>
			<td><?=$entity->committer?></td>
			<td><?=$entity->commit_message?></td>
			<td><?=$entity->sha?></td>

			<td>
				<?=link_to(project_commit_update_path($entity),
							'<i class="icon-edit icon-white"></i> Edit',
							array("class" => "btn btn-warning"))?>
				<?=link_to(project_commit_delete_path($entity),
							'<i class="icon-remove-sign icon-white"></i> Delete',
							array("class" => "btn btn-danger"))?>
			</td>
		</tr>
	<?php } ?>
</table>
<a href="<?=project_commit_new_path() ?>">Add new one</a>
