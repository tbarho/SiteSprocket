<?php

class ARBPayment {

	static $host = "apitest.authorize.net";
	
	static $path = "/xml/v1/request.api";
	
	public $loginname;
	
	public $key;
	
	public $amount;
	
	public $refID;
	
	public $name;
	
	public $length;
	
	public $unit;
	
	public $startDate;
	
	public $totalOccurrences;
	
	public $trialOccurrences = 0;
	
	public $trialAmount = 0;
	
	public $cardNumber;
	
	public $expirationDate;
	
	public $firstName;
	
	public $lastName;
	
	
	public function __construct($loginname, $key) {
		$this->loginname = $loginname;
		$this->transactionkey = $key;	
	}
	
	protected static function send_request_via_fsockopen($content) {
		$posturl = "ssl://" . self::$host;
		$header = "Host: ".self::$host."\r\n";
		$header .= "User-Agent: PHP Script\r\n";
		$header .= "Content-Type: text/xml\r\n";
		$header .= "Content-Length: ".strlen($content)."\r\n";
		$header .= "Connection: close\r\n\r\n";
		$fp = fsockopen($posturl, 443, $errno, $errstr, 30);
		if (!$fp) {
			$response = false;
		}
		else {
			error_reporting(E_ERROR);
			fputs($fp, "POST ".self::$path."  HTTP/1.1\r\n");
			fputs($fp, $header.$content);
			fwrite($fp, $out);
			$response = "";
			while (!feof($fp)) {
				$response = $response . fgets($fp, 128);
			}
			fclose($fp);
			error_reporting(E_ALL ^ E_NOTICE);
		}
		return $response;
	}
	
	protected static function send_request_via_curl($content) {
		$posturl = "https://" . self::$host . self::$path;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $posturl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array("Content-Type: text/xml"));
		curl_setopt($ch, CURLOPT_HEADER, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $content);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
		$response = curl_exec($ch);
		return $response;
	}
	
	
	protected static function parse_return($content) {
	die(var_dump($content));
		$refId = self::substring_between($content,'<refId>','</refId>');
		$resultCode = self::substring_between($content,'<resultCode>','</resultCode>');
		$code = self::substring_between($content,'<code>','</code>');
		$text = self::substring_between($content,'<text>','</text>');
		$subscriptionId = self::substring_between($content,'<subscriptionId>','</subscriptionId>');
		return array ($refId, $resultCode, $code, $text, $subscriptionId);
	}
	
	//helper function for parsing response
	protected static function substring_between($haystack,$start,$end) {
		if (strpos($haystack,$start) === false || strpos($haystack,$end) === false) {
			return false;
		} 
		else {
			$start_position = strpos($haystack,$start)+strlen($start);
			$end_position = strpos($haystack,$end);
			return substr($haystack,$start_position,$end_position-$start_position);
		}
	}
	
	public function submit() {
		$content =
		        "<?xml version=\"1.0\" encoding=\"utf-8\"?>" .
		        "<ARBCreateSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">" .
		        "<merchantAuthentication>".
		        "<name>" . $this->loginname . "</name>".
		        "<transactionKey>" . $this->transactionkey . "</transactionKey>".
		        "</merchantAuthentication>".
				"<refId>" . $this->refID . "</refId>".
		        "<subscription>".
		        "<name>" . $this->name . "</name>".
		        "<paymentSchedule>".
		        "<interval>".
		        "<length>". $this->length ."</length>".
		        "<unit>". $this->unit ."</unit>".
		        "</interval>".
		        "<startDate>" . $this->startDate . "</startDate>".
		        "<totalOccurrences>". $this->totalOccurrences . "</totalOccurrences>".
		        "<trialOccurrences>". $this->trialOccurrences . "</trialOccurrences>".
		        "</paymentSchedule>".
		        "<amount>". $this->amount ."</amount>".
		        "<trialAmount>" . $this->trialAmount . "</trialAmount>".
		        "<payment>".
		        "<creditCard>".
		        "<cardNumber>" . $this->cardNumber . "</cardNumber>".
		        "<expirationDate>" . $this->expirationDate . "</expirationDate>".
		        "</creditCard>".
		        "</payment>".
		        "<billTo>".
		        "<firstName>". $this->firstName . "</firstName>".
		        "<lastName>" . $this->lastName . "</lastName>".
		        "</billTo>".
		        "</subscription>".
		        "</ARBCreateSubscriptionRequest>";

		$response = self::send_request_via_curl($content);
		if ($response) {
			return self::parse_return($response);
		}
		return false;
	}
	
	public function cancel($subscriptionID) {
		$content =
		        "<?xml version=\"1.0\" encoding=\"utf-8\"?>".
		        "<ARBCancelSubscriptionRequest xmlns=\"AnetApi/xml/v1/schema/AnetApiSchema.xsd\">".
		        "<merchantAuthentication>".
		        "<name>" . $this->loginname . "</name>".
		        "<transactionKey>" . $this->transactionkey . "</transactionKey>".
		        "</merchantAuthentication>" .
		        "<subscriptionId>" . $subscriptionID . "</subscriptionId>".
		        "</ARBCancelSubscriptionRequest>";
		$response = self::send_request_via_curl($content);
		if ($response) {
			return self::parse_return($response);
		}
		return false;

	}
}