<div class="row">
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<div class="row">
					<div class="col-md-10">
						<h3 class="panel-title">Параметры</h3>
					</div>
					<div class="col-md-2 text-right">
						<button type="button">
							<i class="fas fa-ellipsis-v dropdown-toggle" data-toggle="dropdown"></i>
							<ul class="dropdown-menu" role="menu" style="right: 0; left: auto;">
								<li><a href="javascript:void(0);" class="btn-send-email-confirm" data-id="<?=$item['id']?>">Отправить подтверждение E-mail</a></li>
								<li class="divider"></li>
								<li><a href="javascript:void(0);" class="btn-user-block" data-id="<?=$item['id']?>">Заблокировать</a></li>
								<li><a href="javascript:void(0);" class="btn-user-delete" data-id="<?=$item['id']?>">Удалить</a></li>
							</ul>
						</button>
					</div>
				</div>
			</div>
			<div class="panel-body">
				<form class="" method="post" id="form-user-params">
					<input type="hidden" name="id" value="<?=$item['id']?>">
					<div class="form-group">
						<label>Группа</label>
						<select name="role" class="form-control">
							<?foreach($roles as $key => $val):?>
								<option value="<?=$key?>" <?=($key == $item['role'])?'selected="true"':''?>><?=$val?></option>
							<?endforeach;?>
						</select>
					</div>
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
							<input type="text" name="birthday" class="form-control" value="<?=$item['birthday']?>">
						</div>
						<div class="form-group col-md-6">
							<label>Телефон</label>
							<input type="text" name="phone" class="form-control" value="<?=$item['phone']?>">
						</div>
					</div>
					<h4>Сброс пароля</h4>
					<div class="row">
						<div class="form-group col-md-6">
							<label>Новый пароль</label>
							<input type="text" name="password" class="form-control" value="">
						</div>
						<div class="form-group col-md-6">
							<label>Повторить новый пароль</label>
							<input type="text" name="re_password" class="form-control" value="">
						</div>
					</div>
					<hr>
					<div class="form-group text-right">
						<button type="button" class="btn btn-primary">Сохранить</button>
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

