<div class="row mb-4">
	<div class="col-10">
		<h3><?=$item['title']?></h3>
	</div>
	<div class="col-2 text-right">
		<a href="/" class="btn btn-outline-primary">Назад</a>
	</div>
</div>

<div class="row">
	<div class="col-12">
		<div class="card">
			<div class="card-body">
				<h5><?=$item['ts_formated']?></h5>
				<img src="<?=$item['img']?>">
				<div><?=$item['description']?></div>
				<div><?=$item['text']?></div>
			</div>
		</div>
	</div>
</div>