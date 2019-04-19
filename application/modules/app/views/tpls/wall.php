<script type="text/html" id="wall-child-tpl">
	<div class="media">
		<a class="pull-left" href="/profile/{USER_ID}/" target="_blank">
			<img class="media-object" src="{USER_IMG}" alt="{USER_NAME}">
		</a>
		<div class="media-body">
			<h4 class="media-heading">
				<a href="/profile/{USER_ID}/" target="_blank">{USER_NAME}</a>
				{ROLE}
				<br><small>{DATE}</small>
			</h4>
			{TEXT}
		</div>
	</div>
</script>

<script type="text/html" id="wall-item-role-tpl">
	<small><span class="label label-success">{VALUE}</span></small>
</script>