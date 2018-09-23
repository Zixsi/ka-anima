<div class="row">

	<div class="col-xs-12 text-center" style="margin-bottom: 30px;">
		<a href="#" class="btn btn-primary">Лекции</a>
		<a href="#" class="btn btn-default disabled">Группа</a>
		<a href="#" class="btn btn-default disabled">Ревью работ</a>
		<a href="#" class="btn btn-default disabled">Онлайн встречи</a>
	</div>

	<div class="col-xs-12">
		<div class="week-panel">
			<?$i = 1;?>
			<?foreach($lectures as $item):?>
				<span class="week-item <?=($i == 1)?'active':''?>">
					<span class="number"><?=$i?></span>
					<span class="name"><?=$item['name']?></span>
				</span>
				<?$i++;?>
			<?endforeach;?>
		</div>
	</div>

	<div class="col-xs-12">
		<h3>Название лекции</h3>
	</div>

	<div class="col-xs-5">
		<div class="video-wrap">
			<iframe src="/video/1" width="100%" height="100%" frameborder="0" allow="autoplay; encrypted-media" allowfullscreen></iframe>
		</div>
		<div class="video-info">
			<h4>Описание</h4>
			<h4>Задание</h4>
			<h4>Материалы для лекции</h4>
		</div>
	</div>
	<div class="col-xs-7">
		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Загрузка заданий</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-6" style="font-size: 14px;">
						<p><b>Видео файлы (mp4) и картинки (jpg, png) пожалуйста, загружайте отдельными файлами.</b></p>
						<p><b>Файлы других типов</b> (не видео и не картинки, а файлы Maya, 3DS Max, RealFlow, Z-Brush и др.) <b>загружайте заархивированными как RAR или ZIP</b>. После загрузки каждого архива, отдельно можете загрузить превью (пример) картинку или видео того что было загружено в архиве.</p>

						<p><b>Требования к загружаемым файлам:</b><br>
						Картинки и видео – <b>jpg</b>, <b>png</b>, <b>mp4</b> (кодек <b>h264</b> или <b>x264</b>), разрешение <b>1280х720</b> пикселей.
						Другие файлы загружать как архивы <b>RAR</b> или <b>ZIP</b>.
						Максимальный размер файлов: <b>250 МБ</b></p>
					</div>
					<div class="col-xs-6">
						<form action="" method="post" class="form">
							<div class="form-group">
								<label></label>
								<input type="file" name="" class="form-control">
							</div>
							<div class="form-group">
								<label>Комментарий к файлу</label>
								<textarea class="form-control" rows="5"></textarea>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

		<div class="panel panel-headline">
			<div class="panel-heading">
				<h3 class="panel-title">Загруженные задания</h3>
			</div>
			<div class="panel-body">
				<table class="table">
					<tbody>
						<tr>
							<td>2018-09-05</td>
							<td>Вася Пупкин</td>
							<td>file.ext</td>
							<td>
								<a href="#" class="btn btn-primary btn-xs">Скачать</a>
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
	</div>

</div>

