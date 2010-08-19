<?php
/**
 * SiteSprocketMemberProfile is the profile pages for a given SiteSprocketMember
 * 
 * @package SiteSprocket
 */
class SiteSprocketMemberProfile extends Page_Controller {

	/**
	 * @var string The URL identifier for this controller
	 */
	public static $url_segment = "member";
	
	/**
	 * @var array A list of all the actions that are publicly accessible
	 * from the URL
	 */
	public static $allowed_actions = array (
		'login',
		'logout',
		'register',
		'RegistrationForm',
		'doregister',
		'edit',
		'EditProfileForm',
		'dosave',
		'thanks'
	);
			
	/**
	 * Get the URL for the login action
	 *
	 * @return string URL to the login action
	 */
	public function LoginURL() {
		return $this->Link("login");
	}
	


	/**
	 * The login action
	 *
	 * It simple sets the return URL and forwards to the standard login form.
	 */
	public function login() {
		Session::set('Security.Message.message', _t('SiteSprocket.CREDENTIALS','Please login to access SiteSprocket'));
		Session::set('Security.Message.type', 'status');
		Session::set("BackURL", $this->Link());
		Director::redirect('Security/login');
	}
	
	/**
	 * The logout action
	 *
	 */
	public function logout() {
		if($member = Member::currentUser())
			$member->logOut();
		$returnTo = DataObject::get_one("HomePage");
		if($returnTo)
			return Director::redirect(Director::absoluteBaseURL().$returnTo->URLSegment);
		else {
			return Director::redirectBack();
		}
	}
	
	/**
	 * Show the registration form
	 */
	public function register() {
		return array(
			"Title" => _t('SiteSprocketMemberProfile.REGTITLE','Site Sprocket Registration'), 
		 	"Subtitle" => _t('SiteSprocketMemberProfile.JOINNOW','Join Site Sprocket Now!')
		);
	}
	
	/**
	 * Factory method for the registration form
	 *
	 * @return Form Returns the registration form
	 */
	function RegistrationForm() {
		
		$data = Session::get("FormInfo.Form_RegistrationForm.data");

		$fields = singleton('SiteSprocketMember')->getBasicFields(true);
	
		$form = new Form(
			$this, 
			'RegistrationForm', 
			$fields,
			new FieldSet(
				new FormAction("doregister", _t('Register.REGISTER','Register'))
			)
		);

		// we should also load the data stored in the session. if failed
		if(is_array($data)) {
			$form->loadDataFrom($data);
		}
		return $form;
	}


	/**
	 * Register a new member
	 *
	 * @param array $data User submitted data
	 * @param Form $form The used form
	 */
	function doregister($data, $form) {
		$group = DataObject::get_one('Group', "Code = '".SiteSprocketConfig::MEMBER_GROUP_NAME."'");
		
		if($member = DataObject::get_one("Member", "`Email` = '". Convert::raw2sql($data['Email']) . "'")) {
			if($member) {
				$form->addErrorMessage("Blurb",_t('SiteSprocketMemberProfile.EMAILEXISTS','Sorry, that email address already exists. Please choose another.'),"bad");
				// Load errors into session and post back
				Session::set("FormInfo.Form_RegistrationForm.data", $data);
				Director::redirectBack();
				return;
			}
  	}  

		// create the new member
		$member = Object::create('Member');
		$form->saveInto($member);
		
		// check password fields are the same before saving
		if($data['Password'] == $data['ConfirmPassword'])
			$member->Password = $data['Password'];
		else
			$form->addErrorMessage("Password",_t('SiteSprocketMemberProfile.PASSNOTMATCH','Both passwords need to match. Please try again.'),"bad");

		// check email fields are the same before saving
		if($data['Email'] == $data['ConfirmEmail'])
			$member->Email = $data['Email'];
		else {
			$form->addErrorMessage("Email",_t('SiteSprocketMemberProfile.EMAILNOTMATCH','Both email addresses need to match. Please try again.'),"bad");

			// Load errors into session and post back
			Session::set("FormInfo.Form_RegistrationForm.data", $data);
			return Director::redirectBack();
		}
		$member->write();
		$member->login();

		$group->Members()->add($member);
		return Director::redirect('/'.Controller::join_links(SiteSprocket::$url_segment));
	}
	
	/**
	 * Edit profile
	 *
	 * @return array Returns an array to render the edit profile page.
	 */
	function edit() {
		$form = $this->EditProfileForm()
			? $this->EditProfileForm()
			: "<p class=\"error message\">" . _t('SiteSprocketMemberProfile.WRONGPERMISSION','You don\'t have the permission to edit that member.') . "</p>";

		return array(
			"Form" => $form
		);
	}


	/**
	 * Factory method for the edit profile form
	 * @todo Is this even used? May be deprecated.
	 * @return Form Returns the edit profile form.
	 */
	function EditProfileForm() {
		$member = $this->Member();

		$fields = singleton('SiteSprocketMember')->getBasicFields();
		$fields->push(new HiddenField("ID"));

		$form = new Form(
			$this, 
			'EditProfileForm', 
			$fields,
			new FieldSet(new FormAction("dosave", _t('SiteSprocketMemberProfile.SAVECHANGES','Save changes')))
		);

		if($member && $member->hasMethod('canEdit') && $member->canEdit()) {
			$member->Password = '';
			$form->loadDataFrom($member);
			return $form;
		} 
		return null;
	}


	/**
	 * Save member profile action
	 *
	 * @param array $data
	 * @param $form
	 */
	function dosave($data, $form) {
		$member = DataObject::get_by_id('Member', $data['ID']);
		$SQL_email = Convert::raw2sql($data['Email']);
		$group = DataObject::get_one('Group', "Code = '".SiteSprocketConfig::MEMBER_GROUP_NAME."'");
		
		// An existing member may have the requested email that doesn't belong to the
		// person who is editing their profile - if so, throw an error
		$existingMember = DataObject::get_one('Member', "Email = '$SQL_email'");
		if($existingMember) {
			if($existingMember->ID != $member->ID) {
				$form->addErrorMessage('Blurb',	_t('SiteSprocketMemberProfile.EMAILEXISTS','Sorry, that email address already exists. Please choose another.'),'bad');
				Director::redirectBack();
				return;
			}
		}
		
		if($member->canEdit()) {
			if(!empty($data['Password']) && !empty($data['ConfirmPassword'])) {
				if($data['Password'] == $data['ConfirmPassword']) {
					$member->Password = $data['Password'];
				} 
				else {
					$form->addErrorMessage("Blurb",	_t('SiteSprocketMemberProfile.PASSNOTMATCH'),"bad");
					Director::redirectBack();
				}
			} 
			else {
				$form->dataFieldByName("Password")->setValue($member->Password);
			}
		}

		$form->saveInto($member);
		$member->write();
		
		if(!$member->inGroup($group))
			$group->Members()->add($member);
		
		Director::redirect('thanks');
	}


	/**
	 * Print the "thank you" page
	 *
	 * Used after saving changes to a member profile.
	 *
	 * @return array Returns the needed data to render the page.
	 */
	function thanks() {
		return array(
			"Form" => _t('SiteSprocketMemberProfile.THANKYOU','You have successfully updated your profile')
		);
	}


	/**
	 * Create a link
	 *
	 * @param string $action Name of the action to link to
	 * @return string Returns the link to the passed action.
	 */
 	function Link($action = null) {
 		return "$this->class/$action";
 	}


	/**
	 * Return the with the passed ID (via URL parameters) or the current user
	 *
	 * @return null|Member Returns the member object or NULL if the member
	 *                     was not found
	 */
 	function Member() {
 		$member = null;
		if(is_numeric($this->urlParams['ID']))
			$member = DataObject::get_by_id('Member', $this->urlParams['ID']);
		else
			$member = Member::currentUser();
		
		return $member;
	}
	
}