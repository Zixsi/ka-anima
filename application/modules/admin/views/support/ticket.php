<div id="support-ticket">
	<div class="row mb-4">
		<div class="col-10">
			<h3>Обращение - <?=$item['code']?></h3>
		</div>
		<div class="col-2 text-right">
			<a href="/admin/support/" class="btn btn-outline-primary">Назад</a>
		</div>
	</div>
	<div class="card mb-4">
		<div class="card-body">
			<div class="row mb-2">
				<div class="col-8"><?=$item['date_f']?></div>
				<div class="col-4 text-right">
					<?/*<span class="badge badge-<?=$item['status_info']['class']?>"><?=$item['status_info']['text']?></span>*/?>
					<form action="" method="post">
						<input type="hidden" name="type" value="update">
						<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
						<div class="input-group">
							<select class="custom-select" name="status">
								<?foreach($statusList as $val):?>
									<option value="<?=$val['code']?>" <?=($val['code'] === $item['status'])?'selected="true"':''?>><?=$val['text']?></option>
								<?endforeach;?>
							</select>
							<div class="input-group-append">
								<button class="btn btn-outline-primary">Изменить</button>
							</div>
						</div>
					</form>
				</div>
			</div>
			<div><?=htmlspecialchars($item['text'])?></div>	
		</div>
	</div>

	<div class="card mb-4">
		<div class="card-body">
			<?=showError($error)?>
			<form action="" method="post">
				<input type="hidden" name="type" value="message">
				<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
				<div class="form-group">
					<textarea name="text" class="form-control" placeholder="Текст сообщения" rows="5"><?=htmlspecialchars($post['text'] ?? '')?></textarea>
				</div>
				<div>
					<button type="submit" class="btn btn-primary">Отправить</button>
				</div>
			</form>
		</div>
	</div>

	<?if(count($items)):?>
		<?foreach($items as $row):?>
			<div class="card mb-4 <?=((int) $row['user'] === 0)?'answer':''?>">
				<div class="card-body">
					<div><?=$row['date_f']?></div>
					<div><?=htmlspecialchars($row['text'])?></div>	
				</div>
			</div>
		<?endforeach;?>
	<?endif;?>
</div>