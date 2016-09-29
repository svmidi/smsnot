<?php 
class ModelModuleSmsnot extends Model {
	
	public function install() {
	// Install Code
	}

	public function uninstall() {
	// Uninstall Code
	}

	public function getTotalCustomers($data = array()) {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "customer`";
		$implode = array();
		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "`newsletter` = '" . (int)$data['filter_newsletter'] . "'";
		}
		if (isset($data['filter_group'])) {
			$implode[] = "`customer_group_id` = '" . (int)$data['filter_group'] . "'";
		}
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$query = $this->db->query($sql);
		return $query->row['total'];
	}

	public function getPhones($data = array()) {
		$sql = "SELECT `firstname`, `lastname`, `telephone` FROM `" . DB_PREFIX . "customer`";
		$implode = array();
		if (isset($data['filter_newsletter']) && !is_null($data['filter_newsletter'])) {
			$implode[] = "`newsletter` = '" . (int)$data['filter_newsletter'] . "'";
		}
		if (isset($data['filter_group'])) {
			$implode[] = "`customer_group_id` = '" . (int)$data['filter_group'] . "'";
		}
		if ($implode) {
			$sql .= " WHERE " . implode(" AND ", $implode);
		}
		$query = $this->db->query($sql);
		return $query->rows;
	}

	public function getLogRecords($filter = array()) {
		$sql = "SELECT * FROM `" . DB_PREFIX . "smsnot` WHERE 1=1";
		if (!empty($filter['filter_text'])) {
			$sql .= " AND text LIKE '" . $this->db->escape($filter['filter_text']) . "%'";
		}

		if (!empty($filter['filter_phone'])) {
			$sql .= " AND phone LIKE '" . (int)$filter['filter_phone'] . "%'";
		}

		if (!empty($filter['filter_status'])) {
			$sql .= " AND status = '" . (int)$filter['filter_status'] . "'";
		}

		if ((isset($filter['filter_date_start'])) && (isset($filter['filter_date_stop']))) {
			$sql .= " AND (DATE(`date`) BETWEEN '".$filter['filter_date_start']."' AND '".$filter['filter_date_stop']."')";
		}

		$sort_data = array(
			'id',
			'status'
		);

		if (isset($filter['sort']) && in_array($filter['sort'], $sort_data)) {
			$sql .= " ORDER BY " . $filter['sort'];
			if (isset($filter['order']) && ($filter['order'] == 'DESC')) {
				$sql .= " DESC";
			} else {
				$sql .= " ASC";
			}
		}


		if (isset($filter['start']) || isset($filter['limit'])) {
			if ($filter['start'] < 0) {
				$filter['start'] = 0;
			}

			if ($filter['limit'] < 1) {
				$filter['limit'] = 20;
			}

			$sql .= " LIMIT " . (int)$filter['start'] . "," . (int)$filter['limit'];
		}
		$query = $this->db->query($sql);
echo $sql;
		return $query->rows;
	}

	public function getLogRecordsTotal($filter = array()) {
		$sql = "SELECT COUNT(*) AS `total` FROM `" . DB_PREFIX . "smsnot` WHERE 1=1";
		if (!empty($filter['filter_text'])) {
			$sql .= " AND text LIKE '" . $this->db->escape($filter['filter_text']) . "%'";
		}

		if (!empty($filter['filter_phone'])) {
			$sql .= " AND phone LIKE '" . (int)$filter['filter_phone'] . "%'";
		}

		if ((isset($filter['filter_date_start'])) && (isset($filter['filter_date_stop']))) {
			$sql .= " AND (DATE(`date`) BETWEEN '".$filter['filter_date_start']."' AND '".$filter['filter_date_stop']."')";
		}

		$query = $this->db->query($sql);
		return $query->row['total'];
	}
}
?>