<?php
namespace Opencart\Admin\Model\Extension\OcnServerInfo\Dashboard;
class ServerInfo extends \Opencart\System\Engine\Model {
	public function install(): void {}

	public function uninstall(): void {}
	
	public function getInfo() {
		$rows = $this->db->query('show variables')->rows;
		$data = [];
		foreach ($rows as $row) {
			$data[$row['Variable_name']] = $row['Value'];
		}
		
		return $data;
	}
}
