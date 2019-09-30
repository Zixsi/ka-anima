<div class="row mb-4">
	<div class="col-6">
		<h3>Редактирование профиля</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../" class="btn btn-outline-primary">Назад</a>
	</div>
</div>

<div class="row">
	<div class="col-6 offset-3">
		<div class="card">
			<div class="card-body">
				<?=alert_error($error);?>
				<?//debug($user);?>
				<form action="" method="POST" enctype="multipart/form-data">
					<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
					<div class="row">
						<div class="col-12 mb-2 text-left">
							<img src="<?=$user['img']?>" width="100" height="100" style="border: 1px solid #f6f2f2;">
						</div>
						<div class="form-group col-12">
							<label for="ftitle">
								<span>Цель</span>
								<span class="mdi mdi-information-outline" title="" style="font-size: 16px; cursor: pointer;"
									data-toggle="tooltip" 
									data-placement="bottom" 
									data-original-title="максимум 255 символов"></span>
							</label>
							<input type="text" id="ftitle" name="title" class="form-control" placeholder="" value="<?=$user['title']?>">
						</div>
						<div class="form-group col-6">
							<label for="fperiod">E-mail</label>
							<input type="text" class="form-control" placeholder="" value="<?=$user['email']?>" disabled="true">
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
					</div>
					<div class="row">
						<div class="form-group col-6">
							<label for="fperiod">Имя</label>
							<input type="text" name="name" id="fname" class="form-control" placeholder="Имя" value="<?=set_value('name', $user['name'], true)?>">
						</div>
						<div class="form-group col-6">
							<label for="flastname">Фамилия</label>
							<input type="text" name="lastname" id="flastname" class="form-control" placeholder="Фамилия" value="<?=set_value('lastname', $user['lastname'], true)?>">
						</div>
						<div class="form-group col-6">
							<label for="fbirthday">Дата рождения</label>
							<input type="text" name="birthday" id="fbirthday" class="form-control datepiker" autocomplete="off" placeholder="01-01-1970" value="<?=set_value('birthday', date('d-m-Y', strtotime($user['birthday'])), true)?>">
						</div>
						<div class="form-group col-6">
							<label for="fphone">Телефон</label>
							<input type="text" name="phone" id="fphone" class="form-control" placeholder="Телефон" value="<?=set_value('phone', $user['phone'], true)?>">
						</div>
						<div class="form-group col-12">
							<label for="fsoc">Ссылка на профиль соцсети</label>
							<input type="text" name="soc" id="fsoc" class="form-control" placeholder="" value="<?=set_value('soc', $user['soc'], true)?>">
						</div>
						<div class="form-group col-12">
							<label for="fsoc">Ссылка на Discord</label>
							<input type="text" name="discord" id="fsoc" class="form-control" placeholder="" value="<?=set_value('discord', $user['discord'], true)?>">
						</div>
						<div class="form-group col-12">
							<button type="submit" class="btn btn-primary">Сохранить</button>
						</div>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>