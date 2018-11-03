<div class="row">

	<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?></h3>

	<div class="col-xs-12 text-center" style="margin-bottom: 30px;">
		<a href="/courses/<?=$group_id?>/" class="btn btn-default">Лекции</a>
		<a href="/courses/<?=$group_id?>/group/" class="btn btn-default">Группа</a>
		<a href="/courses/<?=$group_id?>/review/" class="btn btn-default">Ревью работ</a>
		<a href="/courses/<?=$group_id?>/stream/" class="btn btn-primary">Онлайн встречи</a>
	</div>

</div>

<?if($item):?>
	<div class="row">
		<div class="col-xs-12">
			<div class="alert alert-danger text-center" style="font-size: 24px;">Начало <?=$item['ts']?></div>
		</div>
		<div class="col-xs-6">
			<div class="panel">
				<div class="panel-heading">
					<div class="col-xs-6">
						<h3 class="panel-title"><?=$group['name']?>: <?=$item['name']?></h3>
					</div>
				</div>
				<div class="panel-body" style="padding-top: 30px;">
					<div class="video-wrap">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?=$item['video_code']?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="video-info">
						<h4>Описание</h4>
						<p><?=$item['description']?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-xs-6">
			<div class="panel panel-headline">
				<div class="panel-body">
					<form action="" method="post" class="form">
						<div class="form-group">
							<label>Разместить сообщение</label>
							<textarea name="text" class="form-control" rows="5" placeholder="Поделитесь вашими мыслями..."></textarea>
						</div>
						<div class="form-group text-right">
							<button type="submit" class="btn btn-primary btn-xxs">Разместить</button>
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
<?else:?>
	<div class="row">
		<div class="col-xs-12">
			<div class="alert alert-danger text-center" style="font-size: 24px;">Нет запланированных онлайн встреч</div>
			<div class="text-center" style="padding: 50px 0px;">
				<img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/unicorn.jpg" width="300" height="300">
			</div>
		</div>
	</div>
<?endif;?>