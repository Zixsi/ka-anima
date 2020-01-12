<div class="row mb-4">
	<div class="col-12 col-sm-9">
		<h3><?=htmlspecialchars($item['title'])?></h3>
	</div>
	<div class="d-none d-sm-block col-sm-3 text-right">
		<a href="/" class="btn btn-outline-primary">Назад</a>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h5><?=$item['ts_formated']?></h5>
				<img src="<?=$item['img']?>" class="mb-4" style="max-width: 600px; width: 100%;">
				<div><?=url2link($item['description'])?></div>
				<div><?=url2link($item['text'])?></div>
			</div>
		</div>
		<div class="text-right mt-2 d-block d-sm-none">
			<a href="/" class="btn btn-outline-primary">Назад</a>
		</div>
	</div>
</div>