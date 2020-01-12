<div class="row mb-4">
	<div class="col-6">
		<h3>Лекции</h3>
	</div>
	<div class="col-6 text-right">
		<a href="./add/" class="btn btn-primary">Добавить</a>
	</div>
</div>


<div class="card">
	<div class="card-body" style="padding-top: 30px;">
		<table class="table table-striped" id="admin-lectures-table">
			<thead>
				<tr>
					<th>#</th>
					<th>Id</th>
					<th>Статус</th>
					<th>Название</th>
					<th>Сортировка</th>
					<th class="text-right" width="200">Действие</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?$i = 0;?>
					<?foreach($items as $item):?>
						<tr>
							<td><?=$i?></td>
							<td><?=$item['id']?></td>
							<td>
								<?if($item['active']):?>
									<span class="badge badge-success">ENABLED</span>
								<?else:?>
									<span class="badge badge-danger">DISABLED</span>
								<?endif;?>
							</td>
							<td><?=$item['name']?></td>
							<td><?=$item['sort']?></td>
							<td class="text-right">
								<a href="./edit/<?=$item['id']?>/" class="btn btn-xs btn-primary d-inline-block" title="Редактировать">
									<i class="mdi mdi-pencil"></i>
								</a>
								<a href="javascript:void(0);" class="btn btn-xs btn-danger d-inline-block btn-item-remove" data-id="<?=$item['id']?>" title="Удалить">
									<i class="mdi mdi-delete"></i>
								</a>
							</td>
						</tr>
						<?$i++;?>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>

<div class="modal modal-compact fade" id="admin-remove-lecture-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="id" value="0">
					<h4 class="text-center">Вы действительно хотите удалить эту лекцию?</h4>
					<div class="form-groupp text-center pt-4">
						<button type="submit" class="btn btn-danger" style="margin-right: 10px;">Удалить</button>
						<button type="button" class="btn btn-secondary" style="margin-left: 10px;" data-dismiss="modal">Отмена</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>