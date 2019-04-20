<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Лекции</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="./add/" class="btn btn-primary btn-xs">Добавить</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<table class="table table-striped" id="admin-lectures-table">
			<thead>
				<tr>
					<th>Id</th>
					<th>Статус</th>
					<th>Название</th>
					<th>Сортировка</th>
					<th class="text-right">Действие</th>
				</tr>
			</thead>
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $item):?>
						<tr>
							<td><?=$item['id']?></td>
							<td>
								<?if($item['active']):?>
									<span class="label label-success">ENABLED</span>
								<?else:?>
									<span class="label label-danger">DISABLED</span>
								<?endif;?>
							</td>
							<td><?=$item['name']?></td>
							<td><?=$item['sort']?></td>
							<td class="text-right">
								<a href="./edit/<?=$item['id']?>/" class="btn btn-xxs btn-default lnr lnr-pencil" title="Редактировать"></a>
								<a href="javascript:void(0);" class="btn btn-xxs btn-danger lnr lnr-trash btn-item-remove" data-id="<?=$item['id']?>" title="Удалить"></a>
							</td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>

<div class="modal fade" id="admin-remove-lecture-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="id" value="0">
					<h4 class="text-center">Вы действительно хотите удалить эту лекцию?</h4>
					<div class="form-group text-center" style="padding-top: 20px;">
						<button type="submit" class="btn btn-danger" style="margin-right: 10px;">Удалить</button>
						<button type="button" class="btn btn-default" style="margin-left: 10px;" data-dismiss="modal">Отмена</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>