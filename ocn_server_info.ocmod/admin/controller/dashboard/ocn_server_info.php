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
		$this->load->model('setting/setting');
		$data = [
			'dashboard_ocn_server_info_status' => 0,
			'dashboard_ocn_server_info_width' => 12,
			'dashboard_ocn_server_info_sort_order' => 0,
			'dashboard_ocn_server_info_is_expanded' => 1,
			'dashboard_ocn_server_info_is_space_progress' => 0,
		];
		$this->model_setting_setting->editSetting('dashboard_ocn_server_info', $data);
	}
	
	public function uninstall(): void
	{
	}
	
	public function index(): void
	{
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		
		$this->document->setTitle($this->language->get('heading_title'));
		
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
		
		// Info
//		$data['data_author'] = $this->author;
//		$data['data_version'] = $this->version;
		$data['columns'] = [12];
		
		// Config
		$data['dashboard_ocn_server_info_status'] = $this->config->get('dashboard_ocn_server_info_status');
		$data['dashboard_ocn_server_info_width'] = $this->config->get('dashboard_ocn_server_info_width');
		$data['dashboard_ocn_server_info_sort_order'] = $this->config->get('dashboard_ocn_server_info_sort_order');
		$data['dashboard_ocn_server_info_is_expanded'] = $this->config->get('dashboard_ocn_server_info_is_expanded');
		$data['dashboard_ocn_server_info_is_space_progress'] = $this->config->get('dashboard_ocn_server_info_is_space_progress');
		
		// Template
		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');
		
		$this->response->setOutput($this->load->view('extension/ocn_server_info/dashboard/ocn_server_info_form', $data));
	}
	
	public function save(): void
	{
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		
		$json = [];
		
		if (!$this->user->hasPermission('modify', 'extension/ocn_server_info/dashboard/ocn_server_info')) {
			$json['error'] = $this->language->get('error_permission');
		}
		
		if (!$json) {
			$this->load->model('setting/setting');
			
			$this->model_setting_setting->editSetting('dashboard_ocn_server_info', $this->request->post);
			
			$json['success'] = $this->language->get('text_success');
		}
		
		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}
	
	/**
	 * @return string
	 */
	public function dashboard(): string
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info');
		
		$data['user_token'] = $this->user_token;
		
		// Config
		$data['dashboard_ocn_server_info_is_expanded'] = $this->config->get('dashboard_ocn_server_info_is_expanded');
		$data['dashboard_ocn_server_info_is_space_progress'] = $this->config->get('dashboard_ocn_server_info_is_space_progress');
		
		// Urls
		$data['url_edit'] = $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info', 'user_token=' . $this->user_token);
		
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
		$data['modal_df'] = $this->modalDf();
		$data['space'] = $this->space($this->config->get('dashboard_ocn_server_info_is_space_progress'));
		$data['versions'] = $this->versions($data);
		
		return $this->load->view('extension/ocn_server_info/dashboard/ocn_server_info_info', $data);
	}
	
	public function size()
	{
		$this->response->setOutput(
			$this->load->view(
				'extension/ocn_server_info/dashboard/ocn_server_info_df',
				[
					'df' => shell_exec('df -h'),
				]
			)
		);
	}
	
	public function info()
	{
		return phpinfo();
	}
	
	private function versions(array $data)
	{
		return $this->load->view(
			'extension/ocn_server_info/dashboard/ocn_server_info_versions',
			$data
		);
	}
	
	private function modalDf()
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info_modal_df');
		
		// Token
		$data['user_token'] = $this->user_token;
		
		// Urls
		$data['url_size'] = $this->url->link('extension/ocn_server_info/dashboard/ocn_server_info.size', 'user_token=' . $this->user_token);
		
		return $this->load->view('extension/ocn_server_info/dashboard/ocn_server_info_modal_df', $data);
	}
	
	private function space(bool $isProgress)
	{
		// Language
		$this->load->language('extension/ocn_server_info/dashboard/ocn_server_info_space_progress');
		
		// Data
		$disk_free_space = disk_free_space(".");
		$disk_total_space = disk_total_space(".");
		$data['disk_free_space'] = $this->bytesToStr($disk_free_space);
		$data['disk_total_space'] = $this->bytesToStr($disk_total_space);
		$data['disk_free_space_percent'] = round($disk_free_space * 100 / $disk_total_space, 2);
		$data['disk_total_space_percent'] = round(100 - $data['disk_free_space_percent'], 2);
		$data['disk_free_space_color'] = $data['disk_free_space_percent'] > 50
			? 'bg-success'
			: ($data['disk_free_space_percent'] > 25
				? 'bg-warning'
				: 'bg-danger'
			);
		$data['disk_space'] = sprintf($this->language->get('text_space'), $data['disk_free_space'], $data['disk_total_space']);
		$data['is_progress'] = $isProgress;
		
		return $this->load->view('extension/ocn_server_info/dashboard/ocn_server_info_space', $data);
	}
	
	private function bytesToStr(int $bytes)
	{
		$prefix = ['B', 'KB', 'MB', 'GB', 'TB', 'EB', 'ZB', 'YB'];
		$base = 1024;
		$class = min((int)log($bytes, $base), count($prefix) - 1);
		return sprintf('%1.2f', $bytes / pow($base, $class)) . $prefix[$class];
	}
}
