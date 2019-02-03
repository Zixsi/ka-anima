<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Пользователи</h3>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		
		<?if($message && $message['type'] === true):?>
			<?=alert_success($message['text']);?>
		<?endif;?>

		<table class="table table-striped">
			<tbody>
				<?if(is_array($users)):?>
					<?foreach($users as $item):?>
						<tr>
							<td width="60">
								<a href="/profile/<?=$item['id']?>/" target="_blank">
									<img src="<?=$item['img']?>" alt="" width="42" height="42">
								</a>
							</td>
							<td>
								<a href="/profile/<?=$item['id']?>/" target="_blank"><?=$item['full_name']?></a>	
							</td>
							<td class="text-right">
								<?if($item['is_friend']):?>
									<button class="btn btn-light disabled">В друзьях</button>
								<?else:?>
									<form action="" method="post">
										<input type="hidden" name="id" value="<?=$item['id']?>">
										<a href="/profile/messages/<?=$item['id']?>/" class="btn btn-primary">Написать сообщение</a>
										<button type="submit" class="btn btn-primary">Добавить в друзья</button>
									</form>
								<?endif;?>
							</td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>