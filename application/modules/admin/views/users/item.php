<div class="row">
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-10">
						<h3 class="panel-title">Параметры</h3>
					</div>
					<div class="col-md-2 text-right">
						<?if($item['role'] != '5'):?>
							<button type="button">
								<i class="fas fa-ellipsis-v dropdown-toggle" data-toggle="dropdown"></i>
								<ul class="dropdown-menu" role="menu" style="right: 0; left: auto;">
									<li><a href="javascript:void(0);" class="btn-send-email-confirm" data-id="<?=$item['id']?>">Отправить подтверждение E-mail</a></li>
									<li class="divider"></li>
									<?if($item['blocked'] == 0):?>
										<li><a href="javascript:void(0);" class="btn-user-block" data-id="<?=$item['id']?>">Блокировать</a></li>
									<?else:?>
										<li><a href="javascript:void(0);" class="btn-user-unblock" data-id="<?=$item['id']?>">Разблокировать</a></li>
									<?endif;?>
									<?if($item['deleted'] == 0):?>
										<li><a href="javascript:void(0);" class="btn-user-remove" data-id="<?=$item['id']?>">Удалить</a></li>
									<?endif;?>
								</ul>
							</button>
						<?endif;?>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<form class="" method="post" id="form-user-params">
					<input type="hidden" name="id" value="<?=$item['id']?>">
					<?if($item['role'] != '5'):?>
						<div class="form-group">
							<label>Роль</label>
							<select name="role" class="form-control">
								<option value="0" <?=($item['role'] == 0)?'selected="true"':''?>>ученик</option>
								<option value="1" <?=($item['role'] == 1)?'selected="true"':''?>>преподаватель</option>
							</select>
						</div>
					<?endif;?>
					<div class="form-group">
						<label>E-mail</label>
						<input type="text" name="email" class="form-control" readonly value="<?=$item['email']?>">
					</div>
					<div class="row">
						<div class="form-group col-md-6">
							<label>Фамилия</label>
							<input type="text" name="lastname" class="form-control" value="<?=$item['lastname']?>">
						</div>
						<div class="form-group col-md-6">
							<label>Имя</label>
							<input type="text" name="name" class="form-control" value="<?=$item['name']?>">
						</div>
						<div class="form-group col-md-6">
							<label>День рождения</label>
							<input type="text" name="birthday" class="form-control" readonly value="<?=$item['birthday']?>">
						</div>
						<div class="form-group col-md-6">
							<label>Телефон</label>
							<input type="text" name="phone" class="form-control" readonly value="<?=$item['phone']?>">
						</div>
					</div>
					<h4>Сброс пароля</h4>
					<div class="row">
						<div class="form-group col-md-6">
							<label>Новый пароль</label>
							<input type="password" name="password" class="form-control" value="">
						</div>
						<div class="form-group col-md-6">
							<label>Повторить новый пароль</label>
							<input type="password" name="re_password" class="form-control" value="">
						</div>
					</div>
					<hr>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary">Сохранить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Статистика</h3>
			</div>
			<div class="panel-body">
				<ul>
					<li>Дата регистрации</li>
					<li>Дата последней активности</li>
					<li>Баланс</li>
					<li>Список транзакций</li>
					<li>Список подписок</li>
				</ul>
			</div>
		</div>
	</div>
	<div class="col-xs-12">
		<div class="panel">
			<div class="panel-heading">
				<h3 class="panel-title">Лог действий</h3>
			</div>
			<div class="panel-body">
				Действия совершенные пользователем
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="remove-user-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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

<div class="modal fade" id="block-user-modal" tabindex="-1" role="dialog" aria-labelledby="" aria-hidden="true">
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