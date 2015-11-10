<?php
class ControllerModuleSmsnot extends Controller {

	public function onCheckout($order_id) {
		$this->load->model('checkout/order');
		$order_info = $this->model_checkout_order->getOrder($order_id);

		$this->load->model('setting/setting');
		$setting = $this->model_setting_setting->getSetting('smsnot');

		if(isset($setting) && ($setting['smsnot-enabled']) && (!empty($setting['smsnot-apikey'])) && ($setting['smsnot-owner'] == 'on')) {
			if (!$order_info['order_status_id']) {
					$message = 'New order '.$order_id.', in store '.$this->config->get('config_name').'. Total: '.$order_info['total'];
					$response=$this->sms_send($setting['smsnot-apikey'], $setting['smsnot-phone'], $message, $setting['sender']);
			}
		}
    }

    private function sms_send($api_id, $to=0, $text=0, $sender='') {
		$param=array(
		"api_id"	=>	$api_id,
		"to"		=>	$to,
		"text"		=>	$text,
		"from"		=>	$sender);
		$ch = curl_init("http://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}

}