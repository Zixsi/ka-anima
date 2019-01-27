<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Новый вопрос</h3>
		</div>
		<div class="col-xs-6 text-right">
			<a href="../" class="btn btn-default btn-xs">Назад</a>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="col-xs-12">
			<?=alert_error($error);?>
			<form action="./" method="POST" enctype="multipart/form-data">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<label for="fquestion">Вопрос</label>
					<input type="text" name="question" id="fquestion" class="form-control" value="<?=set_value('question', '', true)?>">
				</div>
				<div class="form-group">
					<label for="fanswer">Ответ</label>
					<textarea name="answer" id="fanswer" class="form-control" rows="10"><?=set_value('answer', '', true)?></textarea>
				</div>
				<div class="form-group">
					<button type="submit" class="btn btn-xs btn-primary">Сохранить</button>
				</div>
			</form>
		</div>
	</div>
</div>