<?php

/**
 * The controller that drives the Silverstripe administrative interface for
 * the SiteSprocket application.
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
class SiteSprocketAdmin extends LeftAndMain implements PermissionProvider
{
	

	/**
	 * @var string The title for the Silverstripe top menu
	 */
	static $menu_title = "Projects";
	
	/**
	 * @var string The url segment that will load this controller (e.g. /admin/projects)
	 */
	static $url_segment = "projects";
	
	/**
	 * @var SiteSprocket Stores an instance of {@link SiteSprocketPage} in cache. Necessary for building
	 *		links to frontend controller actions
	 *
	 */
	static $site_sprocket = null;
	
	
	/**
	 * @var array The actions on this controller that can be called from the URL, with their required
	 *			  permissions, if spplicable.
	 */
	static $allowed_actions = array (
		'allprojects' => 'SITESPROCKET_BACKEND',
		'unassignedprojects' => 'SITESPROCKET_BACKEND',
		'openprojects' => 'SITESPROCKET_BACKEND',
		'closedprojects' => 'SITESPROCKET_BACKEND',
		'settings' => 'ADMIN',
		'closedbyme' => 'SITESPROCKET_BACKEND',
		'myprojects' => 'SITESPROCKET_BACKEND',
		'updatesort',
		'autocomplete' => 'SITESPROCKET_BACKEND',
		'edit' => 'SITESPROCKET_BACKEND',
		'results' => 'SITESPROCKET_BACKEND',
		'updateproject' => 'SITESPROCKET_BACKEND',
		'CreateMessageForm' => 'SITESPROCKET_BACKEND',
		'upload',
		'nextpage',
		'prevpage',
		'updateperpage'
	);
	
	
	/**
	 * @var array Cast custom methods as specific data types
	 */
	static $casting = array (
		'AllProjectsCount' => 'Int',
		'UnassignedProjectsCount' => 'Int',
		'ClosedByMeCount' => 'Int',
		'OpenProjectsCount' => 'Int',
		'ClosedProjectsCount' => 'Int',
		'MyProjectsCount' => 'Int'
	);
	
			
	/**
	 * Sometimes, because the frontend and backend controllers share code, it's necessary to determine
	 * which one is active
	 *
	 * @return boolean
	 */
	public static function backend() {
		return Controller::curr()->class == "SiteSprocketAdmin";
	}
	
	
	/**
	 * Look at the sort information stored in session, and create an order by clause for a SQL query
	 *
	 * @return string
	 */
	public static function get_sort() {
		$field = Session::get("SSPAdmin.Sort") ? Session::get("SSPAdmin.Sort") : "Created";
		$dir = Session::get("SSPAdmin.SortDir") ? Session::get("SSPAdmin.SortDir") : "desc";
		if(strpos($field,'-') !== false) {
			list($relation, $property) = explode('-', $field);
			$table = singleton('SiteSprocketProject')->has_one($relation);
			return "`$table`.$property $dir";
		}
		return "$field $dir";
	}
	
	
	/**
	 * Get the page limit from session, or fall back on the config
	 *
	 * @return int
	 */
	public static function get_per_page() {
		return Session::get("SSPAdmin.PerPage") ? Session::get("SSPAdmin.PerPage") : SiteSprocketConfig::DEFAULT_PER_PAGE;
	}
	
	
	/**
	 * Get the current page number from session
	 *
	 * @return int
	 */
	public static function get_page() {
		return Session::get("SSPAdmin.Page") ? Session::get("SSPAdmin.Page") : 1;	
	}
	
	
	/**
	 * Calculate the offset, based on the current page, and the number of records per page
	 *
	 * @return int
	 */
	public static function get_offset() {
		return (self::get_page() - 1) * self::get_per_page();
	}
	
	
	/**
	 * Get a SQL ready limit clause based on the per page and offset values
	 *
	 * @return string
	 */
	public static function get_limit() {
		return sprintf('%d,%d', self::get_offset(), self::get_per_page());
	}
	
	public static function reset_page() {
		Session::set("SSPAdmin.Page", null);
		Session::set("SSPAdmin.PerPage", null);
	}
	

	/**
	 * Initialize the controller and load the dependencies
	 */
	public function init() {
		parent::init();
		Requirements::javascript(THIRDPARTY_DIR.'/jquery/jquery.js');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-metadata/jquery.metadata.js');
		Requirements::javascript(THIRDPARTY_DIR.'/jquery-livequery/jquery.livequery.js');
		Requirements::javascript('sitesprocket/javascript/jquery.form.js');
		Requirements::javascript('sitesprocket/javascript/ssp_admin.js');
		Requirements::css('sitesprocket/css/ssp_admin.css');
		
		// We'll need to refer to an instance of the frontend at times
		if($ssp = DataObject::get_one("SiteSprocket")) {
			self::$site_sprocket = $ssp;
		}
		else {
			user_error('Please create a SiteSprocket page before using the admin',E_USER_ERROR);
		}
	}
	
	/**
	 * @var array Create custom permissions for the backend
	 */
	public function providePermissions() {
		return array(
			'SITESPROCKET_BACKEND' => 'Has access to the SiteSprocket administrative console'
		);
	}
	
	
	/**
	 * Controller action for showing all projects
	 *
	 * @return SSViewer
	 */
	public function allprojects() {
		Session::set("SSPAdmin.Filter", null);
		if($this->getRequest()->getVar('reset')) {
			self::reset_page();
		}
		return $this->customise(array(
			'Heading' => _t('SSPAdmin.ALLPROJECTS','All projects')
		))->renderWith('SiteSprocketAdmin_results');

	}
	

	/**
	 * Controller action for showing unassigned projects
	 *
	 * @return SSViewer
	 */
	public function unassignedprojects() {
		Session::set("SSPAdmin.Filter", "CSRID = 0");
		if($this->getRequest()->getVar('reset')) {
			self::reset_page();
		}		
		return $this->customise(array(
			'Heading' => _t('SSPAdmin.UNASSIGNEDPROJECTS','Unassigned projects')
		))->renderWith('SiteSprocketAdmin_results');

	}
	
	
	/**
	 * Controller action for showing open projects
	 *
	 * @return SSViewer
	 */	
	public function openprojects() {
		Session::set("SSPAdmin.Filter", "Status = 'Open'");
		if($this->getRequest()->getVar('reset')) {
			self::reset_page();
		}		
		return $this->customise(array(
			'Heading' => _t('SSPAdmin.OPENPROJECTS','Open projects')
		))->renderWith('SiteSprocketAdmin_results');

	}


	/**
	 * Controller action for showing closed projects
	 *
	 * @return SSViewer
	 */
	
	public function closedprojects() {
		Session::set("SSPAdmin.Filter", "Status = 'Closed'");
		if($this->getRequest()->getVar('reset')) {
			self::reset_page();
		}		
		return $this->customise(array(
			'Heading' => _t('SSPAdmin.CLOSEDPROJECTS','Closed projects')
		))->renderWith('SiteSprocketAdmin_results');

	}


	/**
	 * Controller action for showing projects closed by the current user
	 *
	 * @return SSViewer
	 */		
	public function closedbyme() {
		Session::set("SSPAdmin.Filter", "CloserID = " . Member::currentUserID());
		if($this->getRequest()->getVar('reset')) {
			self::reset_page();
		}		
		return $this->customise(array(
			'Heading' => _t('SSPAdmin.CLOSEDBYME','Projects closed by me')
		))->renderWith('SiteSprocketAdmin_results');
	}
	

	/**
	 * Controller action for showing all projects assigned to the current user
	 *
	 * @return SSViewer
	 */	
	public function myprojects() {
		Session::set("SSPAdmin.Filter", "CSRID = " . Member::currentUserID());
		if($this->getRequest()->getVar('reset')) {
			self::reset_page();
		}		
		return $this->customise(array(
			'Heading' => _t('SSPAdmin.MYPROJECTS','My projects')
		))->renderWith('SiteSprocketAdmin_results');

	}
	
	
	/**
	 * This action updates the number of records per page based on user submission
	 *
	 * @param SS_HTTPRequest $request
	 * @return SSViewer
	 */
	public function updateperpage(SS_HTTPRequest $request) {
		if($per = $request->requestVar('PerPage')) {
			Session::set("SSPAdmin.PerPage", $per);
		}
		return $this->renderWith('ProjectResults');	
	}
	
	
	/**
	 * This action updates the current page number and refreshes the table view
	 *
	 * @return SSViewer
	 */
	public function nextpage() {
		if(self::NextLink()) {
			Session::set("SSPAdmin.Page", self::get_page() + 1);
		}
		return $this->renderWith('ProjectResults');
	}


	/**
	 * This action updates the current page number and refreshes the table view
	 *
	 * @return SSViewer
	 */
	public function prevpage() {
		if(self::PrevLink()) {
			Session::set("SSPAdmin.Page", self::get_page() - 1);
		}
		return $this->renderWith('ProjectResults');
	}
	
	
	/**
	 * Updates the sort information based on the sort field, passed in the ID param
	 *
	 * @return SSViewer
	 */
	public function updatesort() {
		if($id = Controller::curr()->getRequest()->param('ID')) {
			Session::set("SSPAdmin.Sort", $id);
			$dir = (Session::get("SSPAdmin.SortDir") == "asc") ? "desc" : "asc";
			Session::set("SSPAdmin.SortDir", $dir);
		}
		return $this->renderWith('ProjectResults');
	}
	

	/**
	 * Performs a basic search and returns results to the autocomplete field
	 *
	 * @return SSViewer
	 */
	public function autocomplete() {
		if($s = $this->getRequest()->requestVar('q')) {
			$like = array();
			foreach(SiteSprocketProject::$searchable_fields as $field) {
				$like[] = "$field LIKE '%$s%'";
			}
			$search = implode(' OR ', $like);
			$filter = Session::get("SSPAdmin.Filter");
			if($filter) {
				$where = "($filter) AND ($search)";
			}
			else {
				$where = $search;
			}
			Session::set("SSPAdmin.Filter", $where);
			$set = $this->ProjectResults(true);
			Session::set("SSPAdmin.Filter", $filter);
			return AutoCompleteField::render($set);
		}
	}
	
	
	/**
	 * Gets the edit view for a project. Make sure the CSR has the right to see the project
	 * Note: Though usually called via ajax, this can also be called directly from the url
	 * for jumping to a specific project.
	 *
	 * @return SSViewer
	 */
	public function edit() {
		if(isset($this->urlParams['ID']) && is_numeric($this->urlParams['ID'])) {
			if($p = DataObject::get_by_id("SiteSprocketProject", $this->urlParams['ID'])) {
				if($p->canEdit()) {
					$this->Project = $p;
				}
			}
		}
		if(Director::is_ajax()) {
			return $this->renderWith('SiteSprocketAdmin_edit');
		}
		return array (
			'RightContent' => $this->renderWith('SiteSprocketAdmin_edit')
		);
	}
	
	
	/**
	 * The default project results action
	 *
	 * @return SSViewer
	 */
	public function results() {
		return $this->renderWith('SiteSprocketAdmin_results');
	}
	
	
	/**
	 * Updating status and CSR share an action, and we avoid having to use a "save button"
	 *
	 * @return SS_HTTPResponse
	 */
	public function updateproject() {
		if(is_numeric($this->getRequest()->requestVar('id'))) {
			if($project = DataObject::get_by_id("SiteSprocketProject", $this->getRequest()->requestVar('id'))) {
				if($this->getRequest()->requestVar('name') == "CSRID") {
					$project->CSRID = $this->getRequest()->requestVar('val');
					$project->write();
					return new SS_HTTPResponse(sprintf(_t('SSPAdmin.UPDATEDCSR','Updated CSR to %s'), $project->CSR()->getName()),200);
				}
				elseif($this->getRequest()->requestVar('name') == "Status") {
					$project->Status = $this->getRequest()->requestVar('val');
					$project->write();
					return new SS_HTTPResponse(sprintf(_t('SSPAdmin.UPDATEDSTATUS','Updated status to %s'), $project->Status),200);
				}

			}
		}
	}
	
	
	/**
	 * Template accessor for the results table
	 *
	 * @return SSViewer
	 */
	public function RightContent() {
		return $this->renderWith('SiteSprocketAdmin_results');	
	}
	
	
	/**
	 * Get the total of all the projects
	 *
	 * @return int
	 */
	public function AllProjectsCount() {
		if($set = DataObject::get("SiteSprocketProject")) {
			return $set->Count();
		}
	}


	/**
	 * Get the total of the unassigned projects
	 *
	 * @return int
	 */
	public function UnassignedProjectsCount() {
		if($set = DataObject::get("SiteSprocketProject", "CSRID = 0")) {
			return $set->Count();
		}
	}

	/**
	 * Get the total of the open projects
	 *
	 * @return int
	 */	
	public function OpenProjectsCount() {
		if($set = DataObject::get("SiteSprocketProject", "Status = 'Open'")) {
			return $set->Count();
		}
	}
	
	
	/**
	 * Get the total of the closed projects
	 *
	 * @return int
	 */		
	public function ClosedProjectsCount() {
		if($set = DataObject::get("SiteSprocketProject","Status = 'Closed'")) {
			return $set->Count();
		}
	}
	

	/**
	 * Get the total projects closed by the current user
	 *
	 * @return int
	 */		
	public function ClosedByMeCount() {
		if($set = DataObject::get("SiteSprocketProject","CloserID = ".Member::currentUserID())) {
			return $set->Count();
		}
	}


	/**
	 * Get the total projects assigned to the current user
	 *
	 * @return int
	 */		
	public function MyProjectsCount() {
		if($set = DataObject::get("SiteSprocketProject","CSRID = ".Member::currentUserID())) {
			return $set->Count();
		}
	}
	
	
	/**
	 * This is the workhorse of the backend, as well as the frontend, responsible for retrieving
	 * a list of projects based on any filters, sorts, or pagination that has been applied
	 *
	 * @param boolean $unlimited If true, ignore the limit
	 * @return DataObjectSet
	 */			
	public function ProjectResults($unlimited = false) {
		// Frontend and backend have different table headings. Two different functions
		$sourceFunc = self::backend() ? "summaryFields" : "frontendFields";

		$limit = $unlimited ? null : self::get_limit();
		$filter = Session::get("SSPAdmin.Filter");
		$sort = self::get_sort();

		// Build the query
		$sql = singleton("SiteSprocketProject")->extendedSQL()
				->where($filter)
				->orderby($sort)
				->leftJoin("Member","CreatorID = `Member`.ID")
				->limit($limit);
		// Load the results into a DataObjectSet
		$set = singleton("SiteSprocketProject")->buildDataObjectSet($sql->execute());
		if($set) {
			$set->parseQueryLimit($sql);
			$ret = new DataObjectSet();
			foreach($set as $record) {
				// Give each record a Fields array to represent each cell in its row
				$record->Fields = new DataObjectSet();
				foreach(singleton('SiteSprocketProject')->$sourceFunc() as $field => $label) {
					if(strpos($field,'.') === false) {
						$value = $record->XML_val($field);
					} 
					// for relation "dot" syntax
					else {					
						list($relationMethod, $property) = explode('.', $field);
						if($rel = $record->$relationMethod()) {
							$value = $rel->getField($property);
						}
					}
				
					$record->Fields->push(new ArrayData(array(
						'EditLink' => $record->EditLink(),
						'Value' => $value,
						'Title' => $record->Title, // TB
						'LastEdited' => date('d-M-Y g:ia', strtotime($record->LastEdited)) // TB
					)));
				}
				$ret->push($record);
			}
			$ret->parseQueryLimit($sql);
			return $ret;		
		}
		return false;
	}	
	
	/**
	 * Get the table headings and their sort links, sort states
	 *
	 * @return DataObjectSet
	 */
	public function Headings() {
		$sourceFunc = self::backend() ? "summaryFields" : "frontendFields";
		$set = new DataObjectSet();
		foreach(singleton('SiteSprocketProject')->$sourceFunc() as $field => $label) {
			// Can't pass a "." in the url, so we substitute with dashes, and switch it back
			$field = str_replace('.','-',$field);
			$set->push(new ArrayData(array(
				'SortLink' => Controller::curr()->Link("updatesort/$field"),
				'Sorted' => Session::get("SSPAdmin.Sort") == $field,
				'SortDir' => Session::get("SSPAdmin.SortDir"),
				'Label' => $label
			)));
		}
		return $set;
	}
	
	
	/**
	 * The universal search form for projects
	 *
	 * @return Form
	 */
	public function SearchForm() {
		Validator::set_javascript_validation_handler('none');
		return new Form (
			$this,
			"SearchForm",
			new FieldSet(
				new AutoCompleteField("ProjectSearch", _t('SSPAdmin.SEARCHPROJECTS','Search projects'))
			),
			new FieldSet()
		);
	}
	
	
	/**
	 * Creates the dropdown of results per page options
	 *
	 * @return DropdownField
	 */
	public function PerPageDropdown() {
		$map = SiteSprocketConfig::$per_page_options;
		$field = new FieldGroup (
			new LiteralField('show', _t('SSPAdmin.SHOW','View ')),
			$d = new DropdownField(
				'PerPage',
				'',
				array_combine($map, $map),
				self::get_per_page()
			),
			new LiteralField('perpage', _t('SSPAdmin.PERPAGE',' per page'))
		);
		$url = Controller::join_links(Controller::curr()->Link("updateperpage"));

		// Store the url as metadata to make the ajax event easier
		$d->addExtraClass("{ 'url' : '$url' }");
		return $field;
	}
	
	
	/**
	 * A link to the next page of the result set
	 *
	 * @return SSViewer
	 */
	public function NextLink() {
		if($all = self::ProjectResults(true)) {
			$total = $all->Count();
			if(self::get_offset() + self::get_per_page() < $total) {
				return $this->Link('nextpage');
			}
		}
		return false;		
	}

	/**
	 * A link to the previous page of the result set
	 *
	 * @return SSViewer
	 */
	public function PrevLink() {
		if($all = self::ProjectResults(true)) {
			$total = $all->Count();
			if(self::get_offset() - self::get_per_page() >= 0) {
				return $this->Link('prevpage');
			}
		}
			return false;		
	}
	
	
	/**
	 * Create the dropdown list of CSRs
	 *
	 * @return DropdownField
	 */
	public function CSRDropdown() {
		if($group = DataObject::get_one("Group", "Code = 'sitesprocket-csr'")) {
			if($members = $group->Members()) {
				$val = ($this->Project) ? $this->Project->CSRID : null;
				$d = new DropdownField("CSRID", _t('SSPAdmin.ASSIGNTOCSR','Assign to'), $members->toDropdownMap(), $val);
				$d->setEmptyString('-- ' . _t('SSPAdmin.CHOOSECSR','Choose a CSR') . ' --');
				return $d;
			}
		}
		
	}
	
	
	/**
	 * A list of possible statuses for this project
	 *
	 * @return DropdownField
	 */
	public function StatusDropdown() {
		$val = ($this->Project) ? $this->Project->Status : null;
		return new DropdownField('Status', _t('SSPAdmin.CHANGESTATUS','Change status'), singleton('SiteSprocketProject')->dbObject('Status')->enumValues(), $val);
	}
	
	
	/**
	 * Create the form used to add messsages from the CSR
	 *
	 * @return Form
	 */
	public function CreateMessageForm() {
		if($project = $this->getFromRequest("SiteSprocketProject")) {
		UploadifyField::set_var('script', '/'.Controller::join_links(self::$site_sprocket->Link('upload')));
		UploadifyField::set_var('multi', true);
		$u = new UploadifyField('Attachments', _t('SSPAdmin.UPLOADATTACHMENT','Upload a file'));
		$u->setVar('width','115');
		$u->setVar('wmode','transparent');
			return new Form (
				$this,
				"CreateMessageForm",
				new FieldSet (
					new LiteralField('divMessageOpen', '<div class="add-message">'),
					new TextareaField('MessageText', _t('SSPAdmin.MESSAGETEXT','Message')),
					$u,
					new LiteralField('divMessageClose', '</div>'),
					new LiteralField('divPaymentOpen', '<div class="payment-option">'),
					new HeaderField($title = _t('SSPAdmin.ADDPAYMENTOPTION','Add payment option'), $headingLevel = 4),
					new NumericField('Cost', _t('SSPAdmin.ADDPAYMENTCOST','Additional cost')),
					new TextareaField('Description', _t('SSPAdmin.ADDPAYMENTDESCRIPTION','Description (for invoice)')),
					new LiteralField('divPaymentClose', '</div>'),
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
	 * Handle the submission of a create message form
	 *
	 * @param array $data The form data that was sent
	 * @param Form $form The Form that was used
	 * @return SSViewer
	 */
	public function doCreateMessage($data, $form) {
		if($project = $this->getFromRequest("SiteSprocketProject")) {
			$this->Project = $project;
			$form->saveInto($message = new SiteSprocketMessage());			
			$message->ProjectID = $project->ID;
			$message->AuthorID = Member::currentUserID();
			$message->write();
			$p = $message->Project();
			$p->UnreadClient = 1;
			$p->write();
			if(isset($data['Attachments']) && is_array($data['Attachments'])) {
				foreach($data['Attachments'] as $file_id) {
					if(is_numeric($file_id)) {
						if($file = DataObject::get_by_id("SiteSprocketFile", $file_id)) {
							$file->MessageID = $message->ID;
							$file->write();
						}
					}
				}
			}
			
			if(isset($data['Cost']) && is_numeric($data['Cost'])) {
				$form->saveInto($option = new SiteSprocketPaymentOption());
				$option->write();
				$message->PaymentOptionID = $option->ID;
				$message->write();				
			}
			
			SiteSprocketMailer::send_new_message_from_admin($project, $message);			

		}
		return $this->customise(array(
			'Messages' => $project->Messages()
		))->renderWith('Messages');
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