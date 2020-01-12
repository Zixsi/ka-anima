<div class="row mb-4">
	<div class="col-6">
		<h3><?=$item['course_name']?>: <?=$item['name']?></h3>
	</div>
	<div class="col-6 text-right">
		<a href="../" class="btn btn-secondary">Назад</a>
	</div>
</div>

<?if($item):?>
	<div class="row">
		<?if($item['started'] == false):?>
			<div class="col-12">
				<div class="alert alert-danger text-center" style="font-size: 24px;">Начало <?=date(DATE_FORMAT_FULL, $item['ts_timestamp'])?></div>
			</div>
		<?endif;?>
		<div class="col-6">
			<div class="card">
				<div class="card-body">
					<div class="video-wrap">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?=$item['video_code']?>?modestbranding=1&rel=0&showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
					</div>
					<div class="video-info pt-4">
						<h4>Описание</h4>
						<p><?=$item['description']?></p>
					</div>
				</div>
			</div>
		</div>
		<div class="col-6">
			<div class="card">
				<div class="card-body">
					<iframe src="<?=$item['chat']?>" width="100%" height="600px" frameborder="0"></iframe>
				</div>
			</div>
		</div>
	</div>
<?else:?>
	<div class="row">
		<div class="col-12">
			<div class="alert alert-danger text-center" style="font-size: 24px;">Нет запланированных онлайн встреч</div>
			<div class="text-center mt-4">
				<img src="<?=IMG_DUMMY?>" width="300" height="300">
			</div>
		</div>
	</div>
<?endif;?>