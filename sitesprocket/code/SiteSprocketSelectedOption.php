<?php

/**
 * Defines the SiteSprocketSelectedOption data type, representing a {@link SiteSprocketProductOption}
 * that has been selected by the user and associated with his project.
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocketSelectedOption extends DataObject {

	static $has_one = array (
		'Project' => 'SiteSprocketProject',
		'Attachment' => 'File',
		'Option' => 'SiteSprocketProductOption'
	);
	
	static $has_many = array (
		'Attachments' => 'SiteSprocketFile'
	);
}