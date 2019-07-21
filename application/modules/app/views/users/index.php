<h3 class="mb-4">Пользователи</h3>

<?if($message && $message['type'] === true):?>
	<?=alert_success($message['text']);?>
<?endif;?>

<div class="row">
	<?if(is_array($users)):?>
		<?foreach($users as $item):?>
			<div class="col-md-3 grid-margin stretch-card">
				<div class="card">
					<div class="card-body p-3">
						<div class="d-sm-flex flex-row flex-wrap text-center text-sm-left align-items-center">
							<a href="/profile/<?=$item['id']?>/" target="_blank">
								<img src="<?=$item['img']?>" class="img-lg rounded" alt="profile image"/>
							</a>
							<div class="ml-sm-3 ml-md-0 ml-xl-3 mt-2 mt-sm-0 mt-md-2 mt-xl-0">
								<h6 class="mb-0"><?=$item['full_name']?></h6>
								<p class="text-muted mb-1"><?=$item['email']?></p>
								<p class="mb-0 text-success font-weight-bold"><?=($item['role_name'] ?? ' ')?></p>
							</div>
							<form action="" method="post" class="ml-4 flex-grow-1 text-right">
								<input type="hidden" name="id" value="<?=$item['id']?>">
								<?if($item['is_friend'] === false):?>
									<button type="submit" class="btn btn-primary btn-sm" title="Добавить в друзья">
										<i class="fa fa-plus"></i>
									</button>
								<?endif;?>
								<a href="/profile/messages/<?=$item['id']?>/" class="btn btn-primary btn-sm" title="Написать сообщение">
									<i class="fa fa-envelope-o"></i>
								</a>
							</form>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	<?endif;?>
</div>