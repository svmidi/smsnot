<?php
class ModelExtensionModuleSmsnot extends Model {

	public function getHistoryCount($order_id) {
		$data = array(); 
		$query = $this->db->query("SELECT COUNT(`order_id`) AS `count` FROM `" . DB_PREFIX ."order_history` WHERE `order_id` = ". $order_id.";");
		return $query->row['count'];
	}

	public function setNoticeStatus($send_id = 0, $status) {
		$query = $this->db->query("UPDATE `" . DB_PREFIX . "smsnot` 
			SET `status` = '".(int)$status."' 
			WHERE `" . DB_PREFIX . "smsnot`.`sms_id` = '" . $send_id . "';");

		return true;
	}

	public function setLogRecord($smsru = array()) {
		$sql = "INSERT INTO  `".DB_PREFIX."smsnot` (`id`,`date`,`status`,`phone`,`sms_id`, `text`) 
		VALUES (NULL, NOW(), '".$smsru['error']."', '".$smsru['phone']."', '".$smsru['smsru']."', '".$smsru['text']."')";

		$query = $this->db->query($sql);
		return true;
	}

	public function getHistory($order_id) {
		$query = $this->db->query("SELECT `comment`, `notify` FROM `" . DB_PREFIX ."order_history` WHERE `order_id` = ". $order_id." ORDER BY `order_history_id` DESC LIMIT 1;");
		$data = array('comment' => $query->row['comment'], 'notify' => $query->row['notify']); 
		return $data;
	}
}
?>