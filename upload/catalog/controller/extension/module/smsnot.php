<?php
class ControllerExtensionModuleSmsnot extends Controller {

	public function onCheckout($order = 0) {

		if (is_array($order)) {
			$order_id = $order['order_id'];
		} elseif (($order == 0) && (isset($this->session->data['order_id']))) {
			$order_id = $this->session->data['order_id'];
		} elseif ($order) {
			$order_id = $order;
		}

		if (!is_int($order_id)) {
			return;
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('smsnot');
		$sms_log = (isset($setting['smsnot-log']))?$setting['smsnot-log']:0;

		if (isset($setting) && ($setting['smsnot-enabled']) && (!empty($setting['smsnot-apikey']))) {
			$total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $order_info['currency_code']);
			if (isset($setting['smsnot-owner']) && ($setting['smsnot-owner'] == 'on')) {
				$original = array("{StoreName}","{OrderID}", "{Total}", "{LastName}", "{FirstName}", "{Phone}", "{City}", "{Address}", "{Comment}");
				$replace = array($this->config->get('config_name'), $order_id, $total, $order_info['lastname'], $order_info['firstname'], $order_info['telephone'], $order_info['shipping_city'], $order_info['shipping_address_1'], $order_info['comment']);

				$message = str_replace($original, $replace, $setting['smsnot-message-admin']);

				$phones = explode(',', $setting['smsnot-phone']);
				foreach ($phones as $phone) {
					$this->sms_send($setting['smsnot-apikey'], $phone, $message, $setting['smsnot-sender'], $sms_log);
				}
			}
			if (isset($setting['smsnot-new-order']) && ($setting['smsnot-new-order'] == 'on')) {
				$original = array("{StoreName}","{OrderID}", "{LastName}", "{FirstName}", "{Total}");
				$replace = array($this->config->get('config_name'), $order_id, $order_info['lastname'], $order_info['firstname'], $total);

				$message = str_replace($original, $replace, $setting['smsnot-message-customer']);
				$phone = preg_replace("/[^0-9]/", '', $order_info['telephone']);

				if (preg_match('/(\+|)[0-9]{11,12}/', $phone)) {
					$this->sms_send($setting['smsnot-apikey'], $phone, $message, $setting['smsnot-sender'], $sms_log);
				}
			}
		}
	}

	public function onHistoryChange($order = 0) {

		if (is_array($order)) {
			$order_id = $order['order_id'];
		}  elseif (($order == 0) AND (isset($this->session->data['order_id']))) {
			$order_id = $this->session->data['order_id'];
		} elseif (($order == 0) AND (isset($this->request->get['order_id']))) {
			$order_id = $this->request->get['order_id'];
		} elseif (($order == 0) AND (isset($this->request->post['order_id']))) {
			$order_id = $this->request->post['order_id'];
		} elseif ($order != 0) {
			$order_id = $order
		} else {
			return;
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);	
		$this->load->model('setting/setting');
		$this->load->model('extension/module/smsnot');

		$setting = $this->model_setting_setting->getSetting('smsnot');
		$sms_log = (isset($setting['smsnot-log']))?$setting['smsnot-log']:0;

		if(isset($setting) && ($setting['smsnot-enabled']) && (!empty($setting['smsnot-apikey'])) && ((isset($setting['smsnot-order-change'])) && ($setting['smsnot-order-change'] == 'on')) || ((isset($setting['smsnot-new-order'])) && ($setting['smsnot-new-order'] == 'on')) || ((isset($setting['smsnot-owner'])) && ($setting['smsnot-owner'] == 'on'))) {

			if ($this->model_extension_module_smsnot->getHistoryCount($order_id) > 1) {

				$history = $this->model_extension_module_smsnot->getHistory($order_id);
				$total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $order_info['currency_code']);
				$status = (isset($order_info['order_status']))?$order_info['order_status']:"";

				$original = array("{StoreName}","{OrderID}","{Status}", "{LastName}", "{FirstName}", "{Total}", "{Comment}");
				$replace = array($this->config->get('config_name'), $order_id, $status, $order_info['lastname'], $order_info['firstname'], $total, $history['comment']);

				$message = str_replace($original, $replace, $setting['smsnot-message-template']);

				$phone = preg_replace("/[^0-9]/", '', $order_info['telephone']);

				if (isset($setting['smsnot-order-change-notice']) && ($setting['smsnot-order-change-notice'] == 'on') && ($history['notify'])) {
					$ok = 1;
				} elseif ((isset($setting['smsnot-order-change-notice'])) && ($setting['smsnot-order-change-notice'] == 'on') && (!$history['notify'])) {
					$ok = 0;
				} elseif ( (!isset($setting['smsnot-order-change-notice'])) && (isset($setting['smsnot-order-change'])) && ($setting['smsnot-order-change'] == 'on') ) {
					$ok = 1;
				} elseif (!isset($setting['smsnot-order-change'])) {
					$ok = 0;
				} else {
					$ok = 1;
				}
				
				if ((preg_match('/(\+|)[0-9]{11,12}/', $phone)) && ($ok)) {
					$this->sms_send($setting['smsnot-apikey'], $phone, $message, $setting['smsnot-sender'], $sms_log);
				}
			}
		}
	}

	private function sms_send($api_id, $to = 0, $text = 0, $sender = '', $logRec = 0) {
		if (extension_loaded('curl')) {
			$param = array(
			"api_id"	 =>	$api_id,
			"to"		 =>	$to,
			"text"		 =>	$text,
			"from"		 =>	$sender,
			"json"       => 1,
			"partner_id" => 34316);
			$ch = curl_init("http://sms.ru/sms/send");
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt($ch, CURLOPT_TIMEOUT, 30);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
			$result = curl_exec($ch);
			curl_close($ch);
		} else {
			$result = file_get_contents('http://sms.ru/sms/send?api_id='.$api_id.'&to='.$to.'$text='.$text.'&from='.$sender.'&partner_id=34316&json=1');
		}

		$send_data = json_decode($result, true);

		if ($logRec) {
			$this->load->model('module/smsnot');
			$to_log = array();

			if ($send_data['status_code'] == 100) {
				$to_log['error'] = $send_data['sms'][$to]['status_code'];
				$to_log['smsru'] = $send_data['sms'][$to]['sms_id'];
			} else {
				$to_log['error'] = $send_data['status_code'];
				$to_log['smsru'] = 0;
			}

			$to_log['phone'] = $to;
			$to_log['text'] = $text;
			$this->model_extension_module_smsnot->setLogRecord($to_log);
		}

		return true;

		/*$log = new Log('smsnot_log.txt');
		$log->write('login('.$param["api_id"].'), phone('.$param["to"].'), text('.$param["text"].'), sender('.$param["from"].'): catalog');
		$json['error'] = 0;
		return $json;*/
	}

}