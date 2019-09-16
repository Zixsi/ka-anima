<?
class SystemHook
{
	public $CI;

	public function __construct()
	{
		$this->CI = &get_instance();
	}

	public function profiler()
	{
		$this->CI->output->enable_profiler(false);
	}

	public function initOptions()
	{
		date_default_timezone_set('Europe/Moscow');
		// setlocale(LC_ALL, 'ru_RU.UTF-8');
		$this->CI->form_validation->set_error_delimiters('', '<br>');
	}

	public function sessionStart()
	{
		// запускаем сессию только если пользователь авторизован (ранее была запущена сессия)
		$session_cookie_name = $this->CI->config->item('sess_cookie_name');
		if(!empty($_COOKIE[$session_cookie_name]))
			$this->CI->load->library('session');
	}

	public function checkAuth()
	{
		if(is_cli() == true)
			return;

		$c = $this->CI->router->fetch_class();
		$a = $this->CI->router->fetch_method();
		$d = $this->CI->uri->segment(1);

		$check = $this->CI->Auth->check();
		$user = $this->CI->Auth->user();

		// var_dump($check);
		// var_dump($user); die();

		$ignored = ['auth', 'ajax'];

		// если не авторизован и
		// контроллер не в списке игнора или
		// контроллер авторизации и метод выхода
		// то делаем редирект на авторизацию
		if(!$check && (!in_array($c, $ignored) || ($c === 'auth' && $a === 'logout')))
			redirect('/auth/');

		// если авторизован
		if($check)
		{
			$is_admin = $this->CI->Auth->isAdmin();

			if($this->CI->cache->file->get('user_'. $user['id'] .'_checkAuth') === false)
			{
				$this->CI->UsersHelper->setLastActive($user['id']);
				$this->CI->cache->file->save('user_'. $user['id'] .'_checkAuth', 1, 60);
			}

			// если контроллер авторизации, но не страница из списка
			if($c === 'auth' && !in_array($a, ['logout', 'confirmation']))
			{
				if($is_admin)
					redirect('/admin/');
				else
					redirect('/');
			}

			// если админ но не раздел админки и не страница игнора
			if($is_admin && $d !== 'admin' && !in_array($c, $ignored))
				redirect('/admin/');
			// если не админ но раздел админки
			elseif(!$is_admin && $d === 'admin')
				redirect('/');


			$this->CI->load->library('main/notifications');
		}
	}
}