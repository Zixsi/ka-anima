<?
function ShowError($text)
{
	if(empty($text) == FALSE)
	{
		echo '<div class="alert alert-danger">'.$text.'</div>';
	}
}

function ShowSuccess($text)
{
	if(empty($text) == FALSE)
	{
		echo '<div class="alert alert-success">'.$text.'</div>';
	}
}

function ShowFlashMessage()
{
	$CI = &get_instance();
	if($res = $CI->session->flashdata('flash_message'))
	{
		echo '<div class="alert alert-'.$res['type'].'">'.$res['value'].'</div>';
	}
}

function SetFlashMessage($type, $text)
{
	if(empty($type) == FALSE && empty($text) == FALSE)
	{
		$CI = &get_instance();
		$CI->session->set_flashdata('flash_message', ['type' => strtolower($type), 'value' => $text]);
	}
}

function CrGetKey($pref = null)
{
	$key = random_string('alnum', 8);
	$value = random_string('alnum', 16);
	$pref = (empty($pref) == FALSE)?'_'.$pref:$pref;
	
	$_SESSION['csrfkey'.$pref] = $key;
	$_SESSION['csrfvalue'.$pref] = $value;
	return array('key' => $key, 'value' => $value);
}

function CrValidKey($pref = null)
{
	$pref = (empty($pref) == FALSE)?'_'.$pref:$pref;
		
	return (!empty($_SESSION['csrfkey'.$pref]) && !empty($_REQUEST[$_SESSION['csrfkey'.$pref]]) && 
		$_REQUEST[$_SESSION['csrfkey'.$pref]] == $_SESSION['csrfvalue'.$pref])?TRUE:FALSE;
}

function Debug($data = [])
{
	echo '<pre>'; print_r($data); echo '</pre>';
}