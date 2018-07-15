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
		<table class="table table-striped">
			<thead>
				<tr>
					<th>#</th>
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
								<a href="#" class="btn btn-xxs btn-danger lnr lnr-trash" title="Удалить"></a>
							</td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>