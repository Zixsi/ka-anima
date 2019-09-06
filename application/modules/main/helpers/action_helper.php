<?php

// добавить действие
function action($action, $data = [], $user = null)
{
	$c = &get_instance();
	return $c->UserActionsHelper->add([
		'user' => (($user)?$user:$c->Auth->userID()),
		'action' => $action,
		'data' => $data
	]);
}

// вывод описания
function action_print_description($item)
{
	$c = &get_instance();
	return $c->UserActionsHelper->prepareDescription($item);
}

function test()
{
	return '#test#';
}