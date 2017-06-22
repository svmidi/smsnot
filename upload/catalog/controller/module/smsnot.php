<?php
class ControllerModuleSmsnot extends Controller {

	public function onHistoryChange($order = 0) {

		if (is_array($order)) {
			$order_id = $order['order_id'];
		} elseif (($order == 0) AND (isset($this->session->data['order_id']))) {
			$order_id = $this->session->data['order_id'];
		} elseif (($order == 0) AND (isset($this->request->get['order_id']))) {
			$order_id = $this->request->get['order_id'];
		} else {
			$order_id = $order;
		}

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);	
		$this->load->model('setting/setting');
		$this->load->model('module/smsnot');

		$setting = $this->model_setting_setting->getSetting('smsnot');
		$sms_log = (isset($setting['smsnot-log']))?$setting['smsnot-log']:0;

		if (isset($setting) && ($setting['smsnot-enabled']) && (!empty($setting['smsnot-apikey'])) && ((isset($setting['smsnot-order-change'])) && ($setting['smsnot-order-change'] == 'on')) || ((isset($setting['smsnot-new-order'])) && ($setting['smsnot-new-order'] == 'on')) || ((isset($setting['smsnot-owner'])) && ($setting['smsnot-owner'] == 'on'))) {

			if ($order_info['order_status_id'] && $this->model_module_smsnot->getHistoryCount($order_id) > 1) {

				$history = $this->model_module_smsnot->getHistory($order_id);
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
				} elseif (!isset($setting['smsnot-order-change-notice'])) {
					$ok = 0;
				} else {
					$ok = 1;
				}
				
				if ((preg_match('/(\+|)[0-9]{11,12}/', $phone)) && ($ok)) {
					$this->sms_send($setting['smsnot-apikey'], $phone, $message, $setting['smsnot-sender'], $sms_log);
				}
			} elseif($this->request->get['route'] != 'api/order/delete') {
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
	}

	private function sms_send($api_id, $to = 0, $text = 0, $sender = '', $logRec = 0) {
		$param=array(
		"api_id"     => $api_id,
		"to"         => $to,
		"text"       => $text,
		"from"       => $sender,
		"partner_id" => 34316);
		$ch = curl_init("http://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);
		if ($logRec) {
			$this->load->model('module/smsnot');
			$resp = $this->read_response($result);
			$resp['phone'] = $to;
			$resp['text'] = $text;
			$this->model_module_smsnot->setLogRecord($resp);
		}

		return $result;
		/*$log = new Log('smsnot_log.txt');
		$log->write('login('.$param["api_id"].'), phone('.$param["to"].'), text('.$param["text"].'), sender('.$param["from"].'): catalog');
		$json['error'] = 0;
		return $json;*/
	}

	private function read_response($response){
		$this->load->language('module/smsnot');
		$ex = explode("\n", $response);
		$result = array();
		if ($ex[0] == 100) {
			$balance=explode("=", $ex[2]);
			$result['error'] = 100;
			$result['smsru'] = $ex[1];
			$result['balance'] = $balance[1];
			$result['text'] = $this->language->get('text_send_success');
		} else {
			$result['error'] = $ex[0];
			$result['smsru'] = '';
			$result['text'] = $this->language->get('text_send_error').' ('.$this->error_array[$ex[0]].')';
		}
		return $result;
	}
}
