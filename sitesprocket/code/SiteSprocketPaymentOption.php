<?php

/**
 * Defines the SiteSprocketPaymentOption data type, representing an extra option attached
 * to a {@link SiteSprocketMessage}
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocketPaymentOption extends DataObject {
	
	static $db = array (
		'Cost' => 'Currency',
		'Description' => 'Text',
		'Paid' => 'Boolean'
	);
	
	/**
	 * Get the message that is associated with this payment option.
	 * Note: For all intents and purposes, this should be a one-to-one
	 *
	 * @todo Should this use $belongs_to?
	 * @return SiteSprocketMessage
	 */
	public function Message() {
		return DataObject::get_one("SiteSprocketMessage", "PaymentOptionID = $this->ID");
	}
	
	
	/**
	 * Get the project based on the message that is associated with the payment option
	 *
	 * @return SiteSprocketProject
	 */
	public function Project() {
		if($message = $this->Message()) {
			return $message->Project();
		}
	}
	
	/**
	 * A link to pay for this option. Ties into the {@link SiteSprocket} controller.
	 *
	 * @return string
	 */
	public function PayLink() {
		return Controller::curr()->Link("optionpayment", $this->ID);
	}
	
	
	/**
	 * For security reasons, it's helpful to know if the current user is the one
	 * who is responsible for paying this option. Prevents others from being able
	 * to see this option
	 *
	 * @return boolean
	 */
	public function belongsToUser() {
		if($project = $this->Project()) {
			return $project->CreatorID == Member::currentUserID();
		}	
	}
}