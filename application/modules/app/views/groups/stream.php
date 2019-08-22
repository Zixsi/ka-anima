<div class="row">
	<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?> [<?=$group['type']?>] <?=$group['code']?></h3>
	<?$this->load->view('groups/menu');?>

	<?if($list):?>
		<div class="col-8 offset-2">
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
	<div class="row">
		<?if($item['started'] == false):?>
			<div class="col-12">
				<div class="alert alert-danger text-center" style="font-size: 24px;">Начало <?=date('d-m-Y H:i:s', strtotime($item['ts']))?></div>
			</div>
		<?endif;?>
		<div class="col-6">
			<div class="card">
				<div class="card-body">
					<div class="video-wrap">
						<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?=$item['video_code']?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
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
			<div class="text-center" style="padding: 50px 0px;">
				<img src="<?=TEMPLATE_DIR?>/admin_1/assets/img/unicorn.jpg" width="300" height="300">
			</div>
		</div>
	</div>
<?endif;?>