<?php

global $project;
$project = 'mysite';

global $databaseConfig;
$databaseConfig = array(
	"type" => 'MySQLDatabase',
	"server" => 'localhost', 
	"username" => 'root', 
	"password" => 'root', 
	"database" => 'sitesprocket24',
	"path" => '',
);

MySQLDatabase::set_connection_charset('utf8');

// This line set's the current theme. More themes can be
// downloaded from http://www.silverstripe.org/themes/
SSViewer::set_theme('sitesprocket');

// Set the site locale
i18n::set_locale('en_US');

// enable nested URLs for this site (e.g. page/sub-page/)
SiteTree::enable_nested_urls();

// Force WWW
//Director::forceWWW();

// Force environment to Dev
Director::set_environment_type('dev');


// Temporary fix for Cookie Issue
Cookie::set_report_errors(false);