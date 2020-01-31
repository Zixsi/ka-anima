<div class="row mb-3">
	<div class="col-12 col-sm-9">
		<h3 class="pt-2"><?=htmlspecialchars($item['title'])?></h3>
	</div>
	<div class="d-none d-sm-block col-sm-3 text-right text-right">
		<a href="/workshop/" class="btn btn-outline-primary">Назад</a>
	</div>
</div>
<?//debug($item);?>

<?if(!$this->Auth->isActive()):?>
	<div class="row">
		<div class="col-12">
			<div class="alert alert-fill-danger" role="alert">
				<i class="mdi mdi-alert-circle"></i>
				<span>Пользователь не активирован. Подписка недоступна. Для активации следуйте инструкциям направленным на почту. Если письмо не пришло, проверьте папку спам.</span>
			</div>
		</div>
	</div>
<?else:?>
	<div class="row">
		<div class="col-12">
			<div class="alert alert-fill-warning" role="alert">
				<i class="mdi mdi-alert-circle"></i>
				<span>После успешного подтверждения оплаты курс можно будет найти во вкладке <a href="/subscription/">Подписки</a></span>
			</div>
		</div>
	</div>
<?endif;?>

<div class="row">
	<div class="col-12 mx-auto">
		<div class="card" id="player-module">
			<div class="card-body">
				<div class="wrap" style="background-color: #2c2f41;">
					<div class="row">
						<div class="col-12 col-md-8 mx-auto video-container">
							<div class="video-wrap">
								<?if($item['type'] === 'webinar' && $access === false):?>
									<div style="width: 100%; height: 100%; background-size: cover; background-image: url('/<?=$item['img']?>');" class="iframe"></div>
									<div class="video-lock">
										<div class="row h-100">
											<div class="col-12 my-auto text-center">
												<?if(!$this->Auth->isActive()):?>
													<div class="text">Пользователь не активирован. Подписка недоступна.</div>
												<?else:?>
													<div class="text">Необходимо приобрести курс для получения доступа к видео</div>
													<div>
														<a href="/pay/?action=new&target=workshop&code=<?=$item['code']?>" class="btn btn-primary">Купить</a>
													</div>
												<?endif;?>
											</div>
										</div>
									</div>
								<?else:?>
									<?if(empty($currentVideo)):?>
										<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?=$item['video_code']?>?modestbranding=1&rel=0&showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
									<?else:?>
										<?if($access === false):?>
											<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?=$item['video_code']?>?modestbranding=1&rel=0&showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>

											<div class="video-lock">
												<div class="row h-100">
													<div class="col-12 my-auto text-center">
														<?if(!$this->Auth->isActive()):?>
															<div class="text">Пользователь не активирован. Подписка недоступна.</div>
														<?else:?>
															<div class="text">Необходимо приобрести курс для получения доступа к видео</div>
															<div>
																<a href="/pay/?action=new&target=workshop&code=<?=$item['code']?>" class="btn btn-primary">Купить</a>
															</div>
														<?endif;?>
													</div>
												</div>
											</div>
										<?else:?>
											<iframe src="<?=$currentVideo['iframe_url']?>" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
										<?endif;?>
									<?endif;?>
								<?endif;?>
							</div>
						</div>
						<?if($showRightColl):?>
							<div class="col-12 col-md-4 playlist-container">
								<div class="playlist-head in d-block d-md-none">
									<span><i class="fa fa-bars"></i> <?=($item['type'] === 'webinar')?'Чат':'Список лекций'?></span>
									<span class="icon">
										<i class="fa fa-chevron-down"></i>
										<i class="fa fa-chevron-up"></i>
									</span>
								</div>
								<div class="playlist-wrap <?=($item['type'] === 'webinar')?'nowrap':''?>">
									<?if($item['type'] === 'webinar'):?>
										<iframe src="<?=$item['chat']?>" width="100%" height="100%" frameborder="0"></iframe>
									<?else:?>
										<?//debug($videos);?>
										<nav class="nav flex-columnn playlist" style="">
											<a href="/workshop/item/<?=$item['code']?>/" class="nav-link <?=(empty($currentVideo)?'active':'')?>">
												<i class="fa fa-play-circle-o"></i>
												<span class="title">Трейлер</span>
												<span class="float-right">00:00:00</span>
											</a>
											<?foreach($videos as $video):?>
												<a href="/workshop/item/<?=$item['code']?>/<?=$video['video_code']?>/" class="nav-link <?=((($currentVideo['video_code'] ?? null) === $video['video_code'])?'active':'')?>">
													<?if($access):?>
														<i class="fa fa-play-circle-o"></i>
													<?else:?>
														<i class="fa fa-lock"></i>
													<?endif;?>
													<span class="title"><?=$video['title']?></span>
													<span class="float-right"><?=$video['duration_f']?></span>
												</a>
											<?endforeach;?>
											<?/*for($i = 0; $i < 4; $i++):?>
												<?foreach($videos as $video):?>
													<a href="/workshop/item/<?=$item['code']?>/<?=$video['video_code']?>/" class="nav-link">
														<i class="fa fa-lock"></i>
														<span class="title"><?=$video['title']?></span>
														<span class="float-right"><?=$video['duration_f']?></span>
													</a>
												<?endforeach;?>
											<?endfor;*/?>
										</nav>
									<?endif;?>
								</div>
							</div>
						<?endif;?>
					</div>
				</div>

				<div class="row mt-4">
					<div class="col-11 mx-auto">
						<div class="row">
							<div class="col-12 col-sm-8">
								<h3 class="mt-2 text-primary"><?=htmlspecialchars($item['title'])?><?=(empty($currentVideo) === false)?': '.htmlspecialchars($currentVideo['title']):''?></h3>
								<div>
									<?if($teacher):?>
										<a href="/profile/<?=$teacher['id']?>/"><img src="<?=$teacher['img']?>" class="img-sm"></a> <a href="/profile/<?=$teacher['id']?>/" class="text-primary"><?=$teacher['full_name']?></a>
									<?else:?>
										<img src="<?=TEMPLATE_DIR?>/assets/profile_icon_male2.png" class="img-sm"> <span class="text-primary">Школа CGAim</span>
									<?endif;?>
								</div>
								<?if($item['type'] === 'collection'):?>
									<div class="video-info">
										<span class="mr-4"><i class="fa fa-clock-o mr-2 text-primary"></i><?=time2hours($totalDuration)?></span>
										<span><i class="fa fa-video-camera mr-2 text-primary"></i><?=(count($videos) + 1)?> видео</span>
									</div>
								<?else:?>
									<div class="video-info">
										<span class="mr-4"><i class="fa fa-clock-o mr-2 text-primary"></i><?=$item['date']?></span>
									</div>
								<?endif;?>
							</div>
							<?if($access === false):?>
								<div class="col-12 col-sm-4 text-right mt-3 mt-sm-0">
									<span class="mr-2" style="font-size: 22px;"><?=priceFormat($item['price'])?></span>
									<?if(!$this->Auth->isActive()):?>
										<button type="button" data-toggle="popover" title="Пользователь не активирован" data-content="Подписка недоступна." data-placement="top" class="btn btn-outline-primary"><?=((float) $item['price'] === 0.00)?'Получить':'Купить'?></button>
									<?else:?>
										<a href="/pay/?action=new&target=workshop&code=<?=$item['code']?>" class="btn btn-outline-primary"><?=((float) $item['price'] === 0.00)?'Получить':'Купить'?></a>
									<?endif;?>
								</div>
							<?endif;?>
						</div>
						<hr class="primary">
						<div class="row">
							<div class="col-12">
								<?if($access === false || empty($currentVideo)):?>
									<?=htmlspecialchars($item['description'])?>
								<?else:?>	
									<?=htmlspecialchars($item['video_description'])?>
								<?endif;?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="text-right mt-2 d-block d-sm-none">
			<a href="/workshop/" class="btn btn-outline-primary">Назад</a>
		</div>
	</div>
</div>