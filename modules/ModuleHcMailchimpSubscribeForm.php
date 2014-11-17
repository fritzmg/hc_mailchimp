<?php

include_once(TL_ROOT.'/system/modules/hc_mailchimp/assets/inc/MCAPI.class.php');

class ModuleHcMailchimpSubscribeForm extends Module
{
	protected $strTemplate = 'hc_mailchimp_subscribeForm';

	protected function compile()
	{
        // Load custom template if necessary
        if (($this->hc_mailchimp_template != $this->strTemplate) && ($this->hc_mailchimp_template != ''))
        {
            $this->strTemplate = $this->hc_mailchimp_template;
            $this->Template = new FrontendTemplate($this->strTemplate);
        }

        $listid = $this->hc_mailchimp_subscribeForm_mailchimplist;
		$dateformat = $this->hc_mailchimp_dateformat_mailchimplist;
		$subscribersoption = $this->hc_mailchimp_subscribers_mailchimplist;

		$mailchimpObject = Database::getInstance()
			->prepare("SELECT * FROM tl_hc_mailchimp WHERE id=?")
			->limit(1)
			->execute($listid);

		// Generate a Mailchimp object
		$api = new MCAPI($mailchimpObject->listapikey);

		if (Input::post('submit') == "Senden"){

			$emailCleanValue = trim(Input::post('EMAIL'));

			$postArray = array();

			foreach($_POST as $key => $value){
				$postArray[$key] = trim(Input::post($key));
			}

			// All FormData good, then subscribe
			$api->listSubscribe( $mailchimpObject->listid, $emailCleanValue, $postArray, 'html', ($this->hc_mailchimp_optin_mailchimplist ? true : false));

			if ($api->errorCode){
				// return language file with error code, which api return
				$this->Template->error = $GLOBALS['TL_LANG']['MSC']['hc_mailchimp'][$api->errorCode];

				$this->createForm($api,$mailchimpObject->listid,$dateformat,$subscribersoption);
			} else {
				$this->jumpToOrReload($this->hc_mailchimp_jumpTo_mailchimplist);
			}

		} else {
			$this->createForm($api,$mailchimpObject->listid,$dateformat,$subscribersoption);
		}

	}

	protected function createForm($api,$listid,$dateformat,$subscribersoption)
	{
		$arrMailChimpListFields = array();

		// All MergeVars from list save into $result
		$result = $api->listMergeVars($listid);

		// Loop over all MergeVars
		foreach($result as $mergevar){
			if($mergevar['public']){
				array_push($arrMailChimpListFields, $mergevar);
			}
		}

		// All groups (checkboxes) from list save into $checkboxes
		$checkboxes = $api->listInterestGroupings($listid);

		if($checkboxes != false){
			foreach($checkboxes as $checkbox){
				array_push($arrMailChimpListFields, $checkbox);
			}
		}

		if($subscribersoption == 1){
			// Search all subscribers (max 15000)
			$subscribers = $api->listMembers($listid);
			$this->Template->subscribers = $subscribers['total'];
		}

		$this->Template->fields = $arrMailChimpListFields;
		$this->Template->dateformat = $dateformat;
	}
}
