<div class="row" id="main--news-block">
	<?if($news):?>
		<?foreach($news as $val):?>
			<div class="col-12 col-sm-6 col-lg-4 col-xl-3 news-item">
				<div class="card">
					<a href="/news/<?=$val['id']?>/"><img class="card-img-top" src="<?=$val['img']?>" alt="<?=$val['title']?>"></a>
					<div class="card-body">
						<span class="text-muted text-small"><?=$val['ts_formated']?></span>
						<h4 class="card-title mt-1"><a href="/news/<?=$val['id']?>/" class="text-primary"><?=$val['title']?></a></h4>
						<p class="card-text"><?=$val['text']?></p>
					</div>
				</div>
			</div>
		<?endforeach;?>
	<?endif;?>
</div>
