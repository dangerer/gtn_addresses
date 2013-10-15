<?php
/***************************************************************
*  Copyright notice
*
*  (c) 2013 dietmar angerer <dangerer@gtn-solutions.com>
*  All rights reserved
*
*  This script is part of the TYPO3 project. The TYPO3 project is
*  free software; you can redistribute it and/or modify
*  it under the terms of the GNU General Public License as published by
*  the Free Software Foundation; either version 2 of the License, or
*  (at your option) any later version.
*
*  The GNU General Public License can be found at
*  http://www.gnu.org/copyleft/gpl.html.
*
*  This script is distributed in the hope that it will be useful,
*  but WITHOUT ANY WARRANTY; without even the implied warranty of
*  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*  GNU General Public License for more details.
*
*  This copyright notice MUST APPEAR in all copies of the script!
***************************************************************/
/**
 * [CLASS/FUNCTION INDEX of SCRIPT]
 *
 * Hint: use extdeveval to insert/update function index above.
 */

require_once(PATH_tslib.'class.tslib_pibase.php');


/**
 * Plugin 'GTNAddressearch' for the 'gtn_addresses' extension.
 *
 * @author	dietmar angerer <dangerer@gtn-solutions.com>
 * @package	TYPO3
 * @subpackage	tx_gtnaddresses
 */
class tx_gtnaddresses_pi1 extends tslib_pibase {
	var $prefixId      = 'tx_gtnaddresses_pi1';		// Same as class name
	var $scriptRelPath = 'pi1/class.tx_gtnaddresses_pi1.php';	// Path to this script relative to the extension dir.
	var $extKey        = 'gtn_addresses';	// The extension key.
	var $pi_checkCHash = true;
	
	/**
	 * The main method of the PlugIn
	 *
	 * @param	string		$content: The PlugIn content
	 * @param	array		$conf: The PlugIn configuration
	 * @return	The content that is displayed on the website
	 */
	function main($content, $conf) {
		$this->conf = $conf;
		$this->pi_setPiVarDefaults();
		$this->pi_loadLL();
		
		if ($this->cObj->data["select_key"]!=""){
			$action=$this->cObj->data["select_key"];
		}
		
		if ($action=="DRUPAL_IMPORT"){
			/*tabelle users->fe_users
				uid->uid
				name->username 	
				pass->password
				mail->email
				created->crdate 	
				status->disable (aber 0->1 und 1->0)
				access->lastlogin
			*/
			$result = $GLOBALS['TYPO3_DB']->exec_DELETEquery("fe_users", "uid>1345");
			$result=$GLOBALS['TYPO3_DB']->exec_SELECTquery("*","users","uid>3");
			$content.="Fill table fe_users...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				if($rs["status"] == 0) $rs["status"] = 1;
				else if ($rs["status"] == 1) $rs["status"] = 0;
				$data=array("uid"=>$rs["uid"],"username"=>$rs["name"],"password"=>$rs["pass"],"email"=>$rs["mail"],"crdate"=>$rs["created"],"disable"=>$rs["status"],"lastlogin"=>$rs["access"]);
				
				$GLOBALS['TYPO3_DB']->exec_INSERTquery("fe_users", $data);
			}
			/*tabelle term_data->tx_gtnaddresses_cat
				tid->uid
				vid->cattyp
				name->title
				description->description
				weight->weight*/
			$result = $GLOBALS['TYPO3_DB']->exec_DELETEquery("tx_gtnaddresses_cat", "1");
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("*", "term_data");
			$content .="Fill table tx_gtnadresses_cat...<br/>";	
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$data = array("uid"=>$rs["tid"], "cattype"=>$rs["vid"], "title"=>$rs["name"], "description"=>$rs["description"], "weight"=>$rs["weight"]);
				$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_gtnaddresses_cat", $data);
			}
			/*tabelle vocabulary->tx_gtnaddresses_cattypes
				vid->uid
				name->title
				description->description
				multiple->multiple*/
			$result = $GLOBALS['TYPO3_DB']->exec_DELETEquery("tx_gtnaddresses_cattypes", "1");
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("*", "vocabulary");
			$content.="Fill table tx_gtnaddresses_cattypes...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$data = array("uid"=>$rs["vid"], "title"=>$rs["name"], "description"=>$rs["description"], "multiple"=>$rs["multiple"]);
				$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_gtnaddresses_cattypes", $data);
			}
			
			/*tablle term_node->tx_gtnaddresses_data_cat_mm
				vid->uid_local
				tid->uid_foreign*/
			$result = $GLOBALS['TYPO3_DB']->exec_DELETEquery("tx_gtnaddresses_data_cat_mm", "1");
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("*", "term_node");
			$content.="Fill table tx_gtnaddresses_data_cat_mm...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$data = array("uid_local"=>$rs["vid"], "uid_foreign"=>$rs["tid"]);
				$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_gtnaddresses_data_cat_mm", $data);
			}
			
			//tabelle content_type_psychotherapeutin->tx_gtnaddresses_data (auch manuel mit dump möglich)
			$result = $GLOBALS['TYPO3_DB']->exec_DELETEquery("tx_gtnaddresses_data", "1");
			$result = $GLOBALS['TYPO3_DB'] ->exec_SELECTquery("*","content_type_psychotherapeutin");
			$content.="Transfer table content_type_psychotherapeutin to tx_gtnaddresses_data...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$data=array("uid"=>$rs["vid"],"nachname"=>$rs["field_nachname_value"],"vorname"=>$rs["field_vorname_value"],
				"titel"=>$rs["field_titel_value"],"geschlecht"=>$rs["field_geschlecht_value"],
				"jahrgang"=>$rs["field_jahrgang_value"],"praxis_plz"=>$rs["field_praxis_plz_value"],
				"praxis_ort"=>$rs["field_praxis_ort_value"],"praxis_adresse"=>$rs["field_praxis_adresse_value"],
				"aus_weiterbildung"=>$rs["field_aus_weiterbildungen_value"],"html_format"=>$rs["field_aus_weiterbildungen_format"],
				"praxis2_plz"=>$rs["field_praxis2_plz_value"],"praxis2_ort"=>$rs["field_praxis2_ort_value"],
				"praxis2_adresse"=>$rs["field_praxis2_adresse_value"],"iaus"=>$rs["field_iaus_value"],
				"freiekassenplaetze_abkw"=>$rs["field_freiekassenplaetze_abkw_value"],"freiekassenplaetze2"=>$rs["field_freiekassenplaetze2_value"],
				"vid"=>$rs["vid"], "nid"=>$rs["nid"]);
				
				$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_gtnaddresses_data", $data);
			}
			//von tabellen content_field_....->tx_gtnaddresses_data
			//email
			$result = $GLOBALS['TYPO3_DB'] ->exec_SELECTquery("*","content_field_email");
			$content.="Add fields to tx_gtnaddresses_data from table content_field_email...<br/>";	
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "nid=".$rs["nid"],array("email"=>$rs["field_email_email"])); 
			}
			//von tabellen content_field_....->tx_gtnaddresses_data
			//telefon
			$result = $GLOBALS['TYPO3_DB'] ->exec_SELECTquery("*","content_field_telefon");
			$content.="Add fields to tx_gtnaddresses_data from table content_field_telefon...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "nid=".$rs["nid"],array("telefon"=>$rs["field_telefon_value"])); 
			}
			//von tabellen content_field_....->tx_gtnaddresses_data
			//images
			$result = $GLOBALS['TYPO3_DB'] ->exec_SELECTquery("*","content_field_images");
			$content.="Add fields to tx_gtnaddresses_data from table content_field_images...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "nid=".$rs["nid"],array("images"=>$rs["field_images_data"])); 
			}
			//von tabellen content_field_....->tx_gtnaddresses_data
			//web_adresse
			$result = $GLOBALS['TYPO3_DB'] ->exec_SELECTquery("*","content_field_web_adresse");
			$content.="Add fields to tx_gtnaddresses_data from table content_field_web_adresse...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "nid=".$rs["nid"],array("web_adresse"=>$rs["field_web_adresse_url"])); 
			}
			//tx_gtnaddresses_data feld fe_uid mit users->uid befüllen (relation aus tabelle node)
			//von tabelle node->tx_gtnaddresses_data
			//title->bezeichnung
			//promote->promote
			//sticky->sticky
			$result = $GLOBALS['TYPO3_DB'] ->exec_SELECTquery("*","node");
			$content.="Add fields to tx_gtnaddresses_data from table node...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "nid=".$rs["nid"],array("fe_uid"=>$rs["uid"], "bezeichnung"=>$rs["title"],
														"promote"=>$rs["promote"], "sticky"=>$rs["sticky"])); 
			}
			//tabelle node_revisions->tx_gtnaddresses_data
			//body->beschreibung
			//title->revision_title
			//teaser->teaser
			$result = $GLOBALS['TYPO3_DB'] ->exec_SELECTquery("*","node_revisions");
			$content.="Add fields to tx_gtnaddresses_data from table node_revisions...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "nid=".$rs["nid"],array("beschreibung"=>$rs["body"],
														"revision_titel"=>$rs["title"], "teaser"=>$rs["teaser"])); 
			}
			//signature->signature(tabelle tx_gtnaddresses_data) 
			//data->usersdata 	(tabelle tx_gtnaddresses_data)
			$result = $GLOBALS['TYPO3_DB']->exec_SELECTquery("*", "users");
			$content.="Add fields to tx_gtnaddresses_data from table users...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "fe_uid=".$rs["uid"],array("signature"=>$rs["signature"],
														"usersdata"=>$rs["data"])); 
			}
			//von tabellen files->tx_gtnaddresses_data
			//dateien
			$result = $GLOBALS['TYPO3_DB'] ->exec_SELECTquery("*","files", "status=1");
			$content.="Add fields to tx_gtnaddresses_data from table files...<br/>";
			while($rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result)){
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "fe_uid=".$rs["uid"],array("dateien"=>$rs["filepath"])); 
			}
			/*$content.='
				signature_format->signature_format 	(tabelle tx_gtnaddresses_data)	
			';*/
		}else if($action="LIST"){
			$fields="a.*";
			if ($result=$this->get_address_records($fields)){
				while($rs=mysql_fetch_array($result)){
					$content.=$rs["nachname"]."<br>";
				}
			}else{
				$content.="Für diese Kriterienkombination wurden keine Einträge gefunden...";
			}
		}
	
		return $this->pi_wrapInBaseClass($content);
	}
	
	function get_address_records($fields=array()){
		$sql="SELECT ".$fields." FROM 
		tx_gtnaddresses_data a LEFT JOIN tx_gtnaddresses_data_cat_mm mm ON a.uid=mm.uid_local 
		INNER JOIN tx_gtnaddresses_cat c ON c.uid=mm.uid_foreign INNER JOIN tx_gtnaddresses_cattypes ct ON ct.uid=c.cattype";
		$result=mysql_query($sql);
		if (mysql_num_rows($result)==0) return false;
		else{
			return $result;
		}
	}
	
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gtn_addresses/pi1/class.tx_gtnaddresses_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gtn_addresses/pi1/class.tx_gtnaddresses_pi1.php']);
}

?>