<?php

class OrderPage extends Page
{
	static $db = array();
}

class OrderPage_Controller extends Page_Controller
{
	public function init() {
		parent::init();
		
		Requirements::javascript('sprocketOrderPage/js/page-order/cart-mover.js');
		Requirements::javascript('sprocketOrderPage/js/page-order/check-change.js');
	}
}