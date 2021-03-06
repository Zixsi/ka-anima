<div class="row">
	<?$this->load->view('groups/menu');?>

	<div class="col-12 col-md-6">
		<div class="card mb-4">
			<div class="card-body">
				<div class="row">
					<div class="col-12 col-md-6 pb-3">
						<?if(empty($group['img_src'])):?>
							<span class="img-rounded" style="display: block; width: 100%; height: 100px; background-color: #ccc;"></span>
						<?else:?>
							<img src="/<?=$group['img_src']?>" class="img-rounded" style="width: 100%; height: auto;">
						<?endif;?>
					</div>
					<div class="col-12 col-md-6 text-right">
						<?if(($start_course = days_to_date($group['ts'])) > 0):?>
							<p>Курс начнется через: <?=$start_course?> дней</p>
						<?endif;?>
						<p>Начало обучения: <?=date(DATE_FORMAT_SHORT, strtotime($group['ts']))?></p>
						<p>Завершение обучения:  <?=date(DATE_FORMAT_SHORT, strtotime($group['ts_end']))?></p>
						<p>Всего недель курса: <?=$group['cnt_all']?></p>
						<p>Текущая неделя: <?=$group['current_week']?></p>
						<p>Дней до окончания: <?=((($end_course = days_to_date($group['ts_end'])) > 0)?$end_course:0)?></p>
					</div>
				</div>
			</div>
		</div>

		<div class="card mb-4">
			<div class="card-body">
				<h3 class="card-title">Лекции</h3>
				<?if($lectures):?>
					<?$lectures = array_chunk($lectures, ceil(count($lectures) / 3));?>
					<div class="row">
						<?foreach($lectures as $col):?>
							<div class="col-12 col-md-6">
								<ul>
									<?foreach($col as $val):?>
										<li>
											<?if($val['active']):?>
												<a href="/groups/<?=$group['code']?>/lecture/<?=$val['id']?>"><?=$val['name']?></a>
											<?else:?>
												<span><?=$val['name']?></span>
											<?endif;?>
										</li>
									<?endforeach;?>
								</ul>
							</div>
						<?endforeach;?>
					</div>
				<?endif;?>
			</div>
		</div>
	</div>
	<div class="col-12 col-md-6">
		<div class="card course-card--teacher mb-4">
			<div class="card-body text-center">
				<div>
					<a href="/profile/<?=$teacher['id']?>/">
						<img src="<?=$teacher['img']?>" class="img-lg rounded-circle mb-2" alt="profile image"/>
					</a>
					<h4><?=$teacher['full_name']?></h4>
					<p class="text-muted mb-0"><?=$teacher['role_name']?></p>
				</div>
				<?if(!empty($teacher['title'])):?>
					<p class="mt-2 card-text"><?=$teacher['title']?></p>
				<?endif;?>
			</div>
		</div>

		<div class="card mb-4">
			<div class="card-body">
				<h3 class="card-title">Студенты группы</h3>
				<?if($users):?>
					<?foreach($users as $val):?>
						<a href="/profile/<?=$val['id']?>/"><img src="<?=$val['img']?>" width="50" height="50" class="img-circle" title="<?=$val['full_name']?>"></a>
					<?endforeach;?>
				<?endif;?>
			</div>
		</div>

		<?if($images):?>
			<div class="card mb-4">
				<div class="card-body">
					<h3 class="card-title">Изображения</h3>
					<div class="gallery-block group-image-gallery">
						<?foreach($images as $img):?>
							<div class="item">
								<div class="item-wrapper">
									<a href="/<?=$img['src']?>" class="item-content" data-toggle="lightbox" data-gallery="group-image">
										<img src="/<?=$img['src_thumb']?>" alt="">
									</a>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
		<?endif;?>

		<?if($videos):?>
			<div class="card mb-4">
				<div class="card-body">
					<h3 class="card-title">Видео</h3>
					<div class="gallery-block group-video-gallery">
						<?foreach($videos as $video):?>
							<div class="item">
								<div class="item-wrapper">
									<a href="/<?=$video['src']?>" class="item-content" data-toggle="lightbox" data-gallery="group-video" data-type="video">
										<img src="<?=IMG_DEFAULT_300_300?>" alt="">
									</a>
								</div>
							</div>
						<?endforeach;?>
					</div>
				</div>
			</div>
		<?endif;?>

	</div>

	<?if($subscr['type'] !== 'standart'):?>
		<div class="col-12">
			<?=$this->wall->show($group['id'])?>
		</div>
	<?endif;?>

</div>