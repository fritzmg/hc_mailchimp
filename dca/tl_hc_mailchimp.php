<?php

/**
 * Table tl_hc_mailchimp
 */
$GLOBALS['TL_DCA']['tl_hc_mailchimp'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'enableVersioning'            => true,
		'sql' => array
		(
			'keys' => array
			(
				'id' => 'primary'
			)
		),
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 2,
			'fields'                  => array('listname'),
			'flag'                    => 1,
			'panelLayout'             => 'sort,search,limit'
		),
		'label' => array
		(
			'fields'                  => array('listname','listapikey','listid'),
			'format'                  => '%s',
		),
		'global_operations' => array
		(
			'all' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
				'href'                => 'act=select',
				'class'               => 'header_edit_all',
				'attributes'          => 'onclick="Backend.getScrollOffset()" accesskey="e"'
			)
		),
		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if(!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\'))return false;Backend.getScrollOffset()"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif',
				'attributes'          => 'style="margin-right:3px"'
			),
		)
	),

	// Palettes
	'palettes' => array
	(
		'default'                     => '{listlegend},listname;{mailchimplegend},listapikey,listid'
	),

	// Fields
	'fields' => array
	(
		'id' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL auto_increment"
		),
		'tstamp' => array
		(
			'sql'                     => "int(10) unsigned NOT NULL default '0'"
		),
		'listname' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['listname'],
			'explanation'             => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['listname_explanation'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'sorting'                 => true,
			'flag'                    => 1,
			'filter'                  => true,
			'search'                  => true,
			'eval'                    => array('mandatory'=>true, 'unique'=>true, 'maxlength'=>128),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'listapikey' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['listapikey'],
			'explanation'             => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['listapikey_explanation'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
		'listid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['listid'],
			'explanation'             => &$GLOBALS['TL_LANG']['tl_hc_mailchimp']['listid_explanation'],
			'inputType'               => 'text',
			'exclude'                 => true,
			'eval'                    => array('mandatory'=>true, 'maxlength'=>128),
			'sql'                     => "varchar(128) NOT NULL default ''"
		),
	)
);
