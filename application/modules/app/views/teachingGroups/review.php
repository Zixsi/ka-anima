<div class="row">
	<div class="col-xs-12">
		<h3 class="text-center" style="margin-bottom: 30px;"><?=$group['name']?></h3>

		<div class="panel panel-headline panel-color blue">
			<div class="panel-heading">
				<h3 class="panel-title">Редактирование ревью</h3>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<?=ShowFlashMessage();?>
						<form action="" method="post" enctype="multipart/form-data">
							<input type="hidden" name="<?=$csrf['key']?>" value="<?=$csrf['value']?>">
							<div class="form-group">
								<label>Ученик</label>
								<p><?=$item['user']['email']?></p>
							</div>
							<div class="form-group">
								<label>Url</label>
								<input type="text" class="form-control" name="video_url" value="<?=set_value('video_url', $item['video_url'])?>">
							</div>
							<div class="form-group">
								<label>Рекомендации</label>
								<textarea class="form-control" name="text" style="height: 300px;"><?=set_value('text', $item['text'])?></textarea>
							</div>
							<div class="form-group row">
								<label class="col-xs-12">Файлы</label>
								<div class="col-xs-3">
									<input type="file" name="files[]">
								</div>
								<div class="col-xs-3">
									<input type="file" name="files[]">
								</div>
								<div class="col-xs-3">
									<input type="file" name="files[]">
								</div>
							</div>

							<div class="row">
								<?if($item['files']):?>
									<?foreach($item['files'] as $val):?>
										<div class="col-xs-3">
											<a href="/<?=$val['path'].$val['name']?>"><?=$val['name']?></a>
											<button type="button" class="btn btn-danger btn-xxs btn-remove-file" data-id="<?=$val['id']?>"><i class="lnr lnr-cross"></i></button>
										</div>
									<?endforeach;?>
								<?endif;?>
							</div>

							<div class="form-group">
								<button type="submit" class="btn btn-xs btn-primary">Сохранить</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>

	</div>
</div>