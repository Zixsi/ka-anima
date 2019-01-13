<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Редактирование профиля</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="../" class="btn btn-default btn-xs">Назад</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="col-xs-12">
			<?=alert_error($error);?>
			<form action="" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<label for="fperiod">Имя</label>
					<input type="text" name="name" id="fname" class="form-control" placeholder="Имя" value="<?=set_value('name', $user['name'], true)?>">
				</div>
				<div class="form-group">
					<label for="flastname">Фамилия</label>
					<input type="text" name="lastname" id="flastname" class="form-control" placeholder="Фамилия" value="<?=set_value('lastname', $user['lastname'], true)?>">
				</div>
				<div class="form-group">
					<label for="fbirthday">Дата рождения</label>
					<input type="text" name="birthday" id="fbirthday" class="form-control datepiker" autocomplete="off" placeholder="01-01-1970" value="<?=set_value('birthday', date('d-m-Y', strtotime($user['birthday'])), true)?>">
				</div>
				<div class="form-group">
					<label for="fphone">Телефон</label>
					<input type="text" name="phone" id="fphone" class="form-control" placeholder="Телефон" value="<?=set_value('phone', $user['phone'], true)?>">
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-xs btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>