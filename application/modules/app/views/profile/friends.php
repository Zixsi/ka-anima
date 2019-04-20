<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Друзья</h3>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		
		<?if($message && $message['type'] === true):?>
			<?=alert_success($message['text']);?>
		<?endif;?>

		<table class="table table-striped">
			<tbody>
				<?if(is_array($items)):?>
					<?foreach($items as $item):?>
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
								<form action="" method="post">
									<input type="hidden" name="id" value="<?=$item['id']?>">
									<button type="submit" class="btn btn-danger">Удалить</button>
								</form>
							</td>
						</tr>
					<?endforeach;?>
				<?endif;?>
			</tbody>
		</table>
	</div>
</div>