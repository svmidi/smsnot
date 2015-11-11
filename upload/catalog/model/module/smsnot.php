<?php
class ModelModuleSmsnot extends Model {

	public function getHistoryCount($order_id) {
		$data = array(); 
		$query = $this->db->query("SELECT COUNT(`order_id`) AS `count` FROM `" . DB_PREFIX ."order_history` WHERE `order_id` = ". $order_id.";");
		return $query->row['count'];
	}
}
?>