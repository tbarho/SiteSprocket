<?php

class AuthorizeNet {
		
	protected 	 $AUTHORIZE_LINK_URL;		
	protected	 $TRANSACTION_KEY;			
	protected	 $LOGIN;					
	protected	 $AUTHORIZE_VERSION;			
	protected	 $TEST_MODE = false;										 
	protected	 $ERROR_NUM = false;			
	protected	 $ERROR_DESC = false;					
	protected	 $AUTHORIZE_NET_DATA_A;			
	protected	 $AUTHORIZE_NET_DATA_S;			
	protected	 $APPROVED = false;			
	protected	 $APPROVAL_CODE = false;			
	protected	 $RESPONSE = "";			
	protected	 $VERIFICATION = false;
	protected	 $TYPE = "AUTH_CAPTURE";					
	protected	 $METHOD = "CC";
				 
	public		$AMOUNT;			
	public		$INVOICE;			
	public		$DESCRIPTION;			
	public		$TAX;					
	public		$F_NAME;			
	public		$L_NAME;			
	public		$ADDRESS;			
	public		$CITY;			
	public		$STATE;			
	public		$ZIP;			
	public		$COUNTRY;			
	public		$PHONE;			
	public		$EMAIL;			
	public		$FAX;					
	public		$CARDNUM;			
	public		$EXPIRATION;			
	public		$CARD_CODE;					
					 
		
	public function __construct($url, $key, $login, $version) {
		$this->AUTHORIZE_LINK_URL = $url;
		$this->TRANSACTION_KEY = $key;
		$this->LOGIN = $login;
		$this->AUTHORIZE_VERSION = $version;					
	}
		
	public function useTestMode() {
		$this->TEST_MODE = "true";
	}
	
	public function authorize_exec() {
		$this->create_post_data();
		$this->transmit_data();
		$this->manage_response();
			
	}
	
		
	public function init($associate_array = null) {
		
		//initialize iables & default values...
		//**********************************************************
		
		$AUTHORIZE_NET_FIELDS['x_tran_key'] = $this->TRANSACTION_KEY;
		$AUTHORIZE_NET_FIELDS['x_amount'] = $this->AMOUNT;
		$AUTHORIZE_NET_FIELDS['x_invoice_num'] = $this->INVOICE;
		$AUTHORIZE_NET_FIELDS['x_type'] = $this->TYPE;
		$AUTHORIZE_NET_FIELDS['x_login'] = $this->LOGIN;
		$AUTHORIZE_NET_FIELDS['x_method'] = $this->METHOD;
		$AUTHORIZE_NET_FIELDS['x_delim_data'] = "TRUE";
		$AUTHORIZE_NET_FIELDS['x_delim_char'] = "|";
		$AUTHORIZE_NET_FIELDS['x_relay_repsonse'] = "FALSE";
		
		//Max length = 255;
		$AUTHORIZE_NET_FIELDS['x_description'] = $this->DESCRIPTION;
		$AUTHORIZE_NET_FIELDS['x_test_request'] = $this->TEST_MODE;
		
		
		$AUTHORIZE_NET_FIELDS['x_card_num'] = $this->CARDNUM;
		$AUTHORIZE_NET_FIELDS['x_exp_date'] = $this->EXPIRATION;
		$AUTHORIZE_NET_FIELDS['x_card_code'] = $this->CARD_CODE;
		
		$AUTHORIZE_NET_FIELDS['x_first_name'] = $this->F_NAME;
		$AUTHORIZE_NET_FIELDS['x_last_name'] = $this->L_NAME;
		
		$AUTHORIZE_NET_FIELDS['x_address'] = $this->ADDRESS;
		$AUTHORIZE_NET_FIELDS['x_city'] = $this->CITY;
		$AUTHORIZE_NET_FIELDS['x_state'] = $this->STATE;
		$AUTHORIZE_NET_FIELDS['x_zip'] = $this->ZIP;
		$AUTHORIZE_NET_FIELDS['x_country'] = $this->COUNTRY;
		$AUTHORIZE_NET_FIELDS['x_phone'] = $this->PHONE;
		$AUTHORIZE_NET_FIELDS['x_email'] = $this->EMAIL;
		$AUTHORIZE_NET_FIELDS['x_email_customer'] = "FALSE";
		$AUTHORIZE_NET_FIELDS['x_fax'] = $this->FAX;
		
		
		//**********************************************************
		//
		//	Make mass updates
		//
		//**********************************************************
		if(is_array($associate_array)) {
			reset($associate_array);
			while (list ($key, $val) = each ($associate_array)) {
				if(isset($AUTHORIZE_NET_FIELDS[$key])) {
					$AUTHORIZE_NET_FIELDS[$key] = $val;
				}
			}
		}
		//**********************************************************
		
		$this->AUTHORIZE_NET_DATA_A = $AUTHORIZE_NET_FIELDS;
	}
		
		
	public function create_post_data() {
		$temp = "";
		$temp_a = $this->AUTHORIZE_NET_DATA_A;
		reset($temp_a);
		while (list ($key, $val) = each($temp_a)) {
			$temp .= $key."=".$val . "&";
		}
		$this->AUTHORIZE_NET_DATA_S = $temp;
	}
		
		
	public function transmit_data() {
		$ch =  curl_init();
		
		curl_setopt($ch, CURLOPT_URL,$this->AUTHORIZE_LINK_URL); 
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt ($ch, CURLOPT_POSTFIELDS, $this->AUTHORIZE_NET_DATA_S);  
		
		//echo "<h3>".$this->AUTHORIZE_NET_DATA_S ."</h3>";
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		if(!$returndata = curl_exec($ch)) {
			
			echo "<h1>".curl_error($ch)."</h1>";
			
			 $this->ERROR_NUM = curl_errno($ch);
			 $this->ERROR_DESC = curl_error($ch);
		} else {
			$this->RESPONSE = $returndata;
		}
		curl_close($ch); 
	}
		
		
		
	
	public function manage_response() {			
		$temp_a = explode("|", $this->RESPONSE);
		//_dump($temp_a);
		if($temp_a[0] == "1") {
			$this->APPROVED = true;
			$this->APPROVAL_CODE = $temp_a[6];
		} else {
			$this->ERROR_DESC = $temp_a[3];
		}			
	}

	public function getErrorNumber() {
		return $this->ERROR_NUM;
	}
	
	public function getError() {
		return $this->ERROR_DESC;
	}
	
	public function isApproved() {
		return $this->APPROVED;
	}
	
	public function getApprovalCode() {
		return $this->APPROVAL_CODE;
	}
	
	public function getResponse() {
		return $this->RESPONSE;
	}
	
	public function getVerification() {
		return $this->VERIFICATION;
	}
	
	public function setType($v) {
		$this->TYPE = $v;
	}
	
	public function getType() {
		return $this->TYPE;
	}
	
	public function setMethod($v) {
		$this->METHOD = $v;
	}
	
	public function getMethod() {
		return $this->METHOD;
	}
	
	public function getResponseCode() {
		$response = explode("|", mysql_escape_string($this->getResponse()));
		return $response[2];
	}
	
	public function getErrorCode() {		
		return ($this->getResponseCode() != 1) ? $this->getResponseCode() : 0;	
	}
}
