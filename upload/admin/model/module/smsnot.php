<?php 
class ModelModuleSmsnot extends Model {

	public function install() {
	// Install Code
	} 

	public function uninstall() {
	// Uninstall Code
	}

	public function getTotalCustomers($data = array()) {
		$sql = "SELECT COUNT(*) AS total FROM " . DB_PREFIX . "customer";
		$implode = array();
		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}
		if (!empty($data['filter_group'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
		}
		if (!empty($data['filter_group_news'])) {
			$implode[] = "customer_group_id = '" . (int)$data['filter_customer_group_id'] . "'";
			$implode[] = "newsletter = '" . (int)$data['filter_newsletter'] . "'";
		}
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

}
?>