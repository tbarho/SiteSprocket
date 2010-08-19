<?php

class S3File extends DataObject {

	static $db = array(
		"Name" => "Varchar(255)",
		"Bucket" => "Varchar(255)",
		"URL" => "Varchar(255)"
	);
	
	static $has_one = array(
		"Owner" => "Member"
	);
	
	static $access_key = null;
	
	static $secret_key = null;
	
	static $default_bucket = null;
	
	protected $S3 = null;
	
	protected $uploadBucket = null;
	
	public $fileName = null;
		
	public static function set_auth($access, $secret) {
		self::$access_key = $access;
		self::$secret_key = $secret;
	}
	
	public function __construct($record = null, $isSingleton = false) {
		parent::__construct($record, $isSingleton);
		$this->S3 = new S3(self::$access_key, self::$secret_key);
	}
	
	public function setUploadBucket($bucket) {
		$this->uploadBucket = $bucket;
	}
	
	public function getUploadBucket() {
		return $this->uploadBucket ? $this->uploadBucket : self::$default_bucket;
	}
	
	
	
	public function loadUploaded($filedata) {
		if(!is_array($filedata) || !isset($filedata['tmp_name'])) 
			return false;
			
		$fileTempName = $filedata['tmp_name'];
		
		$fileName = $filedata['name'];
		
		if(!$this->fileName) {
		
			
			$fileName = ereg_replace(' +','-',trim($fileName));
			$fileName = ereg_replace('[^A-Za-z0-9.+_\-]','',$fileName);
			$this->Name = $fileName;
			
		} else {
			$this->Name = $this->fileName . "." . File::get_file_extension($fileName);
		}

		
		
		$bucket = $this->getUploadBucket();
		$this->S3->putBucket($bucket, S3::ACL_PUBLIC_READ);  
		if ($this->S3->putObjectFile($fileTempName, $bucket, $this->Name, S3::ACL_PUBLIC_READ)) { 
			$this->Bucket = $bucket;
			$this->URL = "http://{$bucket}.s3.amazonaws.com/{$this->Name}";
		}
		
		return false;
	}
	
	/**
	 * Return the URL of an icon for the file type
	 */
	public function Icon() {
		$ext = File::get_file_extension($this->Name);
		if(!Director::fileExists(SAPPHIRE_DIR . "/images/app_icons/{$ext}_32.gif")) {
			$ext = $this->appCategory();
		}

		if(!Director::fileExists(SAPPHIRE_DIR . "/images/app_icons/{$ext}_32.gif")) {
			$ext = "generic";
		}

		return SAPPHIRE_DIR . "/images/app_icons/{$ext}_32.gif";
	}
	
	public function appCategory() {
		$ext = File::get_file_extension($this->Name);
		switch($ext) {
			case "aif": case "au": case "mid": case "midi": case "mp3": case "ra": case "ram": case "rm":
			case "mp3": case "wav": case "m4a": case "snd": case "aifc": case "aiff": case "wma": case "apl":
			case "avr": case "cda": case "mp4": case "ogg":
				return "audio";
			
			case "mpeg": case "mpg": case "m1v": case "mp2": case "mpa": case "mpe": case "ifo": case "vob":
			case "avi": case "wmv": case "asf": case "m2v": case "qt":
				return "mov";
			
			case "arc": case "rar": case "tar": case "gz": case "tgz": case "bz2": case "dmg": case "jar":
			case "ace": case "arj": case "bz": case "cab":
				return "zip";
				
			case "bmp": case "gif": case "jpg": case "jpeg": case "pcx": case "tif": case "png": case "alpha":
			case "als": case "cel": case "icon": case "ico": case "ps":
				return "image";
		}
	}
	
	
	
	public function onBeforeWrite() {
		parent::onBeforeWrite();
		$this->OwnerID = Member::currentUserID();
	}

}