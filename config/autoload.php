<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2013 Leo Feyer
 *
 * @package hc_mailchimp
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Modules
	'ModuleHcMailchimpArchive'            => 'system/modules/hc_mailchimp/modules/ModuleHcMailchimpArchive.php',
	'ModuleHcMailchimpSubscribeForm'      => 'system/modules/hc_mailchimp/modules/ModuleHcMailchimpSubscribeForm.php',
	'ModuleHcMailchimpSubscribeFormShort' => 'system/modules/hc_mailchimp/modules/ModuleHcMailchimpSubscribeFormShort.php',
	'ModuleHcMailchimpUnsubscribeForm'    => 'system/modules/hc_mailchimp/modules/ModuleHcMailchimpUnsubscribeForm.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'hc_mailchimp_archive'            => 'system/modules/hc_mailchimp/templates',
	'hc_mailchimp_subscribeForm'      => 'system/modules/hc_mailchimp/templates',
	'hc_mailchimp_subscribeFormShort' => 'system/modules/hc_mailchimp/templates',
	'hc_mailchimp_unsubscribeForm'    => 'system/modules/hc_mailchimp/templates',
));
