<?php
class ModelExtensionDashboardOCNServerInfo extends Model {
	public function index($data = []) {}

	public function getInfo() {
		$rows = $this->db->query('show variables')->rows;
		$data = [];
		foreach ($rows as $row) {
			$data[$row['Variable_name']] = $row['Value'];
		}

		return $data;
	}
}
