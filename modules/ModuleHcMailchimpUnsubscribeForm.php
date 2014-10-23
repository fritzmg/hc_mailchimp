<?php

include_once(TL_ROOT.'/system/modules/hc_mailchimp/assets/inc/MCAPI.class.php');

class ModuleHcMailchimpUnsubscribeForm extends Module
{
	protected $strTemplate = 'hc_mailchimp_unsubscribeForm';

	protected function compile()
	{
        // Load custom template if necessary
        if (($this->hc_mailchimp_template != $this->strTemplate) && ($this->hc_mailchimp_template != ''))
        {
            $this->strTemplate = $this->hc_mailchimp_template;
            $this->Template = new FrontendTemplate($this->strTemplate);
        }

        $mailchimpObject = Database::getInstance()
			->prepare("SELECT * FROM tl_hc_mailchimp WHERE id=?")
			->limit(1)
			->execute($this->hc_mailchimp_unsubscribeForm_mailchimplist);

		// Mailchimp API generieren aus MCAPI Klasse
		$api = new MCAPI($mailchimpObject->listapikey);

		if (Input::post('submit') == "Senden"){

			$emailCleanValue = trim(Input::post('EMAIL'));

			$api->listUnsubscribe( $mailchimpObject->listid, $emailCleanValue, ($this->hc_mailchimp_delete_mailchimplist ? true : false));

			if ($api->errorCode){
				// return language file with error code, which api return
				$this->Template->error = $GLOBALS['TL_LANG']['MSC']['hc_mailchimp'][$api->errorCode];

				$this->createForm($api,$mailchimpObject->listid);
			} else {
				$this->jumpToOrReload($this->hc_mailchimp_jumpTo_mailchimplist);
			}

		} else {
			$this->createForm($api,$mailchimpObject->listid);
		}
	}

	protected function createForm($api,$listid)
	{
		$arrMailChimpListFields = array();

		// All MergeVars from list save into $result
		$result = $api->listMergeVars($listid);

		if($result != false){
			foreach($result as $mergevar){
				if($mergevar['tag'] == 'EMAIL'){ // Wenn Feld EMAIL tag
					array_push($arrMailChimpListFields, $mergevar); // Feld kommt zur Templateliste
				}
			}
		}

		// Felder an Template uebergeben
		$this->Template->fields = $arrMailChimpListFields;
	}

}
