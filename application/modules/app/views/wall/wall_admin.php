<div id="wall-component">
	<?/*
	<div class="panel panel-headline wall-panel">
		<div class="panel-body">
			<form action="" method="post" class="form wall-from" id="wall-main-form">
				<input type="hidden" name="group" value="<?=$target_id?>" data-const="true">
				<div class="wall-from--input-wrap">
					<textarea name="text" class="wall-from--text-input" placeholder="Напишите сообщение..."></textarea>
					<button type="submit" class="btn wall-from--btn-send">
						<i class="fab fa-telegram-plane"></i>
					</button>
				</div>
			</form>
		</div>
	</div>
	*/?>

	<div class="media-list" id="wall-main-list">
		<?foreach($items as $val):?>
			<div class="panel panel-headline wall-panel" id="wall-panel-id<?=$val['id']?>">
				<div class="panel-body">
					<div class="media">
						<a class="pull-left" href="/admin/users/user/<?=$val['user']?>/" target="_blank">
							<img class="media-object" src="<?=$val['img']?>" alt="<?=$val['full_name']?>">
						</a>
						<div class="media-body">
							<h4 class="media-heading">
								<a href="/admin/users/user/<?=$val['user']?>/" target="_blank"><?=$val['full_name']?></a>
								<?if($val['role'] > 0):?>
									<small><span class="label label-success"><?=$val['role_name']?></span></small>
								<?endif;?>
								<br><small><?=$val['ts']?></small>
							</h4>
							<?=htmlspecialchars($val['text'])?>
							<hr>
							<div class="wall-item-tools">
								<button type="button" class="btn btn-comments" data-id="<?=$val['id']?>">
									<i class="far fa-comment-alt"></i> <span class="info-child-cnt"><?=($val['child_cnt'] ?? '')?></span>
								</button>
							</div>
							<div class="wall-wrap-childs">
								<hr>
								<div class="wall-childs" id="wall-childs-id<?=$val['id']?>"></div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<?endforeach;?>
	</div>
	<?if($params['show_more']):?>
		<button class="btn btn-primary btn-block btn-md" id="wall-load-more" data-id="<?=$target_id?>" data-limit="<?=$params['limit']?>" data-offset="<?=$params['offset']?>" data-all="<?=$params['cnt']?>">Показать еще</button>
	<?endif;?>
</div>

<script type="text/html" id="wall-item-tpl">
	<div class="panel panel-headline wall-panel" id="wall-panel-id{ID}">
		<div class="panel-body">
			<div class="media">
				<a class="pull-left" href="/admin/users/user/{USER_ID}/" target="_blank">
					<img class="media-object" src="{USER_IMG}" alt="{USER_NAME}">
				</a>
				<div class="media-body">
					<h4 class="media-heading">
						<a href="/admin/users/user/{USER_ID}/" target="_blank">{USER_NAME}</a>
						{ROLE}
						<br><small>{DATE}</small>
					</h4>
					{TEXT}
					<hr>
					<div class="wall-item-tools">
						<button type="button" class="btn btn-comments" data-id="{ID}">
							<i class="far fa-comment-alt"></i> <span class="info-child-cnt">{CHILD_CNT}</span>
						</button>
					</div>
					<div class="wall-wrap-childs">
						<hr>
						<div class="wall-childs" id="wall-childs-id{ID}"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</script>

<script type="text/html" id="wall-child-tpl">
	<div class="media">
		<a class="pull-left" href="/profile/{USER_ID}/" target="_blank">
			<img class="media-object" src="{USER_IMG}" alt="{USER_NAME}">
		</a>
		<div class="media-body">
			<h4 class="media-heading">
				<a href="/profile/{USER_ID}/" target="_blank">{USER_NAME}</a>
				{ROLE}
				<br><small>{DATE}</small>
			</h4>
			{TEXT}
		</div>
	</div>
</script>

<script type="text/html" id="wall-item-role-tpl">
	<small><span class="label label-success">{VALUE}</span></small>
</script>