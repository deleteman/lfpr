<div class="page-header">
<h1>
	List of [NAME]
</h1>

</div>
<table class="table table-striped">
	<tr>
		[HEADERS: \t\t<th>{attr_name}</th>]
		<th></th>
	</tr>
	<?php foreach($this->entity_list as $idx => $entity) { ?>
		<tr>
			[ATTRIBUTE_VALUE: \t\t\t<td><?=$entity->{attr_name}?></td>]
			<td>
				<?=link_to([UC_NAME]_update_path($entity),
							'<i class="icon-edit icon-white"></i> Edit',
							array("class" => "btn btn-warning"))?>
				<?=link_to([UC_NAME]_delete_path($entity),
							'<i class="icon-remove-sign icon-white"></i> Delete',
							array("class" => "btn btn-danger"))?>
			</td>
		</tr>
	<?php } ?>
</table>
<a href="<?=[UC_NAME]_new_path() ?>">Add new one</a>
