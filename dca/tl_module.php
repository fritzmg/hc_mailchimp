<?php
$GLOBALS['TL_DCA']['tl_module']['palettes']['hc_mailchimp_subscribeFormShort'] =
	'{title_legend},name,headline,type;{list_legend},hc_mailchimp_subscribeFormShort_mailchimplist;{jumpTo_legend},hc_mailchimp_jumpTo_mailchimplist;
	{option_legend},hc_mailchimp_optin_mailchimplist,hc_mailchimp_dateformat_mailchimplist,hc_mailchimp_subscribers_mailchimplist;{template_legend:hide},hc_mailchimp_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['hc_mailchimp_subscribeForm'] =
	'{title_legend},name,headline,type;{list_legend},hc_mailchimp_subscribeForm_mailchimplist;{jumpTo_legend},hc_mailchimp_jumpTo_mailchimplist;
	{option_legend},hc_mailchimp_optin_mailchimplist,hc_mailchimp_dateformat_mailchimplist,hc_mailchimp_subscribers_mailchimplist;{template_legend:hide},hc_mailchimp_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['hc_mailchimp_unsubscribeForm'] =
	'{title_legend},name,headline,type;{list_legend},hc_mailchimp_unsubscribeForm_mailchimplist;{jumpTo_legend},hc_mailchimp_jumpTo_mailchimplist;
	{delete_legend},hc_mailchimp_delete_mailchimplist;{template_legend:hide},hc_mailchimp_template;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['palettes']['hc_mailchimp_archive'] =
	'{title_legend},name,headline,type;{list_legend},hc_mailchimp_archive_mailchimplist,hc_mailchimp_archive_mailchimpfolder;
	{template_legend:hide},hc_mailchimp_template;{expert_legend:hide},guests,cssID,space';

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_subscribeFormShort_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_tp_list'],
	'default'                 => '',
	'exclude'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_hc_mailchimp.listname',
	'search'                  => true,
	'sorting'                 => true,
	'eval'                    => array('mandatory'=>true),
	'sql'                     => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_subscribeForm_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_tp_list'],
	'default'                 => '',
	'exclude'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_hc_mailchimp.listname',
	'search'                  => true,
	'sorting'                 => true,
	'eval'                    => array('mandatory'=>true),
	'sql'                     => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_unsubscribeForm_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_tp_list'],
	'default'                 => '',
	'exclude'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_hc_mailchimp.listname',
	'search'                  => true,
	'sorting'                 => true,
	'eval'                    => array('mandatory'=>true),
	'sql'                     => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_optin_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_tp_optin'],
	'inputType'               => 'checkbox',
	'eval'                    => array('mandatory'=>false, 'isBoolean' => true),
	'sql'                     => "varchar(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_dateformat_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_tp_dateformat'],
	'inputType'               => 'select',
	'options'                 => array('DD-MM-YYYY','MM-DD-YYYY'),
	'sql'                     => "varchar(10) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_delete_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_tp_delete'],
	'inputType'               => 'checkbox',
	'eval'                    => array('mandatory'=>false, 'isBoolean' => false),
	'sql'                     => "varchar(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_jumpTo_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_tp_jumpTo'],
	'exclude'                 => true,
	'inputType'               => 'pageTree',
	'eval'                    => array('fieldType'=>'radio','mandatory'=>true),
	'sql'                     => "int(10) unsigned NOT NULL default '0'"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_archive_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_tp_list'],
	'default'                 => '',
	'exclude'                 => true,
	'inputType'               => 'select',
	'foreignKey'              => 'tl_hc_mailchimp.listname',
	'search'                  => true,
	'sorting'                 => true,
	'eval'                    => array('mandatory'=>true),
	'sql'                     => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_archive_mailchimpfolder'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_folder'],
	'default'                 => '',
	'exclude'                 => true,
	'inputType'               => 'text',
	'sql'                     => "varchar(128) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_subscribers_mailchimplist'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_subscribers_mailchimplist'],
	'inputType'               => 'checkbox',
	'eval'                    => array('mandatory'=>false, 'isBoolean' => false),
	'sql'                     => "varchar(1) NOT NULL default ''"
);

$GLOBALS['TL_DCA']['tl_module']['fields']['hc_mailchimp_template'] = array
(
    'label'                   => &$GLOBALS['TL_LANG']['tl_module']['hc_mailchimp_template'],
    'exclude'                 => true,
    'inputType'               => 'select',
    'options_callback'        => array('tl_module_hc_mailchimp', 'getMailchimpTemplates'),
    'eval'                    => array('tl_class'=>'w50'),
    'sql'                     => "varchar(64) NOT NULL default ''"
);

/**
 * Class tl_module_hc_mailchimp
 */
class tl_module_hc_mailchimp extends Backend
{
    /**
     * Return all templates of active dc-record-type
     * @return array
     */
    public function getMailchimpTemplates(DataContainer $dc)
    {
        return $this->getTemplateGroup($dc->activeRecord->type);
    }
}

?>