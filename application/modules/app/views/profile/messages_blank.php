<div class="email-wrapper wrapper">
	<div class="row align-items-stretch">
		<?if($chats):?>
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
				<div class="message-content text-center">
					<p>Пожалуйста, выберите диалог или создайте
						<?if($friends):?>
							<a href="javascript:void(0);" data-toggle="modal" data-target="#chat-new">новый</a>
						<?else:?>
							<a href="/users/">новый</a>
						<?endif;?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="modal" id="chat-new" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Новое сообщение</h4>
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body">
				<form action="" method="post" class="form">
					<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
					<div class="form-group">
						<select name="target" class="form-control">
							<?foreach($friends as $val):?>
								<option value="<?=$val['id']?>"><?=$val['full_name']?></option>
							<?endforeach;?>
						</select>
					</div>
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