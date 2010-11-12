<?php

/**
 * A simple class that keeps track of AuthNet activity
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class AuthNetLog extends DataObject {

	static $db = array (	
		'Status' => "Enum('SUCCESS,FAIL')",	
		'Code' => 'Int',
		'User' => 'Text',
		'Message' => 'Text'
	);
	
	static $summary_fields = array(
		'Status',
		'Code',
		'User',
		'Message'
	);
	
	
	/**
	 * Record a message to the AuthNet log table
	 *
	 * @param string $status (SUCCESS or FAIL)
	 * @param string $message The message to record
	 * @param int $code The AuthNet code thrown
	 */
	public static function log_message($status, $message, $code = null) {
		$log = new AuthNetLog(array(
			'Status' => $status,
			'Code' => $code,
			'Message' => $message,
			'User' => Member::currentUser()->Email
		));
		$log->write();
	}
	
	
	/**
	 * A shortcut to logging an AuthNet error message
	 *
	 * @param string $message The message to record
	 * @param int $code The AuthNet code thrown
	 */
	public static function log_error($message, $code = null) {
		self::log_message("FAIL", $message, $code);
	}


	/**
	 * A shortcut to logging an AuthNet success message
	 *
	 * @param string $message The message to record
	 * @param int $code The AuthNet code thrown
	 */
	public static function log_success($message, $code = null) {
		self::log_message("SUCCESS", $message, $code);
	}

}