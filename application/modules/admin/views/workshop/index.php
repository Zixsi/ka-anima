<div class="row mb-4">
	<div class="col-12">
		<div class="float-right">
			<a href="/admin/workshop/add/" class="btn btn-outline-primary">Добавить</a>
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
							<th>ID</th>
							<th>Дата</th>
							<th>Статус</th>
							<th>Тип</th>
							<th>Название</th>
							<th>Куплено</th>
							<th class="text-right">Действие</th>
						</tr>
					</thead>
					<tbody>
						<?foreach($items as $row):?>
							<tr>
								<td><?=$row['id']?></td>
								<td><?=$row['date']?></td>
								<td>
									<?if((int) $row['status'] === 1):?>
										<span class="badge badge-success">enabled</span>		
									<?else:?>
										<span class="badge badge-secondary">disabled</span>
									<?endif;?>
								</td>
								<td><?=$row['type']?></td>
								<td><?=$row['title']?></td>
								<td><?=($subscribeCount[$row['id']] ?? 0)?></td>
								<td class="text-right">
									<div class="btn-group">
										<a href="javascript:void(0);" class="btn btn-primary btn-sm add-user-workshop" data-id="<?=$row['id']?>" data-target="#add-user-workshop-modal" title="Добавить пользователя">
											<i class="fa fa-user-plus"></i>
										</a>
										<a href="/admin/workshop/view/<?=$row['id']?>/" class="btn btn-primary btn-sm" title="Просмотр">
											<i class="fa fa-eye"></i>
										</a>
										<a href="/admin/workshop/item/<?=$row['id']?>/" class="btn btn-primary btn-sm" title="Редактирование">
											<i class="fa fa-edit"></i>
										</a>
										<!-- <a href="javascript:void(0);" class="btn btn-danger btn-sm">
											<i class="fa fa-trash"></i>
										</a> -->
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

<div class="modal fade" id="add-user-workshop-modal" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Добавить ученика</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="id" value="">
					<div class="form-group users-block">
						<div>
							<select name="user" class="form-controll" id="select2-users" style="width: 100%;"></select>
						</div>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Добавить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>