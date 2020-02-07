<div class="row mb-4">
	<div class="col-12">
		<div class="float-right">
			<a href="/admin/promocode/add/" class="btn btn-outline-primary">Добавить</a>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
<?//debug($items);?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<table class="table table-condensed">
					<thead>
						<tr>
							<th>Код</th>
							<th>Значение, %</th>
							<th>Тип</th>
							<th>Кол-во</th>
							<th>Дата начала</th>
							<th>Дата завершения</th>
							<th class="text-right">Действие</th>
						</tr>
					</thead>
					<tbody>
						<?foreach($items as $row):?>
							<tr>
								<td><?=$row['code']?></td>
								<td><?=($row['value'] ?? 0)?>%</td>
								<td><?=($types[$row['target_type']] ?? 'Все')?></td>
								<td><?=(int) ($row['count'] ?? 0)?></td>
								<td><?=$row['date_from']?></td>
								<td><?=$row['date_to']?></td>
								<td class="text-right">
									<div class="btn-group">
										<a href="/admin/promocode/item/<?=$row['id']?>/" class="btn btn-primary btn-sm" title="Редактирование">
											<i class="fa fa-edit"></i>
										</a>
										<?/*
										<form action="" method="post">
											<input type="hidden" name="id" value="<?=$row['id']?>">
											<button type="submit" class="btn btn-danger btn-sm">
												<i class="fa fa-trash"></i>
											</button>
										</form>*/?>
									</div>
								</td>
							</tr>
						<?endforeach;?>
					</tbody>
				</table>	
			</div>
		</div>
	</div>
</div>