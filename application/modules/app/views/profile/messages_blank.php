<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Сообщения</h3>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="row">
			<?if($chats):?>
				<div class="col-xs-3" style="border-right: 1px solid #ccc;">
					<ul class="media-list">
						<?foreach($chats as $val):?>
							<li class="media" style="position: relative;">
								<div class="pull-left">
									<img class="media-object" src="<?=$val['img']?>" alt="">
								</div>
								<div class="media-body">
									<?if($val['role'] > 0):?>
										<span class="label label-success message-badge"><?=$val['role_name']?></span>
									<?endif;?>
									<h4 class="media-heading"><?=$val['name']?></h4>
								</div>
								<a href="/profile/messages/<?=$val['id']?>/" style="position: absolute; top: 0; left: 0; right: 0; bottom: 0; z-index: 5;"></a>
							</li>
						<?endforeach;?>
					</ul>
				</div>
			<?endif;?>
			<div class="col-xs-<?=($chats)?'8':'12'?> text-center">
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

<div class="modal" id="chat-new" tabindex="-1" role="dialog" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">Новое сообщение</h4>
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