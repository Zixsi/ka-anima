<?//debug($user);?>
<div class="panel panel-profile">
	<div class="row">
		<div class="col-xs-6">
			<div class="profile-header">
				<div class="overlay"></div>
				<div class="profile-main">
					<img src="<?=$user['img']?>" class="img-circle" alt="Avatar" width="80">
					<h3 class="name"><?=($user['full_name'] ?? 'User')?></h3>
					<span class="online-status status-available">Онлайн</span>
				</div>
				<div class="profile-stat">
					<div class="row">
						<div class="col-md-4 stat-item">
							0 <span>курсов пройдено</span>
						</div>
						<div class="col-md-4 stat-item">
							0 <span>друзей</span>
						</div>
						<div class="col-md-4 stat-item">
							0 <span>достижений</span>
						</div>
					</div>
				</div>
			</div>
			<div class="profile-detail">
				<div class="profile-info">
					<h4 class="heading">Базовая информация</h4>
					<ul class="list-unstyled list-justify">
						<li>Дата рождения <span><?=date('d-m-Y', strtotime($user['birthday']))?></span></li>
						<li>Телефон <span><?=$user['phone']?></span></li>
						<li>Email <span><?=$user['email']?></span></li>
					</ul>
				</div>
				<?/*
				<div class="profile-info">
					<h4 class="heading">Социальные сети</h4>
					<ul class="list-inline social-icons">
						<li><a href="#" class="in-bg"><i class="fa fa-in"></i></a></li>
						<li><a href="#" class="vk-bg"><i class="fa fa-vk"></i></a></li>
						<li><a href="#" class="facebook-bg"><i class="fa fa-facebook"></i></a></li>
						<li><a href="#" class="twitter-bg"><i class="fa fa-twitter"></i></a></li>
						<li><a href="#" class="google-plus-bg"><i class="fa fa-google-plus"></i></a></li>
						<li><a href="#" class="github-bg"><i class="fa fa-github"></i></a></li>
					</ul>
				</div>*/?>
				<?if($owner):?>
					<div class="text-center"><a href="/profile/edit/" class="btn btn-primary">Редактировать</a></div>
				<?endif;?>
			</div>
		</div>
		<div class="col-xs-6">
			<h4 class="panel-heading">Тут какая то еще информация</h4>
		</div>
	</div>
</div>