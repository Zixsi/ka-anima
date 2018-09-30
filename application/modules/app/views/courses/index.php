<div class="row">

	<div class="col-xs-12 text-center" style="margin-bottom: 30px;">
		<a href="/courses/<?=$group_id?>/" class="btn btn-primary">Лекции</a>
		<a href="/courses/<?=$group_id?>/group/" class="btn btn-default">Группа</a>
		<a href="#" class="btn btn-default disabled">Ревью работ</a>
		<a href="#" class="btn btn-default disabled">Онлайн встречи</a>
	</div>

	<div class="col-xs-12">
		<div class="week-panel">
			<?$i = 1;?>
			<?foreach($lectures as $item):?>
				<?if($item['active']):?>
					<a href="/courses/<?=$group_id?>/lecture/<?=$item['id']?>" class="week-item active <?=($lecture_id == $item['id'])?'current':''?>">
						<span class="number"><?=$i?></span>
						<span class="name"><?=$item['name']?></span>
					</a>
				<?else:?>
					<span class="week-item">
						<span class="number"><?=$i?></span>
						<span class="name"><?=$item['name']?></span>
					</span>
				<?endif;?>

				<?$i++;?>
			<?endforeach;?>
		</div>
	</div>

	<?if($lectures_is_active):?>

		<div class="col-xs-12">
			<h3><?=$lecture['name']?></h3>
		</div>

		<div class="col-xs-5">
			<div class="video-wrap">
				<iframe src="/video/<?=$lecture_id?>" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
			</div>
			<div class="video-info">
				<?if(!empty($lecture['description'])):?>
					<h4>Описание</h4>
					<p><?=$lecture['description']?></p>
				<?endif;?>

				<?if(!empty($lecture['task'])):?>
					<h4>Задание</h4>
					<p><?=$lecture['task']?></p>
				<?endif;?>
			</div>
		</div>
		<div class="col-xs-7">
			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Загрузка заданий</h3>
				</div>
				<div class="panel-body">
					<div class="row">
						<div class="col-xs-6" style="font-size: 14px;">
							<p><b>Видео файлы (mp4) и картинки (jpg, png) пожалуйста, загружайте отдельными файлами.</b></p>
							<p><b>Файлы других типов</b> (не видео и не картинки, а файлы Maya, 3DS Max, RealFlow, Z-Brush и др.) <b>загружайте заархивированными как RAR или ZIP</b>. После загрузки каждого архива, отдельно можете загрузить превью (пример) картинку или видео того что было загружено в архиве.</p>

							<p><b>Требования к загружаемым файлам:</b><br>
							Картинки и видео – <b>jpg</b>, <b>png</b>, <b>mp4</b> (кодек <b>h264</b> или <b>x264</b>), разрешение <b>1280х720</b> пикселей.
							Другие файлы загружать как архивы <b>RAR</b> или <b>ZIP</b>.
							Максимальный размер файлов: <b>250 МБ</b></p>
						</div>
						<div class="col-xs-6">
							<form action="" method="post" class="form" enctype="multipart/form-data">
								<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
								<div class="form-group">
									<label></label>
									<input type="file" name="file" class="form-control">
								</div>
								<div class="form-group">
									<label>Комментарий к файлу</label>
									<textarea name="text" class="form-control" rows="5"></textarea>
								</div>
								<div class="form-group">
									<button type="submit" class="btn btn-xs btn-primary">Загрузить</button>
								</div>
							</form>
						</div>
					</div>
				</div>
			</div>

			<div class="panel panel-headline">
				<div class="panel-heading">
					<h3 class="panel-title">Загруженные задания</h3>
				</div>
				<div class="panel-body">
					<?if($lecture_homework):?>
						<table class="table">
							<tbody>
								<?foreach($lecture_homework as $val):?>
									<tr>
										<td><?=$val['ts']?></td>
										<td><?=$val['user_name']?></td>
										<td><?=$val['name']?></td>
										<td>
											<a href="/file/download/<?=$val['id']?>" target="_blank" class="btn btn-primary btn-xs">Скачать</a>
										</td>
									</tr>
								<?endforeach;?>
							</tbody>
						</table>
					<?else:?>
						<div class="text-center">Нет загруженных заданий</div>
					<?endif;?>
				</div>
			</div>
		</div>

	<?endif;?>

</div>

<?if($lectures_is_active == false):?>
	<div class="alert alert-danger text-center" style="font-size: 24px;">Нет активных лекций</div>
<?endif;?>