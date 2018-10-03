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

function cr_get_key($pref = null)
{
	$key = random_string('alnum', 8);
	$value = random_string('alnum', 16);
	$pref = (empty($pref) == FALSE)?'_'.$pref:$pref;
	
	$_SESSION['csrfkey'.$pref] = $key;
	$_SESSION['csrfvalue'.$pref] = $value;
	return array('key' => $key, 'value' => $value);
}

function cr_valid_key($pref = null)
{
	$pref = (empty($pref) == FALSE)?'_'.$pref:$pref;
		
	return (!empty($_SESSION['csrfkey'.$pref]) && !empty($_REQUEST[$_SESSION['csrfkey'.$pref]]) && 
		$_REQUEST[$_SESSION['csrfkey'.$pref]] == $_SESSION['csrfvalue'.$pref])?TRUE:FALSE;
}

function get_rel_path($path)
{
    return str_replace('\\', '/', str_replace(FCPATH, '', $path));
}

function Debug($data = [])
{
	echo '<pre>'; print_r($data); echo '</pre>';
}

// Получить день месяца у дня недели. Например первого воскресенья августа
function compute_day($weekNumber, $dayOfWeek, $monthNumber, $year)
{
    // порядковый номер дня недели первого дня месяца $monthNumber
    $dayOfWeekFirstDayOfMonth = date('w', mktime(0, 0, 0, $monthNumber, 1, $year));
 
    // сколько дней осталось до дня недели $dayOfWeek относительно дня недели $dayOfWeekFirstDayOfMonth
    $diference = 0;
 
    // если нужный день недели $dayOfWeek только наступит относительно дня недели $dayOfWeekFirstDayOfMonth
    if ($dayOfWeekFirstDayOfMonth <= $dayOfWeek)
    {
        $diference = $dayOfWeek - $dayOfWeekFirstDayOfMonth;
    }
    // если нужный день недели $dayOfWeek уже прошёл относительно дня недели $dayOfWeekFirstDayOfMonth
    else
    {
        $diference = 7 - $dayOfWeekFirstDayOfMonth + $dayOfWeek;
    }
 
    return 1 + $diference + ($weekNumber - 1) * 7;
}

function next_monday_ts()
{
	$date = new \DateTime('next monday');
	return $date->getTimestamp();
}
