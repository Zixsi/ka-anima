<div class="row">
	<div class="col-xs-6">
		<div class="panel">
			<div class="panel-heading">
				<div class="col-xs-6">
					<h3 class="panel-title"><?=$item['course_name']?>: <?=$item['name']?></h3>
				</div>
			</div>
			<div class="panel-body" style="padding-top: 30px;">
				<div class="video-wrap">
					<iframe width="100%" height="100%" src="https://www.youtube.com/embed/<?=$item['video_code']?>" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
				</div>
				<div class="video-info">
					<h4>Описание</h4>
					<p><?=$item['description']?></p>
				</div>
			</div>
		</div>
	</div>
	<div class="col-xs-6">
		<div class="panel panel-headline">
			<div class="panel-body">
				<form action="" method="post" class="form">
					<div class="form-group">
						<label>Разместить сообщение</label>
						<textarea name="text" class="form-control" rows="5" placeholder="Поделитесь вашими мыслями..."></textarea>
					</div>
					<div class="form-group text-right">
						<button type="submit" class="btn btn-primary btn-xxs">Разместить</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>