<?php

include_once(TL_ROOT.'/system/modules/hc_mailchimp/assets/inc/MCAPI.class.php');

class ModuleHcMailchimpArchive extends Module
{
	protected $strTemplate = 'hc_mailchimp_archive';

	protected function compile()
	{
		$campaigns = array();

		$mailchimpObject = Database::getInstance()
			->prepare("SELECT * FROM tl_hc_mailchimp WHERE id=?")
			->limit(1)
			->execute($this->hc_mailchimp_archive_mailchimplist);

		// Mailchimp API generieren aus MCAPI Klasse
		$api = new MCAPI($mailchimpObject->listapikey);

		// Folder name
		$foldername = $this->hc_mailchimp_archive_mailchimpfolder;

		// Search folderid, when foldername is set in module
		if($foldername != ''){
			$folderlists = $api->folders();

			foreach($folderlists as $folder){
				if($folder['name'] == $foldername){
					$folderid = $folder['folder_id'];
				} else {
					$folderid = 0;
				}
			}
		} else {
			$folderid = 0;
		}

		// Generate archive from apikey
		if($folderid > 0){
			$filter = array(
				'list_id' => $mailchimpObject->listid,
				'folder_id' => $folderid,
				'status' => 'sent'
			);
			$campaignlist = $api->campaigns($filter);
		} else {
			$filter = array(
				'list_id' => $mailchimpObject->listid,
				'status' => 'sent'
			);
			$campaignlist = $api->campaigns($filter);
		}

		if ($api->errorCode){
			$this->Template->archiveerror = $GLOBALS['TL_LANG']['MSC']['hc_mailchimp']['archiverror'];
		} else {
			foreach($campaignlist['data'] as $c){
				$campaigns[] = $c;
			}
			// Felder an Template uebergeben
			$this->Template->fields = $campaigns;
		}


	}
}
