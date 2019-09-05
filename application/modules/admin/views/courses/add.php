<div class="row mb-4">
	<div class="col-6">
		<h3>Новый курс</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="col-xs-12">
			<?=ShowError($error);?>
			<form action="./" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<input type="hidden" name="only_standart" value="0">
					<div class="form-check d-inline-block m-0">
						<label class="form-check-label">
							<input type="checkbox" class="form-control" name="only_standart" value="1" <?=set_checkbox('only_standart', 1)?>>
							Только стандартный
						</label>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="fperiod">Название</label>
							<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', '', true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="fcode">Символьный код</label>
							<input type="text" name="code" id="fcode" class="form-control" placeholder="" value="<?=set_value('code', '', true)?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="fteacher">Преподаватель</label>
							<select name="teacher" id="fteacher" class="form-control">
								<option selected="true">- - -</option>
								<?foreach($teachers as $val):?>
									<option value="<?=$val['id']?>" <?=set_select('teacher', $val['id'])?> ><?=$val['full_name']?></option>
								<?endforeach;?>
							</select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Изображение</label>
							<input type="file" name="img" class="file-upload-default">
							<div class="input-group">
								<input type="text" class="form-control file-upload-info" disabled="" placeholder="Загрузка файла">
								<span class="input-group-append">
									<button class="file-upload-browse btn btn-secondary" type="button">Выбрать</button>
								</span>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Трейлер (ссылка на youtube)</label>
							<input type="text" name="trailer_url" class="form-control" placeholder="Трейлер (ссылка на youtube)" value="<?=set_value('trailer_url', '', true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Пример работ (ссылка на youtube)</label>
							<input type="text" name="examples_url" class="form-control" placeholder="Пример работ (ссылка на youtube)" value="<?=set_value('examples_url', '', true)?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="fdescription">Описание</label>
							<textarea name="description" id="fdescription" class="form-control" placeholder="Описание" rows="16"><?=set_value('description', '', true)?></textarea>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="fdescription">Стоимость</label>
							<?foreach($structure['price'] as $key => $val):?>
								<div class="row">
									<div class="col-2">
										<div>&nbsp;</div>
										<label class="col-form-label"><b><?=$key?></b></label>
									</div>
									<div class="col-5">
										<label for="fprice_month">Первый месяц</label>
										<div class="input-group">
											<input type="text" name="price[<?=$key?>][month]" class="form-control" value="<?=set_value('price['.$key.'][month]', 0, true)?>">
											<span class="input-group-append">
												<span class="input-group-text"><?=PRICE_CHAR?></span>
											</span>
										</div>
									</div>
									<div class="col-5">
										<label for="fprice_full">Полный курс</label>
										<div class="input-group">
											<input type="text" name="price[<?=$key?>][full]" class="form-control" value="<?=set_value('price['.$key.'][full]', 0, true)?>">
											<span class="input-group-append">
													<span class="input-group-text"><?=PRICE_CHAR?></span>
											</span>
										</div>
									</div>
								</div>
							<?endforeach;?>
						</div>
					</div>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>