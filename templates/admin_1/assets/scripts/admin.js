var GLOBAL_TIME_DELAY = 300;
var AJAX_API_URL = '/admin/ajax/';

$(document).ready(function(){
	$('.delete-item-form').on('submit', function(){
		if(confirm("Вы действительно хотите УДАЛИТЬ этот элемент?") === false)
			return false;
	});

	appMain();
	usersListener();
	courseListener();
});

function appMain()
{	
	$.fn.serializeObject = function(){

        var self = this,
            json = {},
            push_counters = {},
            patterns = {
                "validate": /^[a-zA-Z][a-zA-Z0-9_]*(?:\[(?:\d*|[a-zA-Z0-9_]+)\])*$/,
                "key":      /[a-zA-Z0-9_]+|(?=\[\])/g,
                "push":     /^$/,
                "fixed":    /^\d+$/,
                "named":    /^[a-zA-Z0-9_]+$/
            };


        this.build = function(base, key, value){
            base[key] = value;
            return base;
        };

        this.push_counter = function(key){
            if(push_counters[key] === undefined){
                push_counters[key] = 0;
            }
            return push_counters[key]++;
        };

        $.each($(this).serializeArray(), function(){

            // skip invalid keys
            if(!patterns.validate.test(this.name)){
                return;
            }

            var k,
                keys = this.name.match(patterns.key),
                merge = this.value,
                reverse_key = this.name;

            while((k = keys.pop()) !== undefined){

                // adjust reverse_key
                reverse_key = reverse_key.replace(new RegExp("\\[" + k + "\\]$"), '');

                // push
                if(k.match(patterns.push)){
                    merge = self.build([], self.push_counter(reverse_key), merge);
                }

                // fixed
                else if(k.match(patterns.fixed)){
                    merge = self.build([], k, merge);
                }

                // named
                else if(k.match(patterns.named)){
                    merge = self.build({}, k, merge);
                }
            }

            json = $.extend(true, json, merge);
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
	var roadmap_component = $('.roadmap-component');
	var add_group_modal = $('#add-group-modal');
	var add_group_modal_form = add_group_modal.find('form');
	var remove_group_modal = $('#remove-group-modal');
	var remove_group_modal_form = remove_group_modal.find('form');

	$('.roadmap-component').on('click', '.btn-add-group', function(){
		add_group_modal_form.find('input[name="course"]').val($(this).data('value'));
		add_group_modal.modal('show');
	});

	add_group_modal_form.on('submit', function(){
		var params = $(this).serializeObject();
		ajaxApiQuery('group.create', params, function(res){
			//toastrMsg('success', res);
			window.location.reload(true);
		});

		return false;
	});

	add_group_modal_form.on('change', 'select[name="type"]', function(){
		if($(this).val() == 'private')
			add_group_modal_form.find('.users-block').removeClass('hidden');
		else
			add_group_modal_form.find('.users-block').addClass('hidden');
	});

	$('.roadmap-component').on('click', '.btn-remove-group', function(){
		remove_group_modal.find('input[name="id"]').val($(this).data('id'));
		remove_group_modal.modal('show');
	});

	remove_group_modal_form.on('submit', function(){
		var params = $(this).serializeObject();
		ajaxApiQuery('group.remove', params, function(res){
			//toastrMsg('success', res);
			window.location.reload(true);
		});

		return false;
	});

	$('#select2-users').select2({
		//placeholder: 'Select an item',
		ajax: {
			url: '/admin/users/listForSelect/',
			dataType: 'json',
			delay: 250,
			processResults: function(data) {
				return {
					results: data
				};
			},
			cache: true
		}
	});
}

function usersListener()
{
	var add_item_modal = $('#add-user-modal');
	var add_item_modal_form = add_item_modal.find('form');
	var remove_item_modal = $('#remove-user-modal');
	var remove_item_modal_form = remove_item_modal.find('form');
	var block_item_modal = $('#block-user-modal');
	var block_item_modal_form = block_item_modal.find('form');
	var filter_form = $('#filter-user-form');
	var edit_form = $('#form-user-params');
	
	// создание пользователя
	add_item_modal.on('show.bs.modal', function(){
		clearInput(add_item_modal_form);
	});

	add_item_modal_form.on('submit', function(){
		var params = $(this).serializeObject();
		ajaxApiQuery('user.add', params, function(res){
			add_item_modal.modal('hide');
			clearInput(add_item_modal_form);
			toastrMsg('success', res);

			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	// блокировка пользователя
	$('.btn-user-block').on('click', function(){
		var id = $(this).data('id');
		block_item_modal_form.find('input[name="id"]').val(id);
		block_item_modal.modal('show');
		return false;
	});

	block_item_modal_form.on('submit', function(){
		var params = $(this).serializeObject();
		ajaxApiQuery('user.block', params.id, function(res){
			toastrMsg('success', res);
			block_item_modal.modal('hide');
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	// разблокировка пользователя
	$('.btn-user-unblock').on('click', function(){
		var id = $(this).data('id');
		ajaxApiQuery('user.unblock', id, function(res){
			toastrMsg('success', res);
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	// удаление пользователя
	$('.btn-user-remove').on('click', function(){
		var id = $(this).data('id');
		remove_item_modal_form.find('input[name="id"]').val(id);
		remove_item_modal.modal('show');
		return false;
	});

	remove_item_modal_form.on('submit', function(){
		var params = $(this).serializeObject();
		ajaxApiQuery('user.remove', params.id, function(res){
			toastrMsg('success', res);
			remove_item_modal.modal('hide');
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

	filter_form.on('change', 'input[type="radio"]', function(){
		filter_form.submit();
	});

	edit_form.on('submit', function(){
		var params = $(this).serializeObject();
		ajaxApiQuery('user.edit', params, function(res){
			toastrMsg('success', res);
			setTimeout(function(){
				window.location.reload(true);
			}, 1000);
		});

		return false;
	});

}