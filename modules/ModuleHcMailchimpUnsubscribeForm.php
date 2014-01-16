<?php

include_once(TL_ROOT.'/system/modules/hc_mailchimp/assets/inc/MCAPI.class.php');

class ModuleHcMailchimpUnsubscribeForm extends Module
{
	protected $strTemplate = 'hc_mailchimp_unsubscribeForm';

	protected function compile()
	{

		$moduleParams = Database::getInstance()
			->prepare("SELECT * FROM tl_module WHERE id=?")
			->limit(1)
			->execute($this->id);

		// id der angelegten Liste im Backend Mailchimp
		$listid = $moduleParams->hc_mailchimp_unsubscribeForm_mailchimplist;

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
			if($moduleParams->hc_mailchimp_delete_mailchimplist){
				// User in Liste aufnehmen bzw- Opt-In Verfahren starten
				$api->listUnsubscribe($mailchimpObject->listid, $_POST['EMAIL'], true);
			} else {
				// User in Liste aufnehmen ohne Opt-In Verfahren
				$api->listUnsubscribe($mailchimpObject->listid, $_POST['EMAIL']);
			}

			if ($api->errorCode){

				$error = 1;
				$this->Template->error = $error;

				// invalid EmailAdress
				if($api->errorCode == '502'){
					$error502 = 1;
					$this->Template->error502 = $error502;
				}
				// Email_NotExists
				if($api->errorCode == '232'){
					$error232 = 1;
					$this->Template->error232 = $error232;
				}
				// Email_NotExists
				if($api->errorCode == '215'){
					$error215 = 1;
					$this->Template->error215 = $error215;
				}

				$this->createForm($api,$mailchimpObject->listid);

			} else {
				$this->jumpToOrReload($moduleParams->hc_mailchimp_jumpTo_mailchimplist);
			}

		} else {
			$this->createForm($api,$mailchimpObject->listid);
		}

	}

	protected function createForm($api,$listid){
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
