<?php

/**
 * Defines the SiteSprocketProject data type, the building block of the SiteSprocket application
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */

class SiteSprocketProject extends DataObject {

	static $db = array (
		'TotalCost' => 'Currency',
		'Status' => "Enum('Open,Closed','Open')",
		'Title' => 'Text',
		'Notes' => 'Text',
		'AuthNetApprovalCode' => 'Varchar'
	);
	
	static $has_one = array (
		'Creator' => 'Member',
		'CSR' => 'Member',
		'Closer' => 'Member'
	);
	
	static $has_many = array (
		'SelectedOptions' => 'SiteSprocketSelectedOption',
		'Messages' => 'SiteSprocketMessage'
	);
	
	
	/**
	 * @var array Fields that can be searched in the admin
	 */
	static $searchable_fields = array (
		'Title',
		'Notes',
		'Member.Email',
		'Member.FirstName',
		'Member.Surname',
		'SiteSprocketProject.ID'
	);
	
	/**
	 * @var array Cast custom functions as specific data types
	 */
	static $casting = array (
		'FinalCost' => 'Currency'
	);
	
	
	/**
	 * The list of columns in the result table for the backend
	 *
	 * @return array
	 */
	public function summaryFields() {
		return array (
			'ID' => _t('SSPAdmin.PROJECTID','Project Number'),
			'Title' => _t('SSPAdmin.PROJECTTITLE','Title'),
			'Creator.Email' => _t('SSPAdmin.CREATOREMAIL','Email'),
			'Created' => _t('SSPAdmin.CREATED','Created'),
			'LastEdited' => _t('SSPAdmin.UPDATED','Updated'),
			'Status' => _t('SSPAdmin.STATUS','Status')
		);		
	}
		

	/**
	 * The list of columns in the result table for the frontend
	 *
	 * @return array
	 */
	public function frontendFields() {
		return array (
			'ID' => _t('SSPAdmin.PROJECTID','Project Number - Subject'),
			'Title' => _t('SSPAdmin.PROJECTTITLE','Last Updated'),
			'Status' => _t('SSPAdmin.STATUS','Status')
		);		
	}
	
	
	/**
	 * Make sure the user can edit this project. Must be the creator, CSR, or the admin
	 *
	 * @return boolean
	 */
	public function canEdit() {
		if(!Member::currentUser()) {
			return false;
		}
		return (Permission::check("ADMIN")) || (in_array(Member::currentUserID(), array($this->CreatorID, $this->CSRID)));
	}
	

	/**
	 * Link to edit the project (frontend or backend)
	 *
	 * @return string
	 */
	public function EditLink() {
		return Controller::curr()->Link("edit/$this->ID");
	}
	
	
	/**
	 * Edit link for emails. Send to the admin
	 *
	 * @return string
	 */
	public function EmailEditLink() {
		return "admin/".SiteSprocketAdmin::$url_segment."/edit/$this->ID";
	}
	
	
	/**
	 * Default link for the project
	 *
	 * @return string
	 */
	public function Link() {
		return $this->EditLink();
	}
	
	
	/**
	 * The title to display in search results
	 *
	 * @return string
	 */
	public function AutoCompleteTitle() {
		return "Project: $this->ID (".$this->Creator()->Email.")";
	}
	
	
	/**
	 * Link for the search results
	 *
	 * @return string
	 */
	public function AutoCompleteLink() {
		return $this->EditLink();
	}
	
	
	/**
	 * Get the user's other projects 
	 *
	 * @return DataObjectset
	 */
	public function OtherProjects() {
		return DataObject::get("SiteSprocketProject", "CreatorID = $this->CreatorID AND SiteSprocketProject.ID != $this->ID");
	}
	
	
	/**
	 * Get the payment options that have been offered to this project and accepted
	 *
	 * @return DataObjectSet
	 */
	public function AcceptedPaymentOptions() {
		$sql = singleton("SiteSprocketPaymentOption")->extendedSQL()
					->innerJoin("SiteSprocketMessage", "`SiteSprocketMessage`.PaymentOptionID = `SiteSprocketPaymentOption`.ID")
					->where(array(
						"Paid = 1",
						"`SiteSprocketMessage`.ProjectID = $this->ID"
					));
		return $this->buildDataObjectSet($sql->execute());					
	}
	
	
	/**
	 * This function gets the total cost of the project, including any accepted payment options
	 *
	 * @return int
	 */
	public function FinalCost() {
		$cost = $this->TotalCost;
		if($options = $this->AcceptedPaymentOptions()) {
			foreach($options as $o) {
				$cost += $o->Cost;
			}
		}
		return $cost;
	}
	
	
	/**
	 * Get the closer ID if the status has changed.
	 */
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		$fields = $this->getChangedFields();
		if(isset($fields['Status'])) {
			if($fields['Status']['after'] == "Closed") {
				$this->CloserID = Member::currentUserID();			
			}
		}
	}

}