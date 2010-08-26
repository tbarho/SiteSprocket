<?php

/**
 * Defines the data model for the SiteSprocket order interface
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocket extends Page
{

	static $has_many = array (
		'ProductGroups' => 'SiteSprocketProductGroup',
		'ProductOptions' => 'SiteSprocketProductOption'
	);
	
	
	public function getCMSFields() {
		$f = parent::getCMSFields();
		$f->addFieldToTab("Root.Content.Product Options", new DataObjectManager($this, 'ProductOptions','SiteSprocketProductOption'));
		$f->addFieldToTab("Root.Content.Product G", new DataObjectManager($this, 'ProductGroups','SiteSprocketProductGroup'));
		$f->addFieldToTab("Root.Content.On Submit", new HtmlEditorField('OnSubmit', _t('SSP.ONSUBMIT','On submit text')));
		return $f;
	}
}


/**
 * Defines the controller for all frontend functions and views for the SiteSprocket
 * application.
 *
 * Note: There are several methods in this controller that refer back to {@see SiteSprocketAdmin}
 * methods. Since each controller uses sortable, filterable results tables of projects, they can share
 * a lot of code. Admin functions are not accessible without authentication, so they're called
 * statically from this controller. In retrospect, it may have made more sense to switch the two,
 * and have the admin controller refer to the frontend, but the difference is subtle.
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocket_Controller extends Page_Controller implements PermissionProvider
{

	/**
	 * @var array Allowed actions for this controller that can be called via the URL
	 */
	static $allowed_actions = array (
		'order',
		'updateprice',
		'upload',
		'OrderForm',
		'account',
		'AccountForm',
		'payment',
		'PaymentForm',
		'LoginForm',
		'success',
		'projects',
		'profile',
		'ProfileForm',
		'updatesort',
		'edit',
		'CreateMessageForm',
		'updateperpage',
		'nextpage',
		'prevpage',
		'invoice',
		'optionpayment',
		'OptionPaymentForm'
	);
	
	
	/**
	 * @var array Casts custom functions as specific data types
	 */
	static $casting = array (
		'Total' => 'Currency'
	);
	
	
	/**
	 * @var Stores the selected options in memory to reduce extra queries
	 */
	protected $_selectedOptions = null;
	
		
	/**
	 * Gets all of the options the user has selected from the session. Options are stored
	 * as IDs, and this function loads them into DataObjects in a {@see DataObjectSet}
	 *
	 * @return DataObjectSet
	 */
	public static function get_selected_options() {
		if($data = Session::get("SSP_Options")) {
			$set = new DataObjectSet();
			foreach($data as $id) {
				$set->push(DataObject::get_by_id("SiteSprocketProductOption", $id));
			}
			return $set;
		}
		return false;
	}
	
	
	/**
	 * Builds an AuthNet object off of an array of data. Authnet is used in multiple forms,
	 * so by using naming conventions in the parameters of the forms, AuthNet can be created
	 * in a uniform manner.
	 *
	 * Note: This method does not assign an amount to the transaction, since that is likely to
	 * vary in each application.
	 *
	 * @param array $data An array of data including personal info and credit card fields
	 * @return AuthorizeNet
	 */
	protected static function build_auth_net($data) {
		$AuthNet = new AuthorizeNet(
			SiteSprocketConfig::AUTH_NET_URL,
			SiteSprocketConfig::AUTH_NET_KEY,
			SiteSprocketConfig::AUTH_NET_LOGIN,
			SiteSprocketConfig::AUTH_NET_VERSION
		);
		$AuthNet->useTestMode();
		$m = Member::currentUser();
		
		$AuthNet->CARDNUM = implode("",$data['CardNumber']);
		$AuthNet->EXPIRATION = $data['ExpMonth']."/".$data['ExpYear'];
		$AuthNet->CARD_CODE = $_POST['CCV'];
		
		// Since the array keys are identical, we can save a block of code and get the address
		// info from a single source
		$address_data = isset($data['CustomAddress']) ? $data : $m->getAllFields();

		$AuthNet->F_NAME = isset($address_data['FirstName']) ? $address_data['FirstName'] : '';
		$AuthNet->L_NAME = isset($address_data['Surname']) ? $address_data['Surname'] : '';
		$AuthNet->ADDRESS = isset($address_data['Address']) ? $address_data['Address'] : '';
		$AuthNet->CITY = isset($address_data['City']) ? $address_data['City'] : '';
		$AuthNet->STATE = isset($address_data['State']) ? $address_data['State'] : '';
		$AuthNet->ZIP = isset($address_data['Zip']) ? $address_data['Zip'] : '';
		$AuthNet->COUNTRY = isset($address_data['Country']) ? $address_data['Country'] : '';
		$AuthNet->PHONE = isset($address_data['Phone']) ? $address_data['Phone'] : '';

		$AuthNet->EMAIL = $m->Email;	
		
		return $AuthNet;
	}
	
	
	/**
	 * This function captures the standard payment failure behaviour based on standard request
	 * parameters used as personal info and credit card fields. Assigns an error message to the
	 * form and redirects back.
	 *
	 * @param AuthorizeNet $auth The AuthNet object that was used in the transaction
	 * @param array $data The form data that was sent
	 * @param Form $form The form object that was used
	 * @return
	 */
	protected static function payment_failure($auth, $data, $form) {
		$error_code = $auth->getErrorCode();
		if($error_code == "27")
		  $form->addErrorMessage('CardNumber', _t('SSP.CREDITCARDADDRESSERROR','Error processing credit card. Please ensure that your billing address in your customer profile matches the one for the credit card entered.'),'bad');
		else
		 $form->addErrorMessage('CardNumber', _t('SSP.CREDITCARDERROR','Error processing credit card. Please try again with this or an alternate card or contact your bank.'),'bad');
		
		// Don't store the state of any credit card fields. Force re-entry.
		$data['CardNumber'] = '';
		$data['CCV'] = '';
		$data['ExpMonth'] = '';
		$data['ExpYear'] = '';
		
		// Save the error to the database
		AuthNetLog::log_error($auth->getResponse(), $error_code);
		
		// Notify the admin
		SiteSprocketMailer::notify_payment_failed(Member::currentUser(), $error_code);
		
		Session::set("FormInfo.".$form->FormName().".data", $data);			
		return Director::redirectBack();		
	}	
	

	
	/**
	 * Initialize the controller and load the requirements
	 */
	public function init() {
		parent::init();
		Requirements::clear();
		Requirements::javascript(THIRDPARTY_DIR.'/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-metadata/jquery.metadata.js');
		Requirements::javascript('sitesprocket/javascript/behaviour.js');
		
		//Requirements::css('themes/sitesprocket/css/page-order.css');
	}


	/**
	 * Provide custom permissions to {@see SecurityAdmin}, allowing only certain group(s)
	 * to have access to this application
	 *
	 * @return array
	 */
	public function providePermissions() {
		return array(
			'SITESPROCKET_FRONTEND' => 'Has access to the SiteSprocket frontend'
		);
	}

	
	/**
	 * The default controller action dumps out to the order page
	 *
	 * @return SSViewer
	 */
	public function index() {
		// If the user is already logged in, go to the projects action, otherwise go to the order action
		if(Member::currentUser()) {
			return Director::redirect($this->Link('projects'));
		} else {
			return Director::redirect($this->Link('order'));
		}
		
	}

	
	/**
	 * This controller action renders the view for the create account page.
	 * Note: Not necessary if the user is already logged in.
	 *
	 * @return SSViewer
	 */
	public function account() {
		// If the user is already logged in, we don't need to be here.
		if(Member::currentUser())
			return Director::redirect($this->Link('payment'));	
		if(!$this->SelectedOptions())
			return Director::redirect($this->Link('order'));
		return array();
	}
	
	
	/**
	 * Renders the view for the payment page, provided the user has gone though
	 * the necessary steps.
	 *
	 * @return SSViewer
	 */
	public function payment() {
		if(!Member::currentUser())
			return Director::redirect($this->Link('account'));
		if(!$this->SelectedOptions())
			return Director::redirect($this->Link('order'));
		return array();
	}
	
	
	/**
	 * Handles file uploads from {@see UploadifyField}. Due to the S3 integration,
	 * this function is developped custom for the SiteSprocket application. Ordinarily,
	 * {@link UploadifyField::upload()} would handle the upload as a nested controller on its own.
	 *
	 * @return int The ID of the new file
	 */
	public function upload() {
		if(isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"])) {
			$ext = strtolower(end(explode('.', $_FILES['Filedata']['name'])));
			$file = new SiteSprocketFile();
			$file->setUploadBucket(SiteSprocketConfig::S3_UPLOAD_BUCKET);
			$file->fileName = uniqid('ssp_');
			$file->loadUploaded($_FILES['Filedata']);

			$file->write();
						
			echo $file->ID;
		} 
		else {
			echo ' '; // return something or SWFUpload won't fire uploadSuccess
		}
	}
	
	
	/**
	 * The success page after an order has been completed. Make sure the user
	 * got here by creating a project.
	 *
	 * @return SSViewer
	 */
	public function success() {
		if(!$project = $this->getFromRequest("SiteSprocketProject")) {
			return Director::redirectBack();
		}
		return array (
			'Project' => $project
		);
	}
	
	
	/**
	 * Returns the view of the current member's projects.
	 *
	 * @return SSViewer
	 */
	public function projects() {
		if(!$this->checkMember()) {
			return $this->requireLogin();
		}	
		return array();
	}
	
	
	/**
	 * Edit a project. Gets the project ID from the request {@see getFromRequest()}
	 *
	 * @return array
	 */
	public function edit() {
		if(!$this->checkMember()) {
			return $this->requireLogin();
		}	
		$project = $this->getFromRequest("SiteSprocketProject");
		if($project && !$project->canEdit()) {

			$project = false;
		}
		return array (
			'Project' => $project
		);
	}

	
	/**
	 * Returns the view of the client's invoice
	 *
	 * @return array
	 */
	public function invoice() {
		if(!$this->checkMember()) {
			return $this->requireLogin();
		}	
		$project = $this->getFromRequest("SiteSprocketProject");
		if($project && !$project->canEdit()) {
			$project = false;
		}
		return array (
			'Project' => $project
		);
	}
	
	
	/**
	 * Update the sort on the table of projects. Hands off to {@see SiteSprocketAdmin}
	 *
	 * @return SSViewer
	 */
	public function updatesort() {
		if($this->checkMember()) {
			return SiteSprocketAdmin::updatesort();
		}
	}
	

	/**
	 * Update the number of projects per page. Hands off to {@see SiteSprocketAdmin}
	 *
	 * @return SSViewer
	 */
	public function updateperpage(SS_HTTPRequest $request) {
		if($this->checkMember()) {
			return SiteSprocketAdmin::updateperpage($request);
		}
	}


	/**
	 * Advances the page on the table of projects. Hands off to {@see SiteSprocketAdmin}
	 *
	 * @return SSViewer
	 */	
	public function nextpage() {
		if($this->checkMember()) {
			return SiteSprocketAdmin::nextpage();
		}
	}
	

	/**
	 * Turns back the page on the table of projects. Hands off to {@see SiteSprocketAdmin}
	 *
	 * @return SSViewer
	 */	
	public function prevpage() {
		if($this->checkMember()) {
			return SiteSprocketAdmin::prevpage();
		}
	}
	
	
	/**
	 * The controller action to pay for an additional option. Make sure the current user
	 * owns the option, and that it hasn't been paid yet.
	 *
	 * @return SSViewer
	 */
	public function optionpayment() {
		if(!$this->checkMember()) {
			return $this->requireLogin();
		}
		if($option = $this->getFromRequest('SiteSprocketPaymentOption')) {
			if($option->belongsToUser() && !$option->Paid) {
				return array (
					'PaymentForm' => $this->OptionPaymentForm(),
					'OptionName' => $option->Description
				);
			}
		}
		return Director::redirectBack();
	}
		
	
	/**
	 * Template accessor that creates a link for a given controller action
	 *
	 * @param string $action The action to call on the controller
	 * @param int $id An ID to include in the URL parameters
	 * @return string
	 */
	public function Link($action = null, $id = null) {
		return Controller::join_links($this->URLSegment, $action, $id);
	}

	
	/**
	 * Gets the list of headings for the project results table. Hands off to {@see SiteSprocketAdmin}
	 *
	 * @return DataObjectSet
	 */
	public function Headings() {
		return SiteSprocketAdmin::Headings();
	}
	
	
	/**
	 * Gets the list of projects in the current view. Includes any sorting/filtering/pagination
	 * that has been applied {@see SiteSprocketAdmin::ProjectResults()}
	 *
	 * @return DataObjectSet
	 */
	public function ProjectResults() {
		Session::set("SSPAdmin.Filter","CreatorID = " . Member::currentUserID());
		return SiteSprocketAdmin::ProjectResults();
	}
	
	
	/**
	 * Gets a {@see DropdownField} for all the possible number of results per page
	 *
	 * @return DropdownField
	 */
	public function PerPageDropdown() {
		if($this->checkMember()) {
			return SiteSprocketAdmin::PerPageDropdown();
		}
	}
	
	
	/**
	 * A template accessor that gets the link for the next page of results
	 * {@see SiteSprocketAdmin}
	 * @return 
	 */
	public function NextLink() {
		if($this->checkMember()) {
			return SiteSprocketAdmin::NextLink();
		}
	}

	/**
	 * A template accessor that gets the link for the previous page of results
	 * {@see SiteSprocketAdmin}
	 * @return 
	 */
	public function PrevLink() {
		if($this->checkMember()) {
			return SiteSprocketAdmin::PrevLink();
		}
	}
	
	
	/**
	 * A template accessor that returns all of the options the user has selected
	 *
	 * @return DataObjectSet
	 */
	public function SelectedOptions() {
		if($this->_selectedOptions !== null)
			return $this->_selectedOptions;
		if($set = self::get_selected_options())
			return $this->_selectedOptions = $set;
		return false;
	}
	
	
	/**
	 * Gets the total price for the options selected by the client
	 *
	 * @return int
	 */
	public function Total() {
		$total = 0;
		if($set = $this->SelectedOptions()) {
			foreach($set as $o)
				$total += $o->Price;
		}
		return $total;
	}
	
	
	/**
	 * Creates the order form 
	 *
	 * @return Form
	 */
	public function OrderForm() {
	
		UploadifyField::set_var('script', '/'.$this->Link('upload'));
		UploadifyField::set_var('multi',true);
		
		// Check for data that has been stored in the session
		$data = Session::get("FormInfo.Form_OrderForm.data");
		$selected = Session::get("SSP_Options") ? Session::get("SSP_Options") : array();
		$fields = new FieldSet();
		if($groups = $this->ProductGroups()) {
			foreach($groups as $g) {
				$fields->push(new LiteralField('','<div class="section">'));  // TB
				$fields->push(new HeaderField($title = $g->Title, $headingLevel = 3));
				if($opts = $g->Options()) {
					foreach($opts as $o) {
						$fields->push(new LiteralField('','<div class="product">'));  // TB
						$fields->push(new HeaderField($title = "$" . $o->Price, $headingLevel = 4));
						$fields->push(new CheckboxField('Option_'.$o->ID, $o->Title, in_array($o->ID, $selected)));
						$fields->push(new LiteralField('desc'.$o->ID, "<div class='description'>".$o->Description."</div>"));
						if($o->AllowUploads) {
							$fields->push($f = new UploadifyField("Upload_{$o->ID}"));
							if($o->UploadText) {
								$fields->push(new LiteralField('upl'.$o->ID, "<p class='upload-text'>".$o->UploadText."</p>"));
							}
							$f->imagesOnly();
							$f->setVar('hideButton', 'true'); // TB
							$f->setVar('wmode', 'transparent'); // TB
							$f->setVar('width','205'); // TB
						}
						$fields->push(new LiteralField('','</div>'));  // TB
					}
				}
				$fields->push(new LiteralField('','</div>')); // TB
			}
		}
		$f = new Form (
			$this,
			"OrderForm",
			$fields,
			new FieldSet(new FormAction('doOrder', _t('SSP.ORDER','Order')))
		);
		
		// If data has been saved to the session, load it to preserve the form state
		if(is_array($data)) {
			$f->loadDataFrom($data);
		}
		return $f;
		
	}
	
	
	/**
	 * Creates the "create an account" form
	 *
	 * @return Form
	 */	
	public function AccountForm() {
		$data = Session::get("FormInfo.Form_AccountForm.data");					
		$f = new Form (
			$this,
			"AccountForm",
			new FieldSet (
				new TextField('FirstName', _t('SSP.FIRSTNAME','First Name')),
				new TextField('Surname', _t('SSP.LASTNAME','Last Name')),
				new EmailField('Email', _t('SSP.EMAIL','Email')),
				new PasswordField('Password', _t('SSP.PASSWORD','Password')),
				new PasswordField('PasswordConfirm', _t('SSP.CONFIRMPASSWORD','Confirm')),
				new TextField('Address', _t('SSP.ADDRESS','Address')),
				new TextField('City', _t('SSP.CITY','City')),
				$d = new StateDropdownField('State', _t('SSP.STATE','State')),
				new CountryDropdownField('Country', _t('SSP.COUNTRY','Country')),
				new TextField('Zip', _t('SSP.ZIPCODE','Zip')),
				new TextField('Phone', _t('SSP.PHONE','Phone'))				
			),
			new FieldSet(new FormAction('doCreateAccount', _t('SSP.CREATEACCOUNT','Create account and pay'))),
			new RequiredFields("FirstName","LastName","Email","Password","Address","Country")
		);
		$d->setEmptyString('-- ' . _t('SSP.PLEASESELECT','Please select') . ' --');		

		if(is_array($data)) {
			$f->loadDataFrom($data);
		}
		return $f;
	}
	
	/**
	 * Creates the form for processing payment
	 *
	 * @return Form
	 */
	public function PaymentForm() {
		$year_map = array();
		$year = date('Y');
		for($i = 0;$i < SiteSprocketConfig::EXP_YEAR_RANGE; $i++) {
			$year_map[$year+$i] = $year+$i;
		}
		$data = Session::get("FormInfo.Form_PaymentForm.data");			
		$m = Member::currentUser();
		// TB - Many Edits to this form.  Mostly rearranging, getting rid of useless Dropdown, and adding literals
		$form = new Form (
			$this,
			"PaymentForm",
			new FieldSet (
				new CreditCardField('CardNumber', _t('SSP.CARDNUMBER','Card number')),
				new LiteralField('CardType', '<div id="CardType" class="field optionset"><ul><li class="visa">Visa</li><li class="mc">Master Card</li><li class="ae">American Express</li><li class="disc">Discover</li></ul></div>'),
				new DropdownField('ExpMonth', _t('SSP.EXPMONTH','Exp. Date'), array_combine(range("01","12"), range("01","12"))),
				new DropdownField('ExpYear', _t('SSP.EXPMONTH','/'), $year_map),
				new NumericField('CCV', _t('SSP.CCV','CCV')),
				new LiteralField('ccv-dialog', '<div id="ccv-dialog">How to find a CCV</div>'),
				new HeaderField($title = _t('SSP.BILLINGHEADER','Billing information'), $headingLevel = 3),
				new LiteralField('address_block', '<div>'.implode('<br />', array (
					$m->getName(),
					$m->Address,
					$m->City . ", " . $m->State . " " . $m->Zip,
					$m->Country,
					$m->Phone
				)).'</div>'),
				new CheckboxField('CustomAddress', _t('SSP.USEPROFILEADDRESS','Use a different address')),
				new LiteralField('open_custom_address','<div class="custom_address">'),
				new TextField('FirstName', _t('SSP.FIRSTNAME','First name')),
				new TextField('Surname', _t('SSP.LASTNAME','Last name')),
				new TextField('Address', _t('SSP.ADDRESS','Address')),
				new TextField('City', _t('SSP.CITY','City')),
				$d = new StateDropdownField('State', _t('SSP.STATE','State')),
				new CountryDropdownField('Country', _t('SSP.COUNTRY','Country')),
				new TextField('Zip', _t('SSP.ZIPCODE','Zip')),
				new TextField('Phone', _t('SSP.PHONE','Phone')),
				new LiteralField('close_custom_address','</div>'),
				new LiteralField('divider', '<div class="divider">&nbsp;</div><h2>Additional Info</h2><p>Name your project, and enter any special notes or instructions.</p>'),
				new TextField('Title', _t('SSP.PROJECTTITLE','Project title')),
				new TextareaField('Notes', _t('SSP.PROJECTNOTES','Notes'))		
				
				
			),
			new FieldSet (
				new FormAction('doPayment', _t('SSP.ORDERNOW','Order now!'))
			),
			new RequiredFields("Title","CardType","CardNumber","ExpMonth","ExpYear","CCV")
		);
		if($data) {
			$form->loadDataFrom($data);
		}
		$d->setEmptyString('-- ' . _t('SSP.PLEASESELECT','Please select') . ' --');				
		return $form;
	}
	
	
	/**
	 * Creates a form for paying for an additional option. Borrows most of its fields from
	 * {@see PaymentForm()}, and takes out what is unnecessary.
	 *
	 * @return Form
	 */
	public function OptionPaymentForm() {
		$option = $this->getFromRequest('SiteSprocketPaymentOption');
		if(!$option->belongsToUser() || $option->Paid) {
			return Director::redirectBack();
		}
		$data = Session::get("FormInfo.Form_OptionPaymentForm.data");
		$form = new Form (
			$this,
			"OptionPaymentForm",
			$this->PaymentForm()->Fields(),
			new FieldSet (
				new FormAction('doOptionPayment', _t('SSP.PAY','Pay'))
			),
			new RequiredFields("Title","CardType","CardNumber","ExpMonth","ExpYear","CCV")
		);
		$form->Fields()->removeByName('Title');
		$form->Fields()->removeByName('Notes');
		$form->Fields()->push(new HiddenField('ID','', $option->ID));
		if($data) {
			$form->loadDataFrom($data);
		}
		return $form;
	}
	
	
	/**
	 * Creates the form for editing the user's profile
	 *
	 * @return Form
	 */
	public function ProfileForm() {
		if(!$member = Member::currentUser())
			return Director::redirectBack();
			
		$form = new Form (
			$this,
			"ProfileForm",
			new FieldSet (
				new HeaderField(
					$title = _t('SSP.LOGINCREDS','You can change your email and password. Your new credentials will be used to log you in.'),
					$headingLevel = 3
				),
				new EmailField('Email', _t('SSP.EMAIL','Email')),
				new PasswordField('Password', _t('SSP.PASSWORD','Choose a password')),
				new PasswordField('PasswordConfirm', _t('SSP.CONFIRMPASSWORD','Confirm password')),

				new HeaderField(
					$title = _t('SSP.INVOICESANDRECEIPTS','This information will be used to generate invoices and receipts.'),
					$headingLevel = 3
				),				
				new TextField('Company', _t('SSP.COMPANY','Company')),
				new TextField('FirstName', _t('SSP.FIRSTNAME','First name')),
				new TextField('Surname', _t('SSP.LASTNAME','Last name')),
				new TextField('Address', _t('SSP.ADDRESS','Address')),
				$d = new StateDropdownField('State', _t('SSP.STATE','State')),
				new TextField('Zip', _t('SSP.ZIPCODE','Zip')),
				new CountryDropdownField('Country', _t('SSP.COUNTRY','Country')),
				new TextField('Phone', _t('SSP.PHONE','Phone number'))
			),				
			new FieldSet (
				new FormAction('doProfileSave', _t('SSP.SAVEPROFILE','Save profile'))
			),
			new RequiredFields("FirstName","LastName","Email","Password","Address","Country")
		);
		$d->setEmptyString('-- ' . _t('SSP.PLEASESELECT','Please select') . ' --');
		$form->loadDataFrom($member);
		return $form;
	}
	
	
	/**
	 * Creates the form for adding a new message
	 *
	 * @return Form
	 */
	public function CreateMessageForm() {
		if(!$this->checkMember()) {
			return;
		}
		if($project = $this->getFromRequest("SiteSprocketProject")) {
		UploadifyField::set_var('script', '/'.$this->Link('upload'));
		UploadifyField::set_var('multi', true);
		UploadifyField::set_var('wmode', 'transparent');
		UploadifyField::set_var('width', '205');
			return new Form (
				$this,
				"CreateMessageForm",
				new FieldSet (
					new TextareaField('MessageText', _t('SSPAdmin.MESSAGETEXT','Message')),
					new UploadifyField('Attachments', _t('SSPAdmin.UPLOADATTACHMENT','Upload a file')),
					new HiddenField('ID','', $project->ID)
				),
				new FieldSet (
					new FormAction('doCreateMessage', _t('SSPAdmin.CREATEMESSAGE','Add message'))
				),
				new RequiredFields('MessageText')
			);
		}
	}
	
	
	/**
	 * Make sure the member has the correct permissions
	 *
	 * @todo This should be a static method
	 * @return boolean
	 */
	public function checkMember() {
		return Permission::check("SITESPROCKET_FRONTEND");	
	}
	
	
	/**
	 * Send the user to a login screen, with appropriate messaging
	 *
	 * @return SSViewer
	 */
	public function requireLogin() {
		return Security::permissionFailure($this, _t('SSP.PERMISSIONFAIL','Please log in to access SiteSprocket'));
	}
	
	
	/**
	 * Handle submission of the order form. This method is called every time the form
	 * is updated via AJAX.
	 *
	 * @param array $data The form data that was sent
	 * @param Form $form The form that was used
	 * @return SSViewer
	 */
	public function doOrder($data, $form) {
		$option_ids = array();
		foreach($data as $key => $v) {
			if(stristr($key, "Option_")) {
				list($dummy, $id) = explode("_",$key);
				if($o = DataObject::get_by_id("SiteSprocketProductOption", $id))
					$option_ids[] = $id;
			}
		}
		
		// If the user has posted the form, make sure he checked at least one option. Suppress this
		// for AJAX requests, so the user is allowed to uncheck all of the boxes.
		if(empty($option_ids) && !Director::is_ajax()) {
			Session::set("FormInfo.Form_OrderForm.data",$data);
			$form->addErrorMessage("Blurb",_t('SiteSprocketMemberProfile.INVALIDOPTIONS','Please choose at least one option.'),"bad");
			return Director::redirectBack();			
		}
		
		// Store all of the options in session, and render the new price table.	
		Session::set("SSP_Options", $option_ids);
		if(Director::is_ajax()) {
			return $this->renderWith(array('PriceTable'));
		}
		
		// Process uploads. Note, the files have already been uploaded. The form posts the IDs of the file(s).
		$option_files = array();
		if($opts = $this->SelectedOptions()) {
			foreach($opts as $option) {
				if($option->AllowUploads) {
					// Files are stored as "Upload_" followed by the product option ID they relate to
					if(isset($data['Upload_'.$option->ID]) && is_array($data['Upload_'.$option->ID])) {
						$option_files[$option->ID] = array();
						foreach($data['Upload_'.$option->ID] as $id) {
							if(is_numeric($id)) {
								if($file = DataObject::get_by_id("S3File", $id)) {
									// Create a map of option ID to File ID(s)
									$option_files[$option->ID][] = $file->ID;
								}
							}
						}
					}
				}
			}
		}
		// Keep the files in session. We can't use them until the order has been paid for and a project created.
		Session::set("SSP_OptionFiles", $option_files);
		
		return Director::redirect($this->Link('account'));
	}
	

	/**
	 * Handle the submission of the create account form
	 *
	 * @param array $data The form data that was sent
	 * @param Form $form The form that was used
	 * @return SSViewer
	 */
	public function doCreateAccount($data, $form) {
		
		// Make sure we have a place to store the member
		$group = DataObject::get_one('Group', "Code = 'site-sprocket-clients'");
		if(!$group) {
			$form->addErrorMessage("Blurb",'Sorry, you need to set a group for clients to be added to.  Have the webmaster see the configuration file for details.', "bad");
			Session::set("FormInfo.Form_AccountForm.data", $data);
			return Director::redirectBack();
		}
		
		if($member = DataObject::get_one("Member", "`Email` = '". Convert::raw2sql($data['Email']) . "'")) {
			$form->addErrorMessage("Blurb",_t('SSP.EMAILEXISTS','Sorry, that email address already exists. Please choose another.'),"bad");
			// Load errors into session and post back
			Session::set("FormInfo.Form_AccountForm.data", $data);
			return Director::redirectBack();
		}
		// check password fields are the same before saving
		if($data['Password'] != $data['PasswordConfirm']) {
			$form->addErrorMessage("Password",_t('SiteSprocketMemberProfile.PASSNOTMATCH','Both passwords need to match. Please try again.'),"bad");
			Session::set("FormInfo.Form_AccountForm.data", $data);  // TB
			return Director::redirectBack();
		}

		// create the new member. Object::create() will respect Object::useCustomClass(), which is often used
		// to override the Member class.
		$member = Object::create('Member');
		$form->saveInto($member);
		$member->write();

		$member->login();
		$group->Members()->add($member);
		
		return Director::redirect($this->Link('payment'));
		
	}
	
	
	/**
	 * Handle the credit card processing and final touches before creating the project
	 *
	 * @param array $data The form data that was sent
	 * @param Form $form The form that was used
	 * @return SSViewer
	 */
	public function doPayment($data, $form) {
		
		
		/****  Commented Out For Dev Server ****/
		
		/*
$AuthNet = self::build_auth_net($data);
		$AuthNet->AMOUNT = $this->Total();
		$AuthNet->init();
		$AuthNet->authorize_exec();
	
		$response_code = $AuthNet->getResponseCode();
		$error_code = $AuthNet->getErrorCode();

		if(!$AuthNet->isApproved()) {
			return self::payment_failure($AuthNet, $data, $form);
		}
		
		// Success. Save a message to the database
		AuthNetLog::log_success($AuthNet->getResponse());
*/
		
		
		/****  Commented Out For Dev Server ****/
		
		
		$m = Member::currentUser();
		
		// Create the new project
		$p = new SiteSprocketProject();
		//$p->AuthNetApprovalCode = $AuthNet->getApprovalCode();
		$p->write();
		$p->CreatorID = Member::currentUserID();
		$files = Session::get("SSP_OptionFiles") ? Session::get("SSP_OptionFiles") : array();
		
		// Loop through all the options chosen on the order form, and put them into the new project
		foreach($this->SelectedOptions() as $opt) {
			$selected = new SiteSprocketSelectedOption();
			$selected->ProjectID = $p->ID;
			$selected->OptionID = $opt->ID;
			$selected->write();
			if(isset($files[$opt->ID]) && is_array($files[$opt->ID])) {
				foreach($files[$opt->ID] as $file_id) {
					if($f = DataObject::get_by_id("SiteSprocketFile", $file_id)) {
						$f->OptionID = $selected->ID;
						$f->write();
					}
				}
			}
		}
		
		$p->TotalCost = $this->Total();
		$p->Title = $data['Title'];
		$p->Notes = $data['Notes'];
		$message = new SiteSprocketMessage();
		$message->AuthorID = Member::currentUserID();
		$message->ProjectID = $p->ID;
		$message->MessageText = $p->renderWith('ProjectDetailMessage');
		$message->write();
		$p->write();
		
		// Clear out all the session data in case another order is placed
		Session::set("SSP_Options",null);
		Session::set("SSP_OptionFiles",null);
		
		// Notify the client and the admin
		SiteSprocketMailer::send_new_project($p);
		
		return Director::redirect($this->Link('success',$p->ID));
	}
	
	
	/**
	 * Handle the processing of payment for an additional option. Make sure the user owns
	 * the option and that it hasn't been paid yet.
	 *
	 * @param array $data The form data that was sent
	 * @param Form $form The Form object that was used
	 * @return SSViewer
	 */
	public function doOptionPayment($data, $form) {
		if(!$option = $this->getFromRequest('SiteSprocketPaymentOption')) {
			return Director::redirectBack();
		}
		
		if(!$option->belongsToUser() || $option->Paid) {
			return Director::redirectBack();
		}

		$AuthNet = self::build_auth_net($data);
		$AuthNet->AMOUNT = $option->Cost; 
		$AuthNet->init();
		$AuthNet->authorize_exec();
	
		$response_code = $AuthNet->getResponseCode();
		$error_code = $AuthNet->getErrorCode();

		if(!$AuthNet->isApproved()) {
			return self::payment_failure($data, $form);
		}
		
		// Success. Save a message to the database
		AuthNetLog::log_success($AuthNet->getResponse());
		$option->Paid = '1';
		$option->write();

		return $this->customise(array(
			'Option' => $option
		))->renderWith(array('SiteSprocket_optionpaymentsuccess','Page'));
			
	}
	
	/**
	 * Handles the submission of a profile edit form
	 *
	 * @param array $data The form data that was sent
	 * @param Form $form The form that was used
	 * @return SSViewer
	 */
	public function doProfileSave($data, $form) {
		if($member = Member::currentUser()) {
			$SQL_email = Convert::raw2sql($data['Email']);
			// An existing member may have the requested email that doesn't belong to the
			// person who is editing their profile - if so, throw an error
			$existingMember = DataObject::get_one('Member', "`Member`.ID != $member->ID AND Email = '$SQL_email'");
			if($existingMember) {
				$form->addErrorMessage('Email',	_t('SSP.EMAILEXISTS','Sorry, that email address already exists. Please choose another.'),'bad');
				return Director::redirectBack();
			}
		
			if(!empty($data['Password']) && !empty($data['ConfirmPassword'])) {
				if($data['Password'] == $data['ConfirmPassword']) {
					$member->Password = $data['Password'];
				} 
				else {
					$form->addErrorMessage("Password",	_t('SSP.PASSNOTMATCH','Those passwords do not match.'),"bad");
					return Director::redirectBack();
				}
			} 
			else {
				$form->dataFieldByName("Password")->setValue($member->Password);
			}
			$form->saveInto($member);
			$member->write();	
			$form->sessionMessage(_t('SSP.PROFILEUPDATE','Your profile was updated successfully.'),'good');		
		}
		return Director::redirectBack();	
	}
	
	
	/**
	 * Handles the creation of a message
	 *
	 * @param array $data The form data that was sent
	 * @param Form $form The form that was used
	 * @return SSViewer
	 */
	public function doCreateMessage($data, $form) {
		if($project = $this->getFromRequest("SiteSprocketProject")) {
			if(!$project->canEdit()) {
				return Director::redirectBack();
			}
			$form->saveInto($message = new SiteSprocketMessage());			
			$message->ProjectID = $project->ID;
			$message->AuthorID = Member::currentUserID();
			$message->write();
			if(isset($data['Attachments']) && is_array($data['Attachments'])) {
				foreach($data['Attachments'] as $file_id) {
					if($file = DataObject::get_by_id("SiteSprocketFile", $file_id)) {
						$file->MessageID = $message->ID;
						$file->write();
					}
				}
			}
			
			// Notify the admin
			SiteSprocketMailer::send_new_message_from_client($project);
		}
		if(Director::is_ajax()) {
			return $this->customise(array(
				'Messages' => $project->Messages()
			))->renderWith('ClientMessages');
		}
		return Director::redirectBack();
	}
	
	
	/**
	 * From the URL, or from the request, get an ID and make sure it's numeric
	 *
	 * @return int
	 */
	protected function cleanID() {
		if($this->urlParams['ID'] && is_numeric($this->urlParams['ID']))
			return $this->urlParams['ID'];
		elseif(isset($_REQUEST['ID']) && is_numeric($_REQUEST['ID']))
			return $_REQUEST['ID'];
		return false;
	}
	
	/**
	 * Get an object based on the ID in the request
	 *
	 * @param string $className The name of the class to fetch
	 * @return DataObject
	 */
	protected function getFromRequest($className) {
		if($id = $this->cleanID())
			return DataObject::get_by_id($className, $id);
		return false;
	}
	
	
}