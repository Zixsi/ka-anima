<div class="row">
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Группа <?=$item['name']?> (<?=$item['code']?>)</h3>
			</div>
			<div class="panel-body">
				<button type="button" class="btn btn-primary btn-xxs" id="group--add-user-btn">Добавить ученика</button>
				<table class="table">
					<thead>
						<tr>
							<th>Ученик</th>
							<th class="text-center">Выполеннные задания</th>
							<th class="text-center">Непроверенные работы</th>
						</tr>
					</thead>
					<tbody>
						<?if($users):?>
							<?foreach($users as $val):?>
								<tr <?=($user && $val['id'] == $user['id'])?'class="info"':''?>>
									<td><a href="./?user=<?=$val['id']?>"><?=$val['full_name']?></a></td>
									<td class="text-center"><?=$val['reviews']?> / <?=$item['cnt']?></td>
									<td class="text-center"><?=($val['homeworks'] - $val['reviews'])?></td>
								</tr>
							<?endforeach;?>
						<?else:?>
							<tr colspan="3">
								<td>Ученики отсутствуют</td>
							</tr>
						<?endif;?>
					</tbody>
				</table>
			</div>
		</div>
		<?if($users):?>
			<div class="panel">
				<?if($user):?>
					<div class="panel-heading">
						<h3 class="panel-title">
							<a href="/admin/users/user/<?=$user['id']?>/" target="_blank" style="text-decoration: underline;"><?=$user['full_name']?></a>
						</h3>
					</div>
					<div class="panel-body">
						<div id="group-user-lectures">				
							<?if($homeworks):?>
								<?$i = 1;?>
								<?foreach($homeworks as $val):?>
									<div class="card">
										<div class="head">
											<div class="title"><?=$i?> урок</div>
										</div>
										<div class="body">
											<?if($val['homework'] == false):?>
												<div class="title empty">ДЗ не загружено</div>
											<?elseif($val['review'] == false):?>
												<div class="title">Ученик выполнил задание</div>
												<p>Отчет не загружен</p>
											<?else:?>
												<div class="title">Отчет загружен</div>
												<p>Ученику будет доступен отчет в личном кабинете</p>
											<?endif;?>
										</div>
									</div>
									<?$i++;?>
								<?endforeach;?>
							<?endif;?>
						</div>
					</div>
				<?else:?>
					<h3 class="text-center">Выберите ученика</h3>
				<?endif;?>
			</div>
		<?endif;?>
	</div>
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Онлайн встречи</h3>
			</div>
			<div class="panel-body">
				<table class="table">
					<thead>
						<tr>
							<th>Название</th>
							<th>Дата</th>
							<th class="text-right">Статус</th>
						</tr>
					</thead>
					<tbody>
						<?if(is_array($streams)):?>
							<?foreach($streams as $val):?>
								<tr>
									<td>
										<a href="/admin/streams/item/<?=$val['id']?>/" target="_blank"><?=$val['name']?></a>
									</td>
									<td><?=date('d.m.Y H:i:00', strtotime($val['ts']))?></td>
									<td class="text-right">
										<?if($val['status'] == 0):?>
											<span class="label label-success">В процессе</span>
										<?elseif($val['status'] == -1):?>
											<span class="label label-danger">Завершено</span>
										<?elseif($val['status'] == 2):?>
											<span class="label label-info">Сегодня</span>
										<?else:?>
											<span class="label label-warning">Скоро</span>
										<?endif;?>
									</td>
								</tr>
							<?endforeach;?>
						<?else:?>
							<tr>
								<td colspan="3">Список встреч пуст</td>
							</tr>
						<?endif;?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>


<div class="modal fade" id="group--add-user-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Добавить ученика</h4>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="group_id" value="<?=$item['id']?>">
					<?if($item['type'] === 'standart'):?>
						<div class="form-group users-block">
							<select name="type" class="form-control">
								<option value="none">-- нет --</option>
								<option value="standart">Стандартная</option>
								<option value="advanced">Расширенная</option>
							</select>
						</div>
					<?else:?>
						<input type="hidden" name="type" value="<?=$item['type']?>">
					<?endif;?>
					<div class="form-group users-block">
						<div>
							<select name="user" class="form-control" id="select2-users" style="width: 100%;"></select>
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