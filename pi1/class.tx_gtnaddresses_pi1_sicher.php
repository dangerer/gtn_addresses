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
			/*
			tabelle users->fe_users
			uid->uid
			name->username 	
			pass->password
			mail->email
			signature->signature(tabelle gtn_addresses) 	signature_format->signature_format 	(tabelle gtn_addresses)
			created->crdate 	
			status->disable (aber 0->1 und 1->0)
			access->lastlogin
			data->usersdata 	(tabelle gtn_addresses)
			*/
				
		}
	
		return $this->pi_wrapInBaseClass($content);
	}
}



if (defined('TYPO3_MODE') && $TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gtn_addresses/pi1/class.tx_gtnaddresses_pi1.php'])	{
	include_once($TYPO3_CONF_VARS[TYPO3_MODE]['XCLASS']['ext/gtn_addresses/pi1/class.tx_gtnaddresses_pi1.php']);
}

?>