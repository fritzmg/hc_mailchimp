<?php

include_once(TL_ROOT.'/system/modules/hc_mailchimp/assets/inc/MCAPI.class.php');

class ModuleHcMailchimpUnsubscribeForm extends Module
{
	protected $strTemplate = 'hc_mailchimp_unsubscribeForm';

	protected function compile()
	{
		$mailchimpObject = Database::getInstance()
			->prepare("SELECT * FROM tl_hc_mailchimp WHERE id=?")
			->limit(1)
			->execute($this->hc_mailchimp_unsubscribeForm_mailchimplist);

		// Mailchimp API generieren aus MCAPI Klasse
		$api = new MCAPI($mailchimpObject->listapikey);

		if (Input::post('submit') == "Senden"){

			$emailCleanValue = trim(Input::stripSlashes(Input::xssClean(Input::stripTags(Input::post('EMAIL')))));

			// If user should delete completly from list
			if($this->hc_mailchimp_delete_mailchimplist){
				// Delete user finally
				$api->listUnsubscribe($mailchimpObject->listid, $emailCleanValue, true);
			} else {
				// Set flag to unsubscribe
				$api->listUnsubscribe($mailchimpObject->listid, $emailCleanValue);
			}

			if ($api->errorCode){
				// return language file with error code, which api return
				// error code 502 and 215 : invalid Email address
				// error code 232 : Email not exists
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

		// Alle MergeVars aus der Liste in $result speichern
		$result = $api->listMergeVars($listid);

		// Abfrage ob wirklich etwas gefunden wurde ueber apikey und listid

		// Schleife ueber alle MergeVars
		foreach($result as $mergevar){
			if($mergevar['tag'] == 'EMAIL'){ // Wenn Feld EMAIL tag
				array_push($arrMailChimpListFields, $mergevar); // Feld kommt zur Templateliste
			}
		}

		// Felder an Template uebergeben
		$this->Template->fields = $arrMailChimpListFields;
	}

}
