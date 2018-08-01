$(document).ready(function(){
	
	var lectures_block = $('table.lectures-block');

	$('.row-course').on('click', function(){
		if(!$(this).hasClass('active'))
		{
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
							html += '<tr><td><span class="lnr lnr-eye"></span></td>' +
							'<td><div class="row"><div class="col-xs-8">' + e.name + '</div>' +
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

});