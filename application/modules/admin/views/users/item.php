<div class="row">
	<div class="col-6">

		<div class="card mb-4">
			<div class="card-header">
				<div class="row">
					<div class="col-8">
						<h3 class="card-title pt-2">Параметры</h3>
					</div>
					<div class="col-4 text-right">
						<?if($item['role'] != '5'):?>
							<div class="btn-group">
								<button type="button" class="btn btn-sm btn-light" data-toggle="dropdown">
									<i class="mdi mdi-dots-horizontal"></i>
								</button>
								<ul class="dropdown-menu dropdown-menu-right navbar-dropdown">
									<li class="dropdown-item btn-send-email-confirm" data-id="<?=$item['id']?>">
										<span>Отправить подтверждение E-mail</span>
									</li>
									<li class="dropdown-divider"></li>
									<?if($item['blocked'] == 0):?>
										<li class="dropdown-item btn-user-block" data-id="<?=$item['id']?>">
											<span>Блокировать</span>
										</li>
									<?else:?>
										<li class="dropdown-item btn-user-unblock" data-id="<?=$item['id']?>">
											<span>Разблокировать</span>
										</li>
									<?endif;?>
									<?/*if($item['deleted'] == 0):?>
										<li class="dropdown-item">
											<a href="javascript:void(0);" class="btn-user-remove" data-id="<?=$item['id']?>">Удалить</a>
										</li>
									<?endif;*/?>
								</ul>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
			<div class="card-body">
				<form class="" method="post" id="form-user-params" enctype="multipart/form-data">
					<input type="hidden" name="id" value="<?=$item['id']?>">
					<div class="row pb-4">
						<div class="col-6">
							<input type="hidden" name="img" value="<?=$item['img']?>">
							<img src="<?=$item['img']?>" width="100" height="100" style="border: 1px solid #f6f2f2;">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-6">
							<label>E-mail</label>
							<input type="text" name="email" class="form-control" readonly value="<?=$item['email']?>">
						</div>
						<div class="form-group col-6">
							<label for="fperiod">
								<span>Фото</span>
								<span class="mdi mdi-information-outline" title="" style="font-size: 16px; cursor: pointer;"
									data-toggle="tooltip" 
									data-placement="bottom" 
									data-original-title="<?=UPLOAD_PROFILE_IMG_INFO?>"></span>
							</label>
							<input type="file" name="img" class="file-upload-default">
							<div class="input-group">
								<input type="text" class="form-control file-upload-info" disabled="" placeholder="Загрузка файла">
								<span class="input-group-append">
									<button class="file-upload-browse btn btn-primary" type="button">Выбрать</button>
								</span>
							</div>
						</div>
						<div class="form-group col-12">
							<label>Ссылка на статью в блоге</label>
							<input type="text" name="blog_url" class="form-control" value="<?=$item['blog_url']?>">
						</div>
						<?if($item['role'] != '5'):?>
							<div class="form-group col-6">
								<label>Роль</label>
								<select name="role" class="form-control">
									<option value="0" <?=($item['role'] == 0)?'selected="true"':''?>>ученик</option>
									<option value="1" <?=($item['role'] == 1)?'selected="true"':''?>>преподаватель</option>
								</select>
							</div>
						<?endif;?>
					</div>
					<div class="row">
						<div class="form-group col-6">
							<label>Фамилия</label>
							<input type="text" name="lastname" class="form-control" value="<?=$item['lastname']?>">
						</div>
						<div class="form-group col-6">
							<label>Имя</label>
							<input type="text" name="name" class="form-control" value="<?=$item['name']?>">
						</div>
						<div class="form-group col-6">
							<label>День рождения</label>
							<input type="text" name="birthday" class="form-control datepiker" value="<?=date(DATE_FORMAT_SHORT, strtotime($item['birthday']))?>">
						</div>
						<div class="form-group col-6">
							<label>Телефон</label>
							<input type="text" name="phone" class="form-control" value="<?=$item['phone']?>">
						</div>
						<div class="form-group col-6">
							<label>
								<span>Цель</span>
								<span class="mdi mdi-information-outline" title="" style="font-size: 16px; cursor: pointer;"
									data-toggle="tooltip" 
									data-placement="bottom" 
									data-original-title="максимум 255 символов"></span>
							</label>
							<input type="text" name="title" class="form-control" value="<?=$item['title']?>">
						</div>
						<div class="form-group col-6">
							<label>Соцсеть</label>
							<input type="text" name="soc" class="form-control" value="<?=$item['soc']?>">
						</div>
						<div class="form-group col-6">
							<label>Ссылка на Discord</label>
							<input type="text" name="discord" class="form-control" value="<?=$item['discord']?>">
						</div>
					</div>
					<h4>Сброс пароля</h4>
					<div class="row">
						<div class="form-group col-6">
							<label>Новый пароль</label>
							<input type="password" name="password" class="form-control" value="">
						</div>
						<div class="form-group col-6">
							<label>Повторить новый пароль</label>
							<input type="password" name="re_password" class="form-control" value="">
						</div>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Сохранить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-6">
		<div class="card mb-4">
			<div class="card-header">
				<h3 class="card-title pt-2">Информация</h3>
			</div>
			<div class="card-body">
				<p>
					<?if($item['active'] == 1):?>
						<button class="btn btn-success"><i class="mdi mdi-check"></i>Активирован</button>
					<?else:?>
						<button class="btn btn-danger"><i class="mdi mdi-close"></i>Неактивирован</button>
					<?endif;?>

					<?if($item['blocked'] == 1):?>
						<button class="btn btn-danger"><i class="mdi mdi-lock"></i> Заблокирован</button>
					<?endif;?>
					
					<?if($item['deleted'] == 1):?>
						<button class="btn btn-danger"><i class="mdi mdi-delete"></i> Удален</button>
					<?endif;?>
				</p>
				<p>Дата регистрации: <?=date(DATE_FORMAT_FULL, strtotime($item['ts_created']))?></p>
				<p>Дата последней активности: <?=date(DATE_FORMAT_FULL, strtotime($item['ts_last_active']))?></p>
			</div>
		</div>
		<?if((int) $item['role'] == 0):?>
			<div class="card">
				<div class="card-header">
					<h3 class="card-title pt-2">Подписки</h3>
				</div>
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="text-center">Название</th>
								<th class="text-center">Начинается</th>
								<th class="text-center">Заканчивается</th>
								<th class="text-center">Статус</th>
								<th class="text-center">Осталось оплатить</th>
							</tr>
						</thead>
						<tbody>
							<?if($subscribes):?>
								<?foreach($subscribes as $val):?>
									<tr>
										<td>
											<a href="/admin/groups/<?=$val['code']?>/"><?=$val['description']?></a>
										</td>
										<td class="text-center"><?=date(DATE_FORMAT_SHORT, strtotime($val['ts_start']))?></td>
										<td class="text-center"><?=date(DATE_FORMAT_SHORT, strtotime($val['ts_end']))?></td>
										<td class="text-center">
											<?if($val['active']):?>
												<span class="badge badge-success">Активный</span>
											<?else:?>
												<span class="badge badge-danger">Истек</span>
											<?endif;?>	
										</td>
										<td class="text-center">
											<?if($val['amount'] > 0):?>
												<?=number_format($val['amount'], 2, '.', '')?> <?=PRICE_CHAR?>
											<?else:?>
												<span>- - -</span>
											<?endif;?>
										</td>
									</tr>
								<?endforeach;?>
							<?endif;?>
						</tbody>
					</table>
				</div>
			</div>
		<?elseif((int) $item['role'] === 1):?>
			<div class="card mb-4">
				<div class="card-header">
					<h3 class="card-title pt-2">Онлайн встречи</h3>
				</div>
				<div class="card-body">
					<table class="table table-bordered">
						<thead>
							<tr>
								<th class="text-center">Название</th>
								<th class="text-center">Начинается</th>
								<th class="text-center">Статус</th>
							</tr>
						</thead>
						<tbody>
							<?if($streams):?>
								<?foreach($streams as $val):?>
									<tr>
										<td>
											<a href="/admin/streams/item/<?=$val['id']?>/"><?=$val['name']?></a>
										</td>
										<td class="text-center"><?=date('Y-m-d H:i:00', strtotime($val['ts']))?></td>
										<td class="text-center">
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
							<?endif;?>
						</tbody>
					</table>
				</div>
			</div>
		<?endif;?>
	</div>
</div>

<?if((int) $item['role'] === 0):?>
	<div class="row">
		<div class="col-12">
			<div class="card mb-4">
				<div class="card-header">
					<h3 class="card-title pt-2">Платежи</h3>
				</div>
				<div class="card-body">
					<?//debug($transactions);?>
					<?if($transactions):?>
						<table class="table table-bordered">
							<thead>
								<tr>
									<th width="180">Дата</th>
									<th>Описание</th>
									<th>Сумма</th>
									<th width="200">Хеш</th>
									<th class="text-right" width="120">Статус</th>
								</tr>
							</thead>
							<tbody>
							<?foreach($transactions as $item):?>
								<tr>
									<td><?=$item['ts_f']?></td>
									<td><?=$item['description']?></td>
									<td><?=(($item['type'] === TransactionsModel::TYPE_OUT)?'-':'+')?><?=$item['amount_f']?>  <?=PRICE_CHAR?></td>
									<td><?=$item['pay_system_hash']?></td>
									<td class="text-right"><?=$item['status']?></td>
								</tr>
							<?endforeach;?>
							</tbody>
						</table>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
<?endif;?>

<div class="row">
	<div class="col-12">
		<div class="card mb-4">
			<div class="card-header">
				<h3 class="card-title pt-2">Действия</h3>
			</div>
			<div class="card-body">
				<?if($actions):?>
					<table class="table table-bordered">
						<thead>
							<tr>
								<th width="180">Дата</th>
								<th width="180">Действие</th>
								<th>Описание</th>
							</tr>
						</thead>
						<tbody>
						<?foreach($actions as $item):?>
							<tr>
								<td><?=date(DATE_FORMAT_FULL, strtotime($item['date']))?></td>
								<td><?=$item['action']?></td>
								<td><?=$item['description']?></td>
							</tr>
						<?endforeach;?>
						</tbody>
					</table>
				<?endif;?>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-compact fade" id="remove-user-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="id" value="0">
					<h4 class="text-center">Вы действительно хотите удалить этого пользователя?</h4>
					<div class="form-group text-center" style="padding-top: 20px;">
						<button type="submit" class="btn btn-danger" style="margin-right: 10px;">Удалить</button>
						<button type="button" class="btn btn-default" style="margin-left: 10px;" data-dismiss="modal">Отмена</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="modal modal-compact fade" id="block-user-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
	<div class="modal-dialog modal-md">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post">
					<input type="hidden" name="id" value="0">
					<h4 class="text-center">Вы действительно хотите заблокировать этого пользователя?</h4>
					<div class="form-group text-center" style="padding-top: 20px;">
						<button type="submit" class="btn btn-danger" style="margin-right: 10px;">Подтвердить</button>
						<button type="button" class="btn btn-default" style="margin-left: 10px;" data-dismiss="modal">Отмена</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>