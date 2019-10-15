var GLOBAL_TIME_DELAY = 300;
var AJAX_API_URL = '/ajax/';

$(document).ready(function(){

	$.fn.serializeControls = function() {
		var data = {};

		function buildInputObject(arr, val) {
			if (arr.length < 1)
				return val;  
			var objkey = arr[0];
			if (objkey.slice(-1) == "]") {
				objkey = objkey.slice(0,-1);
			}
			var result = {};
			if (arr.length == 1){
				result[objkey] = val;
			} else {
				arr.shift();
				var nestedVal = buildInputObject(arr,val);
				result[objkey] = nestedVal;
			}
			return result;
		}

		$.each(this.serializeArray(), function() {
			var val = this.value;
			var c = this.name.split("[");
			var a = buildInputObject(c, val);
			$.extend(true, data, a);
		});
		
		return data;
	}

	$(".form-check label,.form-radio label").append('<i class="input-helper"></i>');
	$('[data-toggle="popover"]').popover();
	$('[data-toggle="tooltip"]').tooltip();

	authListener();
	appListener();
	wallComponent();
});

function toastrMsg(type, text)
{
	if(text.indexOf('<br>'))
	{
		text = text.split('<br>');
		if(text.length == 1)
			text = text[0];
		else
			text = text.slice(0, (text.length - 1));
	}

	var options = {
		heading: '',
		text: text,
		showHideTransition: 'slide',
		icon: 'error',
		position: 'top-right',
		hideAfter: 500000,
	};

	switch(type)
	{
		case 'success':
			options.heading = 'Успешно';
			options.icon = 'success';
			options.loaderBg = '#46c35f';
		break;
		case 'error':
			options.heading = 'Ошибка';
			options.icon = 'error';
			options.loaderBg = '#f96868';
		break;
		case 'warning':
			options.heading = 'Передупреждение';
			options.icon = 'warning';
			options.loaderBg = '#f2a654';
		break;
		case 'info':
			options.heading = 'Информация';
			options.icon = 'info';
			options.loaderBg = '#57c7d4';
		break;
	}

	$.toast(options);
}

function showPageLoader()
{
	$('.page-loader').addClass('ld-loading');
}

function hidePageLoader()
{
	$('.page-loader').removeClass('ld-loading');
}

function clearInput(t)
{
	t.find('input, textarea, select').each(function(){
		if($(this).data('const') !== undefined && $(this).data('const') !== null)
		{
			// empty
		}
		else
		{
			if($(this).is('input[type="radio"]') || $(this).is('input[type="checkbox"]'))
			{
				$(this).prop('checked', false);
			}
			else if($(this).is('select'))
			{
				$(this).val($(this).find('option:first').val());
				if($(this).hasClass('selectpicker'))
				{
					$(this).selectpicker("refresh");
				}
			}
			else
			{
				var val = '';
				if($(this).data('default') !== undefined && $(this).data('default') !== null)
				{
					val = $(this).data('default');
				}

				$(this).val(val);
			}
		}
	});
}

function setFormInput(t, data)
{
	t.find('input, textarea, select').each(function(){
		var key = $(this).attr('name');
		if(key !== undefined && key !== null)
		{
			if(key.indexOf('[]') > 0)
			{
				key = key.replace(/\[\]/g, '');
			}
			
			var val = data[key];

			if(val !== undefined && val !== null)
			{
				if($(this).is('input[type="radio"]') || $(this).is('input[type="checkbox"]'))
				{
					if(Array.isArray(val))
					{
						if(val.indexOf($(this).val()) >= 0)
						{
							$(this).prop('checked', true);
						}
					}
					else if($(this).val() === val)
					{
						$(this).prop('checked', true);
					}
				}
				else if($(this).is('select'))
				{
					$(this).val(val);
					if($(this).hasClass('selectpicker'))
					{
						$(this).selectpicker("refresh");
					}
				}
				else
				{
					$(this).val(val);
				}
			}
		}
	});
}

// ajax запрос
function ajaxQuery(method, params, callback)
{
	showPageLoader();
	var query = {'method': method, 'params': params};
	setTimeout(function(){
		$.ajax({
			url: AJAX_API_URL,
			type: 'POST',
			dataType: 'json',
			data: JSON.stringify(query),
			contentType: 'application/json; charset=utf-8',
			success: function(res){
				if(res.result !== undefined && res.result !== null)
				{
					if(callback && typeof(callback) === "function")
						callback(res.result);
				}
				else if(res.error)
				{
					if(res.error.code === 'NOT_AUTHORIZED')
					{
						setTimeout(function(){
							window.location.href = '/';
						}, 2000);
					}
					
					toastrMsg('error', res.error.message);
				}

				hidePageLoader();
			},
			error: function(e, code){
				hidePageLoader();
				toastrMsg('error', 'AJAX ERROR');
				// console.log(e.responseText);
			}
		});
	}, GLOBAL_TIME_DELAY);
}

function ajaxFilesQuery(method, params, files, callback)
{
	showPageLoader();
	var query = {'method': method, 'params': params};

	var formData = new FormData();
	formData.append('query', JSON.stringify(query));

	if(files.length > 0)
	{
		files.forEach(function(e, i){
			formData.append(e.name, e.value);
		});
	}

	setTimeout(function(){
		$.ajax({
			url: AJAX_API_URL,
			type: 'POST',
			dataType: false,
			contentType: false,
			processData: false,
			data: formData,
			success: function(res){

				try
				{
					res = JSON.parse(res);
				}
				catch(err)
				{
					toastrMsg('error', 'error parse json');
					return false;
				}

				if(res.result !== undefined && res.result !== null)
				{
					if(callback && typeof(callback) === "function")
						callback(res.result);
				}
				else if(res.error)
				{
					if(res.error.code === 'NOT_AUTHORIZED')
					{
						setTimeout(function(){
							window.location.href = '/';
						}, 2000);
					}
					
					toastrMsg('error', res.error.message);
				}

				hidePageLoader();
			},
			error: function(e, code){
				hidePageLoader();
				toastrMsg('error', 'AJAX ERROR');
				// console.log(e.responseText);
			}
		});
	}, GLOBAL_TIME_DELAY);
}


function formFiles(form)
{
	var files = [];
	$(form).find('input[type="file"]').each(function(i, e){
		if($(e)[0].files.length > 0)
			files.push({name: $(e).attr('name'), value: $(e)[0].files[0]});
	});

	return files;
}

function datepiker()
{
	if($('.datepiker').length < 1)
		return;

	$.datetimepicker.setLocale('ru');

	$('.datepiker').datetimepicker({
		format: 'd.m.Y',
		formatDate: 'd.m.Y',
		lang:'ru',
		timepicker: false,
		dayOfWeekStart: 1
	});
}

function datetimepicker()
{
	if($('.datetimepicker').length < 1)
		return;

	$.datetimepicker.setLocale('ru');

	$('.datetimepicker').datetimepicker({
		format: 'd.m.Y H:i:s',
		formatDate: 'd.m.Y',
		lang:'ru',
		timepicker: true,
		dayOfWeekStart: 1
	});
}


//====================================================================//

function authListener()
{
	$('#auth--login-form').on('submit', function(){
		ajaxQuery('auth.login', $(this).serializeControls(), function(res){
			window.location.href = '/';
		});
		return false;
	});

	$('#auth--register-form').on('submit', function(){
		ajaxQuery('auth.register', $(this).serializeControls(), function(res){
			window.location.href = '/';
		});
		return false;
	});

	$('#auth--forgot-form').on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('auth.forgot', params.email, function(res){
			toastrMsg('success', 'Письмо отправлено. Проверьте пожалуйста почту');
		});
		return false;
	});

	$('#auth--recovery-form').on('submit', function(){
		ajaxQuery('auth.recovery', $(this).serializeControls(), function(res){
			toastrMsg('success', 'Операция выполнена успешно');
			setTimeout(function(){
				window.location.href = '/';
			}, 3000);
		});
		return false;
	});
}



function appListener()
{	
	datepiker();
	datetimepicker();

	$(document).on('click', '[data-toggle="lightbox"]', function(event) {
		event.preventDefault();
		$(this).ekkoLightbox({ wrapping: false });
	});

	// $('.course-card--groups .btn-date-change input[type="radio"]').on('change', function(){
	// 	$('.pricing-table input[name="group"]').val($(this).val());
	// 	$('.pricing-table-wrapper').show();
	// });

	$('.pricing-table .period-checker input[type="radio"]').on('change', function(){
		$(this).closest('form').find('.price-value').removeClass('active').end().find('.price-value.price-value--' + $(this).val()).addClass('active');
	});

	$('.file-upload-browse').on('click', function() {
		var file = $(this).parent().parent().parent().find('.file-upload-default');
		file.trigger('click');
	});
	$('.file-upload-default').on('change', function() {
		$(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
	});

	// добавление ревью
	var add_review_modal = $('#add-review-modal');
	var add_review_modal_form = add_review_modal.find('form');
	var edit_review_modal = $('#edit-review-modal');
	var edit_review_modal_form = edit_review_modal.find('form');
	
	$('.btn-add-review').on('click', function(){
		var params = {
			group: $(this).data('group'),
			lecture: $(this).data('lecture'),
			user: $(this).data('user')
		};
		setFormInput(add_review_modal_form, params);
		add_review_modal.modal('show');
	});

	$('.btn-edit-review').on('click', function(){
		var id = $(this).data('id');
		ajaxQuery('review.getItem', {id: id}, function(res){
			// console.log(res);
			setFormInput(edit_review_modal_form, res);
			edit_review_modal.modal('show');
		});
	});

	$('.btn-remove-review').on('click', function(){
		ajaxQuery('review.delete', $(this).data('id'), function(res){
			add_review_modal.modal('hide');
			toastrMsg('success', res);
			reloadPage(300);
		});

		return false;
	});

	add_review_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('review.add', params, function(res){
			add_review_modal.modal('hide');
			toastrMsg('success', res);
			reloadPage(300);
		});

		return false;
	});

	edit_review_modal_form.on('submit', function(){
		var params = $(this).serializeControls();
		ajaxQuery('review.edit', params, function(res){
			edit_review_modal.modal('hide');
			toastrMsg('success', res);
			reloadPage(300);
		});

		return false;
	});
}

function reloadPage(timeout = 0)
{
	setTimeout(function(){
		window.location.reload(true);
	}, timeout);
}

function paySuccess()
{
	swal({
		title: 'Congratulations!',
		text: 'You entered the correct answer',
		icon: 'success',
		button: {
			text: "Continue",
			value: true,
			visible: true,
			className: "btn btn-primary"
		}
	});
}

function wallComponent()
{
	var wall_input = $('#wall-component .wall-from--text-input');
	var wall_main_form = $('#wall-main-form');
	var wall_child_from = $('#wall-component .wall-child-from');
	var wall_btn_load_more = $('#wall-load-more');
	var tpl_wall_item = $('#wall-item-tpl').html();
	var tpl_wall_child = $('#wall-child-tpl').html();
	var tpl_wall_item_role = $('#wall-item-role-tpl').html();

	wall_input.on('input change focus', function(e){
		$(this).height(20);
		$(this).height(this.scrollHeight);
	});

	var shift_press = false;
	wall_input.on('keydown', function(e){
		switch(e.which)
		{
			case 16:
				shift_press = true;
				break;
			case 13:
				if(shift_press == false)
				{
					$(this).closest('form').submit();
					e.preventDefault();
				}
				break;
			default:
				// empty
				break;
		}
	});

	wall_input.on('keyup', function(e){
		switch(e.which)
		{
			case 16:
				shift_press = false;
				break;
			default:
				// empty
				break;
		}
	});

	$('body').on('click', '#wall-component .wall-item-tools .btn-comments', function(){
		var is_loaded_child = $(this).hasClass('loaded');
		var parent_id = $(this).data('id');
		$(this).parent().parent().find('.wall-wrap-childs').fadeToggle(0).find('.wall-from--text-input').focus();
		// получить список дочерних комментариев
		if(!is_loaded_child)
		{
			// console.log(parent_id);
			$(this).addClass('loaded');
			$('body').trigger('wall:update-childs', {id: parent_id});
		}
	});

	$('body').on('wall:update-childs', function(e, params){
		var childs_container = $('#wall-childs-id' + params.id);
		if(childs_container.length > 0)
		{
			ajaxQuery('wall.message.child', {id: params.id}, function(res){
				wallFillChilds(childs_container, res);
			});
		}
	});



	wall_btn_load_more.on('click', function(){
		var btn = $(this);
		var target = btn.data('id');
		var limit = btn.data('limit') * 1;
		var offset = btn.data('offset') * 1;
		var all = btn.data('all') * 1;

		ajaxQuery('wall.message.list', {target: target, limit: limit, offset: offset}, function(res){
			offset = offset + limit;
			btn.data('offset',offset);
			if(offset >= all)
				btn.remove();

			// console.log(res);
			wallFill($('#wall-main-list'), res);
		});
	});
	
	// обработка главной формы
	wall_main_form.on('submit', function(){
		var form = $(this);
		var params = $(this).serializeControls();
		ajaxQuery('wall.message', params, function(res){
			clearInput(form);
			window.location.reload(true);
		});
		return false;
	});

	wall_child_from.on('submit', function(){
		var form = $(this);
		var params = $(this).serializeControls();
		ajaxQuery('wall.message', params, function(res){
			clearInput(form);
			$('#wall-component').trigger('wall:update-childs', {id: params.target});
		});
		return false;
	});

	function wallFill(container, data)
	{
		data.forEach(function(e, i){
			html = tpl_wall_item;
			html = html.replace(/{USER_ID}/g, e.user);
			html = html.replace(/{USER_NAME}/g, e.full_name);
			html = html.replace(/{USER_IMG}/g, e.img);
			html = html.replace(/{DATE}/g, e.ts);
			html = html.replace(/{TEXT}/g, e.text);

			if((e.role * 1) > 0)
			{
				html_role = tpl_wall_item_role;
				html_role = html_role.replace(/{VALUE}/g, e.role_name);
				html = html.replace(/{ROLE}/g, html_role);
			}
			else
			{
				html = html.replace(/{ROLE}/g, '');
			}

			html = html.replace(/{TARGET_ID}/g, e.group_id);
			html = html.replace(/{ID}/g, e.id);
			html = html.replace(/{CHILD_CNT}/g, (e.child_cnt * 1));
			
			container.append(html);
		});
	}

	function wallFillChilds(container, data)
	{
		container.empty();
		var cnt = 0;
		data.forEach(function(e, i){
			cnt++;
			html = tpl_wall_child;
			html = html.replace(/{USER_ID}/g, e.user);
			html = html.replace(/{USER_NAME}/g, e.full_name);
			html = html.replace(/{USER_IMG}/g, e.img);
			html = html.replace(/{DATE}/g, e.ts);
			html = html.replace(/{TEXT}/g, e.text);

			if((e.role * 1) > 0)
			{
				html_role = tpl_wall_item_role;
				html_role = html_role.replace(/{VALUE}/g, e.role_name);
				html = html.replace(/{ROLE}/g, html_role);
			}
			else
			{
				html = html.replace(/{ROLE}/g, '');
			}

			container.append(html);
		});

		container.closest('.wall-panel').find('.wall-item-tools .info-child-cnt').text(cnt);
	}
}

function drawChart(selector, label, labels, values, type = 'line', multy = false)
{
	if(selector.length)
	{
		var areaChartData, areaChartOptions, areaChartCanvas, areaChart;
		var backgroundColors = [
					'rgba(255, 99, 132, 0.2)',
					'rgba(54, 162, 235, 0.2)',
					'rgba(255, 206, 86, 0.2)',
					'rgba(75, 192, 192, 0.2)',
					'rgba(153, 102, 255, 0.2)',
					'rgba(255, 159, 64, 0.2)'
				];
		var borderColors = [
					'rgba(255,99,132,1)',
					'rgba(54, 162, 235, 1)',
					'rgba(255, 206, 86, 1)',
					'rgba(75, 192, 192, 1)',
					'rgba(153, 102, 255, 1)',
					'rgba(255, 159, 64, 1)'
				];

		if(multy)
		{
			areaChartData = {
				labels: labels,
				datasets: []
			};

			values.forEach(function(e, i){
				areaChartData.datasets.push({
					label: e.label,
					data: e.values,
					backgroundColor: backgroundColors[i],
					borderColor: borderColors[i],
					borderWidth: 1,
					fill: true, // 3: no fill
				});
			});

			console.log(areaChartData);
		}
		else
		{
			areaChartData = {
				labels: labels,
				datasets: [{
					label: label,
					data: values,
					backgroundColor: backgroundColors,
					borderColor: borderColors,
					borderWidth: 1,
					fill: true, // 3: no fill
				}]
			};
		}

		areaChartOptions = {
			plugins: {
				filler: {
					propagate: true
				}
			}
		};
		
		areaChartCanvas = selector.get(0).getContext("2d");
		areaChart = new Chart(areaChartCanvas, {
			type: type,
			data: areaChartData,
			options: areaChartOptions
		});
	}
}