<?php
class ControllerModuleSmsnot extends Controller {
	private $data = array();

	private $error_array = array(
		100 =>"Сообщение принято к отправке.",
		200 =>"Неправильный api_id",
		201 =>"Не хватает средств на лицевом счету",
		202 =>"Неправильно указан получатель",
		203 =>"Нет текста сообщения",
		204 =>"Имя отправителя не согласовано с администрацией",
		205 =>"Сообщение слишком длинное (превышает 8 СМС)",
		206 =>"Будет превышен или уже превышен дневной лимит на отправку сообщений",
		207 =>"На этот номер (или один из номеров) нельзя отправлять сообщения, либо указано более 100 номеров в списке получателей",
		208 =>"Параметр time указан неправильно",
		209 =>"Вы добавили этот номер (или один из номеров) в стоп-лист",
		210 =>"Используется GET, где необходимо использовать POST",
		211 =>"Метод не найден",
		212 =>"Текст сообщения необходимо передать в кодировке UTF-8 (вы передали в другой кодировке)",
		220 =>"Сервис временно недоступен, попробуйте чуть позже.",
		230 =>"Сообщение не принято к отправке, так как на один номер в день нельзя отправлять более 60 сообщений.",
		300 =>"Неправильный token (возможно истек срок действия, либо ваш IP изменился)",
		301 =>"Неправильный пароль, либо пользователь не найден",
		302 =>"Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)");

	public function index() {

		$this->load->language('module/smsnot');
		$this->load->model('module/smsnot');
		$this->load->model('localisation/language');
		$this->load->model('setting/setting');

		$this->document->setTitle($this->language->get('heading_title'));

		if(!isset($this->request->get['store_id'])) {
			$this->request->get['store_id'] = 0; 
		}

		if (($this->request->server['REQUEST_METHOD'] == 'POST')) {
			if (!$this->user->hasPermission('modify', 'module/smsnot')) {
				$this->error['warning'] = $this->language->get('error_permission');
				$this->session->data['error'] = 'You do not have permissions to edit this module!';
			} else {
				$this->model_setting_setting->editSetting('smsnot', $this->request->post, 0);
				$this->session->data['success'] = $this->language->get('text_success');
			}
			$this->response->redirect(HTTP_SERVER.'index.php?route=module/smsnot&store_id='.$this->request->get['store_id'] . '&token=' . $this->session->data['token']);
		}

		if (isset($this->session->data['success'])) {
			$this->data['success'] = $this->session->data['success'];
			unset($this->session->data['success']);
		} else {
			$this->data['success'] = '';
		}

		if (isset($this->error['warning'])) {
			$this->data['error_warning'] = $this->error['warning'];
		} else {
			$this->data['error_warning'] = '';
		}

		$this->load->model('localisation/order_status');
		$this->data['order_statuses'] = $this->model_localisation_order_status->getOrderStatuses();

		$this->data['breadcrumbs']   = array();
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/home', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('text_module'),
			'href' => $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL'),
		);
		$this->data['breadcrumbs'][] = array(
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('module/smsnot', 'token=' . $this->session->data['token'], 'SSL'),
		);
		
		$this->data['heading_title'] = $this->language->get('heading_title');
		$this->data['button_save'] = $this->language->get('button_save');
		$this->data['button_cancel'] = $this->language->get('button_cancel');
		$this->data['button_test'] = $this->language->get('button_test');
		$this->data['button_send'] = $this->language->get('button_send');
		$this->data['button_refer'] = $this->language->get('button_refer');
		$this->data['button_orderid'] = $this->language->get('button_orderid');
		$this->data['button_storename'] = $this->language->get('button_storename');
		$this->data['button_name'] = $this->language->get('button_name');
		$this->data['button_lastname'] = $this->language->get('button_lastname');
		$this->data['button_status'] = $this->language->get('button_status');
		$this->data['button_total'] = $this->language->get('button_total');

		$this->data['tab_sending'] = $this->language->get('tab_sending');
		$this->data['tab_notice'] = $this->language->get('tab_notice');
		$this->data['tab_gate'] = $this->language->get('tab_gate');

		$this->data['entry_to'] = $this->language->get('entry_to');
		$this->data['entry_sender'] = $this->language->get('entry_sender');
		$this->data['entry_message'] = $this->language->get('entry_message');
		$this->data['entry_enabled'] = $this->language->get('entry_enabled');
		$this->data['entry_message_template'] = $this->language->get('entry_message_template');
		$this->data['entry_message_customer'] = $this->language->get('entry_message_customer');
		$this->data['entry_message_admin'] = $this->language->get('entry_message_admin');
		$this->data['entry_api_key'] = $this->language->get('entry_api_key');
		$this->data['entry_phone'] = $this->language->get('entry_phone');
		$this->data['entry_balance'] = $this->language->get('entry_balance');
		$this->data['entry_characters'] = $this->language->get('entry_characters');

		$this->data['text_description'] = $this->language->get('text_description');
		$this->data['text_newsletter'] = $this->language->get('text_newsletter');
		$this->data['text_all'] = $this->language->get('text_all');
		$this->data['text_all_group'] = $this->language->get('text_all_group');
		$this->data['text_newsletter_group'] = $this->language->get('text_newsletter_group');
		$this->data['text_new_order'] = $this->language->get('text_new_order');
		$this->data['text_order_change'] = $this->language->get('text_order_change');
		$this->data['text_owner'] = $this->language->get('text_owner');
		$this->data['text_enable'] = $this->language->get('text_enable');
		$this->data['text_disable'] = $this->language->get('text_disable');
		$this->data['text_module'] = $this->language->get('text_module');
		$this->data['text_money_add'] = $this->language->get('text_money_add');
		$this->data['text_refresh'] = $this->language->get('text_refresh');

		$this->data['help_message_template'] = $this->language->get('help_message_template');
		$this->data['help_message_customer'] = $this->language->get('help_message_customer');
		$this->data['help_message_admin'] = $this->language->get('help_message_admin');
		$this->data['help_message'] = $this->language->get('help_message');


		$this->data['error_warning']  = '';
		$this->data['action']         = $this->url->link('module/smsnot', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['cancel']         = $this->url->link('extension/module', 'token=' . $this->session->data['token'], 'SSL');
		$this->data['data']           = $this->model_setting_setting->getSetting('smsnot');
		$this->data['balance']        = 0;
		$this->data['token']          = $this->session->data['token'];

		if ($this->data['data']['smsnot-apikey']!='') {
			$balance = $this->get_balance($this->data['data']['smsnot-apikey']);
			$this->data['balance'] = (in_array('balance', $balance))?$balance['balance']:'-';
		}
		
		if(strcmp(VERSION,"2.1.0.1") < 0) {
			$this->load->model('sale/customer_group');
			$this->data['customer_groups'] = $this->model_sale_customer_group->getCustomerGroups(0);
		} else {
			$this->load->model('customer/customer_group');
			$this->data['customer_groups'] = $this->model_customer_customer_group->getCustomerGroups(0);
		}

		$this->data['header']		= $this->load->controller('common/header');
		$this->data['column_left']	= $this->load->controller('common/column_left');
		$this->data['footer']		= $this->load->controller('common/footer');
		$this->response->setOutput($this->load->view('module/smsnot.tpl', $this->data));
	}

	public function install() {
		$this->load->model('module/smsnot');
		$this->model_module_smsnot->install();
		$this->load->model('extension/event');
		$this->model_extension_event->addEvent('smsnot', 'pre.order.history.add', 'module/smsnot/onCheckout');
		$this->model_extension_event->addEvent('smsnot', 'post.order.history.add', 'module/smsnot/onHistoryChange');
		$this->load->model('setting/setting');
		$basic=array(
		'smsnot-sender'=>'',
		'smsnot-phone'=>'',
		'smsnot-apikey'=>'',
		'smsnot-message-template'=>'Order №{OrderID} in {StoreName}, changed status to {Status}',
		'smsnot-message-customer'=>'New order №{OrderID} in {StoreName}',
		'smsnot-message-admin'=>'New order #{OrderID} at the store "{StoreName}". Total {Total}',
		'smsnot-order-change'=>0,
		'smsnot-new-order'=>0,
		'smsnot-owner'=>0,
		'smsnot-enabled'=>0);
		$this->model_setting_setting->editSetting('smsnot', $basic, 0);
	}

	public function uninstall() {
		$this->load->model('setting/setting');
		
		$this->load->model('setting/store');
		$this->model_setting_setting->deleteSetting('smsnot_module',0);
		$stores=$this->model_setting_store->getStores();
		foreach ($stores as $store) {
			$this->model_setting_setting->deleteSetting('smsnot_module', $store['store_id']);
		}
		$this->load->model('module/smsnot');
		$this->model_module_smsnot->uninstall();
		$this->load->model('extension/event');
		$this->model_extension_event->deleteEvent('smsnot');
	}

	public function send() {
		$json = array();
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'module/smsnot')) {
				$json['error'] = 403;
				$json['text'] = 'You do not have permission to perform this action!';
			}
			if (!$this->request->post['message']) {
				$json['error'] = 404;
				$json['message'] = 'The message field should not be empty!';
			}
			if (!$json) {
				$response = $this->sms_send($this->request->post['api'],$this->request->post['to'],$this->request->post['message'],$this->request->post['sender']);
				$json = $response;
			}
		}
		$this->response->setOutput(json_encode($json));
	}

	public function balance(){
		if (!$this->user->hasPermission('modify', 'module/smsnot')) {
			$json['error'] = 403;
			$json['text'] = 'You do not have permission to perform this action!';
		} else {
			$json['error'] = 12;
			$api_key=(isset($this->request->post['api']))?$this->request->post['api']:$api_key;
			if ($api_key == '') {
				$this->load->model('setting/setting');
				$settings = $this->model_setting_setting->getSetting('smsnot');
				$api_key = $settings['smsnot-apikey'];
			}
			if ($api_key != '') {
				$json=$this->get_balance($api_key);
			}
			$this->response->setOutput(json_encode($json));
		}
	}

	public function massend() {
		$this->load->model('module/smsnot');
		$this->load->model('setting/setting');
		$settings = $this->model_setting_setting->getSetting('smsnot');
		$json = array();
		if ($this->request->server['REQUEST_METHOD'] == 'POST') {
			if (!$this->user->hasPermission('modify', 'module/smsnot')) {
				$json['error'] = 403;
				$json['text'] = 'You do not have permission to perform this action!';
			}
			if (!$this->request->post['message']) {
				$json['error'] = 404;
				$json['message'] = 'The message field should not be empty!';
			}
			if (!$json) {
				$filter = array();
				if (($this->request->post['to'] > 10) AND ($this->request->post['to'] < 100)) {
					$group = $this->request->post['to'] % 10;
					$type = intval($this->request->post['to'] / 10);
					$filter['filter_group'] = $group;
				} elseif ($this->request->post['to'] > 100) {
					$group = $this->request->post['to'] % 100;
					$type = intval($this->request->post['to'] / 100);
					$filter['filter_group'] = $group;
				}
				if ($type == 3)
					$filter['filter_newsletter'] = 1;
				if ($this->request->post['to'] == 1)
					$filter['filter_newsletter'] = 1;

				$customers = $this->model_module_smsnot->getPhones($filter);
				$query = '';
				$i = 0;
				foreach ($customers as $customer) {
					if (preg_match('/(\+|)[0-9]{11}/', $customer['telephone'])) {
						$i++;
						$original = array("{StoreName}", "{Name}", "{LastName}");
						$replace = array($this->config->get('config_name'), $customer['firstname'], $customer['lastname']);
						$message = str_replace($original, $replace, $this->request->post['message']);
						$query.='&multi['.$customer['telephone'].']='.$message;
						if ($i>99) {
							$json = $this->sms_multisend($settings['smsnot-apikey'], $query, $settings['smsnot-sender']);
							$query = '';
							$i = 0;
						}
					}
				}
				$json = $this->sms_multisend($settings['smsnot-apikey'], $query, $settings['smsnot-sender']);
			}
		}
		$this->response->setOutput(json_encode($json));
	}

	private function read_response($response){
		$this->load->language('module/smsnot');
		$ex = explode("\n", $response);
		$result=array();
		if ($ex[0] == 100) {
			$balance=explode("=", $ex[2]);
			$result['error'] = 0;
			$result['balance'] = $balance[1];
			$result['text'] = $this->language->get('text_send_success');
		} else {
			$result['error'] = $ex[0];
			$result['text'] = $this->language->get('text_send_error').' ('.$this->error_array[$ex[0]].')';
		}
		return $result;
	}

	private function sms_send($api_id, $to=0, $text=0, $sender='') {
		$param = array(
		"api_id"		=>	$api_id,
		"to"			=>	$to,
		"text"			=>	$text,
		"from"			=>	$sender,
		"partner_id"	=> 34316);
		$ch = curl_init("http://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);
		return $this->read_response($result);
	}

	private function sms_multisend($api_id, $text, $sender='') {
		$param = array(
		"api_id"		=>	$api_id,
		"multi"			=>	$text,
		"from"			=>	$sender,
		"partner_id"	=> 34316);
		$ch = curl_init("http://sms.ru/sms/send");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $param);
		$result = curl_exec($ch);
		curl_close($ch);
		return $this->read_response($result);
	}

	private function get_balance($api_key='') {
		$ch = curl_init("http://sms.ru/my/balance");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_POSTFIELDS, array(
			"api_id"	=>	$api_key
		));
		$response = curl_exec($ch);
		curl_close($ch);
		$ex = explode("\n", $response);
		if (count($ex) == 1) {
			$json['error'] = $response;
			$json['text'] = $this->error_array[$response];
		} else {
			$json['error'] = 0;
			$json['balance'] = $ex[1];
		}
		return $json;
	}
}
?>