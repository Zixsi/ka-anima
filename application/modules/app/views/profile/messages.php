<div class="panel">
	<div class="panel-heading">
		<div class="col-xs-6">
			<h3 class="panel-title">Сообщения</h3>
		</div>
	</div>
	<div class="panel-body" style="padding-top: 30px;">
		<div class="row">
			<div class="col-xs-3" style="border-right: 1px solid #ccc;">
				<?if($chats):?>
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
				<?endif;?>
			</div>
			<div class="col-xs-9">
				<?=alert_error($error);?>

				<?if($messages):?>
					<ul class="media-list">
						<?foreach($messages as $val):?>
							<li class="media">
								<a class="pull-left" href="/profile/<?=$val['user']?>/">
									<img class="media-object" src="<?=$this->imggen->createIconSrc(['seed' => md5('user'.$val['user'])]);?>" alt="">
								</a>
								<div class="media-body">
									<?if($val['role'] > 0):?>
										<span class="label label-success message-badge"><?=$val['role_name']?></span>
									<?endif;?>
									<h4 class="media-heading">
										<span><a href="/profile/<?=$val['user']?>/"><?=$val['name']?></a></span>
										<small><?=$val['ts']?></small>
									</h4>
									
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