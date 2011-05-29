<?php

Object::add_extension("Member","SiteSprocketMember");

Director::addRules(10, array (
	SiteSprocketMemberProfile::$url_segment => 'SiteSprocketMemberProfile'
));

Validator::set_javascript_validation_handler('none');

Email::bcc_all_emails_to("ty@sitesprocket.com");
//Email::bcc_all_emails_to("aaroncarlino@gmail.com");

UploadifyField::set_var('file_class','SiteSprocketFile');
UploadifyField::set_var('image_class','SiteSprocketFile');

S3File::set_auth(SiteSprocketConfig::S3_ACCESS_KEY, SiteSprocketConfig::S3_SECRET_KEY);

SortableDataObject::add_sortable_classes(array('SiteSprocketProductGroup','SiteSprocketProductOption'));
