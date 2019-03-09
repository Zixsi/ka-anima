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

function alert_error($text)
{
	if(!empty($text))
	{
		echo '<div class="alert alert-danger">'.$text.'</div>';
	}
}

function alert_success($text)
{
	if(!empty($text))
	{
		echo '<div class="alert alert-success">'.$text.'</div>';
	}
}

function show_flash_message()
{
	$CI = &get_instance();
	if($res = $CI->session->flashdata('flash_message'))
	{
		echo '<div class="alert alert-'.$res['type'].'">'.$res['value'].'</div>';
	}
}

function set_flash_message($type, $text)
{
	if(!empty($type) && !empty($text))
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

function days_to_date($date)
{
	$date_a = new \DateTime('now');
	$date_b = new \DateTime($date);
	$interval = $date_a->diff($date_b);
	$days = intval($interval->format('%R%a'));

	return ($days > 0)?$days:0;
}

function set_select2($field, $value = '', $default = false)
{
	$CI =& get_instance();
	if(($input = $CI->input->post($field, false)) === null && ($input = $CI->input->get($field, false)) === null)
	{
		return ($default === true) ? ' selected="selected"' : '';
	}

	$value = (string) $value;
	if(is_array($input))
	{
		foreach($input as &$v)
		{
			if($value === $v)
			{
				return ' selected="selected"';
			}
		}

		return '';
	}

	return ($input === $value) ? ' selected="selected"' : '';
}

function is_active_menu_item($c, $a = null)
{
	$CI =& get_instance();
	$cr = strtolower($CI->router->fetch_class());		
	$ar = strtolower($CI->router->fetch_method());
	$c = strtolower($c);
	$a = strtolower($a);
	
	return (($c === $cr) && (empty($a) || $a === null || $a === $ar))?true:false;
}

function roadmap_months($date)
{
	//DATE_MONTHS
	$result = [];
	$date_a = new \DateTime('now');
	$date_b = new \DateTime($date);
	$interval = $date_a->diff($date_b);

	if($date_b < $date_a)
		return $result;

	$year = intval($date_a->format('Y'));
	$month = intval($date_a->format('n'));
	$month_cnt = (intval($interval->y) * 12) + (intval($interval->m) + 2);
	$month_cnt = ($month_cnt < 12)?12:$month_cnt;

	for($i = 0; $i < $month_cnt; $i++)
	{
		if($month > 12)
		{
			$month = 1;
			$year++;
		}

		$result[$year][$month] = DATE_MONTHS['ru'][($month - 1)];
		$month++;
	}

	return $result;
}