<div class="row mb-4">
	<div class="col-6">
		<h3>FAQ разделы</h3>
	</div>
	<div class="col-6 text-right">
		<a href="./add/" class="btn btn-primary">Добавить</a>
	</div>
</div>

<div class="card">
	<div class="card-body" style="padding-top: 30px;">
		<table class="table table-striped">
			<thead>
				<tr>
					<th width="100">Id</th>
					<th>Название</th>
					<th class="text-right" width="200">Действие</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $item):?>
						<tr>
							<td><?=$item['id']?></td>
							<td><?=$item['name']?></td>
							<td class="text-right">
								<a href="./<?=$item['id']?>/" class="btn btn-xs btn-primary d-inline-block" title="Редактировать">
									<i class="mdi mdi-pencil"></i>
								</a>
								<form action="" method="post" class="delete-item-form d-inline-block">
									<input type="hidden" name="id" value="<?=$item['id']?>">
									<input type="hidden" name="type" value="delete">
									<button type="submit" class="btn btn-xs btn-danger" title="Удалить">
										<i class="mdi mdi-delete"></i>
									</button>
								</form>
							</td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>