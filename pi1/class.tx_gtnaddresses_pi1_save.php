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
require_once(PATH_t3lib."class.t3lib_basicfilefunc.php");

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
	var $markerArray = array();
	var $userid;
	var $bildpfad;
	var $meldung="";
	
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
		$this->userid = $GLOBALS['TSFE']->fe_user->user['uid'];
		$this->bildpfad="uploads/tx_gtnaddresses";
		$this->pi_USER_INT_obj = 1;	// Configuring so caching is not expected. This value means that no cHash params are ever set. We do this, because it's a USER_INT object!
		if (!empty($conf["css_File"])) 
			$GLOBALS['TSFE']->additionalHeaderData[$this->extKey] = '<link type="text/css" rel="stylesheet" media="all" href="'.$conf["css_File"].'" />';;		
		
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
				"titel"=>$rs["field_titel_value"],"geschlecht"=>$this->conv_geschlecht_code($rs["field_geschlecht_value"]),
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
				$filename = str_replace("files", "uploads", $rs["filepath"]);
				$filename = str_replace("images", "tx_gtnaddresses", $filename);
				$GLOBALS['TYPO3_DB']->exec_UPDATEquery("tx_gtnaddresses_data", "fe_uid=".$rs["uid"],array("dateien"=>$filename)); 
			}
			/*$content.='
				signature_format->signature_format 	(tabelle tx_gtnaddresses_data)	
			';*/
		}else if($action=="LIST"){
			$inhalt=$this->cObj->FileResource("fileadmin/ooelp/tmpl/listperson.html");
			$inhalt=$this->cObj->getSubpart($inhalt,"###DOCUMENT###");
			$tmplrow=$this->cObj->getSubpart($inhalt,"###ADDRESSRS###");
			$tmplfilter=$this->cObj->getSubpart($inhalt,"###FILTERS###");

			$filters = array();
			$filters = t3lib_div::_GP("filters");
			//print_r($filters);
			foreach ($filters as $filter=>$val) 
				if (trim($val)=="") unset($filters[$filter]);
			if (count($filters)>0) {
				$where .= " WHERE 1=1";
				$inner_i = 0;
				foreach ($filters as $filter=>$val)
					if (($val<>'') and ($val<>'all')) {
						if (strpos($filter, 'select_')!==false) {
							$typecat_uid = substr($filter, 7);
							if ($typecat_uid > 0) {
								$inner_i++;
								$add_inner .= " INNER JOIN tx_gtnaddresses_data_cat_mm mm".$inner_i." ON a.uid=mm".$inner_i.".uid_local ";
								$cat_where .= " AND mm".$inner_i.".uid_foreign = $val";
								}
							}
						else
						switch($filter) {
							case 'bezirk': $where .= " AND c.uid = $val";
								break;
							case 'plz': $where .= " AND (a.praxis_plz LIKE '%".$val."%' OR a.praxis2_plz LIKE '%".$val."%')";
								break;
							case 'ort': $where .= " AND (a.praxis_ort LIKE '%".$val."%' OR a.praxis2_ort LIKE '%".$val."%')";							
								break;
							case 'name': 
							case 'name2':
										$where .= " AND (a.nachname LIKE '%".$val."%' OR a.vorname LIKE '%".$val."%')";
								break;							
							case 'geschlecht': $where .= " AND a.geschlecht=".$val;							
								break;														
							case 'kassenplatze': if ($val>0) $where .= " AND (a.freiekassenplaetze_abkw>0 OR a.freiekassenplaetze2=$val)";													
								break;																					
							case 'stichwort': $where .= " AND (a.aus_weiterbildung LIKE '%".$val."%' OR a.beschreibung LIKE '%".$val."%' OR a.teaser LIKE '%".$val."%')";
								break;							
							default: $where .= " and 1=1";
						}
					}
				$cat_where = substr($cat_where, 4);
				if ($cat_where<>"") $where = $where." AND (".$cat_where.") ";
//				echo $where;			
			};
			
			$fields="a.*";
			$i=1;
			if ($result=$this->get_address_records($fields,$add_inner,$where,"group by a.uid order by a.promote DESC,a.sticky DESC, a.praxis_plz,a.nachname")){
				$imgTSConfig = array();
				$imgTSConfig['file.']['width'] = $this->conf['ListImageMaxWidth'].'m';
				$imgTSConfig['file.']['height'] = $this->conf['ListImageMaxHeight'].'m';					
				$plz="init";
				while($rs=mysql_fetch_array($result)){
					$markerArray=array();$subpartArray=array();
					$markerArray["###aname###"]=$this->get_fullname($rs["titel"],$rs["vorname"],$rs["nachname"]);
					$markerArray["###linkperson###"]=$this->pi_getPageLink($this->conf["pid_addresses_persondetail"],"",array("uid"=>$rs["uid"],"no_cache"=>1));
					$markerArray["###linkedituser###"]=$this->pi_getPageLink($this->conf["pid_addresses_create"],"",array("uid"=>$rs["uid"],"no_cache"=>1));
					$markerArray["###praxis_plz###"]=$rs["praxis_plz"];
					$markerArray["###praxis_ort###"]=$rs["praxis_ort"];
					$markerArray["###praxis_adresse###"]=$rs["praxis_adresse"];
					$markerArray["###telefon###"]=$rs["telefon"];
					$markerArray["###email###"]='<a href="mailto:'.$rs['email'].'">'.$rs["email"].'</a>';
					$markerArray["###web_adresse###"]='<a href="http://'.$rs['web_adresse'].'">'.$rs["web_adresse"].'</a>';
					$fileimages	= explode(",",$rs["dateien"]);
					
					if (($fileimages[0]) and (getimagesize($fileimages[0]))) {
						$imgTSConfig['file'] = $fileimages[0];
						$markerArray["###image###"].= $this->cObj->IMAGE($imgTSConfig);					
					}
					else{	
						$markerArray["###image###"] = "";
						$subpartArray["###IMAGEDIV###"]="";
					}
					$markerArray["###number###"]=$i;
					if ($i&1) $markerArray["###parity###"]="odd";
					else $markerArray["###parity###"]="even";
					if (!$this->has_capability("admin") && !$this->has_capability("FEAdmins")) $subpartArray["###EDITICON###"]="";
					
					if($plz!=$rs["praxis_plz"]) {
						if($plz!="init") $content_rows .='</ul>';
						$content_rows .='<h3>'.$rs["praxis_plz"].'</h3><ul>';
					}
					$plz=$rs["praxis_plz"];
					$content_rows .= $this->cObj->substituteMarkerArrayCached($tmplrow,$markerArray,$subpartArray,array());
					//$content.=$tmplrowcur;
					$i++;
				}
				$content_rows .='</ul>';
				$subpartArray["###FILTERS###"] = $this->getFiltersForm($tmplfilter,$filters);
				$subpartArray["###ADDRESSRS###"] = $content_rows;
				$content .= $this->cObj->substituteMarkerArrayCached($inhalt,$markerArray,$subpartArray,array());
				
			}else{
				$content .= $this->getFiltersForm($tmplfilter,$filters);
				$content.=$this->pi_getLL('no_results');
			}
		}else if($action=="CREATE"){
			$inhalt=$this->cObj->FileResource("fileadmin/ooelp/tmpl/createperson.html");
			$params=array("no_cache"=>"1");
			$this->markerArray["###action###"]=$this->pi_getPageLink($GLOBALS["TSFE"]->id,"",$params);
			if ($this->piVars["deldatei"]!="" && intval($this->piVars["uid"])>0){
				$this->update_datei_in_db("tx_gtnaddresses_data",$this->clean_param($this->piVars["deldatei"],"PARAM_PATH"),intval($this->piVars["uid"]));
			}
			$uid=$this->handleform("tx_gtnaddresses_data");
			$this->markerArray["###uid###"]=$uid;
			$this->markerArray["###meldung###"]=$this->format_meldung($this->meldung);
								
			// bezirks list
			$inhalt=$this->cObj->getSubpart($inhalt,"###DOCUMENT###");
			$tmpl_bezirklist = $this->cObj->getSubpart($inhalt, "###BEZIRK###"); 
			$res_b = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('c.uid as cuid, c.title as title', 'tx_gtnaddresses_cat c', 'title<>"" AND cattype=11','','title');
			foreach ($res_b as $b) 
				$arr_b[$b["cuid"]] = $b["title"];
			$res_curr_b = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('uid_foreign', 'tx_gtnaddresses_data_cat_mm', 'uid_local='.$uid,'','sorting');	
			foreach ($res_curr_b as $cb) 
				$arr_curr_b[] = $cb["uid_foreign"];
			$subpartArray["###BEZIRK###"] = $this->get_select_list($tmpl_bezirklist, $arr_b, $arr_curr_b, "", 1);
			
			$inhalt=$this->cObj->substituteMarkerArrayCached($inhalt,$this->markerArray,$subpartArray,array());
			$content=$inhalt;	
		}else if($action=="PERSONDETAIL"){		
			$inhalt=$this->cObj->FileResource("fileadmin/ooelp/tmpl/persondetail.html");
			$inhalt=$this->cObj->getSubpart($inhalt,"###DOCUMENT###");
			
			$id = t3lib_div::_GP("uid");
			if ((empty($id)) or ($id<=0)) {
				header('Location: ' . t3lib_div::locationHeaderUrl($this->pi_getPageLink($this->conf["pid_addresses_list"],"",array())));die;
			};
			
			$content .= $this->person_detail($inhalt, $id);	
		}	
				
				
				
		return $this->pi_wrapInBaseClass($content);
	}
	function get_fullname($title,$firstname,$lastname){
		$name=trim($this->add_leer($title).$this->add_leer($firstname).$this->add_leer($lastname));
		return $name;
	}
	function add_leer($wert,$vorne=0){
		if (!empty($wert)){
			if ($vorne==1) $wert=" ".$wert;
			else $wert.=" ";
		}
		return $wert;
	}
	function format_meldung($meldung){
		if ($meldung!="") $meldung='<h2 class="meldung">'.$meldung.'</h2>';
		return $meldung;
	}
	function get_address_records($fields=array(),$add_from,$where=" WHERE 1=1",$groupby=""){
		$sql="SELECT ".$fields." FROM tx_gtnaddresses_data a 
			LEFT JOIN tx_gtnaddresses_data_cat_mm mm ON a.uid=mm.uid_local 
			INNER JOIN tx_gtnaddresses_cat c ON c.uid=mm.uid_foreign 
			INNER JOIN tx_gtnaddresses_cattypes ct ON ct.uid=c.cattype".
			$add_from.$where." ".$groupby;
		//echo "<br><br><br><br>".$sql;
		$result=mysql_query($sql);
		if (mysql_num_rows($result)==0) return false;
		else{
			return $result;
		}
	}
	function update_datei_in_db($table,$datei_to_delete,$uid){
		$cont=explode("B@@B",$datei_to_delete);
		echo "<br><br><br><br><br>".$cont[0];
		unlink($cont[0]);
		$recs = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow("*", $table, "uid=".$uid);
		$oldval=$recs[$cont[1]];
		$newval=$this->delete_value_from_list($cont[0],$oldval);
		$result=$GLOBALS['TYPO3_DB']->exec_UPDATEquery($table, "uid=".$uid,array($cont[1]=>$newval));
	}
	function delete_value_from_list($wert,$liste){
		$liste=str_replace($wert,"",$liste);
		$liste=str_replace(",,",",",$liste);
		$liste=preg_replace("/^,/","",$liste);
		$liste=preg_replace("/,$/","",$liste);
		return $liste;
	}
	function has_capability($gruppe){
		if ($GLOBALS["TSFE"]->fe_user->user["usergroup"]=="") return false;
		else{
			$querygroup="SELECT title FROM fe_groups";
			$querygroup.=" WHERE (uid=".str_replace(","," OR uid=",$GLOBALS["TSFE"]->fe_user->user["usergroup"]).")";
			$querygroup.=" AND (title='".$gruppe."'";
			if ($gruppe!="admin") $querygroup.=" OR title='alle'";
			$querygroup.=")";
			
			$result = mysql_query($querygroup);
			if (mysql_num_rows($result)>0) return true; //feld hidden bekommt wert 0	
			else return false; //feld hidden bekommt wert1
		}
	}
	function handleform($tablename,$uidt=0){
		t3lib_div::loadTCA($tablename);
		$tca=$GLOBALS['TCA'][$tablename];
		//t3lib_div::debug($tca);
		//print_r($this->piVars);
		
		$felder=explode(",",$tca["interface"]["showRecordFieldList"]);
		$data=array();
		if ($this->piVars["uid"]!="" || t3lib_div::_GP("uid")!="" || $uidt>0){
			
			if ($this->piVars["uid"]!=""){
					$uid=intval($this->piVars["uid"]);
					
					//t3lib_div::debug($this->piVars);
					/*
					 //create query to test in phpMyAdmin
					foreach ($this->piVars[data] as $key=>$value){
							$data[$key]=$value;
							$keys.=",".$key;
							$vals.=',"'.$value.'"';
					}
					$vals=preg_replace("/^,/","",$vals);
					$keys=preg_replace("/^,/","",$keys);
		
					$sql='INSERT INTO '.$tablename.' ('.$keys.') VALUES ('.$vals.')';
					echo $sql;
					*/
					
					$data=$this->piVars[data];
					
					foreach($tca["columns"] as $k=>$tcapart){
						if($tcapart["config"]["eval"]=="date"){
							if (strpos($data[$k],".")===false){}
							else{
								$datum=explode(".",$data[$k]);
								$datum=$this->checkdatum($datum);
								if (intval($datum[2])>=1970){$data[$k]=strtotime($datum[1]."/".$datum[0]."/".$datum[2]);}
								else {$data[$k]=$this->safestrtotime($datum[1],$datum[0],$datum[2]);}
								 //$data[$k]=strtotime($datum[1]."/".$datum[0]."/".$datum[2]);
							};
						}else if ($tcapart["config"]["internal_type"] == 'file'){
							
							$this->fileFunc = t3lib_div::makeInstance("t3lib_basicFileFunctions");
							if ($uid!=-1){
								 $recs = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow("*", $tablename, "uid=".$uid);
								 $oldval=$recs[$k];
							}else $oldval="";
							$newfilen=$this->hidepath($this->storeImage($k,$this->bildpfad,$rs[$k],$tcapart["config"]["max_size"],$tcapart["config"]["allowed"]));
							if ($newfilen!="" && $oldval!="") $data[$k]=$oldval.",".$newfilen;
							else if ($newfilen!="") $data[$k]=$newfilen;
							else $data[$k]=$oldval;
						}
						//hier cleanfile einfügen???
					}
					
					$data["tstamp"]=time();
					$data["moduser_id"]=$this->userid;
					$data["edited_from"]=$this->userid;
					if ($uid==-1){
						$data["crdate"]=time();
						$data["cruser_id"]=$this->userid;
						
						$GLOBALS['TYPO3_DB']->exec_INSERTquery($tablename, $data);
						$uid = $GLOBALS['TYPO3_DB']->sql_insert_id();
						if (($this->piVars[mm]!="")){
							$dat=array($k=>0);
							foreach($this->piVars[mm] as $k=>$v){
								foreach($v as $ka=>$va){
									if($va>0){
										$dat=array($k=>1);
										//echo "<br>".$tca["columns"][$k]["config"]["MM"]."---".$uid."---".$va;
										//$GLOBALS['TYPO3_DB']->exec_INSERTquery($tca["columns"][$k]["config"]["MM"], array("uid_local"=>$uid,"uid_foreign"=>$va,"tstamp"=>time(),"crdate"=>time(),"cruser_id"=>$this->userid,"moduser_id"=>$this->userid));
										$GLOBALS['TYPO3_DB']->exec_INSERTquery($tca["columns"][$k]["config"]["MM"], array("uid_local"=>$uid,"uid_foreign"=>$va));
									}
								}
							}
							
							$GLOBALS['TYPO3_DB']->exec_UPDATEquery($tablename, "uid=".$uid,$dat);
						}
					}else{
						
						if (($this->piVars[mm]!="")){
							//t3lib_div::debug($this->piVars[mm]);
							foreach($this->piVars[mm] as $k=>$v){
								$data[$k]=0;
								//echo "delete from ".$tca["columns"][$k]["config"]["MM"]." where uid_local=".$uid;
								$GLOBALS['TYPO3_DB']->exec_DELETEquery($tca["columns"][$k]["config"]["MM"],"uid_local=".$uid);
								foreach($v as $ka=>$va){
									if($va>0){
										$data[$k]=1;
										//echo $tca["columns"][$k]["config"]["MM"]." - ".$uid." - ".$va." - ";
										//$GLOBALS['TYPO3_DB']->exec_INSERTquery($tca["columns"][$k]["config"]["MM"], array("uid_local"=>$uid,"uid_foreign"=>$va,"tstamp"=>time(),"crdate"=>time(),"cruser_id"=>$this->userid,"moduser_id"=>$this->userid));
										$GLOBALS['TYPO3_DB']->exec_INSERTquery($tca["columns"][$k]["config"]["MM"], array("uid_local"=>$uid,"uid_foreign"=>$va));
									}
								}
							}
						}
						
						/*t3lib_div::debug($data);		$inh="";
						foreach($data as $k=>$v){
							$inh.=$k."='".$v."',";
						}	
						echo "<br><br><br><br>update ".$tablename." set ".$inh." where uid=".$uid;
						/**/
						$GLOBALS['TYPO3_DB']->exec_UPDATEquery($tablename, "uid=".$uid,$data);
						
					}
					
					//t3lib_div::debug($this->piVars);
					if (intval($this->piVars[teachers][teacher_address])>0){
						$datat=array("uid_local"=>$uid,"uid_foreign"=>intval($this->piVars[teachers][teacher_address]),"tstamp"=>time(),"crdate"=>time(),"cruser_id"=>$this->userid,"moduser_id"=>$this->userid,"position"=>intval($this->piVars[teachers][teacher_position]),
						"teacher_honorar"=>$this->piVars[teachers][teacher_honorar],"teacher_kosten"=>$this->piVars[teachers][teacher_kosten],"teacher_flugkosten"=>$this->piVars[teachers][teacher_flugkosten],
						"teacher_verpflegung"=>$this->piVars[teachers][teacher_verpflegung]);
						//t3lib_div::debug($datat);
						$GLOBALS['TYPO3_DB']->exec_INSERTquery("tx_gtntz_courses_teacher_mm", $datat);

					}
			}else{
				if ($uidt>0) $uid=$uidt;
				else $uid=t3lib_div::_GP("uid");
			}
		
				$result=$GLOBALS['TYPO3_DB']->exec_SELECTquery("*",$tablename, "uid=".$uid);
				$rs = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($result);
				foreach ($rs as $key1=>$val){
					$this->markerArray["###".$key1."###"]=$val;
					if($tca["columns"][$key1]["config"]["type"]=="check" && $val=="1"){
						$this->markerArray["###".$key1."###"]=' checked="checked"';
					}else if($tca["columns"][$key1]["config"]["internal_type"] == 'file'){
						$this->markerArray["###".$key1."_list###"]=$this->make_fe_filelist($val,$key1);
					}else if($tca["columns"][$key1]["config"]["eval"]=="date" && $val!=0){
							$this->markerArray["###".$key1."###"]=date("d.m.Y",$val);
					}elseif($tca["columns"][$key1]["config"]["type"]=="select"){
						if (!empty($tca["columns"][$key1]["config"]["MM"])){
							$mmsel=array();
							
							$resultmm=$GLOBALS['TYPO3_DB']->exec_SELECTquery("*",$tca["columns"][$key1]["config"]["MM"], "uid_local=".$uid);
							while ($rsmm = $GLOBALS['TYPO3_DB']->sql_fetch_assoc($resultmm)){
									$mmsel[]=$rsmm["uid_foreign"];
							}					
							$this->markerArray["###".$key1."###"]=$mmsel;
						} else if (!empty($tca["columns"][$key1]["config"]["items"])){
							$this->markerArray["###".$key1."###"] = "";
							foreach ($tca["columns"][$key1]["config"]["items"] as $text=>$value) {
									if ($val==$value[1]) 
										$this->markerArray["###".$key1."_".$value[1]."###"] = ' checked="checked"';
									else 	
										$this->markerArray["###".$key1."_".$value[1]."###"] = '';									
							};
						}
					}
				}	
				
				$this->markerArray["###rsinfo###"]=$this->create_rsinfo($rs["crdate"],$rs["tstamp"],$rs["cruser_id"],$rs["moduser_id"]);
				$this->markerArray["###uid###"]=$uid;
				//t3lib_div::debug($this->markerArray);
		}else{
				if (!empty($this->piVars["data"]))
					$data = $this->piVars[data];
				foreach ($felder as $feld){
						if (empty($data[$feld]))
							$this->markerArray["###".$feld."###"]='';
						else 
							$this->markerArray["###".$feld."###"]=$data[$feld];
				}
				$this->markerArray["###uid###"]="-1";
				$this->markerArray["###rsinfo###"]="";
				$this->markerArray["###images_list###"]="";
				$this->markerArray["###dateien_list###"]="";
				$uid=-1;
		} 
		return $uid;
	}
	
	function clean2($filename){
		$umlaute = Array("/ä/","/ö/","/ü/","/Ä/","/Ö/","/Ü/","/ß/","/JPG/");
		$replace = Array("ae","oe","ue","Ae","Oe","Ue","ss","jpg");
		$filename = preg_replace($umlaute,$replace,$filename);
		return $filename;
	}	 // clean2 
	function get_file_extension($file_name) {
		return substr(strrchr($file_name,'.'),1);
	}
	function storeImage($datei,$bildpfad,$dbwert,$max_size,$allowed)	{
		echo "<br><br><br><hr> ";
		
		
		$allowedp=str_replace(",","|",$allowed); //prepare for preg_match
		if($_FILES[$this->prefixId][tmp_name][data][$datei]){ 
			$ext=$this->get_file_extension($_FILES[$this->prefixId][name]["data"][$datei]);

			if ($_FILES[$this->prefixId][size][data][$datei]>($max_size*1024)){
				$this->meldung="Die Datei ist zu gross und wurde nicht gespeichert.<br>Die maximale Dateigr&ouml;&szlig;e ist ".($max_size/1024)." MB";
				return "";
			}else if (!preg_match("/(jpg|gif|png)/i", $ext)){
				$this->meldung="Das Dateiformat ".$ext." ist nicht erlaubt! <br>Die Datei muss eines dieser Formate haben: ".$allowed." ";
				return "";
			}else{ 
				$sauber = $this->fileFunc->cleanFileName($_FILES[$this->prefixId][name]["data"][$datei]);				
				$sauber = $this->clean2($sauber);
				$uniquename=$this->fileFunc->getUniqueName($sauber,$bildpfad);
				//echo "unique:".$uniquename."<br>";
				move_uploaded_file($_FILES[$this->prefixId][tmp_name]["data"][$datei],$uniquename);
				if (file_exists($bildpfad."/".$dbwert)){
					unlink($bildpfad."/".$dbwert);
				}
				return $uniquename;
			}
		}else{ 
			return $dbwert;
		}
		
	}
	function make_fe_filelist($wert,$feld){
		$inhalt="";
		$imgTSConfig = array();
		$imgTSConfig['file.']['width'] = $this->conf['EditPersonImageMaxWidth'].'m';
		$imgTSConfig['file.']['height'] = $this->conf['EditPersonImageMaxHeight'].'m';		
		if ($wert!=""){
			$filearr=explode(",",$wert);
			if (!empty($filearr)){
				foreach($filearr as $k=>$v){
					if ($v!=""){						
						$inhalt.='<tr><td><a href="'.$v.'">';
						// check is it image or not 
						if (getimagesize($v)) { 
							$imgTSConfig['file'] = $v;
							$inhalt.= $this->cObj->IMAGE($imgTSConfig);
							}
						else
							$inhalt.= str_replace($this->bildpfad,"",$v);
						$inhalt.='</a></td><td><a href="javascript:deldatei(\''.$v.'\',\''.$feld.'\');"><img src="fileadmin/tz/tmpl/images/delete.png" alt="L&ouml;schen" /></a></td></tr>';							
					}
				}
			}
		}
		return $inhalt;
	}
	function formatdatum($datum,$lang=false,$ohnejahr=false,$sekunden=false){
		$datum=$this->df($datum,"SEQUENCE");
		if ($datum<100000 || $datum=="") return "";
		else {
			if ($lang){
				
				if($ohnejahr) return strftime("%d. %B",$datum);
				else{
					 return strftime("%d. %B %Y",$datum);
				}
			}
			else{
				 if($ohnejahr) return strftime("%d.%m.",$datum);
				 else{
				 	if($sekunden) return strftime("%d.%m.%y %H:%M",$datum);
				 	else return strftime("%d.%m.%Y",$datum);
				 }
			}
		}
	}
	function df($param, $type){
		
		switch ($type) {
			case "SEQUENCE":     // Remove everything not 0-9,
        return preg_replace('/[^0-9,]/i', '', $param);
      case "ALPHANUM":     // Remove everything not a-zA-Z0-9
            return preg_replace('/[^A-Za-z0-9]/i', '', $param);
    }
	}
	function get_feusername($uid){
		$rs=$GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow("first_name,last_name", "fe_users", "uid=".$uid);
		if (!empty($rs)){
			return $rs["first_name"]." ".$rs["last_name"];
		}
		else return "";
	}
	function create_rsinfo($crdate,$tstamp,$cruser_id,$moduser_id){
		$str="erstellt am ".$this->formatdatum($crdate)." von ".$this->get_feusername($cruser_id);
		if ($tstamp>$crdate) {
			$str.=", geдndert am ".$this->formatdatum($tstamp)." von ".$this->get_feusername($moduser_id);
		}
		return $str;
	}
	function hidepath($wert){
			return $wert;
	}
	
	// Filters form
	function getFiltersForm($tmplfilter,$filters) {
			$tmplselectbezirk=$this->cObj->getSubpart($tmplfilter,"###SELECT_BEZIRK###");
			$tmplselectgender=$this->cObj->getSubpart($tmplfilter,"###SELECT_GENDER###");
			$tmplselectkassen=$this->cObj->getSubpart($tmplfilter,"###SELECT_KASSENPLATZE###");
			$tmplselect_forrow = $this->cObj->getSubpart($tmplfilter,"###ROW_SELECTS###");
			
			$markerArray["###ACTION_URL###"] = $this->pi_getPageLink($GLOBALS['TSFE']->id);
			$setfilter = t3lib_div::_GP('setfilter');
			$markerArray["###VALUE_SETFILTER###"] = ($setfilter)?"1":"0";
			$markerArray["###SHOW_FILTERS_1###"] = ($setfilter==1)?"display: none;": "";
			$markerArray["###SHOW_FILTERS_2###"] = ($setfilter==0)?"display: none;": "";
			$markerArray["###SUBMIT_BUTTON###"] = $this->pi_getLL('label_submit');
			$markerArray["###ADDITIONAL_BUTTON###"] = $this->pi_getLL('label_additional_submit');
			$markerArray["###GENERAL_SEARCH_BUTTON###"] = $this->pi_getLL('label_general_search');
			$markerArray["###SUBMIT_CLEAR###"] = $this->pi_getLL('label_clear');
			
			$markerArray["###LABEL_BEZIRK###"] = $this->pi_getLL('label_bezirk');
			$markerArray["###LABEL_PLZ###"] = $this->pi_getLL('label_plz');
			$markerArray["###LABEL_ORT###"] = $this->pi_getLL('label_ort');
			$markerArray["###LABEL_GESCHLECHT###"] = $this->pi_getLL('label_geschlecht');
			$markerArray["###LABEL_NAME###"] = $this->pi_getLL('label_name');
			$markerArray["###LABEL_FREE###"] = $this->pi_getLL('label_free');
			$markerArray["###LABEL_STICHWORT###"] = $this->pi_getLL('label_stichwort');
						
			//bezirk
			$res_bezirk = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('c.uid as cuid, c.title as ctitle', 'tx_gtnaddresses_cat c', 'c.title<>"" AND cattype=11','','title');
			foreach ($res_bezirk as $bezirk) 
				$arr_bezirks[$bezirk["cuid"]] = $bezirk["ctitle"];
			$subpartArray["###SELECT_BEZIRK###"] = $this->get_select_list($tmplselectbezirk, $arr_bezirks, $filters["bezirk"], "", 1);				
			//gender
			$arr_genders["1"] = $this->pi_getLL('woman'); $arr_genders["2"] = $this->pi_getLL('man');
			$subpartArray["###SELECT_GENDER###"] = $this->get_select_list($tmplselectgender, $arr_genders, $filters["geschlecht"], "", 0);		
			$subpartArray["###SELECT_GENDER2###"] = $this->get_select_list($tmplselectgender, $arr_genders, $filters["geschlecht2"], "", 0);		
			//kassenplatzen
			$arr_kass[0] = $this->pi_getLL('kassenplatze'); $arr_kass[1] = $this->pi_getLL('kassenplatze_free');
			$subpartArray["###SELECT_KASSENPLATZE###"] = $this->get_select_list($tmplselectkassen, $arr_kass, $filters["kassenplatze"], "", 0);		
			$subpartArray["###SELECT_KASSENPLATZE2###"] = $this->get_select_list($tmplselectkassen, $arr_kass, $filters["kassenplatze2"], "", 0);		
		
			//row selects for additional filter
			// ids of cattype for select
			
			$cattype_ids = array(9, 35, 4, 5, 12, 14, 8, 11, 10, 15);
			$tmpl_select = $this->cObj->getSubpart($tmplselect_forrow,"###SELECT###");
			foreach ($cattype_ids as $cattype_uid) {
				$res_cattype = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow('ct.title as typetitle', 'tx_gtnaddresses_cattypes ct', 'ct.uid='.$cattype_uid,'','title');
				$catname = $res_cattype['typetitle'];
				$markerArray["###LABEL_FILTERNAME###"] = $catname;
				$markerArray["###SELECT_NAME###"] = 'select_'.$cattype_uid;
				// values
				$arr_vals = array();
				$res_v = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows('c.uid as cuid, c.title as ctitle', 'tx_gtnaddresses_cat c', 'c.title<>"" AND cattype='.$cattype_uid,'','weight, title');
					foreach ($res_v as $v) 
				$arr_vals[$v["cuid"]] = $v["ctitle"];				
				$subpartArray["###SELECT###"] = $this->get_select_list($tmpl_select, $arr_vals, $filters["select_".$cattype_uid], "", 0, $markerArray["###SELECT_NAME###"]);	
				$selects_row .= $this->cObj->substituteMarkerArrayCached($tmplselect_forrow,$markerArray,$subpartArray,array());
				//echo $cattype_uid."--".$selects_row."<br>";
			};
			$subpartArray["###ROW_SELECTS###"] = $selects_row;
			
			$markerArray["###VALUE_PLZ###"] = $filters['plz'];
			$markerArray["###VALUE_ORT###"] = $filters['ort'];
			$markerArray["###VALUE_NAME###"] = $filters['name'];
			$markerArray["###VALUE_NAME2###"] = $filters['name2'];
			$markerArray["###VALUE_STICHWORT###"] = $filters['stichwort'];
	
			$content .= $this->cObj->substituteMarkerArrayCached($tmplfilter,$markerArray,$subpartArray,array());
			
			return $content;
	}

	function person_detail($tmpl, $id) {
		$fields = "a.*";
		$table = "tx_gtnaddresses_data a";
		$where = "a.uid=$id";
		$person = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow($fields, $table, $where);	
		
		// revision_titel
		
		$markerArray["###aname###"]=$this->get_fullname($person["titel"],$person["vorname"],$person["nachname"]);
		// titel
		$markerArray["###FIELD_TITEL###"] = $person['titel'];		
		// praxis_adresse
		$markerArray["###FIELD_PRAXIS_ADRESSE###"] = $person['praxis_adresse'];		
		// praxis_plz
		($person['praxis_plz']>0) ? $markerArray["###FIELD_PRAXIS_PLZ###"] = $person['praxis_plz'] : $markerArray["###FIELD_PRAXIS_PLZ###"] = "";
		// praxes_ort
		$markerArray["###FIELD_PRAXIS_ORT###"] = $person['praxis_ort'];	
		$markerArray["###FIELD_BESCHREIBUNG###"] = $person['beschreibung'];	
		$markerArray["###FIELD_TEASER###"] = $person['teaser'];
		// praxis2_adresse
		$markerArray["###FIELD_PRAXIS2_ADRESSE###"] = $person['praxis2_adresse'];		
		// praxis2_plz
		($person['praxis2_plz']>0) ?$markerArray["###FIELD_PRAXIS2_PLZ###"] = $person['praxis2_plz'] : $markerArray["###FIELD_PRAXIS2_PLZ###"] = "";		
		// praxis2_ort
		$markerArray["###FIELD_PRAXIS2_ORT###"] = $person['praxis2_ort'];		
		// telefon
		$markerArray["###FIELD_TELEFON###"] = $person['telefon'];		
		// email
		if ($person['email']<>"")
		{			
			$temp_tmpl = $this->cObj->getSubpart($tmpl,"###EMAIL###");
			$markerArray["###FIELD_EMAIL###"] = $person['email'];
			$subpartsArray["###EMAIL###"] = $this->cObj->substituteMarkerArrayCached($temp_tmpl, $markerArray);
		}
		else $subpartsArray["###EMAIL###"] = "";
		// web_adresse
		if ($person['web_adresse']<>"")
		{			
			$temp_tmpl = $this->cObj->getSubpart($tmpl,"###WWW###");
			if (!preg_match("~^(?:f|ht)tps?://~i", $person['web_adresse'])) 
				$markerArray["###FIELD_WWW###"] = "http://".$person['web_adresse']; 
			else 
				$markerArray["###FIELD_WWW###"] = $person['web_adresse']; 
			$subpartsArray["###WWW###"] = $this->cObj->substituteMarkerArrayCached($temp_tmpl, $markerArray);
		}
		else $subpartsArray["###WWW###"] = "";
		// jahrgang
		if ($person['jahrgang']<>"")
		{			
			$temp_tmpl = $this->cObj->getSubpart($tmpl,"###JAHRGANG###");
			$markerArray["###LABEL_JAHRGANG###"] = $this->pi_getLL('jahrgang');		
			$markerArray["###FIELD_JAHRGANG###"] = $person['jahrgang'];	
			$subpartsArray["###JAHRGANG###"] = $this->cObj->substituteMarkerArrayCached($temp_tmpl, $markerArray);
		}
		else $subpartsArray["###JAHRGANG###"] = "";	
		// iaus
		if ($person['iaus'] == "1")
			$markerArray["###FIELD_IAUS###"] = $this->pi_getLL('iaus');		
		else 
			$markerArray["###FIELD_IAUS###"] = "";					
		// freekassen2
		$markerArray["###LABEL_FREEKASSEN2###"] = $this->pi_getLL('free_kassen2');	
		if (!empty($person['freiekassenplaetze_abkw'])) $markerArray["###DATA_FREEKASSEN2###"] = $person['freiekassenplaetze_abkw'];
		else if ($person['freiekassenplaetze2']==1) $markerArray["###DATA_FREEKASSEN2###"] = "Ja";
		else $subpartsArray["###FREIEKASSENPLAETZE###"]="";
		
		// aus_weiterbildung   
		$markerArray["###LABEL_AUS_WEITERBILDUNGEN###"] = $this->pi_getLL('aus_weiterbildungen');		
		$markerArray["###FIELD_AUS_WEITERBILDUNGEN###"] = nl2br($person['aus_weiterbildung'],1);
		if (empty($person['aus_weiterbildung'])) $subpartsArray["###WEITERBILDUNG###"]="";		
		$markerArray["###BACK###"] = $this->pi_getLL('back');		
		// fe user data
		if ($person['fe_uid']>0 && !empty($this->userid)) {
			$temp_tmpl = $this->cObj->getSubpart($tmpl,"###FE_USER###");
			$markerArray["###LABEL_FE_SUBMITTED###"] = $this->pi_getLL('fe_submitted');	
			$feuser = $GLOBALS['TYPO3_DB']->exec_SELECTgetSingleRow("*", "fe_users", "uid=".$person['fe_uid']);	
			$markerArray["###FE_USER_LOGIN###"] = $feuser['username'];
			$markerArray["###LABEL_AM_FOR_DATE###"] = $this->pi_getLL('am_for_date');	
			$markerArray["###FE_USER_REGDATE###"] = date($this->conf["format_date"], $person['crdate']);
			$subpartsArray["###FE_USER###"] = $this->cObj->substituteMarkerArrayCached($temp_tmpl, $markerArray);
		}
		else $subpartsArray["###FE_USER###"] = "";
		// bereiche
		$ber = $GLOBALS['TYPO3_DB']->exec_SELECTgetRows("*", "tx_gtnaddresses_data_cat_mm mm LEFT JOIN tx_gtnaddresses_cat c ON mm.uid_foreign=c.uid", "mm.uid_local=".$person['uid'],"","c.cattype,c.title");
		if (count($ber)>0) {
			$temp_tmpl = $this->cObj->getSubpart($tmpl,"###BEREICHE###");
			$temp_item = $this->cObj->getSubpart($temp_tmpl,"###BEREICHE_ITEM###");
			$markerArray["###LABEL_BEREICHE###"] = $this->pi_getLL('bereiche');
			$items ="";
			foreach ($ber as $k => $b) {
				$markerArray["###BEREICHE_TITLE###"] = $b['title'];
				$markerArray["###LINK_TO_LIST###"] = $this->pi_getPageLink($this->conf["pid_addresses_list"],"",array("filters[bezirk]"=>$b['uid']));
				$items .= $this->cObj->substituteMarkerArrayCached($temp_item, $markerArray);
			};
			$subpartsArray["###ITEMS###"] = $items;
			$subpartsArray["###BEREICHE###"] .= $this->cObj->substituteMarkerArrayCached($temp_tmpl, $markerArray, $subpartsArray);
		}
		else $subpartsArray["###BEREICHE###"] = "";
		//$markerArray["###IMAGE###"] = $person['images'];
		// images
		$imgTSConfig = array();
		$imgTSConfig['file.']['width'] = $this->conf['SinglePersonImageMaxWidth'].'m';
		$imgTSConfig['file.']['height'] = $this->conf['SinglePersonImageMaxHeight'].'m';					
		$fileimages	= explode(",",$person['dateien']);
		if ($fileimages[0])
			foreach ($fileimages as $fname) {		
				if (getimagesize($fname)) {				
					$imgTSConfig['file'] = $fname;
					$markerArray["###IMAGES###"].= $this->cObj->IMAGE($imgTSConfig);					
				}
				$markerArray["###IMAGES###"] .= "";	
			}
		else	$markerArray["###IMAGES###"] = "";		
		// attachments files
		/*if ($person['dateien']) {		
			$temp_tmpl = $this->cObj->getSubpart($tmpl,"###ATTACHMENTS###");
			$temp_item = $this->cObj->getSubpart($temp_tmpl,"###FILE_ITEM###");
			$items ="";
			$files	= explode(",",$person['dateien']);
			foreach ($files as $f) {	
				$markerArray["###FILE_NAME###"] = basename($f);
				$markerArray["###FILE_SIZE###"] = t3lib_div::formatSize(filesize($f));
				$markerArray["###FILE_PATH###"] = $f;
				$items .= $this->cObj->substituteMarkerArrayCached($temp_item, $markerArray);
			};			
			$subpartsArray["###LIST###"] = $items;
			$subpartsArray["###ATTACHMENTS###"] .= $this->cObj->substituteMarkerArrayCached($temp_tmpl, $markerArray, $subpartsArray);			
		}*/
		$subpartsArray["###ATTACHMENTS###"] = "";
		
//		print_r($person);
	
		$content .= $this->cObj->substituteMarkerArrayCached($tmpl, $markerArray, $subpartsArray);
		return $content;
	} // person_detail
	
	function conv_geschlecht_code($wert){
		if($wert=="m") return 2;
		else if($wert=="f") return 1;
		else return 0;
	}
	function get_select_list($tmpl, $arr, $curr_val, $label="", $sort=0, $select_name="") {
		if (!is_array($curr_val))
			$curr_val = array($curr_val);
		if ($sort==1)
//			asort($arr);
			natcasesort($arr);
		$markerArray["###LABEL###"] = $label;
		$markerArray["###SELECT_NAME###"] = $select_name;
		$tmpl_item = $this->cObj->getSubpart($tmpl, "###ITEM###");			
		if(!empty($arr))
			foreach($arr as $value => $text) { 
				$markerArray["###VALUE###"] = $value;
				$markerArray["###TEXT###"] = $text;
				if (in_array($value, $curr_val)) $markerArray["###SELECTED###"] = "selected=selected";
				else $markerArray["###SELECTED###"] = "";
				$items .= $this->cObj->substituteMarkerArrayCached($tmpl_item, $markerArray);
			};
		$subpartsArray["###SELECT_ITEMS###"] = $items;
		$out .= $this->cObj->substituteMarkerArrayCached($tmpl, $markerArray, $subpartsArray);
		return $out;
	} // get_select_list
	function clean_param($param, $type) {

    switch ($type) {

        case PARAM_INT:
            return (int)$param;  // Convert to integer

        case PARAM_FLOAT:
            return (float)$param;  // Convert to float

        case PARAM_ALPHA:        // Remove everything not a-z
            return preg_replace('/[^a-zA-Z]/i', '', $param);

        case PARAM_ALPHAEXT:     // Remove everything not a-zA-Z_- (originally allowed "/" too)
            return preg_replace('/[^a-zA-Z_-]/i', '', $param);

        case PARAM_ALPHANUM:     // Remove everything not a-zA-Z0-9
            return preg_replace('/[^A-Za-z0-9]/i', '', $param);

        case PARAM_ALPHANUMEXT:     // Remove everything not a-zA-Z0-9_-
            return preg_replace('/[^A-Za-z0-9_-]/i', '', $param);

        case PARAM_SEQUENCE:     // Remove everything not 0-9,
            return preg_replace('/[^0-9,]/i', '', $param);

        
        case PARAM_NOTAGS:       // Strip all tags
            $param = $this->fix_utf8($param);
            return strip_tags($param);

        case PARAM_TEXT:    // leave only tags needed for multilang
        		$param = $this->clean_text($param);
            $param = $this->fix_utf8($param);
            // if the multilang syntax is not correct we strip all tags
            // because it would break xhtml strict which is required for accessibility standards
            // please note this cleaning does not strip unbalanced '>' for BC compatibility reasons
            do {
                if (strpos($param, '</lang>') !== false) {
                    // old and future mutilang syntax
                    $param = strip_tags($param, '<lang>');
                    if (!preg_match_all('/<.*>/suU', $param, $matches)) {
                        break;
                    }
                    $open = false;
                    foreach ($matches[0] as $match) {
                        if ($match === '</lang>') {
                            if ($open) {
                                $open = false;
                                continue;
                            } else {
                                break 2;
                            }
                        }
                        if (!preg_match('/^<lang lang="[a-zA-Z0-9_-]+"\s*>$/u', $match)) {
                            break 2;
                        } else {
                            $open = true;
                        }
                    }
                    if ($open) {
                        break;
                    }
                    return $param;

                } else if (strpos($param, '</span>') !== false) {
                    // current problematic multilang syntax
                    $param = strip_tags($param, '<span>');
                    if (!preg_match_all('/<.*>/suU', $param, $matches)) {
                        break;
                    }
                    $open = false;
                    foreach ($matches[0] as $match) {
                        if ($match === '</span>') {
                            if ($open) {
                                $open = false;
                                continue;
                            } else {
                                break 2;
                            }
                        }
                        if (!preg_match('/^<span(\s+lang="[a-zA-Z0-9_-]+"|\s+class="multilang"){2}\s*>$/u', $match)) {
                            break 2;
                        } else {
                            $open = true;
                        }
                    }
                    if ($open) {
                        break;
                    }
                    return $param;
                }
            } while (false);
            // easy, just strip all tags, if we ever want to fix orphaned '&' we have to do that in format_string()
           	
            return strip_tags($param);

        case PARAM_COMPONENT:
            // we do not want any guessing here, either the name is correct or not
            // please note only normalised component names are accepted
            if (!preg_match('/^[a-z]+(_[a-z][a-z0-9_]*)?[a-z0-9]$/', $param)) {
                return '';
            }
            if (strpos($param, '__') !== false) {
                return '';
            }
            if (strpos($param, 'mod_') === 0) {
                // module names must not contain underscores because we need to differentiate them from invalid plugin types
                if (substr_count($param, '_') != 1) {
                    return '';
                }
            }
            return $param;


        case PARAM_SAFEDIR:      // Remove everything not a-zA-Z0-9_-
            return preg_replace('/[^a-zA-Z0-9_-]/i', '', $param);

        case PARAM_SAFEPATH:     // Remove everything not a-zA-Z0-9/_-
            return preg_replace('/[^a-zA-Z0-9\/_-]/i', '', $param);

        case PARAM_FILE:         // Strip all suspicious characters from filename
            $param = $this->fix_utf8($param);
            $param = preg_replace('~[[:cntrl:]]|[&<>"`\|\':\\\\/]~u', '', $param);
            if ($param === '.' || $param === '..') {
                $param = '';
            }
            return $param;

        case PARAM_PATH:         // Strip all suspicious characters from file path
            $param = $this->fix_utf8($param);
            $param = str_replace('\\', '/', $param);

            // Explode the path and clean each element using the PARAM_FILE rules.
            $breadcrumb = explode('/', $param);
            foreach ($breadcrumb as $key => $crumb) {
                if ($crumb === '.' && $key === 0) {
                    // Special condition to allow for relative current path such as ./currentdirfile.txt.
                } else {
                    $crumb = $this->clean_param($crumb, PARAM_FILE);
                }
                $breadcrumb[$key] = $crumb;
            }
            $param = implode('/', $breadcrumb);

            // Remove multiple current path (./././) and multiple slashes (///).
            $param = preg_replace('~//+~', '/', $param);
            $param = preg_replace('~/(\./)+~', '/', $param);
            return $param;

        case PARAM_HOST:         // allow FQDN or IPv4 dotted quad
            $param = preg_replace('/[^\.\d\w-]/','', $param ); // only allowed chars
            // match ipv4 dotted quad
            if (preg_match('/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/',$param, $match)){
                // confirm values are ok
                if ( $match[0] > 255
                     || $match[1] > 255
                     || $match[3] > 255
                     || $match[4] > 255 ) {
                    // hmmm, what kind of dotted quad is this?
                    $param = '';
                }
            } elseif ( preg_match('/^[\w\d\.-]+$/', $param) // dots, hyphens, numbers
                       && !preg_match('/^[\.-]/',  $param) // no leading dots/hyphens
                       && !preg_match('/[\.-]$/',  $param) // no trailing dots/hyphens
                       ) {
                // all is ok - $param is respected
            } else {
                // all is not ok...
                $param='';
            }
            return $param;

        case PARAM_URL:          // allow safe ftp, http, mailto urls
            $param = $this->fix_utf8($param);
            include_once($CFG->dirroot . '/lib/validateurlsyntax.php');
            if (!empty($param) && validateUrlSyntax($param, 's?H?S?F?E?u-P-a?I?p?f?q?r?')) {
                // all is ok, param is respected
            } else {
                $param =''; // not really ok
            }
            return $param;

        case PARAM_LOCALURL:     // allow http absolute, root relative and relative URLs within wwwroot
            $param = clean_param($param, PARAM_URL);
            if (!empty($param)) {
                if (preg_match(':^/:', $param)) {
                    // root-relative, ok!
                } elseif (preg_match('/^'.preg_quote($CFG->wwwroot, '/').'/i',$param)) {
                    // absolute, and matches our wwwroot
                } else {
                    // relative - let's make sure there are no tricks
                    if (validateUrlSyntax('/' . $param, 's-u-P-a-p-f+q?r?')) {
                        // looks ok.
                    } else {
                        $param = '';
                    }
                }
            }
            return $param;

        case PARAM_PEM:
            $param = trim($param);
            // PEM formatted strings may contain letters/numbers and the symbols
            // forward slash: /
            // plus sign:     +
            // equal sign:    =
            // , surrounded by BEGIN and END CERTIFICATE prefix and suffixes
            if (preg_match('/^-----BEGIN CERTIFICATE-----([\s\w\/\+=]+)-----END CERTIFICATE-----$/', trim($param), $matches)) {
                list($wholething, $body) = $matches;
                unset($wholething, $matches);
                $b64 = clean_param($body, PARAM_BASE64);
                if (!empty($b64)) {
                    return "-----BEGIN CERTIFICATE-----\n$b64\n-----END CERTIFICATE-----\n";
                } else {
                    return '';
                }
            }
            return '';

        case PARAM_BASE64:
            if (!empty($param)) {
                // PEM formatted strings may contain letters/numbers and the symbols
                // forward slash: /
                // plus sign:     +
                // equal sign:    =
                if (0 >= preg_match('/^([\s\w\/\+=]+)$/', trim($param))) {
                    return '';
                }
                $lines = preg_split('/[\s]+/', $param, -1, PREG_SPLIT_NO_EMPTY);
                // Each line of base64 encoded data must be 64 characters in
                // length, except for the last line which may be less than (or
                // equal to) 64 characters long.
                for ($i=0, $j=count($lines); $i < $j; $i++) {
                    if ($i + 1 == $j) {
                        if (64 < strlen($lines[$i])) {
                            return '';
                        }
                        continue;
                    }

                    if (64 != strlen($lines[$i])) {
                        return '';
                    }
                }
                return implode("\n",$lines);
            } else {
                return '';
            }

        case PARAM_TAG:
            $param = $this->fix_utf8($param);
            // Please note it is not safe to use the tag name directly anywhere,
            // it must be processed with s(), urlencode() before embedding anywhere.
            // remove some nasties
            $param = preg_replace('~[[:cntrl:]]|[<>`]~u', '', $param);
            //convert many whitespace chars into one
            $param = preg_replace('/\s+/', ' ', $param);
            //$param = textlib::substr(trim($param), 0, TAG_MAX_LENGTH);
            return $param;

        case PARAM_TAGLIST:
            $param = $this->fix_utf8($param);
            $tags = explode(',', $param);
            $result = array();
            foreach ($tags as $tag) {
                $res = clean_param($tag, PARAM_TAG);
                if ($res !== '') {
                    $result[] = $res;
                }
            }
            if ($result) {
                return implode(',', $result);
            } else {
                return '';
            }
  
        case PARAM_EMAIL:
            $param = $this->fix_utf8($param);
            if ($this->validate_email($param)) {
                return $param;
            } else {
                return '';
            }
        default:                 // throw error, switched parameters in optional_param or another serious problem
            print_error("unknownparamtype", '', '', $type);
    }
	}
	function fix_utf8($value) {
    if (is_null($value) or $value === '') {
        return $value;

    } else if (is_string($value)) {
        if ((string)(int)$value === $value) {
            // shortcut
            return $value;
        }

        // Lower error reporting because glibc throws bogus notices.
        $olderror = error_reporting();
        if ($olderror & E_NOTICE) {
            error_reporting($olderror ^ E_NOTICE);
        }

        // Note: this duplicates min_fix_utf8() intentionally.
        static $buggyiconv = null;
        if ($buggyiconv === null) {
            $buggyiconv = (!function_exists('iconv') or iconv('UTF-8', 'UTF-8//IGNORE', '100'.chr(130).'€') !== '100€');
        }

        if ($buggyiconv) {
            if (function_exists('mb_convert_encoding')) {
                $subst = mb_substitute_character();
                mb_substitute_character('');
                $result = mb_convert_encoding($value, 'utf-8', 'utf-8');
                mb_substitute_character($subst);

            } else {
                // Warn admins on admin/index.php page.
                $result = $value;
            }

        } else {
            $result = iconv('UTF-8', 'UTF-8//IGNORE', $value);
        }

        if ($olderror & E_NOTICE) {
            error_reporting($olderror);
        }

        return $result;

    } else if (is_array($value)) {
        foreach ($value as $k=>$v) {
            $value[$k] = $this->fix_utf8($v);
        }
        return $value;

    } else if (is_object($value)) {
        $value = clone($value); // do not modify original
        foreach ($value as $k=>$v) {
            $value->$k = $this->fix_utf8($v);
        }
        return $value;

    } else {
        // this is some other type, no utf-8 here
        return $value;
    }
	}
	function validate_email($address) {

    return (preg_match('#^[-!\#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+'.
                 '(\.[-!\#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+)*'.
                  '@'.
                  '[-!\#$%&\'*+\\/0-9=?A-Z^_`a-z{|}~]+\.'.
                  '[-!\#$%&\'*+\\./0-9=?A-Z^_`a-z{|}~]+$#',
                  $address));
	}
	function clean_text($wert){
		$wert=str_replace("<?","",$wert);
		$wert=str_replace("$","",$wert);
		$wert=str_replace("script","",$wert);
		$wert=str_replace("?php","",$wert);
	}
	function checkdatum($datum){
		$datum[0]=intval($datum[0]);$datum[1]=intval($datum[1]);$datum[2]=intval($datum[2]);
		if ($datum[0]>31) $datum[0]=31;
		if ($datum[1]>12) $datum[1]=12;
		if ($datum[2]<date("y",(time()+(60*60*24*365*10)))) $datum[2]=($datum[2]+2000);
		else if ($datum[2]<100) $datum[2]=($datum[2]+1900);
		return $datum;
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gtn_addresses/pi1/class.tx_gtnaddresses_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gtn_addresses/pi1/class.tx_gtnaddresses_pi1.php']);
}

?>