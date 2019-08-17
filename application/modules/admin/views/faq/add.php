<div class="row mb-4">
	<div class="col-6">
		<h3>Новый вопрос</h3>
	</div>
	<div class="col-6 text-right">
		<a href="../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<div class="col-12">
			<?=alert_error($error);?>
			<form action="" method="POST">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<label>Категория</label>
					<select name="section" class="form-control">
						<option value="0" <?=set_select2('section', 0, true)?>>-- нет --</option>
						<?foreach($sections as $val):?>
							<option value="<?=$val['id']?>" <?=set_select2('section', $val['id'])?>><?=$val['name']?></option>
						<?endforeach;?>
					</select>
				</div>
				<div class="form-group">
					<label for="fquestion">Вопрос</label>
					<input type="text" name="question" id="fquestion" class="form-control" value="<?=set_value('question', '', true)?>">
				</div>
				<div class="form-group">
					<label for="fanswer">Ответ</label>
					<textarea name="answer" id="fanswer" class="form-control" rows="10"><?=set_value('answer', '', true)?></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>