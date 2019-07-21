<div class="row">
	<div class="col-6 offset-3">
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<div class="border-bottom text-center pb-4">
							<img src="<?=$user['img']?>" alt="profile" class="img-lg rounded-circle mb-3"/>
							<div class="mb-3">
								<h3><?=($user['full_name'] ?? 'User')?></h3>
							</div>
							<p class="w-75 mx-auto mb-3">Bureau Oberhaeuser is a design bureau focused on Information- and Interface Design. </p>
							<div class="d-flex justify-content-center">
								<?if(!$owner):?>
									<?if(!$is_friends):?>
										<form action="/users/" method="post">
											<input type="hidden" name="id" value="<?=$user['id']?>">
											<button type="submit" class="btn btn-primary mr-1">Добавить в друзья</button>
										</form>
									<?endif;?>
									<a href="/profile/messages/<?=$user['id']?>/" class="btn btn-primary">Написать сообщение</a>
								<?else:?>
									<a href="/profile/friends/" class="btn btn-primary mr-1">Друзья (<?=$friends_cnt?>)</a>
									<a href="/profile/edit/" class="btn btn-primary">Редактировать</a>
								<?endif;?>
							</div>
						</div>
						<div class="py-4">
							<p class="clearfix">
								<span class="float-left">Дата рождения</span>
								<span class="float-right text-muted"><?=date(DATE_FORMAT_SHORT, strtotime($user['birthday']))?></span>
							</p>
							<p class="clearfix">
								<span class="float-left">Телефон</span>
								<span class="float-right text-muted"><?=$user['phone']?></span>
							</p>
							<p class="clearfix">
								<span class="float-left">E-mail</span>
								<span class="float-right text-muted"><?=$user['email']?></span>
							</p>
						</div>
					</div>
					<?/*
					<div class="col-lg-8">
						<div class="text-center">
							<h3>Тут поселился радужный пони</h3>
							<img src="<?=IMG_UNICORN?>">
						</div>
					</div>*/?>
				</div>
			</div>
		</div>
	</div>
</div>