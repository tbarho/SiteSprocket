<?php

/**
 * Defines the SiteSprocketMessage data type, representing a message that is attached
 * to a {@link SiteSprocketProject}
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocketMessage extends DataObject {
	
	static $db = array (
		'MessageText' => 'HTMLText'
	);
	
	static $has_one = array (
		'Author' => 'Member',
		'Project' => 'SiteSprocketProject',
		'PaymentOption' => 'SiteSprocketPaymentOption'
	);
	
	static $has_many = array (
		'Attachments' => 'SiteSprocketFile'
	);
	
	/**
	 * Is this message by the current user?
	 *
	 * @return boolean
	 */
	public function You() {
		return $this->AuthorID == Member::currentUserID();
	}
	
	/**
	 * Update the related project's LastEdited field when a message is created
	 */
	public function onAfterWrite() {
		parent::onAfterWrite();
		if($p = $this->Project()) {
			$p->LastEdited = $this->LastEdited;
			$p->write();
		}
	}
}