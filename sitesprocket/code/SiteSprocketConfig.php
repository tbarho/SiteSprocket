<?php

/**
 * Stores all the global settings for the SiteSprocket application
 *
 * @package SiteSprocket
 * @author Aaron Carlino
 */
abstract class SiteSprocketConfig
{
	/**
	 * @const string The group name for Site Sprocket members
	 */
	 const MEMBER_GROUP_NAME = "SiteSprocket Clients";
	 
	 /**
	  * @const string The group name for Site Sprocket CSRs
	  */
	 const CSR_GROUP_NAME = "SiteSprocket CSR";
	 
	 /**
	  * @const string The "from" address for all emails
	  */ 
	 const EMAIL_FROM_ADDRESS = "SiteSprocket <ty@sitesprocket.com>";
	 
	 const ORDERS_EMAIL_ADDRESS = "ty@sitesprocket.com";
	 
	 const FAIL_EMAIL_ADDRESS = "ty@sitesprocket.com";
	 
	 /**
	  * @const int The default number of records per page in a table
	  */
	 const DEFAULT_PER_PAGE = 10;
	 
	/**
	 * @const string The link to the AuthNet API
	 */
	 const AUTH_NET_URL = "https://secure.authorize.net/gateway/transact.dll";
	 
	/**
	 * @const string The login for the AuthNet API
	 */
	 const AUTH_NET_LOGIN = "6nCWBf4R7v";
	 
	/**
	 * @const string The transaction key to the AuthNet API
	 */
	 const AUTH_NET_KEY = "62R876jqAbGKcw4Q";
	 
	/**
	 * @const string The version of the AuthNet API to use
	 */
	 const AUTH_NET_VERSION = "3.1";
	 
	 /**
	  * @const int The number of future years to show for the expiration date
	  */
	 const EXP_YEAR_RANGE = 10;
	 
	/**
	 * @const string The access key to the Amazon S3 API
	 */
	 const S3_ACCESS_KEY = "AKIAIIA5NINI4TW4ZHMQ";
	 
	/**
	 * @const string The bucket where the uploaded assets will go on Amazon S3
	 */
	 const S3_UPLOAD_BUCKET = "sitesprocketassets";

	/**
	 * @const string The secret key to the Amazon S3 API
	 */	 
	 const S3_SECRET_KEY = "rfzR3rho/cweyv/rYuJcd5dxZHJHFkNIpK4yBf0c";
	
	/**
	 * @var array The list of options for the per-page control
	 */
	 public static $per_page_options = array (
	 	'10',
	 	'20',
	 	'50',
	 	'100'
	 );
}