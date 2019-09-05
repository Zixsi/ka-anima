<div class="row">
	<?$this->load->view('groups/menu');?>

	<div class="col-8 offset-2">
		<div class="week-panel owl-carousel">
			<?$i = 0;?>
			<?if(is_array($lectures)):?>
				<?foreach($lectures as $item):?>
					<?if($item['active'] && $subscr_is_active):?>
						<a href="/groups/<?=$group['code']?>/lecture/<?=$item['id']?>" data-index="<?=$i?>" class="week-item active <?=($lecture_id == $item['id'])?'current':''?>">
							<span class="number">
								<span><?=$i?></span>
								<?if(isset($item['homework_fail']) && $item['homework_fail']):?>
									<span class="badge bg-danger" 
										data-toggle="tooltip" 
										data-placement="bottom" 
										data-original-title="не загружено домашнее задание">!</span>
								<?endif;?>
							</span>
							<span class="name"><?=$item['name']?></span>
						</a>
					<?else:?>
						<span class="week-item">
							<span class="number">
								<span><?=$i?></span>
								<?if(isset($item['homework_fail']) && $item['homework_fail']):?>
									<span class="badge bg-danger" 
										data-toggle="tooltip" 
										data-placement="bottom" 
										data-original-title="не загружено домашнее задание">!</span>
								<?endif;?>
							</span>
							<span class="name"><?=$item['name']?></span>
						</span>
					<?endif;?>

					<?$i++;?>
				<?endforeach;?>
			<?endif;?>
		</div>
	</div>

	<?if($lectures_is_active && $subscr_is_active):?>

		<div class="col-12">
			<h3><?=$lecture['name']?></h3>
		</div>

		<div class="col-5">
			<div class="card">
				<div class="card-body">
					<div class="video-wrap mb-4">
						<iframe src="/video/<?=$lecture['video_code']?>/" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
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

						<?if(!empty($lecture['files'])):?>
							<h4>Файлы к заданию</h4>
							<?foreach($lecture['files'] as $val):?>
								<p><a href="/file/download/<?=$val['id']?>" target="_blank"><?=$val['name']?></a></p>
							<?endforeach;?>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
		<div class="col-7">
			<?if($lecture['type'] == 0 && $lecture['can_upload_files'] && ($data['subscr']['type'] ?? '') !== 'standart'):?>
				<div class="card mb-4">
					<div class="card-body">
						<h3 class="card-title">Загрузка заданий</h3>
						<?if($error):?>
							<div class="alert alert-danger"><?=$error?></div>
						<?endif;?>
						<div class="row">
							<div class="col-6" style="font-size: 14px;">
								<p><b>Видео файлы (mp4) и картинки (jpg, png) пожалуйста, загружайте отдельными файлами.</b></p>
								<p><b>Файлы других типов</b> (не видео и не картинки, а файлы Maya, 3DS Max, RealFlow, Z-Brush и др.) <b>загружайте заархивированными как RAR или ZIP</b>. После загрузки каждого архива, отдельно можете загрузить превью (пример) картинку или видео того что было загружено в архиве.</p>

								<p><b>Требования к загружаемым файлам:</b><br>
								Картинки и видео – <b>jpg</b>, <b>png</b>, <b>mp4</b> (кодек <b>h264</b> или <b>x264</b>), разрешение <b>1280х720</b> пикселей.
								Другие файлы загружать как архивы <b>RAR</b> или <b>ZIP</b>.
								Максимальный размер файлов: <b>250 МБ</b></p>
							</div>
							<div class="col-6">
								<form action="" method="post" class="form" enctype="multipart/form-data">
									<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
									<div class="form-group">
										<input type="file" name="file" class="file-upload-default">
										<div class="input-group">
											<input type="text" class="form-control file-upload-info" disabled="" placeholder="Загрузка файла">
											<span class="input-group-append">
												<button class="file-upload-browse btn btn-primary" type="button">Выбрать</button>
											</span>
										</div>
									</div>
									<div class="form-group">
										<textarea name="text" class="form-control" rows="5" placeholder="Комментарий к файлу"></textarea>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Загрузить</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>

				<div class="card panel-headline">
					<div class="card-body">
						<h3 class="card-title">Загруженные задания</h3>
						<?if($lecture_homework):?>
							<table class="table">
								<tbody>
									<?foreach($lecture_homework as $val):?>
										<tr>
											<td><?=$val['ts']?></td>
											<td><?=$val['full_name']?></td>
											<td><?=$val['name']?></td>
											<td class="text-right">
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
				
			<?endif;?>
		</div>

	<?endif;?>

</div>

<?if($subscr_is_active == false):?>
	<div class="alert alert-danger text-center" style="font-size: 24px;">Ваша подписка закончилась. <a href="/subscription/">Продлите чтобы получить доступ.</a></div>
	<div class="text-center" style="padding: 50px 0px;">
		<img src="<?=IMG_UNICORN?>" width="300" height="300">
	</div>
<?elseif($lectures_is_active == false):?>
	<div class="alert alert-danger text-center" style="font-size: 24px;">
		<span>Нет активных лекций</span><br>
	</div>
	<div class="text-center" style="padding: 50px 0px;">
		<img src="<?=IMG_UNICORN?>" width="300" height="300">
	</div>
<?endif;?>