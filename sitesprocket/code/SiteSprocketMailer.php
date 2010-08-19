<?php

/**
 * Handles all of the email correspondence for SiteSprocket application
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocketMailer extends Controller {

	
	/**
	 * Send notifications to admin, client about new project
	 *
	 * @param SiteSprocketProject $project The project that has been created
	 */
	public static function send_new_project(SiteSprocketProject $project) {
		if(!$member = $project->Creator())
			return;
			
		$e = new Email(
			SiteSprocketConfig::EMAIL_FROM_ADDRESS, 
			SiteSprocketConfig::ORDERS_EMAIL_ADDRESS, 
			sprintf("New SiteSprocket Project [%d], [%s]", $project->ID, $project->obj('TotalCost')->Nice())
		);
		$e->setTemplate("NewProjectAdmin");
		$e->populateTemplate(array(
			'Project' => $project
		));
		$e->send();
		
		$e = new Email(
			SiteSprocketConfig::EMAIL_FROM_ADDRESS, 
			$member->Email,		
			"SiteSprocket order confirmation #{$project->ID}"			
		);
		$e->populateTemplate(array(
			'Project' => $project
		));
		$e->setTemplate("NewProjectClient");
		$e->send();
	}


	/**
	 * Notifies the admin that a payment failed
	 *
	 * @param Member $member The user that was trying to pay
	 * @param int $error_code The error code that was sent from the payment gateway
	 */
	public static function send_payment_failed(Member $member, $error_code = null) {
		$e = new Email(
			SiteSprocketConfig::EMAIL_FROM_ADDRESS, 
			SiteSprocketConfig::FAIL_EMAIL_ADDRESS, 
			"A payment failed"
		);
		$e->setTemplate("PaymentFailed");
		$e->populateTemplate(array(
			'Member' => $member,
			'ErrorCode' => $error_code
		));
		$e->send();
	}
	
	
	/**
	 * Notify an admin of a new message from the client
	 *
	 * @param SiteSprocketProject $project The project with which the message is associated
	 */
	public static function send_new_message_from_client(SiteSprocketProject $project) {
		$e = new Email(
			SiteSprocketConfig::EMAIL_FROM_ADDRESS, 
			"orders@sitesprocket.com", 
			"A client posted a new message"
		);
		$e->setTemplate("NewMessageFromClient");
		$e->populateTemplate(array(
			'Member' => $project->Creator(),
			'Project' => $project
		));
		$e->send();	
	}


	/**
	 * Notify the client of a new message from the CSR
	 *
	 * @param SiteSprocketProject $project The project with which the message is associated
	 * @param SiteSprocketMessage $message The message that was sent
	 */
	public static function send_new_message_from_admin(SiteSprocketProject $project, SiteSprocketMessage $message) {
		$e = new Email(
			SiteSprocketConfig::EMAIL_FROM_ADDRESS, 
			$project->Creator()->Email, 
			"SiteSprocket: New message for order #{$project->ID}"
		);
		$e->setTemplate("NewMessageFromAdmin");
		$e->populateTemplate(array(
			'Member' => $project->Creator(),
			'Message' => $message,
			'Project' => $project
		));
		$e->send();	
	}

}