<div class="page-header">
<h1>
	List of Faq
</h1>

</div>
<table class="table table-striped">
	<tr>
		<th>id</th>
		<th>created_at</th>
		<th>updated_at</th>
		<th>project_id</th>
		<th>question</th>
		<th>answer</th>
		<th>order</th>

		<th></th>
	</tr>
	<?php foreach($this->entity_list as $idx => $entity) { ?>
		<tr>
			<td><?=$entity->id?></td>
			<td><?=$entity->created_at?></td>
			<td><?=$entity->updated_at?></td>
			<td><?=$entity->project_id?></td>
			<td><?=$entity->question?></td>
			<td><?=$entity->answer?></td>
			<td><?=$entity->order?></td>

			<td>
				<?=link_to(faq_update_path($entity),
							'<i class="icon-edit icon-white"></i> Edit',
							array("class" => "btn btn-warning"))?>
				<?=link_to(faq_delete_path($entity),
							'<i class="icon-remove-sign icon-white"></i> Delete',
							array("class" => "btn btn-danger"))?>
			</td>
		</tr>
	<?php } ?>
</table>
<a href="<?=faq_new_path() ?>">Add new one</a>
