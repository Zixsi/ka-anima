<div class="row mb-4">
	<?$this->load->view('groups/menu');?>

	<?if($subscr_is_active == false):?>
		<div class="col-12">
			<div class="alert alert-fill-danger" role="alert" style="font-size: 16px;">
				<i class="mdi mdi-alert-circle"></i>
				<span>Ваша подписка закончилась. <a href="/pay/?action=renewal&hash=<?=$subscr['hash']?>" style="color: #fff;"><u>Продлите чтобы получить доступ</u></a>.</span>
			</div>
		</div>
	
	<?elseif($lectures_is_active == false):?>
		<div class="col-12">
			<div class="alert alert-fill-danger" role="alert" style="font-size: 16px;">
				<i class="mdi mdi-alert-circle"></i>
				<span>Нет активных лекций</span>
			</div>
		</div>
	<?endif;?>
	<?//debug($lectures);?>
	<div class="col-12 mx-auto">
		<div class="card" id="player-module">
			<div class="card-body py-0">
				<div class="wrap" style="background-color: #2c2f41;">
					<div class="row">
						<div class="col-12 col-md-8 mx-auto video-container">
							<div class="video-wrap">
								<?if($subscr_is_active == false):?>
									<div class="video-lock">
										<div class="row h-100">
											<div class="col-12 my-auto text-center">
												<div class="text">Ваша подписка закончилась. <a href="/pay/?action=renewal&hash=<?=$subscr['hash']?>" style="color: #fff;"><u>Продлите чтобы получить доступ</u></a>.</div>
											</div>
										</div>
									</div>
								<?elseif($lectures_is_active == false):?>
									<div class="video-lock">
										<div class="row h-100">
											<div class="col-12 my-auto text-center">
												<div class="text">Нет активных лекций</div>
											</div>
										</div>
									</div>
								<?else:?>
									<iframe src="<?=$lecture['iframe_url']?>" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
								<?endif;?>
							</div>
						</div>
						<div class="col-12 col-md-4 playlist-container">
							<div class="playlist-head in d-block d-md-none">
								<span><i class="fa fa-bars"></i> Список лекций</span>
								<span class="icon">
									<i class="fa fa-chevron-down"></i>
									<i class="fa fa-chevron-up"></i>
								</span>
							</div>
							<div class="playlist-wrap">
								<nav class="nav flex-columnn playlist">
									<?$i = 0;?>
									<?if(is_array($lectures)):?>
										<?foreach($lectures as $item):?>
											<?if($item['active'] && $subscr_is_active):?>
												<a href="/groups/<?=$group['code']?>/lecture/<?=$item['id']?>/" class="nav-link  <?=($lecture_id == $item['id'])?'active':''?>">
														<i class="fa fa-play-circle-o"></i>
													<span class="title"><?=$i?>. <?=$item['name']?></span>
													<?if(isset($item['homework_fail']) && $item['homework_fail'] && ($subscr['type'] ?? '') !== 'standart'):?>
														<span class="float-right"><span class="badge bg-danger" 
															data-toggle="tooltip" 
															data-placement="bottom" 
															data-original-title="не загружено домашнее задание" style="color: #fff; font-weight: bold;">!</span></span>
													<?endif;?>
												</a>
											<?else:?>
												<span class="nav-link  <?=($lecture_id == $item['id'])?'active':''?>">
														<i class="fa fa-lock"></i>
													<span class="title"><?=$i?>. <?=$item['name']?></span>

													<?if((int) $item['active'] === 0):?>
														<span class="float-right"><?=date(DATE_FORMAT_SHORT, strtotime($item['ts']))?></span>
													<?endif;?>
													<?/*if(isset($item['homework_fail']) && $item['homework_fail'] && ($subscr['type'] ?? '') !== 'standart'):?>
														<span class="float-right"><span class="badge bg-danger" 
															data-toggle="tooltip" 
															data-placement="bottom" 
															data-original-title="не загружено домашнее задание" style="color: #fff; font-weight: bold;">!</span></span>
													<?endif;*/?>
												</span>
											<?endif;?>
											<?$i++;?>
										<?endforeach;?>
									<?endif;?>
								</nav>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<div class="row">
	<?if($lectures_is_active && $subscr_is_active):?>
		<div class="col-12">
			<div class="card mb-4">
				<div class="card-body">
					<h3><?=($lecture['name'] ?? '- - -')?></h3>
					<div class="video-info">
						<?if(!empty($lecture['description'])):?>
							<h4>Описание</h4>
							<div class="mb-2"><?=$lecture['description']?></div>
						<?endif;?>

						<?if(!empty($lecture['task'])):?>
							<h4>Задание</h4>
							<div class="mb-2"><?=$lecture['task']?></div>
						<?endif;?>

						<?if(!empty($lecture['files'])):?>
							<h4>Файлы к заданию</h4>
							<?foreach($lecture['files'] as $val):?>
								<div class="mb-2"><a href="/file/download/<?=$val['id']?>" target="_blank"><?=$val['name']?></a></div>
							<?endforeach;?>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>
</div>

<div class="row">
	<?if($lectures_is_active && $subscr_is_active):?>
		<?if(($lecture['type'] ?? 0) == 0 && $lecture['can_upload_files'] && ($subscr['type'] ?? '') !== 'standart'):?>

			<div class="col-12 col-xl-6">
				<div class="card mb-4">
					<div class="card-body">
						<h3 class="card-title">Загрузка заданий</h3>
						<?if($error):?>
							<div class="alert alert-danger"><?=$error?></div>
						<?endif;?>
						<div class="row">
							<div class="col-12 col-md-6 col-xl-12" style="font-size: 14px;">
								<p><b>Видео файлы (mp4, mov) и картинки (jpg, png) пожалуйста, загружайте отдельными файлами.</b></p>
								<p><b>Файлы других типов</b> (не видео и не картинки, а файлы Maya, 3DS Max, RealFlow, Z-Brush и др.) <b>загружайте заархивированными как RAR или ZIP</b>. После загрузки каждого архива, отдельно можете загрузить превью (пример) картинку или видео того что было загружено в архиве.</p>

								<p><b>Требования к загружаемым файлам:</b><br>
								Картинки и видео – <b>jpg</b>, <b>png</b>, <b>mp4</b> (кодек <b>h264</b> или <b>x264</b>), разрешение <b>1280х720</b> пикселей.
								Другие файлы загружать как архивы <b>RAR</b> или <b>ZIP</b>.
								Максимальный размер файлов: <b>250 МБ</b></p>
							</div>
							<div class="col-12 col-md-6 col-xl-12">
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
										<textarea name="text" class="form-control" rows="5" placeholder="Комментарий к файлу (максимум 2000 символов)"></textarea>
									</div>
									<div class="form-group">
										<button type="submit" class="btn btn-primary">Загрузить</button>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="col-12 col-xl-6">
				<div class="card panel-headline">
					<div class="card-body">
						<h3 class="card-title">Загруженные задания</h3>
						<?if($lecture_homework):?>
							<div class="table-responsive">
								<table class="table">
									<tbody>
										<?foreach($lecture_homework as $val):?>
											<tr>
												<td><?=$val['ts']?></td>
												<td><?=$val['full_name']?></td>
												<td><?=$val['name']?></td>
												<td class="text-right">
													<a href="/file/download/<?=$val['file']?>" target="_blank" class="btn btn-primary btn-xs">Скачать</a>
												</td>
											</tr>
										<?endforeach;?>
									</tbody>
								</table>
							</div>
						<?else:?>
							<div class="text-center">Нет загруженных заданий</div>
						<?endif;?>
					</div>
				</div>
				
			</div>

		<?endif;?>
	<?endif;?>

</div>