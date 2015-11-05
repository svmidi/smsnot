<?php 
class ModelModuleSmsnot extends Model {

  	public function getSetting($group, $store_id = 0) {
	    $data = array(); 
	    $query = $this->db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
	    foreach ($query->rows as $result) {
	      if (!$result['serialized']) {
	        $data[$result['key']] = $result['value'];
	      } else {
	        $data[$result['key']] = unserialize($result['value']);
	      }
	    } 
	    return $data;
	}
  
  	public function editSetting($group, $data, $store_id = 0) {
	    $this->db->query("DELETE FROM " . DB_PREFIX . "setting WHERE store_id = '" . (int)$store_id . "' AND `group` = '" . $this->db->escape($group) . "'");
	    foreach ($data as $key => $value) {
	      if (!is_array($value)) {
	        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape($value) . "'");
	      } else {
	        $this->db->query("INSERT INTO " . DB_PREFIX . "setting SET store_id = '" . (int)$store_id . "', `group` = '" . $this->db->escape($group) . "', `key` = '" . $this->db->escape($key) . "', `value` = '" . $this->db->escape(serialize($value)) . "', serialized = '1'");
	      }
	    }
	}
	
	public function getNumber($number) {
		$this->load->model('setting/setting');
		$sms_config = $this->model_setting_setting->getSetting('Smsnot', $this->config->get('config_store_id'));
		if ($sms_config['Smsnot']['enabled']) {
			return $Sms_config['Smsnot']['Phone'];	
		}
		else
			return false;
	}
	
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

	public function getCustomer($customer_id) {
		$query = $this->db->query("SELECT DISTINCT * FROM " . DB_PREFIX . "customer WHERE customer_id = '" . (int)$customer_id . "'");
		return $query->row;
	}

}
?>