<?php

/**
 * Defines the SiteSprocketFile data type
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocketFile extends S3File {

	static $has_one = array (
		'Option' => 'SiteSprocketSelectedOption',
		'Message' => 'SiteSprocketMessage'
	);
}