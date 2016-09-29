<?php
class ControllerApiSmscallback extends Controller {
	public function index() {

		if ((isset($this->request->request["data"])) && ($this->config->get('smsnot-log'))) {

			$this->load->model('module/smsnot');

			foreach ($this->request->request["data"] as $entry) {

				$lines = explode("\n", $entry);
				if ($lines[0] == "sms_status") {
					if (preg_match("/^[0-9]{3,7}-[0-9]{3,8}$/",$lines[1])) {
						$this->model_module_smsnot->setNoticeStatus($lines[1], $lines[2]);
					}
				}
			}

		}

		$this->response->setOutput(100);
	}
}
