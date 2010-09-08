<?php

class HomePage extends Page
{
	static $db = array();
	static $has_one = array();
	static $icon = 'sprocketHomePage/images/homePage';
}

class HomePage_Controller extends Page_Controller
{
	public function init() {
		parent::init();
		
		// TEMPORARY  Test
		
		// Require jQuery for splash area
		Requirements::javascript('themes/sitesprocket/js/jquery.main.js');
	}
}