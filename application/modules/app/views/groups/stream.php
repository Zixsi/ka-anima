<div class="row">
	<?$this->load->view('groups/menu');?>

	<?if($list):?>
		<div class="col-12">
			<div class="week-panel owl-carousel">
				<?$i = 1;?>
				<?foreach($list as $stream_item):?>
					<a href="/groups/<?=$group['code']?>/stream/<?=$stream_item['id']?>/" data-index="<?=($i - 1)?>" class="week-item active <?=($item['id'] == $stream_item['id'])?'current':''?>">
						<span class="number"><?=$i?></span>
						<span class="name"><?=$stream_item['name']?></span>
					</a>
					<?$i++;?>
				<?endforeach;?>
			</div>
		</div>
	<?endif;?>
</div>

<?if($item):?>
	<?if($item['started'] == false):?>
		<div class="row">
			<div class="col-12">
				<div class="col-12">
					<div class="alert alert-fill-danger" role="alert" style="font-size: 16px;">
						<i class="mdi mdi-alert-circle"></i>
						<span>Начало <?=date('d-m-Y H:i:s', strtotime($item['ts']))?></span>
					</div>
				</div>
			</div>
		</div>
	<?endif;?>

	<div class="row mb-4">
		<div class="col-12 mx-auto">
			<div class="card" id="player-module">
				<div class="card-body">
					<div class="wrap" style="background-color: #2c2f41;">
						<div class="row">
							<div class="col-12 col-md-8 mx-auto video-container">
								<div class="video-wrap">
									<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?=$item['video_code']?>?modestbranding=1&rel=0&showinfo=0" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
								</div>
							</div>
							<div class="col-12 col-md-4 playlist-container">
								<div class="playlist-head in d-block d-md-none">
									<span><i class="fa fa-bars"></i> Чат</span>
									<span class="icon">
										<i class="fa fa-chevron-down"></i>
										<i class="fa fa-chevron-up"></i>
									</span>
								</div>
								<div class="playlist-wrap nowrap">
									<iframe src="<?=$item['chat']?>" width="100%" height="100%" frameborder="0"></iframe>
								</div>
							</div>
						</div>
					</div>

					<div class="video-info pt-4 px-4">
						<h4>Описание</h4>
						<p><?=$item['description']?></p>
					</div>
				</div>
			</div>
		</div>
	</div>

<?else:?>
	<div class="row">
		<div class="col-12">
			<div class="alert alert-danger text-center" style="font-size: 24px;">Нет запланированных онлайн встреч</div>
			<div class="text-center" style="padding: 50px 0px;">
				<img src="<?=IMG_DUMMY?>" width="300" height="300">
			</div>
		</div>
	</div>
<?endif;?>