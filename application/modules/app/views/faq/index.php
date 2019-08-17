<?if($chunks):?>	
	<?foreach($chunks as $chunk):?>
		<div class="row">
			<?foreach($chunk as $section):?>
				<div class="col-12 col-md-6">
					<div class="row">
						<div class="col-12 grid-margin">
							<div class="card">
								<div class="faq-block card-body">
									<div class="container-fluid py-2">
										<h5 class="mb-0"><?=$section['name']?></h5>
									</div>
									<div id="accordion-<?=$section['id']?>" class="accordion">
										<?$show = true;?>
										<?foreach($section['items'] as $item):?>
											<div class="card mb-0">
												<div class="card-header" id="faq-item--heading<?=$item['id']?>">
													<h5 class="mb-0">
														<a data-toggle="collapse" data-target="#faq-item<?=$item['id']?>" aria-expanded="<?=($show)?'true':'false'?>" aria-controls="faq-item<?=$item['id']?>"><?=$item['question']?></a>
													</h5>
												</div>
												<div id="faq-item<?=$item['id']?>" class="collapse <?=($show)?'show':''?>" aria-labelledby="faq-item--heading<?=$item['id']?>" data-parent="#accordion-<?=$section['id']?>">
													<div class="card-body">
														<p class="mb-0"><?=$item['answer']?><p>
													</div>
												</div>
											</div>
											<?$show = false;?>
										<?endforeach;?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			<?endforeach;?>
		</div>
	<?endforeach;?>
<?endif;?>