<?php

include_once(TL_ROOT.'/system/modules/hc_mailchimp/assets/inc/MCAPI.class.php');

class ModuleHcMailchimpArchive extends Module
{
	protected $strTemplate = 'hc_mailchimp_archive';

	protected function compile()
	{
		$campaigns = array();

		$moduleParams = Database::getInstance()
			->prepare("SELECT * FROM tl_module WHERE id=?")
			->limit(1)
			->execute($this->id);

		// id der angelegten Liste im Backend Mailchimp
		$listid = $moduleParams->hc_mailchimp_archive_mailchimplist;

		$mailchimpObject = Database::getInstance()
			->prepare("SELECT * FROM tl_hc_mailchimp WHERE id=?")
			->limit(1)
			->execute($listid);

		// Mailchimp API generieren aus MCAPI Klasse
		$api = new MCAPI($mailchimpObject->listapikey);

		// Folder name
		$foldername = $moduleParams->hc_mailchimp_archive_mailchimpfolder;

		// Seacr folderid, when foldername is set in module
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
			$this->Template->archiveerror = 1;
		} else {
			foreach($campaignlist['data'] as $c){
				$campaigns[] = $c;
			}
			// Felder an Template uebergeben
			$this->Template->fields = $campaigns;
		}


	}
}
