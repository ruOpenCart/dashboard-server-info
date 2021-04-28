<?php
class ControllerExtensionDashboardOCNServerInfo extends Controller {
	private $errors = [];
	private $columns;
	private $user_token;
	private $version = '3.0.0.0';
	private $author = 'Hkr';

	public function __construct($registry) {
		parent::__construct($registry);

		for ($i = 3; $i <= 12; $i++) {
			$this->columns[] = $i;
		}
		$this->user_token = $this->session->data['user_token'];
	}

	public function install() {
		$this->load->model('setting/setting');
		$data = [
			'dashboard_ocn_server_info_status' => 0,
			'dashboard_ocn_server_info_width' => 12,
			'dashboard_ocn_server_info_sort_order' => 0
		];
		$this->model_setting_setting->editSetting('dashboard_ocn_server_info', $data);
	}

	public function uninstall() {}

	public function index() {
		$this->document->addScript('view/javascript/ocn_server_info.js');
		$this->document->addStyle('view/stylesheet/ocn_server_info.css');

		$this->load->language('extension/dashboard/ocn_server_info');
		$this->document->setTitle($this->language->get('heading_title'));

		$this->load->model('setting/setting');

		// Edit settings
		if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {

			$this->model_setting_setting->editSetting('dashboard_ocn_server_info', $this->request->post);

			$this->session->data['success'] = $this->language->get('text_success');

			if (isset($this->request->post['apply']) && $this->request->post['apply']) {
				$this->response->redirect($this->getFullLink([ 'dashboard' => 'extension/dashboard/ocn_server_info']));
			}

			$this->response->redirect($this->getFullLink([ 'dashboard' => 'marketplace/extension', 'params' => ['type' => 'dashboard']]));
		}

		// Info
		$data = [
			'data_author' => $this->author,
			'data_version' => $this->version,
		];

		//Errors
		if (isset($this->session->data['success'])) {
			$data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$data['success'] = '';
		}
		if (isset($this->session->data['warning'])) {
			$data['warning'] = $this->session->data['warning'];
			unset($this->session->data['warning']);
		} else {
			$data['warning'] = '';
		}

		// Settings
		$data['dashboard_ocn_server_info_status'] = isset($this->request->post['dashboard_ocn_server_info_status'])
			? $this->request->post['dashboard_ocn_server_info_status']
			: $this->config->get('dashboard_ocn_server_info_status');
		$data['dashboard_ocn_server_info_width'] = isset($this->request->post['dashboard_ocn_server_info_width'])
			? $this->request->post['dashboard_ocn_server_info_width']
			: $this->config->get('dashboard_ocn_server_info_width');
		$data['dashboard_ocn_server_info_sort_order'] = isset($this->request->post['dashboard_ocn_server_info_sort_order'])
			? $this->request->post['dashboard_ocn_server_info_sort_order']
			: $this->config->get('dashboard_ocn_server_info_sort_order');

		// Breadcrumbs
		$data['breadcrumbs'] = $this->getBreadcrumbs('extension/dashboard/ocn_server_info');

		// Urls
		$data['url_action'] = $this->getFullLink(['dashboard' => 'extension/dashboard/ocn_server_info']);
		$data['url_cancel'] = $this->getFullLink(['dashboard' => 'marketplace/extension', 'params' => ['type' => 'dashboard']]);

		$data['columns'] = $this->columns;

		// Templates
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/dashboard/ocn_server_info_form', $data));
	}

	public function phpInfo()
	{
		return phpinfo();
	}

	public function dashboard() {
		$this->load->language('extension/dashboard/ocn_server_info');

		// PHP
		$data['phpinfo_url'] = $this->getFullLink([ 'dashboard' => 'extension/dashboard/ocn_server_info/phpInfo']);
		$data['php_version'] = phpversion();
		$data['php_version_recommend'] = version_compare(phpversion(), '7.3', '<');
		$data['php_register_globals'] = ini_get('register_globals');
		$data['php_magic_quotes_gpc'] = ini_get('magic_quotes_gpc');
		$data['php_session_auto_start'] = ini_get('session.auto_start');
		$data['php_file_uploads'] = ini_get('file_uploads');
		$data['php_display_errors'] = ini_get('display_errors');
		$data['php_xdebug'] = extension_loaded('xdebug');
		$data['php_memory_limit'] = ini_get('memory_limit');
		$data['php_post_max_size'] = ini_get('post_max_size');
		$data['php_upload_max_filesize'] = ini_get('upload_max_filesize');
		$data['php_max_execution_time'] = ini_get('max_execution_time');
		$data['php_max_input_vars'] = ini_get('max_input_vars');
		$data['php_gd'] = extension_loaded('gd');
		$data['php_curl'] = extension_loaded('curl');
		$data['php_openssl'] = function_exists('openssl_encrypt');
		$data['php_zlib'] = extension_loaded('zlib');
		$data['php_zip'] = extension_loaded('zip');
		$data['php_imagick'] = extension_loaded('imagick');
		if (function_exists('ioncube_loader_version')) {
			$data['php_ioncube'] = ioncube_loader_version();
		}

		// DB
		$this->load->model('extension/dashboard/ocn_server_info');
		$dbInfo = $this->model_extension_dashboard_ocn_server_info->getInfo();
		$data['db_version'] = $dbInfo['version'];
		$data['db_max_allowed_packet'] = $dbInfo['max_allowed_packet'];
		$data['db_connect_timeout'] = $dbInfo['connect_timeout'];
		$data['db_max_connections'] = $dbInfo['max_connections'];

		// SERVER
		$data['server_software'] = $_SERVER['SERVER_SOFTWARE'];
		$data['server_os'] = php_uname();


		return $this->load->view('extension/dashboard/ocn_server_info_widget', $data);
	}

	protected function validate() {
		if (!$this->user->hasPermission('modify', 'extension/dashboard/ocn_server_info')) {
			$this->errors['warning'] = $this->language->get('error_permission');
		}

		return !$this->errors;
	}

	private function getBreadcrumbs($extension) {
		return [
			[
				'text' => $this->language->get('text_home'),
				'href' => $this->getFullLink(['dashboard' => 'common/dashboard'])
			],
			[
				'text' => $this->language->get('text_extension'),
				'href' => $this->getFullLink([ 'dashboard' => 'marketplace/extension', 'params' => ['type' => 'dashboard']])
			],
			[
				'text' => $this->language->get('heading_title'),
				'href' => $this->getFullLink(['dashboard' => $extension])
			]
		];
	}

	private function getFullLink($data = []) {
		$url = '';
		if (isset($data['params'])) {
			foreach ($data['params'] as $key => $value) {
				$url .= '&' . $key . '=' . $value;
			}
		}
		$url .= '&user_token=' . $this->user_token;

		return $this->url->link($data['dashboard'], $url, true);
	}
}
