$(document).ready(function(){
	
	var lectures_block = $('table.lectures-block');

	$('.row-course').on('click', function(){
		if(!$(this).hasClass('active'))
		{
			$(this).closest('table').find('.row-course').removeClass('active').end().end().addClass('active');
			$('#lectures-video video').attr('src', '');
			$('#lecture-task-text').empty();

			$.ajax({
				url: '/ajax/courseLectures/',
				method: 'POST',
				data: {id: $(this).data('id')},
				dataType: 'json',
				success: function(res){

					lectures_block.empty();
					if(res.items.length > 0)
					{
						var html = '';
						res.items.forEach(function(e, i){
							e.task = (e.task ==undefined || e.task == null)?'':e.task;
							
							html += '<tr data-video="' + e.video + '"><td><span class="lnr lnr-eye"></span></td>' +
							'<td><div class="row"><div class="col-xs-8">' + e.name + '</div>' + 
							'<div class="hidden data-task">' + e.task + '</div>' +
							'<div class="col-xs-4 text-right"><span class="lnr lnr-sync"></span></div>' + 
							'</div><div class="progress progress-xs"><div class="progress-bar progress-bar-success" ' +
							' role="progressbar" aria-valuenow="80" aria-valuemin="0" aria-valuemax="100" style="width: 80%">' +
							'</div></div></td></tr>';
						});

						lectures_block.append(html);
					}
				},
				error: function(e){
					//
				}
			});
		}
	});


	$('body').on('click', 'table.lectures-block tr', function(){

		var video = $(this).data('video');
		var task = $(this).find('.data-task').html();
		if(video !== null && video !== undefined)
		{
			$('#lectures-video video').attr('src', video);
			$('#lecture-task-text').html(task);
		}
	});

});