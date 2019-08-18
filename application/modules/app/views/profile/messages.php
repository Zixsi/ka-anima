<div class="email-wrapper wrapper">
	<div class="row align-items-stretch">
		<?if($chats):?>
			<?//debug($chats);?>
			<div class="mail-sidebar col-2 pt-3 bg-white">
				<div class="menu-bar">
					<div class="wrapper">
						<div class="online-status d-flex justify-content-between align-items-center">
						<p class="chat">Чаты</p></div>
					</div>
					<ul class="profile-list" style="border-right: 1px solid #f6f2f2;">
						<?foreach($chats as $val):?>
							<li class="profile-list-item">
								<a href="/profile/messages/<?=$val['id']?>/">
									<span class="pro-pic">
										<img src="<?=$val['img']?>" alt="" width="40" height="40">
									</span>
									<div class="user">
										<p class="u-name"><?=$val['name']?></p>
										<p class="u-designation"><?=$val['role_name']?></p>
									</div>
									<?if($val['unread'] > 0):?>
										<p class="notification-ripple notification-ripple-bg">
											<span class="ripple notification-ripple-bg"></span>
											<span class="ripple notification-ripple-bg"></span>
											<span class="ripple notification-ripple-bg"></span>
										</p>
									<?endif;?>
								</a>
							</li>
						<?endforeach;?>
					</ul>
				</div>
			</div>
		<?endif;?>
		<?if($chats):?>
			<div class="mail-view col-10 bg-white">
		<?else:?>
			<div class="mail-view col-12 bg-white">
		<?endif;?>
			<div class="message-body">
				<div class="message-content">
					<?=alert_error($error);?>

					<?if($messages):?>
						<?//debug($messages);?>
						<ul class="list-unstyled">
							<?foreach($messages as $val):?>
								<li class="media mb-4">
									<a href="/profile/<?=$val['user']?>/">
										<img class="mr-3" src="<?=$val['img']?>" alt="" width="40" height="40">
									</a>
									<div class="media-body">
										<?if($val['role'] > 0):?>
											<span class="label label-success message-badge"><?=$val['role_name']?></span>
										<?endif;?>
										<h5 class="mt-0 mb-1"">
											<span><a href="/profile/<?=$val['user']?>/"><?=$val['name']?></a></span>
											<small><?=$val['ts']?></small>
										</h5>
										
										<div><?=$val['text']?></div>
									</div>
								</li>
							<?endforeach;?>
						</ul>
					<?endif;?>

					<form action="" method="post" class="form">
						<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
						<div class="form-group">
							<textarea name="text" class="form-control" rows="5" placeholder="Сообщение..."></textarea>
						</div>
						<div class="form-group text-right">
							<button type="submit" class="btn btn-primary btn-xs">Отправить</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>