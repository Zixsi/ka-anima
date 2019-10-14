<div id="support-ticket">
	<div class="row mb-4">
		<div class="col-10">
			<h3>Обращение - <?=$item['code']?></h3>
		</div>
		<div class="col-2 text-right">
			<a href="/support/" class="btn btn-outline-primary">Назад</a>
		</div>
	</div>
	<div class="card mb-4">
		<div class="card-body">
			<div><?=$item['date_f']?></div>
			<div><?=htmlspecialchars($item['text'])?></div>	
		</div>
	</div>
	<?if(count($items)):?>
		<?if($isEnabledMessage):?>
			<div class="card mb-4">
				<div class="card-body">
					<?=showError($error)?>
					<form action="" method="post">
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
		<?endif;?>

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