<?php
class ControllerModuleSmsnot extends Controller {

	public function onCheckout($order = 0) {
		if (is_array($order)) {
			$order_id = $order['order_id'];
		} elseif ($order == 0) {
			$order_id = $this->session->data['order_id'];
		} else {
			$order_id = $order;
		}
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('smsnot');

		if(isset($setting) && ($setting['smsnot-enabled']) && (!empty($setting['smsnot-apikey']))) {
			if (isset($setting['smsnot-owner']) && ($setting['smsnot-owner'] == 'on') {
				$total = $this->currency->convert($order_info['total'], $order_info['currency_code'], $order_info['currency_code']);
				$original = array("{StoreName}","{OrderID}", "{Total}");
				$replace = array($this->config->get('config_name'), $order_id, $total);

				$message = str_replace($original, $replace, $setting['smsnot-message-admin']);

				$this->sms_send($setting['smsnot-apikey'], $setting['smsnot-phone'], $message, $setting['smsnot-sender']);
			}
			if (isset($setting['smsnot-new-order']) && ($setting['smsnot-new-order'] == 'on') {
				$original = array("{StoreName}","{OrderID}");
				$replace = array($this->config->get('config_name'), $order_id);

				$message = str_replace($original, $replace, $setting['smsnot-message-customer']);
				if (preg_match('/(\+|)[0-9]{11,12}/', $order_info['telephone'])) {
					$this->sms_send($setting['smsnot-apikey'], $order_info['telephone'], $message, $setting['smsnot-sender']);
				}
			}
		}
	}

	public function onHistoryChange($order_id = 0) {
		$order_id = ($order_id != 0)?$order_id:$this->request->get['order_id'];

		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);	
		$this->load->model('setting/setting');
		$this->load->model('module/smsnot');

		$setting = $this->model_setting_setting->getSetting('smsnot');

		if(isset($setting) && ($setting['smsnot-enabled']) && (!empty($setting['smsnot-apikey'])) && ($setting['smsnot-order-change'] == 'on')) {

			if ($order_info['order_status_id'] && $this->model_module_smsnot->getHistoryCount($order_id)>1) {
				$status = (isset($order_info['order_status']))?$order_info['order_status']:"";

				$original = array("{StoreName}","{OrderID}","{Status}");
				$replace = array($this->config->get('config_name'), $order_id, $status);

				$message = str_replace($original, $replace, $setting['smsnot-message-template']);
				
				if (preg_match('/(\+|)[0-9]{11,12}/', $order_info['telephone'])) {
					$response=$this->sms_send($setting['smsnot-apikey'], $order_info['telephone'], $message, $setting['smsnot-sender']);
				}
			}
		}
	}

	private function sms_send($api_id, $to=0, $text=0, $sender='') {
		$param=array(
		"api_id"	 =>	$api_id,
		"to"		 =>	$to,
		"text"		 =>	$text,
		"from"		 =>	$sender,
		"partner_id" => 34316);
		$ch = curl_init("http://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}