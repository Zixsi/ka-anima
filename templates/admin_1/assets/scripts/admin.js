var GLOBAL_TIME_DELAY = 300;
var AJAX_API_URL = '/admin/ajax/';

$(document).ready(function(){
	$('.delete-item-form').on('submit', function(){
		if(confirm("Вы действительно хотите УДАЛИТЬ этот элемент?") === false)
			return false;
	});

	appMain();
	courseListener();
});

function appMain()
{	
	$.fn.serializeJSON=function() {
		var json = {};
		jQuery.map($(this).serializeArray(), function(n, i){
			if((n['name'].indexOf('[') > 0) && (n['name'].indexOf(']') > 0))
			{
				if(json[n['name']] === null || json[n['name']] === undefined)
				{
					json[n['name']] = [];
				}

				json[n['name']].push(n['value']);
			}
			else
			{
				json[n['name']] = n['value'];
			}
		});
		return json;
	};

	$('input.type-int-number').on('change keyup input click', function() {
		if($(this).val().match(/[^0-9]/g))
		{
			$(this).val($(this).val().replace(/[^0-9]/g, ''));
		}
	});

	toastr.options = {
		"closeButton": true,
		"debug": false,
		"newestOnTop": false,
		"progressBar": true,
		"positionClass": "toast-top-right",
		"preventDuplicates": false,
		"onclick": null,
		"showDuration": "0",
		"hideDuration": "1000",
		"timeOut": "5000",
		"extendedTimeOut": "1000",
		"showEasing": "swing",
		"hideEasing": "linear",
		"showMethod": "fadeIn",
		"hideMethod": "fadeOut"
	};
}

function toastrMsg(type, text)
{
	toastr[type](text, '');
}

function showLoader(t)
{
	$(t).addClass('ld-loading');
}

function hideLoader(t)
{
	$(t).removeClass('ld-loading');
}

function showPageLoader()
{
	$('.page-loader').addClass('ld-loading');
}

function hidePageLoader()
{
	$('.page-loader').removeClass('ld-loading');
}

function disableInput(t)
{
	$(t).find('input, select, button').attr("disabled", true);
}

function enableInput(t)
{
	$(t).find('input, select, button').attr("disabled", false);
}

function clearInput(t)
{
	t.find('input, textarea, select').each(function(){

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

// Запрос к API
function ajaxApiQuery(method, params, callback)
{
	showPageLoader();
	var query = {'jsonrpc': '2.0', 'method': method, 'params': params, 'id': 1};
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
					{
						callback(res.result);
					}
				}
				else if(res.error)
				{
					if(res.error.code === -32000)
					{
						setTimeout(function(){
							window.location.href = "/";
						}, 3000);
					}
					
					toastrMsg('error', res.error.message);
				}

				hidePageLoader();
			},
			error: function(e, code){
				hidePageLoader();
				toastrMsg('error', 'AJAX ERROR');
				console.log(e.responseText);
			}
		});
	}, GLOBAL_TIME_DELAY);
}

function courseListener()
{
	// name="course"
	var add_group_modal = $('#add-group-modal');
	var add_group_modal_form = add_group_modal.find('form');

	$('.roadmap-component').on('click', '.btn-add-group', function(){
		add_group_modal_form.find('input[name="course"]').val($(this).data('value'));
		add_group_modal.modal('show');
	});
	

	add_group_modal_form.on('submit', function(){
		var params = $(this).serializeJSON();
		ajaxApiQuery('group.create', params, function(res){
			toastrMsg('success', res);
			window.location.reload(true);
		});

		return false;
	});
}