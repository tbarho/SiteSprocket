<?php

/**
 * Defines the SiteSprocketProductGroup data type, representing a holder for
 * {@link SiteSprocketProductOption}
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */

class SiteSprocketProductGroup extends DataObject
{
	static $db = array (
		'Title' => 'Varchar',
		'LimitOne' => 'Boolean'
	);
	
	static $has_many = array (
		'Options' => 'SiteSprocketProductOption'
	);
	
	static $has_one = array (
		'SiteSprocket' => 'SiteSprocket'
	);
	
	static $summary_fields = array (
		'Title' => 'Title'
	);
	
	public function canCreate() {
		return true;	
	}
	
	public function canDelete() {
		return true;	
	}
	
	public function canEdit() {
		return true;
	}
	
	public function getCMSFields() {
		return new FieldSet (
			new TextField('Title', _t('SSP.GROUPNAME','Name')),
			new CheckboxField('LimitOne', _t('SSP.GROUPLIMIT', 'Allow only one option?'))
		);
	}
	
	public function LimitOne() {
		if($this->LimitOne) {
			return " limit-one";
		}
		
		return false;
	}
	
	
}