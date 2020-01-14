<div class="row">
	<?$this->load->view('groups/menu');?>
</div>

<div class="row mb-2">
	<div class="col-12">
		<div class="card">
			<div class="card-body py-3">
				<form action="" method="get" class="row">
					<div class="col-12 col-sm-4 mb-2 mb-sm-0">
						<select class="form-control form-control-sm" name="filter[lecture]">
							<option value="0" <?=set_select2('filter[lecture]', '0', true)?>>-- All lectures --</option>
							<?if($lectures):?>
								<?foreach($lectures as $val):?>
									<option value="<?=$val['id']?>" <?=set_select2('filter[lecture]', $val['id'])?>><?=$val['name']?></option>
								<?endforeach;?>
							<?endif;?>
						</select>
					</div>
					<div class="col-12 col-sm-4 mb-2 mb-sm-0">
						<select class="form-control form-control-sm" name="filter[user]">
							<option value="0" <?=set_select2('filter[user]', '0', true)?>>-- All users --</option>
							<?foreach($users as $val):?>
								<option value="<?=$val['id']?>" <?=set_select2('filter[user]', $val['id'])?>><?=$val['full_name']?></option>
							<?endforeach;?>
						</select>
					</div>
					<div class="col-12 col-sm-4">
						<button type="submit" class="btn btn-primary">Фильтровать</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

<div class="row mb-4">
	<div class="col-12 mx-auto">
		<div class="card" id="player-module">
			<div class="card-body py-0">
				<div class="wrap" style="background-color: #2c2f41;">
					<div class="row">
						<div class="col-12 col-md-8 mx-auto video-container">
							<div class="video-wrap">
								<?if($review_item):?>
									<iframe src="<?=$review_item['iframe_url']?>" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
								<?else:?>
									<div class="video-lock">
										<div class="row h-100">
											<div class="col-12 my-auto text-center">
												<div class="text">Не выбрано видео</div>
											</div>
										</div>
									</div>
								<?endif;?>
							</div>
						</div>
						<div class="col-12 col-md-4 playlist-container">
							<div class="playlist-head in d-block d-md-none">
								<span><i class="fa fa-bars"></i> Список ревью</span>
								<span class="icon">
									<i class="fa fa-chevron-down"></i>
									<i class="fa fa-chevron-up"></i>
								</span>
							</div>
							<div class="playlist-wrap">
								<nav class="nav flex-columnn playlist">
									<?$i = 1;?>
									<?if($items):?>
										<?foreach($items as $item):?>
											<a href="/groups/<?=$group['code']?>/review/<?=$item['id']?>/<?=($filter_url)?'?'.$filter_url:''?>" class="nav-link  <?=($review_item['id'] == $item['id'])?'active':''?>">
													<i class="fa fa-play-circle-o"></i>
												<span class="title"><?=$i?>. <?=($item['user'])?$item['user_name']:'Общий обзор'?> (<?=date(DATE_FORMAT_SHORT, strtotime($item['ts']))?>) - <?=$item['name']?></span>

												<?if(is_array($not_viewed) && in_array($item['id'], $not_viewed)):?>
													<span class="float-right"><span class="badge bg-danger" 
															data-toggle="tooltip" 
															data-placement="bottom" 
															data-original-title="не просмотрено" style="color: #fff; font-weight: bold;">!</span></span>
												<?endif;?>
											</a>
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

<?if($review_item):?>
<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<div class="video-info">
					<div class="row">
						<div class="col-auto">
							<b>Дата:</b>
						</div>
						<div class="col-auto">
							<span><?=date(DATE_FORMAT_SHORT, strtotime($review_item['ts']))?></span>
						</div>
					</div>
					<div class="row">
						<div class="col-auto">
							<b>Лекция:</b>
						</div>
						<div class="col-auto">
							<span><?=$lectures[$review_item['lecture_id']]['index']?>. <?=$review_item['lecture_name']?></span>
						</div>
					</div>

					<?if(intval($review_item['user']) !== 0):?>
						<div class="row">
							<div class="col-auto">
								<b>Пользователь:</b>
							</div>
							<div class="col-auto">
								<span><?=$review_item['user_name']?></span>
							</div>
						</div>
					<?endif;?>

					<?if(!empty($review_item['text'])):?>
						<div class="row">
							<div class="col-auto">
								<b>Рекомендации:</b>
							</div>
							<div class="col-auto">
								<span><?=$review_item['text']?></span>
							</div>
						</div>
					<?endif;?>

					<?if(!empty($review_item['file_url'])):?>
						<div class="row">
							<div class="col-auto">
								<b>Приложение:</b>
							</div>
							<div class="col-auto">
								<a href="<?=$review_item['file_url']?>" class="btn btn-sm" target="_blank">Скачать</a>
							</div>
						</div>
					<?endif;?>
				</div>
			</div>
		</div>
	</div>
</div>
<?endif;?>