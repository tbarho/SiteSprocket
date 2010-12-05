<?php

class SiteSprocketSubscription extends DataObject {

	static $db = array (
		'SubscriptionNumber' => 'Varchar'
	);
	
	static $has_one = array (
		'Member' => 'Member',
		'Option' => 'SiteSprocketProductOption'
	);

}