<?php

/**
 * Defines the SiteSprocketProductOption data type, representing an option that can be added
 * to a {@link SiteSprocketProject}
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */

class SiteSprocketProductOption extends DataObject
{
	static $db = array (
		'Title' => 'Varchar',
		'Description' => 'HTMLText',
		'Price' => 'Currency',
		'AllowUploads' => 'Boolean',
		'UploadText' => 'Text',
		'IsRecurring' => 'Boolean',
		'RecurringStart' => 'Date',
		'RecurringEnd' => 'Date',
		'RecurringAmount' => 'Int',
		'RecurringLength' => 'Int',
		'RecurringUnit' => "Enum('months,days')"
	);
	
	static $has_one = array (
		'Group' => 'SiteSprocketProductGroup',
		'SiteSprocket' => 'SiteSprocket'
	);
	
	static $has_many = array (
		'Uploads' => 'SiteSprocketFile'
	);
	
	static $summary_fields = array (
		'Title' => 'Title',
		'Group.Title' => 'Group',
		'Description' => 'Description',
		'Price' => 'Price',
		'AllowUploadsNice' => 'Allow uploads?',
		'UploadText' => 'Upload text'
	);
	
	static $searchable_fields = array (
		'Title'
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
	
	/**
	 * Returns a readable yes/no for the AllowUploads boolean
	 *
	 * @return string
	 */
	public function getAllowUploadsNice() {
		return $this->obj('AllowUploads')->Nice();
	}
	
	public function getCMSFields() {
		$set = DataObject::get("SiteSprocketProductGroup");
		$map = $set ? $set->toDropdownMap() : array();
		$f = new FieldSet (
			new TextField('Title', _t('SSP.OPTIONTITLE','Title')),
			$d = new DropdownField('GroupID', _t('SSP.OPTIONGROUP','Group'), $map),
			new TextareaField('Description', _t('SSP.OPTIONDESCRIPTION','Description')),
			new CurrencyField('Price', _t('SSP.OPTIONPRICE','Price')),
			new CheckboxField('AllowUploads', _t('SSP.ALLOWUPLOADS','Allow uploads')),
			new TextareaField('UploadText', _t('SSP.UPLOADTEXT','Upload text')),
			new CheckboxField('IsRecurring', _t('SSP.ISRECURRING','Recurring?')),
			new DatePickerField('RecurringStart', _t('SSP.RECURRINGSTART','Start of recurring billing')),
			new DatePickerField('RecurringEnd', _t('SSP.RECURRINGEND','End of recurring billing')),
			new FieldGroup(
				new LiteralField('bill', _t('SSP.BILLTHECARD','Bill the card $')),
				new NumericField('RecurringAmount',''),
				new LiteralField('every',_t('SSP.EVERY','every')),
				new NumericField('RecurringLength',''),
				new DropdownField('RecurringUnit','', $this->dbObject('RecurringUnit')->enumValues())
			)
		);
		$d->setEmptyString(_t('SSP.CHOOSEGROUP','-- Please select a group --'));
		return $f;
	}
	
	
	/**
	 * Template accessor for an upload field for this option
	 *
	 * @return UploadifyField
	 */
	public function UploadField() {
		$f = new UploadifyField("Upload_{$this->ID}");
		return $f;
	}
	
	
	/**
	 * Has the user selected this option?
	 *
	 * @return boolean
	 */
	public function Selected() {
		$data = Session::get("FormInfo.Form_OrderForm.data");
		if(isset($data['Options']) && is_array($data['Options'])) {
			return in_array($this->ID, $data['Options']);
		}
		return false;
			
	}
}