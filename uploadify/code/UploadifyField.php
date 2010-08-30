<?php

class UploadifyField extends FormField
{
	
	public static $allowed_actions = array (
		'upload'
	);
	
	public static $defaults = array (
		'script' => '',
		'uploader' => 'uploadify/javascript/uploadify.swf',
		'scriptAccess' => 'sameDomain',
		'queueSizeLimit' => '999',
		'multi' => true,
		'auto' => true,
		'fileExt' => '*.*',
		'fileDesc' => ' ',
		'cancelImg' => 'uploadify/images/cancel.png',
		'image_class' => 'Image',
		'file_class' => 'File',
		'upload_dir' => 'Uploads',
		'wmode' => '',
		'hideButton' => 'false',
		'width' => '30'
	);
	
	public $fileTypes = array ();
	
	public $extraParams = array ();
	
	public $configuration = array ();
	
	public static function set_var($var, $value) {
		self::$defaults[$var] = $value;
	}
	
	
	/**
	 * Convert a shorthand byte value from a PHP configuration directive to an integer value
	 * @param    string   $value
	 * @return   int
	 */
	public static function convert_bytes($value) {
	    if ( is_numeric( $value ) ) {
	        return $value;
	    } 
	    else {
	        $value_length = strlen( $value );
	        $qty = substr( $value, 0, $value_length - 1 );
	        $unit = strtolower( substr( $value, $value_length - 1 ) );
	        switch ( $unit ) {
	            case 'k':
	                $qty *= 1024;
	                break;
	            case 'm':
	                $qty *= 1048576;
	                break;
	            case 'g':
	                $qty *= 1073741824;
	                break;
	        }
	        return $qty;
	    }
	}	
	
	public function __construct($name, $title = null, $configuration = array(), $form = null) {
		parent::__construct($name, $title, null, $form);
		$this->setVar('sizeLimit', self::convert_bytes(ini_get('upload_max_filesize')));
		$this->setVar('buttonText', _t('Uploadify.BUTTONTEXT','UPLOAD'));
		$this->addParam('PHPSESSID', session_id());
		// A little hack to make things easier in the CMS
		if(is_subclass_of(Controller::curr()->class,"LeftAndMain"))
			$this->allowFolderSelection = true;
	}

	public function getSetting($setting) {
		if(isset($this->configuration[$setting]))
			return $this->configuration[$setting];
		return isset(self::$defaults[$setting]) ? self::$defaults[$setting] : false;
	}
	
	public function addParam($key, $value) {
		$this->extraParams[$key] = $value;
	}
	
	
	public function setVar($setting, $value) {
		$this->configuration[$setting] = $value;
	}
	
	public function setFileTypes($array, $desc = " ") {
		foreach($array as $type) {
			$this->fileTypes[] = strtolower($type);
			$this->fileTypes[] = strtoupper($type);
			$this->setVar('fileDesc', $desc);
		}
	}
	
	public function imagesOnly() {
		$this->setFileTypes(array(
			'jpeg','jpg','gif','png'
		), _t('Uploadify.IMAGES','Images'));
	}
	
	public function imagesPlusPhotoshop() {
		$this->setFileTypes(array(
			'jpeg','jpg','gif','png','psd'
		), _t('Uploadify.IMAGESPSD', 'Images & Photoshop Files'));
	}
	
	public function sitesprocketFiles() {
		$this->setFileTypes(array(
			'jpeg','jpg','gif','png','psd','zip','gz'
		), _t('Uploadify.SITESPROCKETFILES', 'SiteSprocket Files'));
	}
	
	protected function loadFileTypes() {
		if(!empty($this->fileTypes)) {
			$this->setVar('fileExt','*.'.implode(';*.',$this->fileTypes));
		}
	}
	
	public function Metadata() {
		$ret = array();
		foreach(self::$defaults as $setting => $value)
			$ret[] = "$setting : '".$this->getSetting($setting)."'";
		$data = implode(",", $ret);
		if(!empty($this->extraParams)) {
			$data .= ", scriptData : { ";
			$extras = array();
			foreach($this->extraParams as $key => $val) {
				$extras[] = "'$key' : '$val'";
			}
			$params = implode(",", $extras);
			$data .= $params . "}";
		}
		return $data;
	}

	public function FieldHolder() {
		Requirements::javascript("uploadify/javascript/swfobject.js");
		Requirements::javascript("uploadify/javascript/uploadify.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-metadata/jquery.metadata.js");
		Requirements::javascript(THIRDPARTY_DIR."/jquery-livequery/jquery.livequery.js");
		Requirements::javascript("uploadify/javascript/uploadify_init.js");
		Requirements::css("uploadify/css/uploadify.css");
		$this->loadFileTypes();
		return '<div class="uploadify-field"><input type="file" class="uploadify {'.$this->Metadata().'}" name="'.$this->Name().'" id="'.$this->id().'" /></div>';
	}
	
	public function upload() {
		if (isset($_FILES["Filedata"]) && is_uploaded_file($_FILES["Filedata"]["tmp_name"])) {
			$ext = strtolower(end(explode('.', $_FILES['Filedata']['name'])));
			$class = in_array($ext, array('jpg','jpeg','gif','png')) ? $this->getSetting('image_class') : $this->getSetting('file_class');
			$file = new $class();
			$u = new Upload();
			if(!isset($_REQUEST['folder']))
				$_REQUEST['folder'] = $this->getSetting('upload_dir');
			$u->loadIntoFile($_FILES['Filedata'], $file, $_REQUEST['folder']);
			$file->write();			
			echo $file->ID;
		} 
		else {
			echo ' '; // return something or SWFUpload won't fire uploadSuccess
		}
	}
}