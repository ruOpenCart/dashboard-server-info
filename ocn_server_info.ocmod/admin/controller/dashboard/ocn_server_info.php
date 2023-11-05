<?php

namespace Opencart\Admin\Controller\Extension\OcnServerInfo\Dashboard;

class OcnServerInfo extends \Opencart\System\Engine\Controller
{
	private $user_token;
	
	public function __construct($registry)
	{
		parent::__construct($registry);
		
		$this->user_token = $this->session->data['user_token'];
	}
	
	public function install(): void
	{
		// Data
		$data = [
			'dashboard_ocn_server_info_status' => 0,
			'dashboard_ocn_server_info_width' => 12,
			'dashboard_ocn_server_info_sort_order' => 0,
			'dashboard_ocn_server_info_is_expanded' => 0,
			'dashboard_ocn_server_info_free_space_status' => 0,
			'dashboard_ocn_server_info_free_space_is_progressbar' => 0,
			'dashboard_ocn_server_info_filesystem_status' => 0,
			'dashboard_ocn_server_info_filesystem_is_inodes' => 0,
			'dashboard_ocn_server_info_filesystem_is_total' => 0,
			'dashboard_ocn_server_info_size_opencart_status' => 0,
			'dashboard_ocn_server_info_size_opencart_is_storage' => 0,
			'dashboard_ocn_server_info_size_opencart_is_logs' => 0,
			'dashboard_ocn_server_info_size_opencart_is_images' => 0,
		];
		
		// Settings
		$this->load->model('setting/setting');
		$this->model_setting_setting->editSetting('dashboard_ocn_server_info', $data);
	}
	
	public function uninstall(): void
	{
	}
	
	public function index(): void
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		$this->document->setTitle($this->language->get('heading_title'));
		
		// Data
		$data = [
			'columns' => [12],
			'dashboard_ocn_server_info_status' => $this->config->get('dashboard_ocn_server_info_status'),
			'dashboard_ocn_server_info_width' => $this->config->get('dashboard_ocn_server_info_width'),
			'dashboard_ocn_server_info_sort_order' => $this->config->get('dashboard_ocn_server_info_sort_order'),
			'dashboard_ocn_server_info_is_expanded' => $this->config->get('dashboard_ocn_server_info_is_expanded'),
			'dashboard_ocn_server_info_free_space_status' => $this->config->get('dashboard_ocn_server_info_free_space_status'),
			'dashboard_ocn_server_info_free_space_is_progressbar' => $this->config->get('dashboard_ocn_server_info_free_space_is_progressbar'),
			'dashboard_ocn_server_info_filesystem_status' => $this->config->get('dashboard_ocn_server_info_filesystem_status'),
			'dashboard_ocn_server_info_filesystem_is_inodes' => $this->config->get('dashboard_ocn_server_info_filesystem_is_inodes'),
			'dashboard_ocn_server_info_filesystem_is_total' => $this->config->get('dashboard_ocn_server_info_filesystem_is_total'),
			'dashboard_ocn_server_info_size_opencart_status' => $this->config->get('dashboard_ocn_server_info_size_opencart_status'),
			'dashboard_ocn_server_info_size_opencart_is_storage' => $this->config->get('dashboard_ocn_server_info_size_opencart_is_storage'),
			'dashboard_ocn_server_info_size_opencart_is_logs' => $this->config->get('dashboard_ocn_server_info_size_opencart_is_logs'),
			'dashboard_ocn_server_info_size_opencart_is_images' => $this->config->get('dashboard_ocn_server_info_size_opencart_is_images'),
		];
		
		// Breadcrumbs
		$data['breadcrumbs'] = [
			[
				'text' => $this->language->get('text_home'),
				'href' => $this->url->link('common/dashboard', 'user_token=' . $this->user_token),
			],
			[
				'text' => $this->language->get('text_extension'),
				'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->user_token . '&type=dashboard'),
			],
			[
				'text' => $this->language->get('heading_title'),
				'href' => $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info', 'user_token=' . $this->user_token),
			],
		];
		
		// Buttons
		$data['save'] = $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.save', 'user_token=' . $this->user_token);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->user_token . '&type=dashboard');
		
		// Templates
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		// Response
		$this->response->setOutput($this->load->view('extension/ocn_server_info/dashboard/ocn_server_info_form', $data));
	}
	
	public function save(): void
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		
		// Data
		$json = [];
		
		// Permissions
		if (!$this->user->hasPermission('modify', 'extension/ocn_server_info/dashboard/ocn_server_info')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		// Settings
		if (!$json) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('dashboard_ocn_server_info', $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		}
		
		// Response
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	public function dashboard(): string
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		
		// Config
		$data['dashboard_ocn_server_info_is_expanded'] = $this->config->get('dashboard_ocn_server_info_is_expanded');
		$data['free_space_status'] = $this->config->get('dashboard_ocn_server_info_free_space_status');
		$data['size_opencart_status'] = $this->config->get('dashboard_ocn_server_info_size_opencart_status');
		$data['dashboard_ocn_server_info_free_space_is_progressbar'] = $this->config->get('dashboard_ocn_server_info_free_space_is_progressbar');
		
		// Urls
		$data['url_edit'] = $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info', 'user_token=' . $this->user_token);
		$data['url_size_opencart'] = $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.opencart', 'user_token=' . $this->user_token);
		$data['url_size_progressbar'] = $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.progressbar', 'user_token=' . $this->user_token);
		
		// PHP
		$data['phpinfo_url'] = $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.info', 'user_token=' . $this->user_token);
		$data['php_version'] = phpversion();
		$data['php_version_recommend'] = version_compare(phpversion(), '8.0', '<');
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
		$data['php_ioncube'] = function_exists('ioncube_loader_version') ? ioncube_loader_version() : null;
		
		// DB
		$this->load->model('extension/ocn_server_info/dashboard/ocn_server_info');
		$dbInfo = $this->model_extension_ocn_server_info_dashboard_ocn_server_info->getInfo();
		$data['db_version'] = $dbInfo['version'];
		$data['db_version_comment'] = $dbInfo['version_comment'];
		$data['db_max_allowed_packet'] = $dbInfo['max_allowed_packet'];
		$data['db_connect_timeout'] = $dbInfo['connect_timeout'];
		$data['db_max_connections'] = $dbInfo['max_connections'];
		
		// Server
		$data['server_software'] = $_SERVER['SERVER_SOFTWARE'];
		$data['server_os'] = php_uname();
		
		// Parts
		$data['modal_system'] = $this->modalFileSystem();
		$data['template_loading'] = $this->load->view(
			'extension/ocn_server_info/dashboard/ocn_server_info_template_loading'
		);;
		$data['versions'] = $this->versions($data);
		$data['size_opencart'] = $this->load->view(
			'extension/ocn_server_info/dashboard/ocn_server_info_size_opencart',
			$data
		);
		$data['size_progressbar'] = $this->load->view(
			'extension/ocn_server_info/dashboard/ocn_server_info_size_progressbar',
			$data
		);
		
		// View
		return $this->load->view('extension/ocn_server_info/dashboard/ocn_server_info_dashboard', $data);
	}
	
	public function info()
	{
		// Html
		return phpinfo();
	}
	
	public function system()
	{
		// Helpers
		$this->load->helper('extension/ocn_server_info/size');
		
		// Command
		$command = 'df -h';
		if ($this->config->get('dashboard_ocn_server_info_filesystem_is_inodes')) {
			$command .= 'i';
		}
		if ($this->config->get('dashboard_ocn_server_info_filesystem_is_total')) {
			$command .= ' --total';
		}
		
		// Response
		$this->response->setOutput(
			$this->load->view(
				'extension/ocn_server_info/dashboard/ocn_server_info_size_system',
				[
					'url_size_system' => $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.system', 'user_token=' . $this->user_token),
					'df' => shell_exec($command),
				]
			)
		);
	}
	
	public function opencart()
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		
		// Helpers
		$this->load->helper('extension/ocn_server_info/size');
		
		// Data
		$data = [
			'url_size_opencart' => $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.opencart', 'user_token=' . $this->user_token),
			'size_opencart' => du_dir(DIR_OPENCART),
		];
		if ($this->config->get('dashboard_ocn_server_info_size_opencart_is_storage')) {
			$data['is_storage'] = $this->config->get('dashboard_ocn_server_info_size_opencart_is_storage');
			$data['size_storage'] = du_dir(DIR_STORAGE);
		}
		if ($this->config->get('dashboard_ocn_server_info_size_opencart_is_logs')) {
			$data['is_logs'] = $this->config->get('dashboard_ocn_server_info_size_opencart_is_logs');
			$data['size_logs'] = du_dir(DIR_LOGS);
		}
		if ($this->config->get('dashboard_ocn_server_info_size_opencart_is_images')) {
			$data['is_images'] = $this->config->get('dashboard_ocn_server_info_size_opencart_is_images');
			$data['size_image'] = du_dir(DIR_IMAGE);
			$data['size_image_cache'] = du_dir(DIR_IMAGE . 'cache/');
		}
		
		// Response
		$this->response->setOutput(
			$this->load->view(
				'extension/ocn_server_info/dashboard/ocn_server_info_size_opencart',
				$data
			)
		);
	}
	
	public function progressbar()
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		
		// Helpers
		$this->load->helper('extension/ocn_server_info/size');
		
		// Urls
		$data['url_size_progressbar'] = $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.progressbar', 'user_token=' . $this->user_token);
		
		// Utils
		$disk_free_space = disk_free_space(".");
		$disk_total_space = disk_total_space(".");
		
		// Data
		$data['disk_free_space'] = bytes_to_str($disk_free_space);
		$data['disk_total_space'] = bytes_to_str($disk_total_space);
		$data['disk_free_space_percent'] = round($disk_free_space * 100 / $disk_total_space, 2);
		$data['disk_total_space_percent'] = round(100 - $data['disk_free_space_percent'], 2);
		$data['disk_free_space_color'] = $data['disk_free_space_percent'] > 50
			? 'bg-success'
			: ($data['disk_free_space_percent'] > 25
				? 'bg-warning'
				: 'bg-danger'
			);
		$data['disk_space'] = sprintf($this->language->get('text_free_space'), $data['disk_free_space'], $data['disk_total_space']);
		$data['is_progress'] = $this->config->get('dashboard_ocn_server_info_free_space_is_progressbar');
		
		// Response
		$this->response->setOutput(
			$this->load->view(
				'extension/ocn_server_info/dashboard/ocn_server_info_size_progressbar',
				$data
			)
		);
	}
	
	private function versions(array $data)
	{
		// View
		return $this->load->view(
			'extension/ocn_server_info/dashboard/ocn_server_info_versions',
			$data
		);
	}
	
	private function modalFileSystem()
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		
		// Data
		$data = [
			'user_token' => $this->user_token,
			'url_size_system' => $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.system', 'user_token=' . $this->user_token),
		];
		
		// View
		return $this->load->view('extension/ocn_server_info/dashboard/ocn_server_info_modal_system', $data);
	}
}
