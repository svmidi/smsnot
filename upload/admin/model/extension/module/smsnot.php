<?php 
class ModelExtensionModuleSmsnot extends Model {
	
	public function install() {
		$sql = "CREATE TABLE IF NOT EXISTS `" . DB_PREFIX . "smsnot` (`id` int(11) NOT NULL AUTO_INCREMENT, `text` text NOT NULL, `date` datetime NOT NULL, `sms_id` tinytext NOT NULL, `status` int(11) NOT NULL, `phone` tinytext NOT NULL, PRIMARY KEY (`id`)) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=32;";
		$this->db->query($sql);
	}

	public function uninstall() {
		$sql = "DROP TABLE " . DB_PREFIX . "smsnot;";
		$this->db->query($sql);
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
			if (isset($filter['order']) && ($filter['order'] == 'ASC')) {
				$sql .= " ASC";
			} else {
				$sql .= " DESC";
			}
		} else {
			$sql .= " ORDER BY id DESC";
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

	public function setLogRecord($smsru = array()) {

		$sql = "INSERT INTO  `".DB_PREFIX."smsnot` (`id`,`date`,`status`,`phone`,`sms_id`, `text`) 
		VALUES (NULL, NOW(), '".$smsru['error']."', '".$smsru['phone']."', '".$smsru['smsru']."', '".$smsru['text']."')";

		$query = $this->db->query($sql);
		return true;
	}
}
?>