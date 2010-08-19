<?php

class PaymentPage extends Page
{
	static $db = array();
}

class PaymentPage_Controller extends Page_Controller
{
	public function init() {
		parent::init();
		
		
		//Requirements::javascript('http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.3/jquery-ui.min.js');
		Requirements::javascript('sprocketOrderPage/js/page-payment/cc-buttons.js');
		
		//Requirements::css('sprocketOrderPage/css/ui-lightness/jquery-ui-1.8.4.custom.css');
	}
}