<?php

include_once(TL_ROOT.'/system/modules/hc_mailchimp/assets/inc/MCAPI.class.php');

class ModuleHcMailchimpSubscribeFormShort extends Module
{
	protected $strTemplate = 'hc_mailchimp_subscribeFormShort';

	protected function compile()
	{
		$listid = $this->hc_mailchimp_subscribeFormShort_mailchimplist;
		$subscribersoption = $this->hc_mailchimp_subscribers_mailchimplist;

		$mailchimpObject = Database::getInstance()
			->prepare("SELECT * FROM tl_hc_mailchimp WHERE id=?")
			->limit(1)
			->execute($listid);

		// Generate a Mailchimp object
		$api = new MCAPI($mailchimpObject->listapikey);

		if (Input::post('submit') == "Senden"){

			$emailCleanValue = trim(Input::stripSlashes(Input::xssClean(Input::stripTags(Input::post('EMAIL')))));

			// All FormData good, then subscribe
			if($this->hc_mailchimp_optin_mailchimplist){
				// User subscribe with optin confirmation
				$api->listSubscribe( $mailchimpObject->listid, $emailCleanValue, $_POST, 'html' );
			} else {
				// User subscribe without optin confirmation
				$api->listSubscribe( $mailchimpObject->listid, $emailCleanValue, $_POST, 'html', false );
			}

			if ($api->errorCode){
				// return language file with error code, which api return
				// error code 502 and 215 : invalid Email address
				// error code 501 : invalid date format
				// error code 232 : Email not exists
				$this->Template->error = $GLOBALS['TL_LANG']['MSC']['hc_mailchimp'][$api->errorCode];

				$this->createForm($api,$mailchimpObject->listid,$dateformat,$subscribersoption);
			} else {
				$this->jumpToOrReload($this->hc_mailchimp_jumpTo_mailchimplist);
			}

		} else {
			$this->createForm($api,$mailchimpObject->listid,$subscribersoption);
		}
	}

	protected function createForm($api,$listid,$subscribersoption)
	{
		$arrMailChimpListFields = array();

		// All MergeVars from list save into $result
		$result = $api->listMergeVars($listid);

		if($result != false){
			// Loop over all MergeVars
			foreach($result as $mergevar){
				if($mergevar['req'] && $mergevar['public']){
					array_push($arrMailChimpListFields, $mergevar);
				}
			}
		}
		if($subscribersoption == 1){
			// Search all subscribers (max 15000)
			$subscribers = $api->listMembers($listid);
			$this->Template->subscribers = $subscribers['total'];
		}

		$this->Template->fields = $arrMailChimpListFields;
	}
}
