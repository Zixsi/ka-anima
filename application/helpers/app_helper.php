<?php

/**
 * @return Doctrine\DBAL\Connection
 */
function dbConnection()
{
    include APPPATH . 'config/database.php';
    
    return \Doctrine\DBAL\DriverManager::getConnection([
        'driver' => 'pdo_mysql',
        'user' => $db['default']['username'],
        'password' => $db['default']['password'],
        'dbname' => $db['default']['database'],
        'host' => $db['default']['hostname'],
        'charset'  => $db['default']['char_set'],
        'driverOptions' => [
            1002 => 'SET NAMES utf8'
        ]
    ]);
}

/**
 * @staticvar CI_Upload $instance
 * @return CI_Upload
 */
function uploader()
{
    static $instance;

    if ($instance === null) {
        $ci = get_instance();
        $ci->load->config('upload');
        $ci->load->library('upload');
        $instance = $ci->upload;
    }


    return $instance;
}

/**
 * @staticvar CI_Config $instance
 * @return CI_Config
 */
function config()
{
    static $instance;

    if ($instance === null) {
        $instance = get_instance()->config;
    }


    return $instance;
}

function showError($text)
{
    if (empty($text) == false) {
        echo '<div class="alert alert-danger">' . $text . '</div>';
    }
}

function showSuccess($text)
{
    if (empty($text) == false) {
        echo '<div class="alert alert-success">' . $text . '</div>';
    }
}

function ShowFlashMessage()
{
    $CI = &get_instance();
    if ($res = $CI->session->flashdata('flash_message')) {
        echo '<div class="alert alert-' . $res['type'] . '">' . $res['value'] . '</div>';
    }
}

function SetFlashMessage($type, $text)
{
    if (empty($type) == FALSE && empty($text) == FALSE) {
        $CI = &get_instance();
        $CI->session->set_flashdata('flash_message', ['type' => strtolower($type), 'value' => $text]);
    }
}

function alert_error($text)
{
    if (!empty($text)) {
        echo '<div class="alert alert-danger">' . $text . '</div>';
    }
}

function alert_success($text)
{
    if (!empty($text)) {
        echo '<div class="alert alert-success">' . $text . '</div>';
    }
}

function show_flash_message()
{
    $CI = &get_instance();
    if ($res = $CI->session->flashdata('flash_message')) {
        echo '<div class="alert alert-' . $res['type'] . '">' . $res['value'] . '</div>';
    }
}

function set_flash_message($type, $text)
{
    if (!empty($type) && !empty($text)) {
        $CI = &get_instance();
        $CI->session->set_flashdata('flash_message', ['type' => strtolower($type), 'value' => $text]);
    }
}

function cr_get_key($pref = null)
{
    $key = random_string('alnum', 8);
    $value = random_string('alnum', 16);
    $pref = (empty($pref) == FALSE) ? '_' . $pref : $pref;

    $_SESSION['csrfkey' . $pref] = $key;
    $_SESSION['csrfvalue' . $pref] = $value;
    return array('key' => $key, 'value' => $value);
}

function cr_valid_key($pref = null)
{
    $pref = (empty($pref) == FALSE) ? '_' . $pref : $pref;

    return (!empty($_SESSION['csrfkey' . $pref]) && !empty($_REQUEST[$_SESSION['csrfkey' . $pref]]) &&
        $_REQUEST[$_SESSION['csrfkey' . $pref]] == $_SESSION['csrfvalue' . $pref]) ? TRUE : FALSE;
}

function get_rel_path($path)
{
    return str_replace('\\', '/', str_replace(FCPATH, '', $path));
}

function debug($data = [])
{
    echo '<pre>';
    var_dump($data);
    echo '</pre>';
}

// Получить день месяца у дня недели. Например первого воскресенья августа
function compute_day($weekNumber, $dayOfWeek, $monthNumber, $year)
{
    // порядковый номер дня недели первого дня месяца $monthNumber
    $dayOfWeekFirstDayOfMonth = date('w', mktime(0, 0, 0, $monthNumber, 1, $year));

    // сколько дней осталось до дня недели $dayOfWeek относительно дня недели $dayOfWeekFirstDayOfMonth
    $diference = 0;

    // если нужный день недели $dayOfWeek только наступит относительно дня недели $dayOfWeekFirstDayOfMonth
    if ($dayOfWeekFirstDayOfMonth <= $dayOfWeek) {
        $diference = $dayOfWeek - $dayOfWeekFirstDayOfMonth;
    }
    // если нужный день недели $dayOfWeek уже прошёл относительно дня недели $dayOfWeekFirstDayOfMonth
    else {
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

    return ($days > 0) ? $days : 0;
}

function set_select2($field, $value = '', $default = false)
{
    $CI = & get_instance();
    if (($input = $CI->input->post($field, false)) === null && ($input = $CI->input->get($field, false)) === null) {
        return ($default === true) ? ' selected="selected"' : '';
    }

    $value = (string) $value;
    if (is_array($input)) {
        foreach ($input as &$v) {
            if ($value === $v) {
                return ' selected="selected"';
            }
        }

        return '';
    }

    return ($input === $value) ? ' selected="selected"' : '';
}

function set_value2($field, $default = '', $html_escape = TRUE)
{
    $CI = & get_instance();

    $value = (isset($CI->form_validation) && is_object($CI->form_validation) && $CI->form_validation->has_rule($field)) ? $CI->form_validation->set_value($field, $default) : ($CI->input->post($field, FALSE) ?? $CI->input->get($field, FALSE));

    isset($value) OR $value = $default;
    return ($html_escape) ? html_escape($value) : $value;
}

function is_active_menu_item($c, $a = null)
{
    $CI = & get_instance();
    $cr = strtolower($CI->router->fetch_class());
    $ar = strtolower($CI->router->fetch_method());
    $c = strtolower($c);
    $a = strtolower($a);

    return (($c === $cr) && (empty($a) || $a === null || $a === $ar)) ? true : false;
}

function roadmap_months($date)
{
    //DATE_MONTHS
    $result = [];
    $date_a = new \DateTime('now');
    $date_a->modify('-1 month');
    $date_b = new \DateTime($date);
    $interval = $date_a->diff($date_b);

    if ($date_b < $date_a)
        return $result;

    $year = intval($date_a->format('Y'));
    $month = intval($date_a->format('n'));
    $month_cnt = (intval($interval->y) * 12) + (intval($interval->m) + 2);
    $month_cnt = ($month_cnt < 12) ? 12 : $month_cnt;

    for ($i = 0; $i < $month_cnt; $i++) {
        if ($month > 12) {
            $month = 1;
            $year++;
        }

        $result[$year][$month] = DATE_MONTHS['ru'][($month - 1)];
        $month++;
    }

    return $result;
}

function date_diff_months($date1, $date2)
{
    $date_a = ($date1 instanceof DateTime) ? $date1 : new DateTime($date1);
    $date_b = ($date2 instanceof DateTime) ? $date2 : new DateTime($date2);
    return abs((($date_b->format('Y') - $date_a->format('Y')) * 12) + ($date_b->format('n') - $date_a->format('n')));
}

function roadmap_check_intersect($item, $list)
{
    $date_a1 = new DateTime($item['ts']);
    $date_a2 = new DateTime($item['ts_end']);

    $index = 0;
    foreach ($list as $key => $val) {
        $index = $key;

        if (empty($val))
            break;

        $collision = 0;
        foreach ($val as $v) {
            $date_b1 = new DateTime($v['ts']);
            $date_b2 = new DateTime($v['ts_end']);

            if (($date_a1 <= $date_b2) && ($date_a2 >= $date_b1))
                $collision++;
        }

        if ($collision === 0)
            break;
        else
            $index++;
    }

    return $index;
}

function calc_crop_rect($width, $height)
{
    $result = ['x' => 0, 'y' => 0, 'width' => 0, 'height' => 0];
    $result['width'] = (int) $width;
    $result['height'] = (int) $height;

    if ($result['height'] === 0)
        return $result;

    if ($result['height'] > $result['width']) {
        $result['y'] = ceil(($result['height'] - $result['width']) / 2);
        $result['height'] = $result['width'];
    } elseif ($result['width'] > $result['height']) {
        $result['x'] = ceil(($result['width'] - $result['height']) / 2);
        $result['width'] = $result['height'];
    }

    return $result;
}

function thumb($src)
{
    $thumb_src = str_replace('.', '_thumb.', $src);
    if (file_exists(/* $_SERVER['DOCUMENT_ROOT']. */$thumb_src))
        $src = $thumb_src;

    return $src;
}

function url2link($text)
{
    return preg_replace('/(http:\/\/[[:print:]]+)/sm', '<a href="$1" target="_blank">$1</a>', $text);
}

function getVideoId($url)
{
    preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $url, $match);

    return $match[1] ?? '';
}

function getIp()
{
    return ($_SERVER['HTTP_X_REAL_IP'] ?? $_SERVER['HTTP_CLIENT_IP'] ?? $_SERVER['HTTP_X_FORWARDED_FOR'] ?? $_SERVER['REMOTE_ADDR']);
}

function getUserAgent()
{
    return ($_SERVER['HTTP_USER_AGENT'] ?? '');
}

function setRequestBackUri($url = null)
{
    $urlFinal = urlencode( ($url ?? $_SERVER['REQUEST_URI']) );
    setcookie('backRequestUri', $urlFinal, time() + 86400, '/');
}

function getRequestBackUri()
{
    $result = null;
    if (isset($_COOKIE['backRequestUri']))
        $result = $_COOKIE['backRequestUri'];

    return $result;
}

function clearRequestBackUri()
{
    setcookie('backRequestUri', '', 0, '/');
}

function num2word($num, $words)
{
    $num = $num % 100;
    if ($num > 19) {
        $num = $num % 10;
    }
    switch ($num) {
        case 1: {
                return($words[0]);
            }
        case 2: case 3: case 4: {
                return($words[1]);
            }
        default: {
                return($words[2]);
            }
    }
}

function extractItemId($data, $key = 'id')
{
    $result = null;
    if (is_array($data)) {
        if (array_key_exists($key, $data))
            $result = $data[$key];
        else {
            foreach ($data as $row)
                $result[] = $row[$key];
        }
    }

    return $result;
}

function extractValues($data, $field, $key = null)
{
    $result = [];
    if (is_array($data)) {
        foreach ($data as $row) {
            if (array_key_exists($field, $row)) {
                if ($key && isset($row[$key]))
                    $result[$row[$key]] = $row[$field];
                else
                    $result[] = $row[$field];
            }
        }
    }

    return $result;
}

function groupByField($data, $key, $uniqe = false)
{
    $result = [];

    if (is_array($data) && empty($data) === false && empty($key) === false) {
        foreach ($data as $row) {
            if (array_key_exists($key, $row) === false)
                continue;

            $itemKey = $row[$key];
            if ($uniqe) {
                $result[$itemKey] = $row;
            } else {
                if (array_key_exists($itemKey, $result) === false)
                    $result[$itemKey] = [];

                $result[$itemKey][] = $row;
            }
        }
    }

    return $result;
}

function setArrayKeys($data, $key)
{
    return groupByField($data, $key, true);
}

function isValidYoutubeVideoUrl($url)
{
    return (empty(getVideoId($url)) === false);
}

function isValidYandexVideoUrl($url)
{
    $CI = & get_instance();
    $CI->load->library('ydvideo');
    return $CI->ydvideo->validUrl($url);
}

function uploadFile($name, $config)
{
    $result = false;

    if (isset($_FILES[$name]) && empty($_FILES[$name]['name']) === false) {
        $CI = & get_instance();
        $CI->load->config('upload');
        $uploadConfig = $CI->config->item($config);
        $CI->load->library('upload', $uploadConfig);

        if ($CI->upload->do_upload($name) == false)
            throw new Exception($CI->upload->display_errors(), 1);

        $result = $CI->upload->data();
    }

    return $result;
}

function time2minutes($val)
{
    $val = (int) $val;
    $m = floor($val / 60);
    $s = $val - ($m * 60);
    $m = str_pad($m, 2, '0', STR_PAD_LEFT);
    $s = str_pad($s, 2, '0', STR_PAD_LEFT);

    return $m . ':' . $s;
}

function time2hours($val)
{
    $val = (int) $val;
    $h = floor($val / 3600);
    $val = $val - ($h * 3600);
    $m = floor($val / 60);
    $s = $val - ($m * 60);
    $h = str_pad($h, 2, '0', STR_PAD_LEFT);
    $m = str_pad($m, 2, '0', STR_PAD_LEFT);
    $s = str_pad($s, 2, '0', STR_PAD_LEFT);

    return $h . ':' . $m . ':' . $s;
}

function priceFormat($value, $char = true, $showFree = true)
{
    $result = null;
    if ($showFree && (float) $value === 0.0)
        $result = 'FREE';
    else
        $result = number_format((float) $value, 2, '.', ' ') . ($char ? '&nbsp;&nbsp;' . PRICE_CHAR : '');

    return $result;
}

function getVimeoVideoId($url)
{
    preg_match('/(https?:\/\/)?(www\.)?(player\.)?vimeo\.com\/([a-z]*\/)*([0-9]{6,11})[?]?.*/', $url, $match);
    return $match[5] ?? '';
}

function getVideoIframeUrl($video)
{
    $result = '/video/' . $video['video_code'] . '/';
    if (strpos($video['code'], 'vimeo')) {
        $result = 'https://player.vimeo.com/video/' . getVimeoVideoId($video['code']);
    } elseif (isValidYoutubeVideoUrl($video['code'])) {
        $result = 'https://www.youtube.com/embed/' . getVideoId($video['code']) . '?modestbranding=1&rel=0&showinfo=0';
    }

    return $result;
}

function makeUrl($params = [], $ignoreFields = [])
{
    $result = '/';
    $urlParams = $_GET;
    $params = array_merge($urlParams, $params);
    foreach ($ignoreFields as $val)
        unset($params[$val]);

    if (count($params))
        $result .= '?' . http_build_query($params);

    return $result;
}

function makeDefaultFormFields($params = [], $ignoreFields = [])
{
    $urlParams = $_GET;
    $params = array_merge($urlParams, $params);
    if (count($params)) {
        foreach ($params as $key => $val) {
            if (in_array(trim($key), $ignoreFields))
                continue;

            echo '<input type="hidden" name="' . htmlspecialchars(trim($key)) . '" value="' . htmlspecialchars(trim($val)) . '">';
        }
    }
}

function set_radio2($field, $value = '', $default = FALSE)
{
    $CI = & get_instance();

    $value = (string) $value;
    $input = $CI->input->post($field, FALSE) ?? $CI->input->get($field, FALSE);

    if (is_array($input)) {
        // Note: in_array('', array(0)) returns TRUE, do not use it
        foreach ($input as &$v) {
            if ($value === $v) {
                return ' checked="checked"';
            }
        }

        return '';
    }


    return ($input === $value || $default === TRUE) ? ' checked="checked"' : '';
}
