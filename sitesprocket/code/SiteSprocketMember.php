<?php

/**
 * Decorates the {@link Member} object with extra fields needed for the SiteSprocket application
 * 
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocketMember extends DataObjectDecorator
{
	public function extraStatics() {
		return array (
			'db' => array (
				'Company' => 'Text',
				'City' => 'Text',
				'State' => 'Varchar(2)',
				'Address' => 'Text',
				'Zip' => 'Varchar(10)',
				'Phone' => 'Varchar(15)'
			),
			'has_one' => array (
				'Avatar' => 'Image'
			),
			'has_many' => array (
				'Subscriptions' => 'SiteSprocketSubscription'
			)
		);
	}
			
}