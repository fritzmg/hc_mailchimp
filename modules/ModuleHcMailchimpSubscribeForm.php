<?php

include_once(TL_ROOT.'/system/modules/hc_mailchimp/assets/inc/MCAPI.class.php');

class ModuleHcMailchimpSubscribeForm extends Module
{
	protected $strTemplate = 'hc_mailchimp_subscribeForm';

	protected function compile()
	{
		$moduleParams = Database::getInstance()
			->prepare("SELECT * FROM tl_module WHERE id=?")
			->limit(1)
			->execute($this->id);

		$listid = $moduleParams->hc_mailchimp_subscribeForm_mailchimplist;
		$dateformat = $moduleParams->hc_mailchimp_dateformat_mailchimplist;
		$subscribersoption = $moduleParams->hc_mailchimp_subscribers_mailchimplist;

		$mailchimpObject = Database::getInstance()
			->prepare("SELECT * FROM tl_hc_mailchimp WHERE id=?")
			->limit(1)
			->execute($listid);

		// Mailchimp API generieren aus MCAPI Klasse
		$api = new MCAPI($mailchimpObject->listapikey);

		if (isset($_POST['submit'])){

			// Texteingabe filtern
			function daten_reiniger($inhalt){
				if(is_array($inhalt)){
					foreach($inhalt as $key => $value){
						$inhalt[$key] = daten_reinigen($value);
					}
				}else {
					if(!empty($inhalt)){
						//HTML- und PHP-Code entfernen
						$inhalt = strip_tags($inhalt);
						//Umlaute und Sonderzeichen in HTML-Schreibweise umwandeln
						$inhalt = htmlspecialchars($inhalt);
						//Entfernt überflüssige Zeichen
						//Anfang und Ende einer Zeichenkette
						$inhalt = trim($inhalt);
						//Backslashes entfernen
						$inhalt = stripslashes($inhalt);
					}
				}
				return $inhalt;
			}

			//Schreibarbeit durch Umwandeln sparen
			foreach($_POST as $key=>$element){
				if($key != "submit"){
					//Eingabe filtern
					$_POST[$key] = daten_reiniger($element);
				}
			}

			// All FormData good, then subscribe
			if($moduleParams->hc_mailchimp_optin_mailchimplist){
				// User in Liste aufnehmen bzw- Opt-In Verfahren starten
				$api->listSubscribe( $mailchimpObject->listid, $_POST['EMAIL'], $_POST, 'html' );
			} else {
				// User in Liste aufnehmen ohne Opt-In Verfahren
				$api->listSubscribe( $mailchimpObject->listid, $_POST['EMAIL'], $_POST, 'html', false );
			}

			if ($api->errorCode){

				$error = 1;
				$this->Template->error = $error;

				// invalid EmailAdress
				if($api->errorCode == '502'){
					$error502 = 1;
					$this->Template->error502 = $error502;
				}
				// Email already exists
				if($api->errorCode == '214'){
					$error214 = 1;
					$this->Template->error214 = $error214;
				}
				// Requierd MergeTag
				if($api->errorCode == '250'){
					$error250 = 1;
					$this->Template->error250 = $error250;
				}

				// Formular wird erstellt
				$this->createForm($api,$mailchimpObject->listid,$dateformat,$subscribersoption);

			} else {
				// Jump to the Follwsite
				$this->jumpToOrReload($moduleParams->hc_mailchimp_jumpTo_mailchimplist);
			}

		} else {
			$this->createForm($api,$mailchimpObject->listid,$dateformat,$subscribersoption);
		}

	}

	protected function createForm($api,$listid,$dateformat,$subscribersoption){
		$arrMailChimpListFields = array();

		// Alle MergeVars aus der Liste in $result speichern
		$result = $api->listMergeVars($listid);

		// Abfrage ob wirklich etwas gefunden wurde ueber apikey und listid

		// Schleife ueber alle MergeVars
		foreach($result as $mergevar){
			if($mergevar['public']){ // Wenn Feld sichtbar
				array_push($arrMailChimpListFields, $mergevar); // Feld kommt zur Templateliste
			}
		}

		// Alle Groups (checkboxes) aus der Liste in $checkboxes speichern
		$checkboxes = $api->listInterestGroupings($listid);

		if($checkboxes != false){
			foreach($checkboxes as $checkbox){
				array_push($arrMailChimpListFields, $checkbox); // Feld kommt zur Templateliste
			}
		}

		if($subscribersoption == 1){
			// Alle Subscribers suchen (max 15000)
			$subscribers = $api->listMembers($listid);
			$this->Template->subscribers = $subscribers['total'];
		}

		// Felder an Template uebergeben
		$this->Template->fields = $arrMailChimpListFields;
		$this->Template->dateformat = $dateformat;
	}
}
