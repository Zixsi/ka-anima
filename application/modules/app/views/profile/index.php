<div class="row">
	<div class="col-6 offset-3">
		<?if(!$this->Auth->isActive()):?>
			<div class="alert alert-fill-warning" role="alert">
				<i class="mdi mdi-alert-circle"></i>
				<span>Пользователь неактивирован. Часть функционала будет недоступно. Для активации следуйте инструкциям направленным на почту.</span>
			</div>
		<?endif;?>
		<div class="card">
			<div class="card-body">
				<div class="row">
					<div class="col-12">
						<div class="border-bottom text-center pb-4">
							<img src="<?=$user['img']?>" alt="profile" class="img-lg rounded-circle mb-3"/>
							<div class="mb-3">
								<h3><?=($user['full_name'] ?? 'User')?></h3>
							</div>
							<p class="w-75 mx-auto mb-3"><?=($user['title'] ?? '')?></p>
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
						<?if($owner || !$this->Auth->isUser()):?>
							<div class="pt-4">
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
								<?if(!empty($user['soc'])):?>
									<p class="clearfix">
										<span class="float-left">Профиль соцсети</span>
										<span class="float-right text-muted">
											<a href="<?=prep_url($user['soc'])?>" target="_blank"><?=prep_url($user['soc'])?></a>
										</span>
									</p>
								<?endif;?>
							</div>
						<?endif;?>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>