<div class="row mb-4">
	<div class="col-6">
		<h3>Редактирование</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../" class="btn btn-secondary">Назад</a>
	</div>
</div>
<?//debug($item);?>
<div class="card">
	<div class="card-body">
		<div class="col-12">
			<?=alert_error($error);?>
			<?=show_flash_message();?>
			<form method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="row">
					<div class="col-12">
						<?if(!empty($item['img'])):?>
							<img src="<?=$item['img_src']?>" class="thumbnail mb-2" width="300">
						<?endif;?>
					</div>
					<div class="col-6">
						<div class="form-group">
							<input type="hidden" name="active" value="0">
							<div class="form-check d-inline-block m-0">
								<label class="form-check-label">
									<input type="checkbox" class="form-control" name="active" value="1" <?=set_checkbox('active', 1, ($item['active'] == 1))?> >
									Доступен?
								</label>
							</div>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<input type="hidden" name="only_standart" value="0">
							<div class="form-check d-inline-block m-0">
								<label class="form-check-label">
									<input type="checkbox" class="form-control" name="only_standart" value="1" <?=set_checkbox('only_standart', 1, ($item['only_standart'] == 1))?> >
									Только стандартный
								</label>
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="fname">Название</label>
							<input type="text" name="name" id="fname" class="form-control" placeholder="Название" value="<?=set_value('name', $item['name'], true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="fcode">Символьный код</label>
							<input type="text" name="" readonly="true" disabled="true" id="fcode" class="form-control" placeholder="" value="<?=set_value('code', $item['code'], true)?>">
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
									<option value="<?=$val['id']?>" <?=set_select('teacher', $val['id'], ($val['id'] === $item['teacher']))?> ><?=$val['full_name']?></option>
								<?endforeach;?>
							</select>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Изображение</label>
							<input type="hidden" name="img" value="<?=$item['img']?>">
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
							<input type="text" name="trailer_url" class="form-control" placeholder="Трейлер (ссылка на youtube)" value="<?=set_value('trailer_url', $item['trailer_url'], true)?>">
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Пример работ (ссылка на youtube)</label>
							<input type="text" name="examples_url" class="form-control" placeholder="Пример работ (ссылка на youtube)" value="<?=set_value('examples_url', $item['examples_url'], true)?>">
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Meta keywords</label>
							<textarea name="meta_keyword" id="" class="form-control" placeholder="" rows="8"><?=set_value('meta_keyword', $item['meta_keyword'], true)?></textarea>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Meta description</label>
							<textarea name="meta_description" id="" class="form-control" placeholder="" rows="8"><?=set_value('meta_description', $item['meta_description'], true)?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Основные программы</label>
							<textarea name="text_app_main" id="" class="form-control" placeholder="" rows="8"><?=set_value('text_app_main', $item['text_app_main'], true)?></textarea>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label>Дополнительные программы</label>
							<textarea name="text_app_other" id="" class="form-control" placeholder="" rows="8"><?=set_value('text_app_other', $item['text_app_other'], true)?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label>Вступительный текст</label>
							<textarea name="preview_text" id="" class="form-control" placeholder="" rows="8"><?=set_value('preview_text', $item['preview_text'], true)?></textarea>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="col-6">
						<div class="form-group">
							<label for="fdescription">Описание</label>
							<textarea name="description" id="fdescription" class="form-control" placeholder="Описание" rows="16"><?=htmlspecialchars_decode(set_value('description', $item['description'], true))?></textarea>
						</div>
					</div>
					<div class="col-6">
						<div class="form-group">
							<label for="">Стоимость</label>
							<?foreach($structure['price'] as $key => $val):?>
								<div class="row">
									<div class="col-2">
										<div>&nbsp;</div>
										<label class="col-form-label"><b><?=$key?></b></label>
									</div>
									<div class="col-5">
										<label for="fprice_month">Первый месяц</label>
										<div class="input-group">
											<input type="text" name="price[<?=$key?>][month]" class="form-control" value="<?=set_value('price['.$key.'][month]', $item['price'][$key]['month'], true)?>">
											<span class="input-group-append">
												<span class="input-group-text"><?=PRICE_CHAR?></span>
											</span>
										</div>
									</div>
									<div class="col-5">
										<label for="fprice_full">Полный курс</label>
										<div class="input-group">
											<input type="text" name="price[<?=$key?>][full]" class="form-control" value="<?=set_value('price['.$key.'][full]', $item['price'][$key]['full'], true)?>">
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

<link href="<?=TEMPLATE_DIR?>/tools/editor/styles/simditor.css" rel="stylesheet">
<script src="<?=TEMPLATE_DIR?>/tools/editor/scripts/module.js"></script>
<script src="<?=TEMPLATE_DIR?>/tools/editor/scripts/hotkeys.js"></script>
<script src="<?=TEMPLATE_DIR?>/tools/editor/scripts/uploader.js"></script>
<script src="<?=TEMPLATE_DIR?>/tools/editor/scripts/simditor.js"></script>

<script>
$(document).ready(function(){
	var editor_conf = ['title','bold','italic','underline','fontScale','ol','ul'];
	var editor = new Simditor({
		id: 'fdescription',
		textarea: $('#fdescription'),
		toolbar: editor_conf,
		pasteImage: false,
	});
});
</script>