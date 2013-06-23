<div class="page-header">
<h1>
	List of User
</h1>

</div>
<table class="table table-striped">
	<tr>
		<th>id</th>
		<th>created_at</th>
		<th>updated_at</th>
		<th>username</th>
		<th>password</th>
		<th>role</th>

		<th></th>
	</tr>
	<?php foreach($this->entity_list as $idx => $entity) { ?>
		<tr>
			<td><?=$entity->id?></td>
			<td><?=$entity->created_at?></td>
			<td><?=$entity->updated_at?></td>
			<td><?=$entity->username?></td>
			<td><?=$entity->password?></td>
			<td><?=$entity->role?></td>

			<td>
				<?=link_to(user_update_path($entity),
							'<i class="icon-edit icon-white"></i> Edit',
							array("class" => "btn btn-warning"))?>
				<?=link_to(user_delete_path($entity),
							'<i class="icon-remove-sign icon-white"></i> Delete',
							array("class" => "btn btn-danger"))?>
			</td>
		</tr>
	<?php } ?>
</table>
<a href="<?=user_new_path() ?>">Add new one</a>
