<?php
/*
********************************************************
		TinyButStrong 1.97.12 (2004-07-20)
		Template Engine for Pro and Beginners
------------------------
Web site : www.tinybutstrong.com
Author   : skrol29@freesurf.fr
********************************************************
This library is free software.
You can redistribute and modify it even for commercial usage,
but you must accept and respect the LPGL License (v2.1 or later).
*/

// You can change the TinyButStrong markers in your code.
if (!isset($GLOBALS['tbs_ChrOpen'])) $GLOBALS['tbs_ChrOpen'] = '[';
if (!isset($GLOBALS['tbs_ChrClose'])) $GLOBALS['tbs_ChrClose'] = ']';
if (!isset($GLOBALS['tbs_CacheMask'])) $GLOBALS['tbs_CacheMask'] = 'cache_tbs_*.php';

// Render flags
define('TBS_NOTHING', 0);
define('TBS_OUTPUT', 1);
define('TBS_EXIT', 2);
// Special cache actions
define('TBS_DELETE', -1);
define('TBS_CANCEL', -2);
define('TBS_CACHENOW', -3);
define('TBS_CACHEONSHOW', -4);

// Check PHP version
if (PHP_VERSION<'4.0.6') tbs_Misc_Alert('PHP Version Check','Your PHP version is '.PHP_VERSION.' while TinyButStrong needs PHP version 4.0.6 or higher.' );
if (PHP_VERSION<'4.1.0') {
	function array_key_exists (&$key,&$array) {
		return key_exists($key,$array);
	}
}

// Init common variables
$GLOBALS['tbs_CurrVal'] = '';
$GLOBALS['tbs_CurrRec'] = array();
$GLOBALS['tbs_CurrNav'] = false;

$GLOBALS['_tbs_FrmMultiLst'] = array();
$GLOBALS['_tbs_FrmSimpleLst'] = array();
$GLOBALS['_tbs_PhpVarLst'] = false;
$GLOBALS['_tbs_Timer'] = tbs_Misc_Timer();
$GLOBALS['_tbs_False'] = false;
tbs_Misc_ActualizeChr();

// Classes
class clsTbsLocator {
	var $PosBeg = false;
	var $PosEnd = false;
	var $Enlarged = false;
	var $EnlargeType = false;
	var $FullName = false;
	var $SubName = '';
	var $SubOk = false;
	var $SubLst = array();
	var $SubNbr = 0;
	var $PrmLst = array();
	var $BlockFound = false;
}

class clsTinyButStrong {

	// Public properties
	var $Source = ''; // Current result of the merged template
	var $Render = 3;
	var $HtmlCharSet = '';
	// Private properties
	var $_Version = '1.97.12';
	var $_LastFile = ''; // The last loaded template file
	var $_StartMerge = 0;
	var $_Timer = false; // True if a system field about time has been found in the template
	var $_CacheFile = false; // The name of the file to save the content in.
	var $_DebugTxt = '';

	// Public methods
	function LoadTemplate($File,$HtmlCharSet='') {
		$this->_StartMerge = tbs_Misc_Timer();
		tbs_Misc_ActualizeChr();
		// Load the file
		if (tbs_Misc_GetFile($this->Source,$File)===false) {
			tbs_Misc_Alert('LoadTemplate Method','Unable to read the file \''.$File.'\'.');
			return false;
		}

		$this->_LastFile = $File;
		// CharSet
		if ($HtmlCharSet==-1) {
			$this->HtmlCharSet = '';
			$Pos = 0;
			while ($Loc = tbs_Html_FindTag($this->Source,'META',true,$Pos,true,1,true)) {
				$Pos = $Loc->PosEnd + 1;
				if (isset($Loc->PrmLst['http-equiv'])) {
					if (strtolower($Loc->PrmLst['http-equiv'])==='content-type') {
						if (isset($Loc->PrmLst['content'])) {
							$x = ';'.strtolower($Loc->PrmLst['content']).';';
							$x = str_replace(' ','',$x);
							$p = strpos($x,';charset=');
							if ($p!==false) {
								$x = substr($x,$p+strlen(';charset='));
								$p = strpos($x,';');
								if ($p!==false) $x = substr($x,0,$p);
								$this->HtmlCharSet = $x;
								$Loc = false;
							}
						}
					}
				}
			}
		} else {
			$this->HtmlCharSet = $HtmlCharSet;
		}
		// Include files
		tbs_Misc_ClearPhpVarLst();
		tbs_Merge_Auto($this->Source,$this->HtmlCharSet,true);
		return true;
	}

	function GetBlockSource($BlockName,$List=false) {
		$x = false;
		if ($List) {
			$SrcLst = array();
			$Nbr = 0;
			$Pos = 0;
			while ($Loc = tbs_Locator_FindBlockNext($this->Source,$BlockName,$Pos,$x)) {
				$Nbr++;
				$SrcLst[$Nbr] = $Loc->BlockSrc;
				$Pos = $Loc->PosEnd;
				$x = false;
			}
			return $SrcLst;
		} else {
			$Loc = tbs_Locator_FindBlockNext($this->Source,$BlockName,0,$x);
			if ($Loc===false) {
				return false;
			} else {
				return $Loc->BlockSrc;
			}
		}
	}

	function MergeBlock($BlockName,$SrcId,$Query='',$PageSize=0,$PageNum=0,$RecKnown=0) {
		return tbs_Merge_Block($this->Source,$this->HtmlCharSet,$BlockName,$SrcId,$Query,$PageSize,$PageNum,$RecKnown);
	}

	function MergeField($Name,$Value,$Fct=false) {
		tbs_Misc_ClearPhpVarLst(); // Usefull here because the field can have an file inclusion
		if ($Fct) {
			if (function_exists($Value)) {
				$PosBeg = 0;
				while ($Loc = tbs_Locator_FindTbs($this->Source,$Name,$PosBeg,true)) {
					$PosBeg = tbs_Locator_Replace($this->Source,$this->HtmlCharSet,$Loc,@$Value($Loc->SubName,$Loc->PrmLst),false);
				}
			} else {
				tbs_Misc_Alert('MergeField Method','Custom function \''.$Value.'\' is not found.');
			}
		} else {
			tbs_Merge_Field($this->Source,$this->HtmlCharSet,$Name,$Value,true,true,false);
		}
	}

	function MergeSpecial($Type) {
		$Type = strtolower($Type);
		tbs_Misc_ClearPhpVarLst();
		tbs_Merge_Special($this,$Type);
	}

	function MergeNavigationBar($BlockName,$Options,$PageCurr,$RecCnt=-1,$RecByPage=1) {
		return tbs_Merge_NavigationBar($this->Source,$this->HtmlCharSet,$BlockName,$Options,$PageCurr,$RecCnt,$RecByPage);
	}

	function Show($End='',$MergePhpVar=true,$Output='') {
		// Those parameters are only for compatibility
		// Now you must use the ->Render property
		tbs_Misc_ClearPhpVarLst();

		if ($MergePhpVar) {
			tbs_Merge_Special($this,'include,include.onshow,var,sys,check,timer');
		} else {
			tbs_Merge_Special($this,'include,include.onshow,sys,check,timer');
		}

		if ($this->_DebugTxt!=='') $this->Source = $this->_DebugTxt.$this->Source;

		if ($this->_CacheFile!==false) tbs_Cache_Save($this->_CacheFile,$this->Source);

		if ($Output==='') {
			if (($this->Render & TBS_OUTPUT) == TBS_OUTPUT) echo $this->Source;
		} elseif ($Output) {
			echo $this->Source;
		}

		if ($End==='') {
			if (($this->Render & TBS_EXIT) == TBS_EXIT) exit;
		} elseif ($End) {
			exit;
		}

	}

	function CacheAction($CacheId,$TimeOut=3600,$Dir='') {

		global $tbs_CacheMask;

		$CacheId = trim($CacheId);
		$Res = false;

		if ($TimeOut === TBS_CANCEL) { // Cancel cache save if any
			$this->_CacheFile = false;
		} elseif ($CacheId === '*') {
			if ($TimeOut === TBS_DELETE) $Res = tbs_Cache_DeleteAll($Dir,$tbs_CacheMask);
		} else {
			$CacheFile = tbs_Cache_File($Dir,$CacheId,$tbs_CacheMask);
			if ($TimeOut === TBS_CACHENOW) {
				tbs_Cache_Save($CacheFile,$this->Source);
			} elseif ($TimeOut === TBS_DELETE) {
				if (file_exists($CacheFile)) @unlink($CacheFile);
			} elseif ($TimeOut === TBS_CACHEONSHOW) {
				$this->_CacheFile = $CacheFile;
				@touch($CacheFile);
			} elseif($TimeOut>=0) {
				$Res = tbs_Cache_IsValide($CacheFile,$TimeOut);
				if ($Res) { // Load the cache
					$this->_CacheFile = false;
					if (tbs_Misc_GetFile($this->Source,$CacheFile)) {
						if (($this->Render & TBS_OUTPUT) == TBS_OUTPUT) echo $this->Source;
						if (($this->Render & TBS_EXIT) == TBS_EXIT) Exit;
					} else {
						tbs_Misc_Alert('CacheAction Method','Unable to read the file \''.$CacheFile.'\'.');
						$Res==false;
					}
				} else {
					// The result will be saved in the cache when the Show() method is called
					$this->_CacheFile = $CacheFile;
					@touch($CacheFile);
				}
			}
		}

		return $Res;

	}

	// Hidden methods
	function DebugPrint($Txt) {
		if ($Txt===false) {
			$this->_DebugTxt = '';
		} else {
			$this->_DebugTxt .= 'Debug: '.htmlentities($Txt).'<br>';
		}
	}

	function DebugLocator($Name) {
		$this->_DebugTxt .= tbs_Misc_DebugLocator($this->Source,$Name);
	}

	// Only for compatibility
	function MergePHPVar() {
		tbs_Merge_Special($this->Source,'var',true);
	}
	
}

class clsTbsDataSource {

	var $Type = false;
	var $SubType = 0;
	var $SrcId = false;
	var $Query = '';
	var $RecSet = false;
	var $PrevRec = array();
	var $CurrRec = false;
	var $RowNum = 0;
  var $AlertTitle = '';

	function DataPrepare(&$SrcId,&$BlockName) {
	
		$this->SrcId = &$SrcId;
		$this->AlertTitle = 'MergeBlock '.$GLOBALS['tbs_ChrOpen'].$BlockName.$GLOBALS['tbs_ChrClose'];
	
		if (is_array($SrcId)) {
			$this->Type = 0;
		} elseif (is_resource($SrcId)) {
	
			$Key = get_resource_type($SrcId);
			switch ($Key) {
			case 'mysql link'            : $this->Type = 1; break;
			case 'mysql link persistent' : $this->Type = 1; break;
			case 'mysql result'          : $this->Type = 1; $this->SubType = 1; break;
			case 'odbc link'             : $this->Type = 2; break;
			case 'odbc link persistent'  : $this->Type = 2; break;
			case 'odbc result'           : $this->Type = 2; $this->SubType = 1; break;
			case 'mssql link'            : $this->Type = 3; break;
			case 'mssql link persistent' : $this->Type = 3; break;
			case 'mssql result'          : $this->Type = 3; $this->SubType = 1; break;
			case 'pgsql link'            : $this->Type = 8; break;
			case 'pgsql link persistent' : $this->Type = 8; break;
			case 'pgsql result'          : $this->Type = 8; $this->SubType = 1; break;
			case 'sqlite database'       : $this->Type = 9; break;
			case 'sqlite database (persistent)'	: $this->Type = 9; break;
			case 'sqlite result'         : $this->Type = 9; $this->SubType = 1; break;
			default :
				$SubKey = 'resource type';
				$this->Type = 7;
				$x = strtolower($Key);
				$x = str_replace('-','_',$x);
				$Function = '';
				$i = 0;
				$iMax = strlen($x);
				while ($i<$iMax) {
					if (($x[$i]==='_') or (($x[$i]>='a') and ($x[$i]<='z')) or (($x[$i]>='0') and ($x[$i]<='9'))) {
						$Function .= $x[$i];
						$i++;
					} else {
						$i = $iMax;
					}
				}
			}
	
		} elseif (is_string($SrcId)) {
	
			switch (strtolower($SrcId)) {
			case 'array' : $this->Type = 0; $this->SubType = 1; break;
			case 'clear' : $this->Type = 0; $this->SubType = 2; break;
			case 'mysql' : $this->Type = 1; $this->SubType = 2; break;
			case 'mssql' : $this->Type = 3; $this->SubType = 2; break;
			case 'text'  : $this->Type = 4; break;
			case 'num'   : $this->Type = 6; break;
			default :
				$Key = $SrcId;
				$SubKey = 'keyword';
				$this->Type = 7;
				$Function = $SrcId;
			}
	
		} elseif (is_object($SrcId)) {
			$Key = get_class($SrcId);
			if ($Key==='COM') {
				if (strlen(@$SrcId->ConnectionString())>0) { // Look if it's a Connection object
					if ($SrcId->State==1) {
						$this->Type = 5; // ADODB
					} else {
						tbs_Misc_Alert($this->AlertTitle,'The specified ADODB Connection is not open or not ready.');
					}
				} elseif (strlen(@$SrcId->CursorType())>0) { // Look if it's a RecordSet object
					if ($SrcId->State==1) {
						$this->Type = 5; // ADODB
						$this->SubType = 1;
					} else {
						tbs_Misc_Alert($this->AlertTitle,'The specified ADODB Recordset is not open or not ready.');
					}
				} else {
					tbs_Misc_Alert($this->AlertTitle,'The specified COM Object is not a Connection or a Recordset.');
				}
			} else {
				$SubKey = 'object type';
				$this->Type = 7;
				$Function = $Key;
			}
	
		} elseif ($SrcId===false) {
			tbs_Misc_Alert($this->AlertTitle,'The specified source is set to FALSE. Maybe your connection has failed.');
		} else {
			tbs_Misc_Alert($this->AlertTitle,'Unsupported variable type : \''.gettype($SrcId).'\'.');
		}

		if ($this->Type===7) {
			$FctOpen  = 'tbsdb_'.$Function.'_open';
			$FctFetch = 'tbsdb_'.$Function.'_fetch';
			$FctClose = 'tbsdb_'.$Function.'_close';
			if (function_exists($FctOpen)) {
				if (function_exists($FctFetch)) {
						if (function_exists($FctClose)) {
							$this->FctOpen = $FctOpen;
							$this->FctFetch = $FctFetch;
							$this->FctClose = $FctClose;
						} else {
						$this->Type = tbs_Misc_Alert($this->AlertTitle,'The expected custom function \''.$FctClose.'\' is not found.');
					}
				} else {
					$this->Type = tbs_Misc_Alert($this->AlertTitle,'The expected custom function \''.$FctFetch.'\' is not found.');
				}
			} else {
				$this->Type = tbs_Misc_Alert($this->AlertTitle,'The data source Id \''.$Key.'\' is an unsupported '.$SubKey.'. And the corresponding custom function \''.$FctOpen.'\' is not found.');
			}
		}

		return ($this->Type!==false);

	}

	function DataOpen(&$Query) {
	
		switch ($this->Type) {
		case 0: // Array
			if ($this->SubType==0) {
				$this->RecSet = &$this->SrcId;
			} elseif ($this->SubType==1) {
				if (is_array($Query)) {
					$this->RecSet = &$Query;
				} elseif (is_string($Query)) {
					if (isset($GLOBALS[$Query])) {
						if (is_array($GLOBALS[$Query])) {
							$this->RecSet = &$GLOBALS[$Query];
						} else {
							$this->RecSet = tbs_Misc_Alert($this->AlertTitle,'The global variable \''.$Query.'\' must be an array.');
						}
					} else {
						$this->RecSet = tbs_Misc_Alert($this->AlertTitle,'The global variable \''.$Query.'\' doesn\'t exist.');
					}
				} else {
					$this->RecSet = tbs_Misc_Alert($this->AlertTitle,'Type \''.gettype($Query).'\' not supported for the Query Parameter going with \'array\' Source Type.');
				}
			} elseif ($this->SubType==2) {
				$this->RecSet = array();
			}
			if ($this->RecSet!==false) {
				$this->Count = count($this->RecSet);
				$this->Reset = true;
			}
			break;
		case 1: // MySQL
			switch ($this->SubType) {
			case 0: $this->RecSet = @mysql_query($Query,$this->SrcId); break;
			case 1: $this->RecSet = $this->SrcId; break;
			case 2: $this->RecSet = @mysql_query($Query); break;
			}
			if ($this->RecSet===false) tbs_Misc_Alert($this->AlertTitle,'MySql error message when opening the query: '.mysql_error());
			break;
		case 2: // ODBC
			switch ($this->SubType) {
			case 0: $this->RecSet = @odbc_exec($this->SrcId,$Query); break;
			case 1: $this->RecSet = $this->SrcId; break;
			}
			if ($this->RecSet===false) {
				tbs_Misc_Alert($this->AlertTitle,'ODBC error message when opening the query: '.odbc_errormsg());
			} else {
				$this->Fields = array();
				$iMax = odbc_num_fields($this->RecSet);
				for ($i=1;$i<=$iMax;$i++) {
					$this->Fields[$i] = ''.odbc_field_name($this->RecSet,$i);
				}
			}
			break;
		case 3: // MsSQL
			switch ($this->SubType) {
			case 0: $this->RecSet = @mssql_query($Query,$this->SrcId); break;
			case 1: $this->RecSet = $this->SrcId; break;
			case 2: $this->RecSet = @mssql_query($Query); break;
			}
			if ($this->RecSet===false) tbs_Misc_Alert($this->AlertTitle,'SQL-Server error message when opening the query: '.mssql_get_last_message());
			break;
		case 4: // Text
			if (is_string($Query)) {
				$this->RecSet = &$Query;
			} else {
				$this->RecSet = ''.$Query;	
			}
			break;
		case 5: // ADODB
			switch ($this->SubType) {
			case 0:
				$this->RecSet = @$this->SrcId->Execute($Query); // We use the Connection object reather than the Recordset object in order to manage errors
				if ($this->SrcId->Errors->Count>0) {
					$this->RecSet = tbs_Misc_Alert($this->AlertTitle,'ADODB error message when opening the query: '.$this->SrcId->Errors[0]->Description);
				} elseif ($this->RecSet->State!=1) {
					$this->RecSet = tbs_Misc_Alert($this->AlertTitle,'The ADODB query doesn\'t return a RecordSet or the ResordSet is not ready.');
				}
				break;
			case 1:
				$this->RecSet = &$this->SrcId;
				break;
			}
			if ($this->RecSet!==false) {
				$this->Fields = array();
				$iMax = $this->RecSet->Fields->Count;
				for ($i=0;$i<$iMax;$i++) {
					$this->Fields[$i] = ''.$this->RecSet->Fields[$i]->Name;
				}
			}
			break;
		case 6: // Num
			$this->NumMin = 1;
			$this->NumStep = 1;
			If (is_array($Query)) {
				if (isset($Query['min'])) $this->NumMin = $Query['min'];
				if (isset($Query['step'])) $this->NumStep = $Query['step'];
				if (isset($Query['max'])) {
					$this->RecSet = $Query['max'];
				} else {
					$this->RecSet = tbs_Misc_Alert($this->AlertTitle,'The \'num\' source is an array that has no value for the \'max\' key.');
				}
				if ($this->NumStep==0) $this->RecSet = tbs_Misc_Alert($this->AlertTitle,'The \'num\' source is an array that has a step value set to zero.');
			} else {
				$this->RecSet = ceil($Query);
			}
			if ($this->RecSet!==false) $this->NumVal = $this->NumMin;
			break;
		case 7: // Custom function
			$FctOpen = $this->FctOpen;
			$this->RecSet = $FctOpen($this->SrcId,$Query);
			break;
		case 8: // PostgreSQL
			switch ($this->SubType) {
			case 0: $this->RecSet = @pg_query($this->SrcId,$Query); break;
			case 1: $this->RecSet = $this->SrcId; break;
			}
			if ($this->RecSet===false) tbs_Misc_Alert($this->AlertTitle,'PostgreSQL error message when opening the query: '.pg_last_error($this->SrcId));
			break;
		case 9: // SQLite
			switch ($this->SubType) {
			case 0: $this->RecSet = @sqlite_query($this->SrcId,$Query); break;
			case 1: $this->RecSet = $this->SrcId; break;
			}
			if ($this->RecSet===false) tbs_Misc_Alert($this->AlertTitle,'SQLite error message when opening the query:'.sqlite_error_string(sqlite_last_error($this->SrcId)));
			break;
		}	

		$this->RowNum = 0;
		return ($this->RecSet!==false);

	}
	
	function DataFetch() {
	
		switch ($this->Type) {
		case 0: // Array
			if ($this->RowNum<$this->Count) {
				if ($this->Reset) {
					$this->CurrRec = reset($this->RecSet);
					$this->Reset = false;
				} else {
					$this->CurrRec = next($this->RecSet);
				}
				if (!is_array($this->CurrRec)) $this->CurrRec = array('key'=>key($this->RecSet), 'val'=>$this->CurrRec);
			} else {
				$this->CurrRec = false;
			}
			break;
		case 1: // MySQL
				$this->CurrRec = mysql_fetch_assoc($this->RecSet);
			break;
		case 2: // ODBC, odbc_fetch_array -> Error with PHP 4.1.1
			$this->CurrRec = odbc_fetch_row($this->RecSet);
			if ($this->CurrRec) {
				$this->CurrRec = array();
				foreach ($this->Fields as $colid=>$colname) {
					$this->CurrRec[$colname] = odbc_result($this->RecSet,$colid);
				}
			}
			break;
		case 3: // MsSQL
			$this->CurrRec = mssql_fetch_array($this->RecSet);
			break;
		case 4: // Text
			if ($this->RowNum===0) {
				if ($this->RecSet==='') {
					$this->CurrRec = false;
				} else {
					$this->CurrRec = &$this->RecSet;
				}
			} else {
				$this->CurrRec = false;
			}
			break;
		case 5: // ADODB
		if ($this->RecSet->EOF()) {
				$this->CurrRec = false;
			} else {
				$this->CurrRec = array();
				foreach ($this->Fields as $colid=>$colname) {
					$this->CurrRec[$colname] = $this->RecSet->Fields[$colid]->Value;
				}
				$this->RecSet->MoveNext(); // brackets () must be there
			}
			break;
		case 6: // Num
			if ($this->NumVal<=$this->RecSet) {
				$this->CurrRec = array('val'=>$this->NumVal);
				$this->NumVal = $this->NumVal + $this->NumStep;
			} else {
				$this->CurrRec = false;
			}
			break;
		case 7: // Custom function
			$FctFetch = $this->FctFetch;
			$this->CurrRec = $FctFetch($this->RecSet,$this->RowNum+1);
			break;
		case 8: // PostgreSQL
			$this->CurrRec = @pg_fetch_array($this->RecSet,$this->RowNum,PGSQL_ASSOC); // warning comes when no record left.
			break;
		case 9: // SQLite
			$this->CurrRec = sqlite_fetch_array($this->RecSet,SQLITE_ASSOC);
			break;
		}	
	
		// Set the row count
		if ($this->CurrRec!==false) $this->RowNum++;
	
	}
	
	function DataClose() {
		switch ($this->Type) {
		case 1: mysql_free_result($this->RecSet); break;
		case 2: odbc_free_result($this->RecSet); break;
		case 3: mssql_free_result($this->RecSet); break;
		case 5: $this->RecSet->Close; break;
		case 7: $FctClose=$this->FctClose; $FctClose($this->RecSet); break;
		case 8: pg_free_result($this->RecSet); break;
		}
	
	}

}

//*******************************************************

// Find a TBS Field-Merge
function tbs_Locator_FindTbs(&$Txt,$Name,$Pos,$AcceptSub) {

	$PosEnd = false;
	$PosMax = strlen($Txt) -1;

 	do {

		// Search for the opening char
		if ($Pos>$PosMax) return false;
		$PosOpen = strpos($Txt,$GLOBALS['tbs_ChrOpen'],$Pos);
		
		// If found => next chars are analyzed
		if ($PosOpen===false) {
			return false;
		} else {
			$Pos = $PosOpen + 1;
			// Look if what is next the begin char is the name of the locator
			if (strcasecmp(substr($Txt,$PosOpen+1,strlen($Name)),$Name)===0) {
				$Loc = new clsTbsLocator;
				// Then we check if what is next the name of the merge is an expected char
				$ReadPrm = false;
				$PosX = $PosOpen + 1 + strlen($Name);
				$x = $Txt[$PosX];

				if ($x===$GLOBALS['tbs_ChrClose']) {
					$PosEnd = $PosX;
				} elseif ($AcceptSub and ($x==='.')) {
					$Loc->SubOk = true; // it is no longer the false value
					$ReadPrm = true;
					$PosX++;
				} elseif (strpos(';',$x)!==false) {
					$ReadPrm = true;
					$PosX++;
				}

				if ($ReadPrm) tbs_Locator_ReadPrm($Txt,$PosX,';','= ','\'','([{',')]}',$GLOBALS['tbs_ChrClose'],0,$Loc,$PosEnd);

			}
		}

	} while ($PosEnd===false);

	$Loc->PosBeg = $PosOpen;
	$Loc->PosEnd = $PosEnd;
	if ($Loc->SubOk) {
		$Loc->FullName = $Name.'.'.$Loc->SubName;
		$Loc->SubLst = explode('.',$Loc->SubName);
		$Loc->SubNbr = count($Loc->SubLst);
	} else {
		$Loc->FullName = $Name;
	}
	if ($ReadPrm and isset($Loc->PrmLst['comm'])) {
		$Loc->PosBeg0 = $Loc->PosBeg;
		$Loc->PosEnd0 = $Loc->PosEnd;
		$Loc->Enlarged = tbs_Locator_EnlargeToStr($Txt,$Loc,'<!--' ,'-->');
	}

	return $Loc;

}


// This function reads parameters that follow the Begin Position, and it returns parameters in an array
function tbs_Locator_ReadPrm(&$Txt,$Pos,$ChrsPrm,$ChrsEqu,$ChrsStr,$ChrsOpen,$ChrsClose,$ChrEnd,$LenMax,&$Loc,&$PosEnd) {
/*
$Pos       : position in $Txt where the scan begins
$ChrsPrm   : a string that contains all characters that can be a parameter separator (typically : space and ;)
$ChrsEqu   : a string that contains all characters that can be an equal symbol (used to get prm value )
$ChrsStr   : a string that contains all characters that can be a string delimiters (typically : ' and ")
$ChrsOpen  : a string that contains all characters that can be an opening bracket (typically : ( )
$ChrsClose : a string that contains all characters that can be an closing bracket (typically : ( )
$ChrEnd    : the character that marks the end of the parameters list.
$LenMax    : the maximum of characters to read (enables to not read all document when parameters have an unvalide syntax)
$Loc       : the current TBS locator
$PosEnd    : (returned value) the position of the $ChrEnd in the $Txt string
*/

	// variables initialisation
	$PosCur = $Pos;           // The cursor position
	$PosBuff = true;          // True if the current char has to be added to the buffer
	$PosEnd = false;          // True if the end char has been met
	$PosMax = strlen($Txt)-1; // The max position that the cursor can go
	if ($LenMax>0) {
		if ($PosMax>$PosDeb+$LenMax) {
			$PosMax = $PosDeb+$LenMax;
		}
	}

	$PrmNbr = 0;
	$PrmName = '';
	$PrmBuff = '';
	$PrmPosBeg = false;
	$PrmPosEnd = false;
	$PrmEnd  = false;
	$PrmPosEqu  = false; // Position of the first equal symbol
	$PrmChrEqu  = '';    // Char of the first equal symbol
	$PrmCntOpen = 0;     // Number of bracket inclusion. 0 means no bracket encapuslation.
	$PrmIdxOpen = false; // Index of the current opening bracket in the $ChrsOpen array. False means we are not inside a bracket.
	$PrmCntStr = 0;      // Number of string delimiters found.
	$PrmIdxStr = false;  // Index of the current string delimiter. False means we are not inside a string.
	$PrmIdxStr1 = false; // Save the first string delimiter found.

	do {

		if ($PosCur>$PosMax) return;

		if ($PrmIdxStr===false) {

			// we are not inside a string, we check if it's the begining of a new string
			$PrmIdxStr = strpos($ChrsStr,$Txt[$PosCur]);

			if ($PrmIdxStr===false) {
				// we are not inside a string, we check if we are not inside brackets
				if ($PrmCntOpen===0) {
					// we are not inside brackets
					if ($Txt[$PosCur]===$ChrEnd) {// we check if it's the end of the parameters list
						$PosEnd = $PosCur;
						$PrmEnd = true;
						$PosBuff = false;
					} elseif (strpos($ChrsEqu,$Txt[$PosCur])!==false) { // we check if it's an equal symbol
							if ($PrmPosEqu===false) {
							if (trim($PrmBuff)!=='') {
								$PrmPosEqu = $PosCur;
								$PrmChrEqu = $Txt[$PosCur];
							}
						} elseif ($PrmChrEqu===' ') {
							if ($PosCur==$PrmPosEqu+1) {
								$PrmPosEqu = $PosCur;
								$PrmChrEqu = $Txt[$PosCur];
							}
						}
					} elseif (strpos($ChrsPrm,$Txt[$PosCur])!==false) { // we check if it's a parameter separator
						$PosBuff = false;
						if ($Txt[$PosCur]===' ') {// The space char can be a parameter separator only in HTML locators
							if ($PrmBuff!=='') {
								$PrmEnd = true;
							}
						} else { //-> if ($Txt[$PosCur]===' ') {...
							// We have a ';' separator
							$PrmEnd = true;
						}
					} else {
						// check if it's an opening bracket
						$PrmIdxOpen = strpos($ChrsOpen,$Txt[$PosCur]);
						if ($PrmIdxOpen!==false) {
							$PrmCntOpen++;
						}
					}
				} else { //--> if ($PrmCntOpen==0)
					// we are inside brackets, we have to check if there is another opening bracket or a closing bracket
					if ($Txt[$PosCur]===$ChrsOpen[$PrmIdxOpen]) {
						$PrmCntOpen++;
					} elseif ($Txt[$PosCur]===$ChrsClose[$PrmIdxOpen]) {
						$PrmCntOpen--;
					}
				}
			} else { //--> if ($IdxStr===false)
				// we meet a new string
				$PrmCntStr++; // count the number of string delimiters met for the current parameter
				if ($PrmCntStr===1) $PrmIdxStr1=$PrmIdxStr; // save the first delimiter for the current parameter
			} //--> if ($IdxStr===false)

		} else { //--> if ($IdxStr===false)

			// we are inside a string,

			if ($Txt[$PosCur]===$ChrsStr[$PrmIdxStr]) {// we check if we are on a char delimiter
				if ($PosCur===$PosMax) {
					$PrmIdxStr = false;
				} else {
					// we check if the next char is also a string delimiter, is it's so, the string continue
					if ($Txt[$PosCur+1]===$ChrsStr[$PrmIdxStr]) {
						$PosCur++; // the string continues
					} else {
						$PrmIdxStr = false; // the string ends
					}
				}
			}

		} //--> if ($IdxStr===false)

		// Check if it's the end of the scan
		if ($PosEnd===false) {
			if ($PosCur>=$PosMax) {
				$PosEnd = $PosCur; // end of the scan
				$PrmEnd = true;
			}
		}

		// Add the current char to the buffer
		if ($PosBuff) {
			$PrmBuff .= $Txt[$PosCur];
			if ($PrmPosBeg===false) $PrmPosBeg = $PosCur;
			$PrmPosEnd = $PosCur;
		} else {
			$PosBuff = true;
		}

		// analyze the current parameter
		if ($PrmEnd===true) {
			if (strlen($PrmBuff)>0) {
				if (($PrmNbr===0) and ($Loc->SubOk) ) {
					// Set the SubName value
					$Loc->SubName = $PrmBuff;
					$PrmEquMode = 0;
				} else {
					if ($PrmPosEqu===false) {
						$PrmName = trim($PrmBuff);
						$PrmBuff = true;
					} else {
						$PrmName = trim(substr($PrmBuff,0,$PrmPosEqu-$PrmPosBeg));
						$PrmBuff = trim(substr($PrmBuff,$PrmPosEqu-$PrmPosBeg+1));
						if ($PrmCntStr===1) tbs_Misc_DelDelimiter($PrmBuff,$ChrsStr[$PrmIdxStr1]);
					}
					$Loc->PrmLst[$PrmName] = $PrmBuff;
				}
				$PrmNbr++; // Useful for subname identification
				$PrmBuff = '';
				$PrmPosBeg = false;
				$PrmCntStr = 0;
				$PrmCntOpen = 0;
				$PrmIdxStr = false;
				$PrmIdxOpen = false;
				$PrmPosEqu = false;
			}
			$PrmEnd  = false;
		}

		// next char
		$PosCur++;

	} while ($PosEnd===false);

}

// This function enables to enlarge the pos limits of the Locator.
// If the search result is not correct, $PosBeg must not change its value, and $PosEnd must be False.
// This is because of the calling function.
function tbs_Locator_EnlargeToStr(&$Txt,&$Loc,$StrBeg,$StrEnd) {

	// Search for the begining string
	$Pos = $Loc->PosBeg;
	$Ok = false;
	do {
		$Pos = strrpos(substr($Txt,0,$Pos),$StrBeg[0]);
		if ($Pos!==false) {
			if (substr($Txt,$Pos,strlen($StrBeg))===$StrBeg) $Ok = true;
		}
	} while ( (!$Ok) and ($Pos!==false) );

	if ($Ok) {
		$PosEnd = strpos($Txt,$StrEnd,$Loc->PosEnd + 1);
		if ($PosEnd===false) {
			$Ok = false;
		} else {
			$Loc->PosBeg = $Pos;
			$Loc->PosEnd = $PosEnd + strlen($StrEnd) - 1;
		}
	}

	return $Ok;

}

function tbs_Locator_EnlargeToTag(&$Txt,&$Loc,$Tag,$Encaps,$Extend,$ReturnSrc) {

	if ($Tag==='') { return false; }
	elseif ($Tag==='row') {$Tag = 'tr'; }
	elseif ($Tag==='opt') {$Tag = 'option'; }

	$Ok = false;

	$TagO = tbs_Html_FindTag($Txt,$Tag,true,$Loc->PosBeg-1,false,$Encaps,false);
	if ($TagO!==false) {
		// Search for the closing tag
		$TagC = tbs_Html_FindTag($Txt,$Tag,false,$Loc->PosEnd+1,true,$Encaps,false);
		if ($TagC!==false) {
			
			// It's ok, we get the text string between the locators (including the locators!)
			$Ok = true;
			$PosBeg = $TagO->PosBeg;
			$PosEnd = $TagC->PosEnd;

			// Extend
			if ($Extend===0) {
				if ($ReturnSrc) {
					$Ok = '';
					if ($Loc->PosBeg>$TagO->PosEnd) $Ok .= substr($Txt,$TagO->PosEnd+1,min($Loc->PosBeg,$TagC->PosBeg)-$TagO->PosEnd-1);
					if ($Loc->PosEnd<$TagC->PosBeg) $Ok .= substr($Txt,max($Loc->PosEnd,$TagO->PosEnd)+1,$TagC->PosBeg-max($Loc->PosEnd,$TagO->PosEnd)-1);
				}
			} else { // Forward
				$TagC = true;
				for ($i=$Extend;$i>0;$i--) {
					if ($TagC!==false) {
						$TagO = tbs_Html_FindTag($Txt,$Tag,true,$PosEnd+1,true,1,false);
						if ($TagO!==false) {
							$TagC = tbs_Html_FindTag($Txt,$Tag,false,$TagO->PosEnd+1,true,0,false);
							if ($TagC!==false) {
								$PosEnd = $TagC->PosEnd;
							}
						}
					}
				}
				$TagO = true;
				for ($i=$Extend;$i<0;$i++) { // Backward
					if ($TagO!==false) {
						$TagC = tbs_Html_FindTag($Txt,$Tag,false,$PosBeg-1,false,1,false);
						if ($TagC!==false) {
							$TagO = tbs_Html_FindTag($Txt,$Tag,true,$TagC->PosBeg-1,false,0,false);
							if ($TagO!==false) {
								$PosBeg = $TagO->PosBeg;
							}
						}
					}
				}
			} //-> if ($Extend!==0) {

			$Loc->PosBeg = $PosBeg;
			$Loc->PosEnd = $PosEnd;
			
		}
	}

	return $Ok;

}

// Search and cache TBS locators founded in $Txt.
function tbs_Locator_SectionCache(&$Txt,&$BlockName,&$LocR) {
	
	$LocR->BlockNbr++;
	$LocR->BlockName[$LocR->BlockNbr] = $BlockName;
	$LocR->BlockSrc[$LocR->BlockNbr] = $Txt;
	$LocR->BlockLoc[$LocR->BlockNbr] = array();
	$LocR->BlockChk[$LocR->BlockNbr] = false;

	if (isset($GLOBALS['tbs_TurboBlock']) and (!$GLOBALS['tbs_TurboBlock'])) {
		$LocR->BlockLoc[$LocR->BlockNbr][0] = 0;
		$LocR->BlockChk[$LocR->BlockNbr] = true;
		return;
	}

	$LocLst = &$LocR->BlockLoc[$LocR->BlockNbr];

	$Pos = 0;
	$PrevEnd = -1;
	$Nbr = 0;
	while ($Loc = tbs_Locator_FindTbs($Txt,$BlockName,$Pos,true)) {
		if ($Loc->SubName==='#') {
			$Loc->IsRecNum = true;
			$Loc->SubName = '';
		} else {
			$Loc->IsRecNum = false;
		}
		if ($Loc->PosBeg>$PrevEnd) {
			// The previous tag is not embeding => increment
			$Nbr++;
		} else {
			// The previous tag is embeding => no increment, then previous is over writed
			$LocR->BlockChk[$LocR->BlockNbr] = true;
		}
		$PrevEnd = $Loc->PosEnd;
		if ($Loc->Enlarged) { // Parameter 'comm'
			$Pos = $Loc->PosBeg0+1;
			$Loc->Enlarged = false;
		} else {
			$Pos = $Loc->PosBeg+1;
		}
		$LocLst[$Nbr] = $Loc;
	}
	
	$LocLst[0] = $Nbr;
	
}

// This function enables to merge a locator with a text and returns the position just after the replaced block
// This position can be useful because we don't know in advance how $Value will be replaced.
function tbs_Locator_Replace(&$Txt,&$HtmlCharSet,&$Loc,&$Value,$CheckSub) {

	// Found the value if there is a subname
	if ($CheckSub and $Loc->SubOk) {
		$SubId = 0;
		while ($SubId<$Loc->SubNbr) {
			$x = $Loc->SubLst[$SubId]; // &$Loc... brings an error with Event Example, I don't know why.
			if (is_array($Value)) {
				if (isset($Value[$x])) {
					$Value = &$Value[$x];
				} elseif (array_key_exists($x,$Value)) {// can happens when value is NULL
					$Value = &$Value[$x];
				} else {	
					unset($Value); $Value = '';
					if (!isset($Loc->PrmLst['noerr'])) tbs_Misc_Alert('Array value','Can\'t merge '.$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].' because there is no key named \''.$x.'\'.',true);
				}
				$SubId++;
			} elseif (is_object($Value)) {
				if (method_exists($Value,$x)) {
					$x = call_user_func(array(&$Value,$x));
				} else {
					$x = $Value->$x;
				}
				$Value = &$x; unset($x);
				$SubId++;
			} else {
				if (isset($Loc->PrmLst['selected'])) {
					$SelArray = &$Value;
				} else {
					if (!isset($Loc->PrmLst['noerr'])) tbs_Misc_Alert('Object or Array value expected','Can\'t merge '.$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].' because the value before the key \''.$x.'\' (type: '.gettype($Value).') is not an object or an array.',true);
				}
				unset($Value); $Value = '';
				$SubId = $Loc->SubNbr;
			}
		}
	}

	$CurrValSave = &$GLOBALS['tbs_CurrVal'];
	$CurrVal = $Value;
	$GLOBALS['tbs_CurrVal'] = &$CurrVal;

	$Select = false;	
	$HtmlConv = true;
	$BrConv = true;   // True if we have to convert nl to br with Html conv.
	$WhiteSp = false; // True if we have to preserve whitespaces
	$EmbedVal = false;// Value to embed in the current val
	$Script = true;   // False to ignore script execution
	$Protect = true;  // Default value for common field

	// File inclusion
	if (isset($Loc->PrmLst['file'])) {
		$File = $Loc->PrmLst['file'];
		tbs_Misc_ReplaceVal($File,$CurrVal);
		tbs_Merge_PhpVar($File,$GLOBALS['_tbs_False']); // The file definition may contains PHPVar field
		$OnlyBody = true;
		if (isset($Loc->PrmLst['htmlconv'])) {
			if (strtolower($Loc->PrmLst['htmlconv'])==='no') {
				$OnlyBody = false; // It's a text file, we don't get the BODY part
			}
		}
		if (tbs_Misc_GetFile($CurrVal,$File)) {
			if ($OnlyBody) $CurrVal = tbs_Html_GetPart($CurrVal,'BODY',false,true);
		} else {
			$CurrVal = '';
			if (!isset($Loc->PrmLst['noerr'])) tbs_Misc_Alert('Parameter \'file\'','Field '.$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].' : unable to read the file \''.$File.'\'.',true);
		}
		$HtmlConv = false;
		$Protect = false; // Default value for file inclusion
	}

	// OnFormat event
	if (isset($Loc->PrmLst['onformat'])) {
		$OnFormat = $Loc->PrmLst['onformat'];
		if (function_exists($OnFormat)) {
			$OnFormat($Loc->FullName,$CurrVal);
		} else {
			if (!isset($Loc->PrmLst['noerr'])) tbs_Misc_Alert('Parameter \'onformat\'','Field '.$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].' : the function \''.$OnFormat.'\' doesn\'t exist.',true);
		}
	}

	// Select a value in a HTML option list
	if (isset($Loc->PrmLst['selected'])) {
		$Select = true;
		if (is_array($CurrVal)) {
			$SelArray = &$CurrVal;
			unset($CurrVal); $CurrVal = ' ';
		} else {
			$SelArray = false;
		}
	}

	// Convert the value to a string, use format if specified
	if (isset($Loc->PrmLst['frm'])) {
		$CurrVal = tbs_Misc_Format($Loc,$CurrVal);
		$HtmlConv = false;
	} else {
		if (!is_string($CurrVal)) $CurrVal = strval($CurrVal);
	}

	// case of an 'if' 'then' 'else' options
	if (isset($Loc->PrmLst['if'])) {
		$x = $Loc->PrmLst['if'];
		tbs_Misc_ReplaceVal($x,$CurrVal);
		if (tbs_Misc_CheckCondition($x)) {
			if (isset($Loc->PrmLst['then'])) {
				$EmbedVal = $CurrVal;
				$CurrVal = $Loc->PrmLst['then'];
			} // else -> it's the given value
		} else {
			$Script = false;
			if (isset($Loc->PrmLst['else'])) {
				$EmbedVal = $CurrVal;
				$CurrVal = $Loc->PrmLst['else'];
			} else {
				$CurrVal = '';
				$Protect = false; // Only because it is empty
			}
		}
	}

	if ($Script) {// Include external PHP script
		if (isset($Loc->PrmLst['script'])) {
			$File = $Loc->PrmLst['script'];
			tbs_Misc_ReplaceVal($File,$CurrVal);
			tbs_Merge_PhpVar($File,$GLOBALS['_tbs_False']); // The file definition may contains PHPVar field
			if (isset($Loc->PrmLst['getob'])) ob_start();
			$tbs_CurrVal = &$CurrVal; // For compatibility with TBS<1.90. The included script uses local variable and $tbs_CurrVal was told to be available.
			if (isset($Loc->PrmLst['once'])) {
				include_once($File);
			} else {
				include($File);
			}
			if (isset($Loc->PrmLst['getob'])) {
				$CurrVal = ob_get_contents();
				ob_end_clean();
			}
			$HtmlConv = false;
		}
	}

	// Check HtmlConv parameter
	if (isset($Loc->PrmLst['htmlconv'])) {
		$x = strtolower($Loc->PrmLst['htmlconv']);
		$x = '+'.str_replace(' ','',$x).'+';
		if (strpos($x,'+no+')!==false) $HtmlConv = false;
		if (strpos($x,'+yes+')!==false) $HtmlConv = true;
		if (strpos($x,'+nobr+')!==false) { $HtmlConv = true; $BrConv = false; }
		if (strpos($x,'+esc+')!==false) { $HtmlConv = false; $CurrVal = str_replace('\'','\'\'',$CurrVal); }
		if (strpos($x,'+wsp+')!==false) $WhiteSp = true;
		if (strpos($x,'+look+')!==false) {
			if (tbs_Html_IsHtml($CurrVal)) {
				$HtmlConv = false;
				$CurrVal = tbs_Html_GetPart($CurrVal,'BODY',false,true);
			} else {
				$HtmlConv = true;
			}
		}
	} else {
		if ($HtmlCharSet===false) $HtmlConv = false; // No HTML
	}

	// MaxLength
	if (isset($Loc->PrmLst['max'])) {
		$x = intval($Loc->PrmLst['max']);
		if (strlen($CurrVal)>$x) {
			if ($HtmlConv or ($HtmlCharSet===false)) {
				$CurrVal = substr($CurrVal,0,$x-1).'...';
			} else {
				tbs_Html_Max($CurrVal,$x);
			}
		}
	}

	// HTML conversion
	if ($HtmlConv) {
		tbs_Html_Conv($CurrVal,$HtmlCharSet,$BrConv,$WhiteSp);
		if ($EmbedVal!==false) tbs_Html_Conv($EmbedVal,$HtmlCharSet,$BrConv,$WhiteSp);
	}

	// We protect the data that does not come from the source of the template
	// Explicit Protect parameter
	if (isset($Loc->PrmLst['protect'])) {
		$x = strtolower($Loc->PrmLst['protect']);
		switch ($x) {
		case 'no' : $Protect = false; break;
		case 'yes': $Protect = true; break;
		}
	}
	if ($Protect) {
		if ($EmbedVal===false) {
			$CurrVal = str_replace($GLOBALS['tbs_ChrOpen'],$GLOBALS['tbs_ChrProtect'],$CurrVal);
		} else {
			// We must not protec the data wich comes from the source of the template, only the embeded value
			$EmbedVal = str_replace($GLOBALS['tbs_ChrOpen'],$GLOBALS['tbs_ChrProtect'],$EmbedVal);
			tbs_Misc_ReplaceVal($CurrVal,$EmbedVal);
		}
	}

	// Case when it is an empty string
	if ($CurrVal==='') {
		if (isset($Loc->PrmLst['.'])) {
			$CurrVal = '&nbsp;'; // Enables to avoid blanks in HTML tables
		} elseif (isset($Loc->PrmLst['ifempty'])) {
			$CurrVal = $Loc->PrmLst['ifempty'];
		}
	}

	// Parameter 'friend'
	if ($CurrVal==='') {
		if ($Loc->EnlargeType===false) {
			if (isset($Loc->PrmLst['friend2']))       { $Loc->EnlargeType = 2;
			} elseif (isset($Loc->PrmLst['friend']))  { $Loc->EnlargeType = 1;
			} elseif (isset($Loc->PrmLst['frienda'])) { $Loc->EnlargeType = 4;
			} elseif (isset($Loc->PrmLst['friendb'])) { $Loc->EnlargeType = 3;
			} else { $Loc->EnlargeType = 0;
			}
			if ($Loc->EnlargeType!==0) {
				$Loc->PosBeg0 = $Loc->PosBeg;
				$Loc->PosEnd0 = $Loc->PosEnd;
			}
		}
		if ($Loc->EnlargeType!==0) {
			$Loc->Enlarged = true;
			if ($Loc->EnlargeType===1) {
				tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['friend'],1,0,false);
			} elseif ($Loc->EnlargeType===2) {
				$CurrVal = tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['friend2'],1,0,true);
			} elseif ($Loc->EnlargeType===3) {
				$Loc2 = tbs_Html_FindTag($Txt,$Loc->PrmLst['friendb'],true,$Loc->PosBeg,false,1,false);
				if ($Loc2!==false) {
					$Loc->PosBeg = $Loc2->PosBeg;
					if ($Loc->PosEnd<$Loc2->PosEnd) $Loc->PosEnd = $Loc2->PosEnd;
				}
			} elseif ($Loc->EnlargeType===4) {
				$Loc2 = tbs_Html_FindTag($Txt,$Loc->PrmLst['frienda'],true,$Loc->PosBeg,true,1,false);
				if ($Loc2!==false) $Loc->PosEnd = $Loc2->PosEnd;
			}
		}
	}		

	$Txt = substr_replace($Txt,$CurrVal,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
	$NewEnd = $Loc->PosBeg + strlen($CurrVal);

	if ($Select) tbs_Html_MergeItems($Txt,$Loc,$CurrVal,$SelArray,$NewEnd);

	$GLOBALS['tbs_CurrVal'] = &$CurrValSave; // Restore saved value. This is useful for field inclusion.
	return $NewEnd; // Returns the new end position of the field

}

// Return the first block locator object just after the PosBeg position
function &tbs_Locator_FindBlockNext(&$Txt,$BlockName,$PosBeg,&$P1) {

	$Invalide = true;
	
	// Search for the first tag with parameter "block"
	do {
		$Loc = tbs_Locator_FindTbs($Txt,$BlockName,$PosBeg,true);
		if ($Loc!==false) {
			if (isset($Loc->PrmLst['block'])) {
				$Block = $Loc->PrmLst['block'];
				if ($P1) {
					if (isset($Loc->PrmLst['p1'])) return false;
				} else {
					if (isset($Loc->PrmLst['p1'])) $P1 = true;
				}
				$Invalide = false;
			}
			$PosBeg = $Loc->PosEnd;
		}
	} while ($Invalide and ($Loc!==false));
	
	if ($Invalide) return false;
	
	if ($Block==='begin') { // Block definied using begin/end

		while (($Loc->BlockFound===false) and ($Loc2 = tbs_Locator_FindTbs($Txt,$BlockName,$PosBeg,true))) {
			if (isset($Loc2->PrmLst['block']) and ($Loc2->PrmLst['block']==='end')) {
				$Loc->BlockFound = true;
				$Loc->BlockSrc = substr($Txt,$Loc->PosEnd+1,$Loc2->PosBeg-$Loc->PosEnd-1);
				$Loc->PosEnd = $Loc2->PosEnd;
			} else {
				$PosBeg = $Loc2->PosEnd;
			}
		}
		
		if ($Loc->BlockFound) {
			return $Loc;
		} else {
			return tbs_Misc_Alert('Block definition',$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].'] has a \'begin\' tag, but no \'end\' tag found.');
		}
		
	} else {
		
		// Syntax
		if (!$Loc->SubOk) {
			$PosBeg1 = $Loc->PosBeg;
			$PosEnd1 = $Loc->PosEnd;
		}

		// Enlarge the block
		if (isset($Loc->PrmLst['encaps'])) {
			$Encaps = abs(intval($Loc->PrmLst['encaps']));
		} else {
			$Encaps = 1;
		}
		if (isset($Loc->PrmLst['extend'])) {
			$Extend = intval($Loc->PrmLst['extend']);
		} else {
			$Extend = 0;
		}

		$Invalide = !tbs_Locator_EnlargeToTag($Txt,$Loc,$Block,$Encaps,$Extend,false);
		if ($Invalide) return tbs_Misc_Alert('Block definition',$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].' can not be enlarged or extended.');

	}

	if ($Loc->SubOk) {
		$Loc->BlockSrc = substr($Txt,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
	} else {
		$Loc->BlockSrc = substr($Txt,$Loc->PosBeg,$PosBeg1-$Loc->PosBeg).substr($Txt,$PosEnd1+1,$Loc->PosEnd-$PosEnd1);		
	}

	$Loc->BlockFound = true;
	return $Loc;

}

// Return a locator object covering all block definitions, even if there is no block definition found.
function &tbs_Locator_FindBlockLst(&$Txt,$BlockName,$Pos) {

	$LocR = new clsTbsLocator;
	$LocR->P1 = false;
	$LocR->OnSection = false;
	$LocR->BlockNbr = 0;
	$LocR->BlockSrc = array(); // 1 to BlockNbr
	$LocR->BlockLoc = array(); // 1 to BlockNbr
	$LocR->BlockChk = array(); // 1 to BlockNbr
	$LocR->BlockName = array(); // 1 to BlockNbr
	$LocR->NoDataSrc = '';
	$LocR->SpecialBid = false;
	$LocR->HeaderFound = false;
	$LocR->HeaderNbr = 0;
	$LocR->HeaderBid = array();       // 1 to HeaderNbr
	$LocR->HeaderField = array();     // 1 to HeaderNbr
	$LocR->HeaderPrevValue = array(); // 1 to HeaderNbr
	$LocR->SectionNbr = 0;
	$LocR->SectionBid = array();       // 1 to SectionNbr
	$LocR->SectionIsSerial = array();  // 1 to SectionNbr
	$LocR->SectionSerialBid = array(); // 1 to SectionNbr
	$LocR->SectionSerialOrd = array(); // 1 to SectionNbr
	$LocR->SerialEmpty = false;
	
	$Bid = &$LocR->BlockNbr;
	$Sid = &$LocR->SectionNbr;

	while ($Loc = tbs_Locator_FindBlockNext($Txt,$BlockName,$Pos,$LocR->P1)) {

		$Pos = $Loc->PosEnd;

		// Define the block limits
		if ($LocR->BlockFound) {
			if ( $LocR->PosBeg > $Loc->PosBeg ) $LocR->PosBeg = $Loc->PosBeg;
			if ( $LocR->PosEnd < $Loc->PosEnd ) $LocR->PosEnd = $Loc->PosEnd;
		} else {
			$LocR->BlockFound = true;
			$LocR->PosBeg = $Loc->PosBeg;
			$LocR->PosEnd = $Loc->PosEnd;
		}

		// Merge block parameters
		if (count($Loc->PrmLst)>0) $LocR->PrmLst = array_merge($LocR->PrmLst,$Loc->PrmLst);
		
		// Save the block and cache its tags (incrments $LocR->BlockNbr)
		tbs_Locator_SectionCache($Loc->BlockSrc,$BlockName,$LocR);
		
		// Add the text int the list of blocks
		if (isset($Loc->PrmLst['nodata'])) {
			// Nodata section
			$LocR->NoDataSrc = &$LocR->BlockSrc[$Bid];
		} elseif (isset($Loc->PrmLst['currpage'])) {
			// Special section (used for navigation bar)
			$LocR->SpecialBid = $Bid;
		} elseif (isset($Loc->PrmLst['headergrp'])) {
			// Header
			$LocR->HeaderFound = true;
			$LocR->HeaderNbr++;
			$LocR->HeaderBid[$LocR->HeaderNbr] = $Bid;
			$LocR->HeaderField[$LocR->HeaderNbr] = strtolower($Loc->PrmLst['headergrp']);
			$LocR->HeaderPrevValue[$LocR->HeaderNbr] = false;
		} elseif (isset($Loc->PrmLst['serial'])) {
			// Section	with Serial Sub-Sections
			$Src = &$LocR->BlockSrc[$Bid];
			$Loc0 = false;
			if ($LocR->SerialEmpty===false) {
				$NameSr = $BlockName.'_0';
				$x = false;
				$LocSr = tbs_Locator_FindBlockNext($Src,$NameSr,0,$x);
				if ($LocSr!==false) {
					$LocR->SerialEmpty = $LocSr->BlockSrc;
					$Src = substr_replace($Src,'',$LocSr->PosBeg,$LocSr->PosEnd-$LocSr->PosBeg+1);
				}
			}
			$NameSr = $BlockName.'_1';
			$x = false;
			$LocSr = tbs_Locator_FindBlockNext($Src,$NameSr,0,$x);
			if ($LocSr!==false) {
				$Sid++;
				$LocR->SectionBid[$Sid] = $Bid;
				$LocR->SectionIsSerial[$Sid] = true;
				$LocR->SectionSerialBid[$Sid] = array();
				$LocR->SectionSerialOrd[$Sid] = array();
				$SrBid = &$LocR->SectionSerialBid[$Sid];
				$SrOrd = &$LocR->SectionSerialOrd[$Sid];
				$BidParent = $Bid;
				$SrNum = 1;
				do {
					// Save previous sub-section
					$LocR->BlockLoc[$BidParent][$SrNum] = $LocSr;
					tbs_Locator_SectionCache($LocSr->BlockSrc,$NameSr,$LocR);
					$SrBid[$SrNum] = $Bid;
					$SrOrd[$SrNum] = $SrNum;
					$i = $SrNum;
					while (($i>1) and ($LocSr->PosBeg<$LocR->BlockLoc[$BidParent][$i-1]->PosBeg)) {
						$SrOrd[$i] = $SrOrd[$i-1];
						$SrOrd[$i-1] = $SrNum;
						$i--;
					}
					// Search next section
					$SrNum++;
					$NameSr = $BlockName.'_'.$SrNum;
					$x = false;
					$LocSr = tbs_Locator_FindBlockNext($Src,$NameSr,0,$x);
				} while ($LocSr!==false);
				$SrBid[0] = $SrNum-1;
			}
		} else {
			// Normal section
			$Sid++;
			$LocR->SectionBid[$Sid] = $Bid;
			$LocR->SectionIsSerial[$Sid] = false;
		}
	
	}

	if ($LocR->BlockFound===false) {
		$LocR->PosBeg = 0;
		$LocR->PosEnd = strlen($Txt) - 1;
		tbs_Locator_SectionCache($Txt,$BlockName,$LocR);
		$LocR->SectionNbr = 1;
		$LocR->SectionBid[1] = $Bid;
		$LocR->SectionIsSerial[1] = false;
		$LocR->NoDataSrc = &$LocR->BlockSrc[$Bid];
	} else {
		if (isset($LocR->PrmLst['onsection'])) {
			$LocR->OnSectionFct = $LocR->PrmLst['onsection'];
			if (function_exists($LocR->OnSectionFct)) {
				$LocR->OnSection = true;
			} else {
				tbs_Misc_Alert('Block definition \''.$BlockName.'\'','Unvalide value for the \'onsection\' parameter of the block The block '.$GLOBALS['tbs_ChrOpen'].$BlockName.$GLOBALS['tbs_ChrClose'].'. The function \''.$LocR->OnSectionFct.'\' is not found.');
			}
		}
	}

	return $LocR;

}

// Merge all the occurences of a field-locator in the text string
// Returns the number of fields found.
function tbs_Merge_Field(&$Txt,&$HtmlCharSet,$Name,&$Value,$AcceptSub,$CheckSub,$RecNum) {

	$Nbr = 0;
	$PosBeg = 0;

	if ($RecNum===false) {
		while ($Loc = tbs_Locator_FindTbs($Txt,$Name,$PosBeg,$AcceptSub)) {
			$PosBeg = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$Value,$CheckSub);
			$Nbr++;
		}
	} else {
		while ($Loc = tbs_Locator_FindTbs($Txt,$Name,$PosBeg,$AcceptSub)) {
			if ($Loc->SubName==='#') {
				$PosBeg = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$RecNum,false);
			} else {
				$PosBeg = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$Value,$CheckSub);
			}
			$Nbr++;
		}
	}
	
	return $Nbr;

}

function tbs_Merge_Block(&$Txt,&$HtmlCharSet,&$BlockName,&$SrcId,&$Query,$PageSize,$PageNum,$RecKnown) {

	$Pos = 0;
	$RowTot = 0;
	$Query0 = false; // a not False value means they are parameters
	$QueryOk = true;

	// Get source type and info
	$Src = new clsTbsDataSource;
	if (!$Src->DataPrepare($SrcId,$BlockName)) return 0;

	$CurrRecSave = &$GLOBALS['tbs_CurrRec'];
	$GLOBALS['tbs_CurrRec'] = &$Src->CurrRec;

	do {

		$RowStop = 0; // Stop the merge after this row
		$RowSpe = 0;  // Row with a special block's definition (used for the navigation bar)
		
		// Search the block
		$LocR = tbs_Locator_FindBlockLst($Txt,$BlockName,$Pos);

		if ($LocR->BlockFound) {
			if ($LocR->SpecialBid!==false) $RowSpe = $RecKnown;
			if ($LocR->P1 and ($Query0===false)) $Query0 = $Query; // Save the query definition
		} else {
			$RowStop = 1; // Merge only the first record
		}

		// Replace parameters
		if ($Query0!==false) {
			if ($LocR->BlockFound===false) {
				$Query0 = false; // End of the loop
				$QueryOk = false;
			} else {
				$Query = $Query0;
				$i = 1;
				do {
					$x = 'p'.$i;
					if (isset($LocR->PrmLst[$x])) {
						$Query = str_replace('%p'.$i.'%',$LocR->PrmLst[$x],$Query);
						$i++;
					} else {
						$i = false;
					}
				} while ($i!==false);
			}
		}

		// Open the recordset
		if ($QueryOk) {
			$QueryOk = $Src->DataOpen($Query);
			if (!$QueryOk) {
				$GLOBALS['tbs_CurrRec'] = &$CurrRecSave;
				return $RowTot;
			}
		}

		if ($QueryOk) {
			if ($Src->Type===4) { // Special for Text merge
				if ($LocR->BlockFound) {
					$Src->RowNum = 1;
					$Src->CurrRec = false;
					if ($LocR->OnSection) {
						$Fct = $LocR->OnSectionFct;
						$Fct($LocRs,$Src->CurrRec,$Src->RecSet,$Src->RowNum);
					}
					$Txt = substr_replace($Txt,$Src->RecSet,$LocR->PosBeg,$LocR->PosEnd-$LocR->PosBeg+1);
				} else {
					tbs_Misc_Alert($Src->AlertTitle,'Can\'t merge the block with a text value because the block definition is not found.');
				}
			} else { // Other data source type

				$Src->CurrRec = array();

				// Move to the asked Page
				if ($PageSize>0) {
					if ($PageNum>0) {
						// We pass all record until the asked page
						$RowStop = ($PageNum-1) * $PageSize;
						while ($Src->RowNum<$RowStop) {
							$Src->DataFetch();
							if ($Src->CurrRec===false) $RowStop=$Src->RowNum;	
						}
						if ($Src->CurrRec!==false) $RowStop = $PageNum * $PageSize;
					} else {
						if ($PageNum==-1) { // Goto end of the recordset
							// Read records, saving the last page in $x
							$i = 0;
							while ($Src->CurrRec!==false) {
								$Src->DataFetch();
								if ($Src->CurrRec!==false) {
									$i++;
									if ($i>$PageSize) {
										$x = array();
										$i = 1;
									}
									$x[] = $Src->CurrRec;
								}
							}
							// Close the real recordset source
							$Src->DataClose();
							// Open a new recordset on the array
							$Src->Type = 0; // Array
							$Src->SubType = 0;
							$Src->DataOpen($Query);
							// Modify info in order to make the DataFetch() method work properly
							$Src->Count = $Src->RowNum;
							$Src->RowNum = $Src->RowNum - $i;
							$Src->CurrRec = array();
							$x = '';
						} else {
							$RowStop = 1;
							$PageCnt = 1;
						}
					}
				}

				if ($Src->CurrRec!==false) $Src->DataFetch();

				// Initialise
				$SecId = 1;
				$SecOk = ($LocR->SectionNbr>0);
				$SecIncr = true;
				$BlockRes = ''; // The result of the chained merged blocks
				$SerialMode = false;
				$SerialNum = 0;
				$SerialMax = 0;
				$SerialTxt = array();

				// Main loop
				while($Src->CurrRec!==false) {

					// Merge Headers
					if ($LocR->HeaderFound) {
						$change = ($Src->RowNum===1);
						for ($i=1;$i<=$LocR->HeaderNbr;$i++) {
							$x = $Src->CurrRec[$LocR->HeaderField[$i]];
							if (!$change) $change = !($LocR->HeaderPrevValue[$i]===$x);
							if ($change) {
								if ($SerialMode) {
									$BlockRes .= tbs_Merge_SectionSerial($LocR,$HtmlCharSet,$SecId,$SerialNum,$SerialMax,$SerialTxt);
									$SecIncr = true;
								}
								$BlockRes .= tbs_Merge_SectionCached($LocR,$HtmlCharSet,$LocR->HeaderBid[$i],$Src->CurrRec,$Src->RowNum);
								$LocR->HeaderPrevValue[$i] = $x;
							}
						}
					}

					// Increment Section
					if ($SecIncr and $SecOk) {
						$SecId++;
						if ($SecId>$LocR->SectionNbr) $SecId = 1;
						$SerialMode = $LocR->SectionIsSerial[$SecId];
						if ($SerialMode) {
							$SerialNum = 0;
							$SerialMax = $LocR->SectionSerialBid[$SecId][0];
							$SecIncr = false;
						}
					}

					// Serial Mode Activation
					if ($SerialMode) { // Serial Merge
						$SerialNum++;
						$Bid = $LocR->SectionSerialBid[$SecId][$SerialNum];
						$SerialTxt[$SerialNum] = tbs_Merge_SectionCached($LocR,$HtmlCharSet,$Bid,$Src->CurrRec,$Src->RowNum);
						if ($SerialNum>=$SerialMax) {
							$BlockRes .= tbs_Merge_SectionSerial($LocR,$HtmlCharSet,$SecId,$SerialNum,$SerialMax,$SerialTxt);
							$SecIncr = true;
						}
					} else { // Classic merge
						if ($Src->RowNum===$RowSpe) {
							$Bid = $LocR->SpecialBid;
						} else {
							$Bid = $LocR->SectionBid[$SecId];
						}
						$BlockRes .= tbs_Merge_SectionCached($LocR,$HtmlCharSet,$Bid,$Src->CurrRec,$Src->RowNum);
					}

					// Next row
					if ($Src->RowNum===$RowStop) {
						$Src->CurrRec = false;
					} else {
						// $CurrRec can be set to False by the OnSection event function.
						if ($Src->CurrRec!==false) $Src->DataFetch();
					}

				} //--> while($CurrRec!==false) {

				// Serial: merge the extra the sub-blocks
				if ($SerialMode and !$SecIncr) {
					$BlockRes .= tbs_Merge_SectionSerial($LocR,$HtmlCharSet,$SecId,$SerialNum,$SerialMax,$SerialTxt);
				}

				// Mode Page: Calculate the value to return
				if (($PageSize>0) and ($Src->RowNum>=$RowStop)) {
					if ($RecKnown<0) { // Pass pages in order to count all records
						do {
							$Src->DataFetch();
						} while ($Src->CurrRec!==false);
					} else { // We know that there is more records
						if ($RecKnown>$Src->RowNum) $Src->RowNum = $RecKnown;
					}
				}

				// NoData
				if ($Src->RowNum===0) {
					$BlockRes = $LocR->NoDataSrc;
					if ($LocR->OnSection) {
						$Src->CurrRec = false;
						$Fct = $LocR->OnSectionFct;
						$Fct($BlockName,$Src->CurrRec,$BlockRes,$Src->RowNum);
					}
				}

				// Merge the result
				$Txt = substr_replace($Txt,$BlockRes,$LocR->PosBeg,$LocR->PosEnd-$LocR->PosBeg+1);
				$Pos = $LocR->PosBeg;

			} //-> if ($SrcType===4) {...} else {...

			// Close the resource
			$Src->DataClose();

		} //-> if ($RecSet!==false) {..

		$RowTot += $Src->RowNum;

	} while ($Query0!==false);

	// Merge last record on the entire template
	if ($Src->CurrRec===false) $Src->CurrRec = array(); // For conveniant error message when a column is missing
	tbs_Merge_Field($Txt,$HtmlCharSet,$BlockName,$Src->CurrRec,true,true,$RowTot);

	// End of the merge
	$GLOBALS['tbs_CurrRec'] = &$CurrRecSave;
	return $RowTot;

}

// Look for each 'tbs_check' block and merge them.
function tbs_Merge_Check(&$Txt,&$HtmlCharSet,$BlockName) {
	
	$GrpDisplay = array();
	$GrpElse = array();
	$ElseCnt = 0;
	
	$ElseTurn = false;
	$Continue = true;
	
	while ($Continue) {
	
		$Pos = 0;
	
		while ($Loc = tbs_Locator_FindTbs($Txt,$BlockName,$Pos,true)) {
			if (isset($Loc->PrmLst['block'])) {
				
				if ($ElseTurn) {
					$DelBlock = $GrpDisplay[$Loc->SubName];
					$DelField = !$DelBlock;
				} else {
					if (!isset($GrpDisplay[$Loc->SubName])) $GrpDisplay[$Loc->SubName] = false;
					if (isset($Loc->PrmLst['if'])) {
						if (tbs_Misc_CheckCondition($Loc->PrmLst['if'])) {
							$DelBlock = false;
							$GrpDisplay[$Loc->SubName] = true;
						} else {
							$DelBlock = true;
						}
						$DelField = !$DelBlock;
					} elseif(isset($Loc->PrmLst['else'])) {
						if ($GrpDisplay[$Loc->SubName]) {
							$DelBlock = true;
						} else {
							$DelBlock = false;
							$DelField = false;
							$ElseCnt++;
						}
					} elseif ($Loc->PrmLst['block']==='end') {
						$DelBlock = false;
						$DelField = false;
					} else {
						$DelBlock = false;
						$DelField = true;
					}
				}
								
				// Found the second block if explicit syntax
				if ($DelBlock or $DelField) {
					if ($Loc->PrmLst['block']==='begin') {
						$Pos2 = $Pos;
						while (($Loc->BlockFound===false) and ($Loc2 = tbs_Locator_FindTbs($Txt,$Loc->FullName,$Pos2,false))) {
							if (isset($Loc2->PrmLst['block']) and ($Loc2->PrmLst['block']==='end')) {
								$Loc->BlockFound = true;
							} else {
								$Pos2 = $Loc2->PosEnd;
							}
						}
						if (!$Loc->BlockFound) {
							tbs_Misc_Alert('Block definition',$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].'] has a \'begin\' tag, but no \'end\' tag found.');
							$DelBlock = false;
							$DelField = false;
						}
					}
				}
				
				// Del parts
				if ($DelBlock) {
					if ($Loc->BlockFound) {
						$Txt = substr_replace($Txt,'',$Loc->PosBeg,$Loc2->PosEnd-$Loc->PosBeg+1);
					} else {
						if (isset($Loc->PrmLst['encaps'])) {
							$Encaps = abs(intval($Loc->PrmLst['encaps']));
						} else {
							$Encaps = 1;
						}
						if (isset($Loc->PrmLst['extend'])) {
							$Extend = intval($Loc->PrmLst['extend']);
						} else {
							$Extend = 0;
						}
						$Invalide = !tbs_Locator_EnlargeToTag($Txt,$Loc,$Loc->PrmLst['block'],$Encaps,$Extend,false);
						if ($Invalide) {
							tbs_Misc_Alert('Block definition',$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].' can not be enlarged or extended.');
							$Pos = $Loc->PosEnd;
						} else {
							$Txt = substr_replace($Txt,'',$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
							$Pos = $Loc->PosBeg;
						}
					}
					$Loc->BlockSrc = substr($Txt,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
				} elseif ($DelField) {
					if ($Loc->BlockFound) $Txt = substr_replace($Txt,'',$Loc2->PosBeg,$Loc2->PosEnd-$Loc2->PosBeg+1);
					$Txt = substr_replace($Txt,'',$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1);
					$Pos = $Loc->PosBeg;
				} else {
					$Pos = $Loc->PosEnd;
				}
				
			} else {
				$x = '';
				$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$x,false);
			}
		}

		if ($ElseTurn) {
			$Continue = false;
		} else {
			$ElseTurn = true;
			$Continue = ($ElseCnt>0);
		}

	}

}

// Merge the PHP global variables of the main script.
function tbs_Merge_PhpVar(&$Txt,&$HtmlCharSet) {

	// Check if the PhpVar list has to be initialized
	if ($GLOBALS['_tbs_PhpVarLst']===false) {
		// Build an array that enables to find any global variable name from its lower case name
		$GLOBALS['_tbs_PhpVarLst'] = array();
		$x = array_keys($GLOBALS);
		foreach ($x as $k) {
			$GLOBALS['_tbs_PhpVarLst'][strtolower($k)] = $k;
		}
	}

	// Then we scann all fields in the model
	$Pos = 0;
	while ($Loc = tbs_Locator_FindTbs($Txt,'var',$Pos,true)) {
		if ($Loc->SubNbr>0) {
			$VarName = strtolower($Loc->SubLst[0]);
			if (isset($GLOBALS['_tbs_PhpVarLst'][$VarName])) {
				$Loc->SubLst[0] = $GLOBALS['_tbs_PhpVarLst'][$VarName];
				$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$GLOBALS,true);
			} else {
				if (isset($Loc->PrmLst['noerr'])) {
					$x = '';
					$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$x,false);
				} else {
					tbs_Misc_Alert('Merge PHP global variables','Can\'t merge '.$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].' because there is no PHP global variable named \''.$VarName.'\'.',true);
					$Pos = $Loc->PosEnd + 1;
				}
			}
		} else {
			$k = '';
			$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$k,false);
		}
	}

}

// This function enables to merge TBS special fields
function tbs_Merge_TbsVar(&$TBS) {

	$Pos = 0;

	while ($Loc = tbs_Locator_FindTbs($TBS->Source,'sys',$Pos,true)) {
		$Pos = $Loc->PosEnd;
		switch (strtolower($Loc->SubName)) {
		case 'now':
			$x = mktime();
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,false);
			break;
		case 'version':
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$TBS->_Version,false);
			break;
		case 'script_name':
			if (isset($_SERVER)) { // PHP<4.1.0 compatibilty
				$x = tbs_Misc_GetFilePart($_SERVER['PHP_SELF'],1);
			} else {
				global $HTTP_SERVER_VARS;
				$x = tbs_Misc_GetFilePart($HTTP_SERVER_VARS['PHP_SELF'],1);
			}
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,false);
			break;
		case 'template_name':
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$TBS->_LastFile,false);
			break;
		case 'template_date':
			$x = filemtime($TBS->_LastFile);
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,false);
			break;
		case 'template_path':
			$x = tbs_Misc_GetFilePart($TBS->_LastFile,0);
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,false);
			break;
		case 'name':
			$x = 'TinyButStrong';
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,false);
			break;
		case 'logo':
			$x = '**TinyButStrong**';
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$x,false);
			break;
		case 'merge_time' : $TBS->_Timer = true; break;
		case 'script_time': $TBS->_Timer = true; break;
		case 'charset':
			$Pos = tbs_Locator_Replace($TBS->Source,$TBS->HtmlCharSet,$Loc,$TBS->HtmlCharSet,false);
			break;
		}
	}

}

// Proceed to one of the special merge
function tbs_Merge_Special(&$TBS,$Type) {

	if ($Type==='*') $Type = 'include,include.onshow,var,sys,check,timer';

	$TypeLst = split(',',$Type);
	foreach ($TypeLst as $Type) {
		switch ($Type) {
			case 'var':	tbs_Merge_PhpVar($TBS->Source,$TBS->HtmlCharSet); break;
			case 'sys': tbs_Merge_TbsVar($TBS); break;
			case 'check': tbs_Merge_Check($TBS->Source,$TBS->HtmlCharSet,'tbs_check'); break;
			case 'include': tbs_Merge_Auto($TBS->Source,$TBS->HtmlCharSet,true); break;
			case 'include.onshow': tbs_Merge_Auto($TBS->Source,$TBS->HtmlCharSet,false); break;
			case 'timer':
				if ($TBS->_Timer) { // This property is set within the tbs_Merge_PhpVar() function
					global $_tbs_Timer;
					$Micro = tbs_Misc_Timer();
					$x = $Micro - $TBS->_StartMerge;
					tbs_Merge_Field($TBS->Source,$TBS->HtmlCharSet,'sys.merge_time',$x,false,false,false);
					$x = $Micro - $_tbs_Timer;
					tbs_Merge_Field($TBS->Source,$TBS->HtmlCharSet,'sys.script_time',$x,false,false,false);
				}
				break;
			}
	}

}

function tbs_Merge_SectionCached(&$LocR,&$HtmlCharSet,&$BlockId,&$Record,&$RecNum) {

	$Txt = $LocR->BlockSrc[$BlockId];

	if ($LocR->OnSection) {
		$Txt0 = $Txt;
		$Fct = $LocR->OnSectionFct;
		$Fct($LocR->BlockName[$BlockId],$Record,$Txt,$RecNum);
		if ($Txt0===$Txt) {
			$LocLst = &$LocR->BlockLoc[$BlockId];
			$iMax = $LocLst[0];
			$PosMax = strlen($Txt);
			$DoUnCached = &$LocR->BlockChk[$BlockId];
		} else {
			$iMax = 0;
			$DoUnCached = true;
		}
	} else {
		$LocLst = &$LocR->BlockLoc[$BlockId];
		$iMax = $LocLst[0];
		$PosMax = strlen($Txt);
		$DoUnCached = &$LocR->BlockChk[$BlockId];
	}
	
	if ($Record===false) { // Erase all fields

		$x = '';

		// Chached locators
		for ($i=$iMax;$i>0;$i--) {
			if ($LocLst[$i]->PosBeg<$PosMax) {
				tbs_Locator_Replace($Txt,$HtmlCharSet,$LocLst[$i],$x,false);
				if ($LocLst[$i]->Enlarged) {
					$PosMax = $LocLst[$i]->PosBeg;
					$LocLst[$i]->PosBeg = $LocLst[$i]->PosBeg0;
					$LocLst[$i]->PosEnd = $LocLst[$i]->PosEnd0;
					$LocLst[$i]->Enlarged = false;
				}
			}
		}

		// Unchached locators
		if ($DoUnCached) {
			$BlockName = &$LocR->BlockName[$BlockId];
			$Pos = 0;
			while ($Loc = tbs_Locator_FindTbs($Txt,$BlockName,$Pos,true)) {
				if ($Loc->SubName==='#') {
					$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$RecNum,false);
				} else {
					$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$x,false);
				}
			}
		}		

	} else {
		
		// Chached locators
		for ($i=$iMax;$i>0;$i--) {
			if ($LocLst[$i]->PosBeg<$PosMax) {
				if ($LocLst[$i]->IsRecNum) {
					tbs_Locator_Replace($Txt,$HtmlCharSet,$LocLst[$i],$RecNum,false);
				} else {
					tbs_Locator_Replace($Txt,$HtmlCharSet,$LocLst[$i],$Record,true);
				}
				if ($LocLst[$i]->Enlarged) {
					$PosMax = $LocLst[$i]->PosBeg;
					$LocLst[$i]->PosBeg = $LocLst[$i]->PosBeg0;
					$LocLst[$i]->PosEnd = $LocLst[$i]->PosEnd0;
					$LocLst[$i]->Enlarged = false;
				}
			}
		}

		// Unchached locators
		if ($DoUnCached) {
			$BlockName = &$LocR->BlockName[$BlockId];
			foreach ($Record as $key => $val) {
				$Pos = 0;
				$Name = $BlockName.'.'.$key;
				while ($Loc = tbs_Locator_FindTbs($Txt,$Name,$Pos,true)) {
					$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$val,true);
				}
			}
			$Pos = 0;
			$Name = $BlockName.'.#';
			while ($Loc = tbs_Locator_FindTbs($Txt,$Name,$Pos,true)) {
				$Pos = tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$RecNum,true);
			}
		}

	}

	return $Txt;

}

function tbs_Merge_SectionSerial(&$LocR,&$HtmlCharSet,&$SecId,&$SerialNum,&$SerialMax,&$SerialTxt) {

	$Txt = $LocR->BlockSrc[$LocR->SectionBid[$SecId]];
	$LocLst = &$LocR->BlockLoc[$LocR->SectionBid[$SecId]];
	$OrdLst = &$LocR->SectionSerialOrd[$SecId];

	// Prepare the Empty Item
	if ($SerialNum<$SerialMax) {
		if ($LocR->SerialEmpty===false) {
			$x = false;
			$r = '';
		} else {
			$EmptySrc = &$LocR->SerialEmpty;
		}
	}
	
	// All Items
	for ($i=$SerialMax;$i>0;$i--) {
		$Sr = $OrdLst[$i];
		if ($Sr>$SerialNum) {
			if ($LocR->SerialEmpty===false) {
				$k = $LocR->SectionSerialBid[$SecId][$Sr];
				$EmptySrc = tbs_Merge_SectionCached($LocR,$HtmlCharSet,$k,$x,$r);
			}
			$Txt = substr_replace($Txt,$EmptySrc,$LocLst[$Sr]->PosBeg,$LocLst[$Sr]->PosEnd-$LocLst[$Sr]->PosBeg+1);
		} else {
			$Txt = substr_replace($Txt,$SerialTxt[$Sr],$LocLst[$Sr]->PosBeg,$LocLst[$Sr]->PosEnd-$LocLst[$Sr]->PosBeg+1);
		}
	}

	// Update variables
	$SerialNum = 0;
	$SerialTxt = array();

	return $Txt;
	
}

// Include file
function tbs_Merge_Auto(&$Txt,&$HtmlCharSet,$OnLoad) {

	$TmpValue = '';
	$Nbr = 0;
	$Pos = 0;

	while ($Loc = tbs_Locator_FindTbs($Txt,'tbs_include',$Pos,true)) {
		$Ok = false;
		if ($OnLoad) {
			if (($Loc->SubOk===false) or (strtolower($Loc->SubName)==='onload') ) $Ok = true;
		} else {
			if (strtolower($Loc->SubName)==='onshow') $Ok = true;
		}
		if ($Ok) {
			$Nbr++;
			if ($Nbr>64) {
				tbs_Misc_Alert('Automatic fields','The field '.$GLOBALS['tbs_ChrOpen'].$Loc->FullName.$GLOBALS['tbs_ChrClose'].' can\'t be merged because the limit (64) is riched. You maybe have self-included templates.');
				$Loc=false;
			} else {
				$Pos = $Loc->PosBeg;
				tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$TmpValue,false);
			}
		} else {
			$Pos = $Loc->PosEnd;
		}
	}

}

function tbs_Merge_NavigationBar(&$Txt,&$HtmlCharSet,$BlockName,$Options,$PageCurr,$RecCnt,$RecByPage) {

	// Check values
	if (!is_array($Options)) $Options = array('size'=>intval($Options));
	if (!isset($Options['pos'])) $Options['pos'] = 'step';
	if (!isset($Options['min'])) $Options['min'] = 1;
	if ($Options['size']<=0) $Options['size'] = 10;
	if ($PageCurr<$Options['min']) $PageCurr = $Options['min'];
	if ($RecByPage<=0) $RecByPage = 1;
	$CurrPos = 0;

	$SaveNav = &$GLOBALS['tbs_CurrNav'];
	$CurrNav = array('curr'=>$PageCurr,'first'=>$Options['min'],'last'=>-1,'bound'=>false);
	$GLOBALS['tbs_CurrNav'] = &$CurrNav;

	// Calculate displayed PageMin and PageMax
	if ($Options['pos']=='centred') {
		$PageMin = $Options['min']-1+$PageCurr - intval(floor($Options['size']/2));
	} else {
		// Display by block
		$PageMin = $Options['min']-1+$PageCurr - ( ($PageCurr-1) % $Options['size']);
	}
	$PageMin = max($PageMin,$Options['min']);
	$PageMax = $PageMin + $Options['size'] - 1;
	
	// Calculate previous and next pages
	$CurrNav['prev'] = $PageCurr - 1;
	if ($CurrNav['prev']<$Options['min']) {
		$CurrNav['prev'] = $Options['min'];
		$CurrNav['bound'] = $Options['min'];
	}
	$CurrNav['next'] = $PageCurr + 1;
	if ($RecCnt>=0) {
		$PageCnt = $Options['min']-1 + intval(ceil($RecCnt/$RecByPage));
		$PageMax = min($PageMax,$PageCnt);
		$PageMin = max($Options['min'],$PageMax-$Options['size']+1);
	} else {
		$PageCnt = $Options['min']-1;
	}
	if ($PageCnt>=$Options['min']) {
		if ($PageCurr>=$PageCnt) {
			$CurrNav['next'] = $PageCnt;
			$CurrNav['last'] = $PageCnt;
			$CurrNav['bound'] = $PageCnt;
		} else {
			$CurrNav['last'] = $PageCnt;
		}
	}	

	// Merge general information
	$Pos = 0;
	while ($Loc = tbs_Locator_FindTbs($Txt,$BlockName,$Pos,true)) {
		$Pos = $Loc->PosBeg + 1;
		$x = strtolower($Loc->SubName);
		if (isset($CurrNav[$x])) {
			$Val = $CurrNav[$x];
			if ($CurrNav[$x]==$CurrNav['bound']) {
				if (isset($Loc->PrmLst['endpoint'])) {
					$Val = '';
				}
			}
			tbs_Locator_Replace($Txt,$HtmlCharSet,$Loc,$Val,false);
		}
	}

	// Merge pages
	$Data = array();
	$RecSpe = 0;
	$RecCurr = 0;
	for ($PageId=$PageMin;$PageId<=$PageMax;$PageId++) {
		$RecCurr++;
		if ($PageId==$PageCurr) $RecSpe = $RecCurr;
		$Data[] = array('page'=>$PageId);
	}
	$Query = '';
	tbs_Merge_Block($Txt,$HtmlCharSet,$BlockName,$Data,$Query,0,0,$RecSpe);

	$GLOBALS['tbs_CurrNav'] = &$SaveNav;

}


// This function returns a part of the HTML document (HEAD or BODY)
// The $CancelIfEmpty parameter enables to cancel the extraction when the part is not found.
function tbs_Html_GetPart(&$Txt,$Tag,$WithTags=false,$CancelIfEmpty=false) {

	$x = false;

	$LocOpen = tbs_Html_FindTag($Txt,$Tag,true,0,true,0,false);
	if ($LocOpen!==false) {
		$LocClose = tbs_Html_FindTag($Txt,$Tag,false,$LocOpen->PosEnd+1,true,0,false);
		if ($LocClose!==false) {
			if ($WithTags) {
				$x = substr($Txt,$LocOpen->PosBeg,$LocClose->PosEnd - $LocOpen->PosBeg + 1);
			} else {
				$x = substr($Txt,$LocOpen->PosEnd+1,$LocClose->PosBeg - $LocOpen->PosEnd - 1);
			}
		}
	}

	if ($x===false) {
		if ($CancelIfEmpty) {
			$x = $Txt;
		} else {
			$x = '';
		}
	}

	return $x;

}

// This function returns True if the text seems to have some HTML tags.
function tbs_Html_IsHtml(&$Txt) {

	$IsHtml = false;

	// Search for opening and closing tags
	$pos = strpos($Txt,'<');
	if ( ($pos!==false) and ($pos<strlen($Txt)-1) ) {
		$pos = strpos($Txt,'>',$pos + 1);
		if ( ($pos!==false) and ($pos<strlen($Txt)-1) ) {
			$pos = strpos($Txt,'</',$pos + 1);
			if ( ($pos!==false)and ($pos<strlen($Txt)-1) ) {
				$pos = strpos($Txt,'>',$pos + 1);
				if ($pos!==false) {
					$IsHtml = true;
				}
			}
		}
	}

	// Search for special char
	if ($IsHtml===false) {
		$pos = strpos($Txt,'&');
		if ( ($pos!==false)  and ($pos<strlen($Txt)-1) ) {
			$pos2 = strpos($Txt,';',$pos+1);
			if ($pos2!==false) {
				$x = substr($Txt,$pos+1,$pos2-$pos-1); // We extract the found text between the couple of tags
				if (strlen($x)<=10) {
					if (strpos($x,' ')===false) {
						$IsHtml = true;
					}
				}
			}
		}
	}

	// Look for a simple tag
	if ($IsHtml===false) {
		$Loc1 = tbs_Html_FindTag($Txt,'BR',true,0,true,0,false); // line break
		if ($Loc1===false) {
			$Loc1 = tbs_Html_FindTag($Txt,'HR',true,0,true,0,false); // horizontal line
			if ($Loc1!==false) {
				$IsHtml = true;
			}
		} else {
			$IsHtml = true;
		}
	}

	return $IsHtml;

}

// Merge items of a list, or radio or check buttons.
// At this point, the Locator is already merged with $SelValue.
function tbs_Html_MergeItems(&$Txt,&$Loc,&$SelValue,&$SelArray,$NewEnd) {

	if ($Loc->PrmLst['selected']===true) {
		$IsList = true;
		$MainTag = 'SELECT';
		$ItemTag = 'OPTION';
		$ItemPrm = 'selected';
	} else {
		$IsList = false;
		$MainTag = 'FORM';
		$ItemTag = 'INPUT';
		$ItemPrm = 'checked';
	}
	$ItemPrmZ = ' '.$ItemPrm.'="'.$ItemPrm.'"';

	$TagO = tbs_Html_FindTag($Txt,$MainTag,true,$Loc->PosBeg-1,false,0,false);

	if ($TagO!==false) {

		$TagC = tbs_Html_FindTag($Txt,$MainTag,false,$Loc->PosBeg,true,0,false);
		if ($TagC!==false) {

			// We get the main block without the main tags
			$MainSrc = substr($Txt,$TagO->PosEnd+1,$TagC->PosBeg - $TagO->PosEnd -1);

			if ($IsList) {
				$Item0Beg = $Loc->PosBeg - ($TagO->PosEnd+1);
				$Item0Src = '';
			} else {
				// we delete the merged value
				$MainSrc = substr_replace($MainSrc,'',$Loc->PosBeg - ($TagO->PosEnd+1), strlen($SelValue));
			}

			// Now, we going to scan all of the item tags
			$Pos = 0;
			$SelNbr = 0;
			$Item0Ok = false;
			while ($Pos!==false) {
				$ItemLoc = tbs_Html_FindTag($MainSrc,$ItemTag,true,$Pos,true,0,true);
				if ($ItemLoc===false) {
					$Pos = false;
				} else {

					// we get the value of the item
					$ItemValue = false;
					$Select = true;

					if ($IsList) {
						// Look for the end of the item
						$OptCPos = strpos($MainSrc,'<',$ItemLoc->PosEnd+1);
						if ($OptCPos===false) $OptCPos = strlen($MainSrc);
						if (($Item0Ok===false) and ($ItemLoc->PosBeg<$Item0Beg) and ($Item0Beg<=$OptCPos)) {
							// If it's the original item, we save it and delete it.
							if (($OptCPos+1<strlen($MainSrc)) and ($MainSrc[$OptCPos+1]==='/')) {
								$OptCPos = strpos($MainSrc,'>',$OptCPos);
								if ($OptCPos===false) {
									$OptCPos = strlen($MainSrc);
								} else {
									$OptCPos++;
								}
							}
							$Item0Src = substr($MainSrc,$ItemLoc->PosBeg,$OptCPos-$ItemLoc->PosBeg);
							$MainSrc = substr_replace($MainSrc,'',$ItemLoc->PosBeg,strlen($Item0Src));
							if (!isset($ItemLoc->PrmLst[$ItemPrm])) tbs_Html_InsertAttribute($Item0Src,$ItemPrmZ,$ItemLoc->PosEnd-$ItemLoc->PosBeg);
							$OptCPos = min($ItemLoc->PosBeg,strlen($MainSrc)-1);
							$Select = false;
							$Item0Ok = true;
						} else {
							if (isset($ItemLoc->PrmLst['value'])) {
								$ItemValue = $ItemLoc->PrmLst['value'];
							} else { // The value of the option is its caption.
								$ItemValue = substr($MainSrc,$ItemLoc->PosEnd+1,$OptCPos - $ItemLoc->PosEnd - 1);
								$ItemValue = str_replace(chr(9),' ',$ItemValue);
								$ItemValue = str_replace(chr(10),' ',$ItemValue);
								$ItemValue = str_replace(chr(13),' ',$ItemValue);
								$ItemValue = trim($ItemValue);
							}
						}
						$Pos = $OptCPos;
					} else {
						if ((isset($ItemLoc->PrmLst['name'])) and (isset($ItemLoc->PrmLst['value']))) {
							if (strcasecmp($Loc->PrmLst['selected'],$ItemLoc->PrmLst['name'])==0) {
								$ItemValue = $ItemLoc->PrmLst['value'];
							}
						}
						$Pos = $ItemLoc->PosEnd;
					}

					if ($Select) {
						// we look if we select the item
						$Select = false;
						if ($SelArray===false) {
							if (strcasecmp($ItemValue,$SelValue)==0) {
								if ($SelNbr==0) $Select = true;
							}
						} else {
							if (array_search($ItemValue,$SelArray,false)!==false) $Select = true;
						}
						// Select the item
						if ($Select) {
							if (!isset($ItemLoc->PrmLst[$ItemPrm])) {
								tbs_Html_InsertAttribute($MainSrc,$ItemPrmZ,$ItemLoc->PosEnd);
								$Pos = $Pos + strlen($ItemPrmZ);
								if ($IsList and ($ItemLoc->PosBeg<$Item0Beg)) $Item0Beg = $Item0Beg + strlen($ItemPrmZ);
							}
							$SelNbr++;
						}
					}

				} //--> if ($ItemLoc===false) { ... } else {
			} //--> while ($Pos!==false) {

			if ($IsList) {
				// Add the original item if it's not found
				if (($SelArray===false) and ($SelNbr==0)) $MainSrc = $MainSrc.$Item0Src;
				$NewEnd = $TagO->PosEnd+1 + strlen($MainSrc);
			} else {
				$NewEnd = $Loc->PosBeg;
			}

			$Txt = substr_replace($Txt,$MainSrc,$TagO->PosEnd+1,$TagC->PosBeg-$TagO->PosEnd-1);

		} //--> if ($TagC!==false) {
	} //--> if ($TagO!==false) {

}

function tbs_Html_InsertAttribute(&$Txt,&$Attr,$Pos) {
	// Check for XHTML end characters
	if ($Txt[$Pos-1]==='/') {
		$Pos--;
		if ($Txt[$Pos-1]===' ') $Pos--;
	}
	// Insert the parameter
	$Txt = substr_replace($Txt,$Attr,$Pos,0);
}

// Convert a string to Html with several options
function tbs_Html_Conv(&$Txt,&$HtmlCharSet,&$BrConv,&$WhiteSp) {

	if ($HtmlCharSet==='') {
		$Txt = htmlentities($Txt); // Faster
	} else {
		$Txt = htmlentities($Txt,ENT_COMPAT,$HtmlCharSet);
	}

	if ($WhiteSp) {
		$check = '  ';
		$nbsp = '&nbsp;';
		do {
			$pos = strpos($Txt,$check);
			if ($pos!==false) $Txt = substr_replace($Txt,$nbsp,$pos,1);
		} while ($pos!==false);
	}

	if ($BrConv) $Txt = nl2br($Txt);

}

// This function is a smarter issue to find an HTML tag.
// It enables to ignore full opening/closing couple of tag that could be inserted before the searched tag.
// It also enables to pass a number of encapsulations.
// To ignore encapsulation and opengin/closing just set $Encaps=0.
function &tbs_Html_FindTag(&$Txt,$Tag,$Opening,$PosBeg,$Forward,$Encaps,$WithPrm) {

	if ($Forward) {
		$Pos = $PosBeg - 1;
	} else {
		$Pos = $PosBeg + 1;
	}
	$TagIsOpening = false;
	$TagClosing = '/'.$Tag;
	if ($Opening) {
		$EncapsEnd = $Encaps;
	} else {
		$EncapsEnd = - $Encaps;
	}
	$EncapsCnt = 0;
	$TagOk = false;

	do {

		// Look for the next tag def
		if ($Forward) {
			$Pos = strpos($Txt,'<',$Pos+1);
		} else {
			if ($Pos<=0) {
				$Pos = false;
			} else {
				$Pos = strrpos(substr($Txt,0,$Pos - 1),'<');
			}
		}

		if ($Pos!==false) {
			// Check the name of the tag
			if (strcasecmp(substr($Txt,$Pos+1,strlen($Tag)),$Tag)==0) {
				$PosX = $Pos + 1 + strlen($Tag); // The next char
				$TagOk = true;
				$TagIsOpening = true;
			} elseif (strcasecmp(substr($Txt,$Pos+1,strlen($TagClosing)),$TagClosing)==0) {
				$PosX = $Pos + 1 + strlen($TagClosing); // The next char
				$TagOk = true;
				$TagIsOpening = false;
			}

			if ($TagOk) {
				// Check the next char
				if (($Txt[$PosX]===' ') or ($Txt[$PosX]==='>')) {
					// Check the encapsulation count
					if ($EncapsEnd==0) {
						// No encaplusation check
						if ($TagIsOpening!==$Opening) $TagOk = false;
					} else {
						// Count the number of encapsulation
						if ($TagIsOpening) {
							$EncapsCnt++;
						} else {
							$EncapsCnt--;
						}
						// Check if it's the expected count
						if ($EncapsCnt!=$EncapsEnd) $TagOk = false;
					}
				} else {
					$TagOk = false;
				}
			} //--> if ($TagOk)

		}
	} while (($Pos!==false) and ($TagOk===false));

	// Search for the end of the tag
	if ($TagOk) {
		$Loc = new clsTbsLocator;
		if ($WithPrm) {
			$PosEnd = 0;
			tbs_Locator_ReadPrm($Txt,$PosX,' ','=','\'"','','','>',0,$Loc,$PosEnd);
		} else {
			$PosEnd = strpos($Txt,'>',$PosX);
			if ($PosEnd===false) {
				$TagOk = false;
			}
		}
	}

	// Result
	if ($TagOk) {
		$Loc->PosBeg = $Pos;
		$Loc->PosEnd = $PosEnd;
		return $Loc;
	} else {
		return false;
	}

}

// Limit the number of HTML chars
function tbs_Html_Max(&$Txt,&$Nbr) {

	$pMax = strlen($Txt)-1;
	$p=0;
	$n=0;
	$in = false;
	$ok = true;

	while ($ok) {
		if ($in) {
			if ($Txt[$p]===';') {
				$in = false;
				$n++;
			}
		} else {
			if ($Txt[$p]==='&') {
				$in = true;
			} else {
				$n++;
			}
		}
		if (($n>=$Nbr) or ($p>=$pMax)) {
			$ok = false;
		} else {
			$p++;
		}
	}
	
	if (($n>=$Nbr) and ($p<$pMax)) $Txt = substr($Txt,0,$p).'...';

}

// Return a string that describes all locators with the given name.
function tbs_Misc_DebugLocator(&$Txt,$NameLst) {
	$Nl = "<br>\n";
	$Break = '-------------------'.$Nl;
	$ColOn = '<font color="#993300">';
	$ColOff = '</font>';
	$NbrTot = 0;
	$NameArray = explode(',',$NameLst);
	$Msg = '';
	foreach ($NameArray as $Name) {
		$x = '';
		$Pos = 0;
		$Nbr = 0;
		while ($Loc = tbs_Locator_FindTbs($Txt,$Name,$Pos,true)) {
			$Pos = $Loc->PosBeg+1;
			$Nbr++;
			$NbrTot++;
			if (isset($Loc->PrmLst['block'])) {
				if ($Loc->PrmLst['block']==='begin') {
					$Type = 'Type = '.$ColOn.'Block begining'.$ColOff.', Syntax = '.$ColOn.'Explicit'.$ColOff;
				} elseif ($Loc->PrmLst['block']==='end') {
					$Type = 'Type = '.$ColOn.'Block ending'.$ColOff.', Syntax = '.$ColOn.'Explicit'.$ColOff;
				} elseif ($Loc->SubOk===false) {
					$Type = 'Type = '.$ColOn.'Block'.$ColOff.', Syntax = '.$ColOn.'Relative'.$ColOff.', Html tag = '.$ColOn.htmlentities($Loc->PrmLst['block']).$ColOff;
				} else {
					$Type = 'Type = '.$ColOn.'Block & Field'.$ColOff.', Syntax = '.$ColOn.'Simplified'.$ColOff.', Html tag = '.$ColOn.htmlentities($Loc->PrmLst['block']).$ColOff;
				}
			} else {
				$Type = 'Type = '.$ColOn.'Field'.$ColOff;
			}
			$x .= 'Locator = '.$ColOn.htmlentities(substr($Txt,$Loc->PosBeg,$Loc->PosEnd-$Loc->PosBeg+1)).$ColOff.$Nl;
			$x .= $Type.$Nl;
			$x .= 'Name = '.$ColOn.htmlentities($Name).$ColOff.', subname = '.$ColOn.htmlentities(($Loc->SubName===false)? '(none)':$Loc->SubName).$ColOff.$Nl;
			$x .= 'Begin = '.$ColOn.$Loc->PosBeg.$ColOff.', end = '.$ColOn.$Loc->PosEnd.$ColOff.$Nl;
			foreach ($Loc->PrmLst as $key=>$val) {
				if ($val===true) $val = '(true)';
				if ($val===false) $val = '(false)';
				$x .= 'Parameters['.$ColOn.htmlentities($key).$ColOff.'] = '.$ColOn.htmlentities($val).$ColOff.$Nl;
			}
			$x .= $Break;
		}
		$Msg = $Msg.'Locators '.$ColOn.htmlentities($NameLst).$ColOff.': found = '.$ColOn.$Nbr.$ColOff.$Nl.$Break.$x;
	}
	$Header = 'DEBUG LOCATOR: Search for = '.$ColOn.htmlentities($NameLst).$ColOff.', total found = '.$ColOn.$NbrTot.$ColOff.', Template size = '.$ColOn.strlen($Txt).$ColOff.'.'.$Nl;
	$Msg = $Header.$Break.$Msg;
	return $Msg;
}

// Standard alert message provided by TinyButStrong, return False is the message is cancelled.
function tbs_Misc_Alert($Source,$Message,$NoErr=false) {
	$x = '<br><b>TinyButStrong Error</b> ('.$Source.'): '.htmlentities($Message);
	if ($NoErr) $x = $x.' <em>This message can be cancelled using parameter \'noerr\'.</em>';
	$x = $x."<br><br>\n";
	$x = str_replace($GLOBALS['tbs_ChrOpen'],$GLOBALS['tbs_ChrProtect'],$x);
	echo $x;
	return false;
}

function tbs_Misc_Timer() {
	$x = microtime();
	$Pos = strpos($x,' ');
	if ($Pos===false) {
		$x = '0.0';
	} else {
		$x = substr($x,$Pos+1).substr($x,1,$Pos);
	}
	return (float)$x;
}

// Mark the variable to be initilized
function tbs_Misc_ClearPhpVarLst() {
	$GLOBALS['_tbs_PhpVarLst'] = false;
}

function tbs_Misc_GetFilePart($File,$Part) {
	$Pos = strrpos($File,'/');
	if ($Part===0) { // Path
		if ($Pos===false) {
			return '';
		} else {
			return substr($File,0,$Pos+1);
		}
	} else { // File
		if ($Pos===false) {
			return $File;
		} else {
			return substr($File,$Pos+1);
		}
	}
}

// Load the content of a file into the text variable.
function tbs_Misc_GetFile(&$Txt,$File) {
	$Txt = '';
	$fd = @fopen($File, 'r'); // 'rb' if binary for some OS
	if ($fd===false) return false;
	$fs = filesize($File);
	if ($fs>0) $Txt = fread($fd,$fs);
	fclose($fd);
	return true;
}

// This function return the formated representation of a Date/Time or numeric variable using a 'VB like' format syntax instead of the PHP syntax.
function tbs_Misc_Format(&$Loc,&$Value) {

	global $_tbs_FrmSimpleLst;

	$FrmStr = $Loc->PrmLst['frm'];
	$CheckNumeric = true;
	if (is_string($Value)) $Value = trim($Value);
	
	// Manage Multi format strings
	if (strpos($FrmStr,'|')!==false) {
		
		global $_tbs_FrmMultiLst;
		
		// Save the format if it doesn't exist
		if (isset($_tbs_FrmMultiLst[$FrmStr])) {
			$FrmLst = &$_tbs_FrmMultiLst[$FrmStr];
		} else {
			$FrmLst = explode('|',$FrmStr); // syntax : PostiveFrm|NegativeFrm|ZeroFrm|NullFrm
			$FrmNbr = count($FrmLst);
			if (($FrmNbr<=1) or ($FrmLst[1]==='')) {
				$FrmLst[1] = &$FrmLst[0]; // negativ
				$FrmLst['abs'] = false;
			} else {
				$FrmLst['abs'] = true;
			}
			if (($FrmNbr<=2) or ($FrmLst[2]==='')) $FrmLst[2] = &$FrmLst[0]; // zero
			if (($FrmNbr<=3) or ($FrmLst[3]==='')) $FrmLst[3] = ''; // null
			$_tbs_FrmMultiLst[$FrmStr] = $FrmLst;
		}
		
		// Select the format
		if (is_numeric($Value)) {
			if (is_string($Value)) $Value = 0.0 + $Value;
			if ($Value>0) {
				$FrmStr = &$FrmLst[0];
			} elseif ($Value<0) {
				$FrmStr = &$FrmLst[1];
				if ($FrmLst['abs']) $Value = abs($Value);
			} else { // zero
				$FrmStr = &$FrmLst[2];
				$Minus = '';
			}
			$CheckNumeric = false;
		} else {
			$Value = ''.$Value;
			if ($Value==='') {
				return $FrmLst[3];
			} else {
				return $Value;
			}
		}
		
	}

	if ($FrmStr==='') return ''.$Value;

	// Retrieve the correct simple format
	if (!isset($_tbs_FrmSimpleLst[$FrmStr])) tbs_Misc_FormatSave($FrmStr);
	
	$Frm = &$_tbs_FrmSimpleLst[$FrmStr];

	switch ($Frm['type']) {
	case 'num' :
		// NUMERIC
		if ($CheckNumeric) {
			if (is_numeric($Value)) {
				if (is_string($Value)) $Value = 0.0 + $Value;
			} else {
				return ''.$Value;
			}
		}
		if ($Frm['PerCent']) $Value = $Value * 100;
		$Value = number_format($Value,$Frm['DecNbr'],$Frm['DecSep'],$Frm['ThsSep']);
		return substr_replace($FrmStr,$Value,$Frm['Pos'],$Frm['Len']);
		break;
	case 'date' :
		// DATE
		if (is_string($Value)) {
			if ($Value==='') return '';
			$x = strtotime($Value);
			if ($x===-1) {
				if (!is_numeric($Value)) $Value = 0;
			} else {
				$Value = &$x;
			}
		} else {
			if (!is_numeric($Value)) return ''.$Value;
		}
		if (isset($Loc->PrmLst['locale'])) {
			return strftime($Frm['str_loc'],$Value);
		} else {
			return date($Frm['str_us'],$Value);
		}
		break;
	default:
		return $Frm['string'];
		break;
	}

}

function tbs_Misc_FormatSave(&$FrmStr) {

	global $_tbs_FrmSimpleLst;

	$nPosEnd = strrpos($FrmStr,'0');
	
	if ($nPosEnd!==false) {
		
		// Numeric format
		$nDecSep = '.';
		$nDecNbr = 0;
		$nDecOk = true;
		
		if (substr($FrmStr,$nPosEnd+1,1)==='.') {
			$nPosEnd++;
			$nPosCurr = $nPosEnd;
		} else {
			$nPosCurr = $nPosEnd - 1;
			while (($nPosCurr>=0) and ($FrmStr[$nPosCurr]==='0')) {
				$nPosCurr--;
			}
			if (($nPosCurr>=1) and ($FrmStr[$nPosCurr-1]==='0')) {
				$nDecSep = $FrmStr[$nPosCurr];
				$nDecNbr = $nPosEnd - $nPosCurr;
			} else {
				$nDecOk = false;
			}
		}

		// Thousand separator
		$nThsSep = '';
		if (($nDecOk) and ($nPosCurr>=5)) {
			if ((substr($FrmStr,$nPosCurr-3,3)==='000') and ($FrmStr[$nPosCurr-4]!=='') and ($FrmStr[$nPosCurr-5]==='0')) {
				$nPosCurr = $nPosCurr-4;
				$nThsSep = $FrmStr[$nPosCurr];
			}
		}

		// Pass next zero
		if ($nDecOk) $nPosCurr--;
		while (($nPosCurr>=0) and ($FrmStr[$nPosCurr]==='0')) {
			$nPosCurr--;
		}

		// Percent
		$nPerCent = (strpos($FrmStr,'%')===false) ? false : true;

		$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'num','Pos'=>($nPosCurr+1),'Len'=>($nPosEnd-$nPosCurr),'ThsSep'=>$nThsSep,'DecSep'=>$nDecSep,'DecNbr'=>$nDecNbr,'PerCent'=>$nPerCent);

	} else { // if ($nPosEnd!==false)

		// Date format
		$FrmPHP = '';
		$FrmLOC = '';
		$Local = false;
		$StrIn = false;
		$iMax = strlen($FrmStr);
		$Cnt = 0;

		for ($i=0;$i<$iMax;$i++) {

			if ($StrIn) {
				// We are in a string part
				if ($FrmStr[$i]===$StrChr) {
					if (substr($FrmStr,$i+1,1)===$StrChr) {
						$FrmPHP .= '\\'.$FrmStr[$i]; // protected char
						$FrmLOC .= $FrmStr[$i];
						$i++;
					} else {
						$StrIn = false;
					}
				} else {
					$FrmPHP .= '\\'.$FrmStr[$i]; // protected char
					$FrmLOC .= $FrmStr[$i];
				}
			} else {
				if (($FrmStr[$i]==='"') or ($FrmStr[$i]==='\'')) {
					// Check if we have the opening string char
					$StrIn = true;
					$StrChr = $FrmStr[$i];
				} else {
					$Cnt++;
					if     (strcasecmp(substr($FrmStr,$i,4),'yyyy')===0) { $FrmPHP .= 'Y'; $FrmLOC .= '%Y'; $i += 3; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'yy'  )===0) { $FrmPHP .= 'y'; $FrmLOC .= '%y'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'mmmm')===0) { $FrmPHP .= 'F'; $FrmLOC .= '%B'; $i += 3; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'mmm' )===0) { $FrmPHP .= 'M'; $FrmLOC .= '%b'; $i += 2; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'mm'  )===0) { $FrmPHP .= 'm'; $FrmLOC .= '%m'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'m'   )===0) { $FrmPHP .= 'n'; $FrmLOC .= '%m'; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'wwww')===0) { $FrmPHP .= 'l'; $FrmLOC .= '%A'; $i += 3; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'www' )===0) { $FrmPHP .= 'D'; $FrmLOC .= '%a'; $i += 2; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'w'   )===0) { $FrmPHP .= 'w'; $FrmLOC .= '%u'; }
					elseif (strcasecmp(substr($FrmStr,$i,4),'dddd')===0) { $FrmPHP .= 'l'; $FrmLOC .= '%A'; $i += 3; }
					elseif (strcasecmp(substr($FrmStr,$i,3),'ddd' )===0) { $FrmPHP .= 'D'; $FrmLOC .= '%a'; $i += 2; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'dd'  )===0) { $FrmPHP .= 'd'; $FrmLOC .= '%d'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,1),'d'   )===0) { $FrmPHP .= 'j'; $FrmLOC .= '%d'; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'hh'  )===0) { $FrmPHP .= 'H'; $FrmLOC .= '%H'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'nn'  )===0) { $FrmPHP .= 'i'; $FrmLOC .= '%M'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'ss'  )===0) { $FrmPHP .= 's'; $FrmLOC .= '%S'; $i += 1; }
					elseif (strcasecmp(substr($FrmStr,$i,2),'xx'  )===0) { $FrmPHP .= 'S'; $FrmLOC .= ''  ; $i += 1; }
					else {
						$FrmPHP .= '\\'.$FrmStr[$i]; // protected char
						$FrmLOC .= $FrmStr[$i]; // protected char
						$Cnt--;
					}
				}
			} //-> if ($StrIn) {...} else

		} //-> for ($i=0;$i<$iMax;$i++)
		
		if ($Cnt>0) {
			$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'date','str_us'=>$FrmPHP,'str_loc'=>$FrmLOC);
		} else {
			$_tbs_FrmSimpleLst[$FrmStr] = array('type'=>'else','string'=>$FrmStr);
		}

	} // if ($nPosEnd!==false) {...} else
			
}

// Check if an expression like "exrp1=expr2" is true or false.
function tbs_Misc_CheckCondition($Str) {

	// Find operator and position
	$Ope = '=';
	$Len = 1;
	$Max = strlen($Str)-1;
	$Pos = strpos($Str,$Ope);
	if ($Pos===false) {
		$Ope = '+';
		$Pos = strpos($Str,$Ope);
		if ($Pos===false) return false;
		if (($Pos>0) and ($Str[$Pos-1]==='-')) {
			$Ope = '-+'; $Pos--; $Len=2;
		} elseif (($Pos<$Max) and ($Str[$Pos+1]==='-')) {
			$Ope = '+-'; $Len=2;
		} else {
			return false;
		}
	} else {
		if ($Pos>0) {
			$x = $Str[$Pos-1];
			if ($x==='!') {
				$Ope = '!='; $Pos--; $Len=2;
			} elseif ($Pos<$Max) {
				$y = $Str[$Pos+1];
				if ($y==='=') {
					$Len=2;
				} elseif (($x==='+') and ($y==='-')) {
					$Ope = '+=-'; $Pos--; $Len=3;
				} elseif (($x==='-') and ($y==='+')) {
					$Ope = '-=+'; $Pos--; $Len=3;
				}
			} else {
			}
		}
	}


	// Read values
	$Val1  = trim(substr($Str,0,$Pos));
	$Nude1 = tbs_Misc_DelDelimiter($Val1,'\'');
	$Val2  = trim(substr($Str,$Pos+$Len));
	$Nude2 = tbs_Misc_DelDelimiter($Val2,'\'');

	// Compare values
	if ($Ope==='=') {
		return (strcasecmp($Val1,$Val2)==0);
	} elseif ($Ope==='!=') {
		return (strcasecmp($Val1,$Val2)!=0);
	} else {
		if ($Nude1) $Val1 = (float) $Val1;
		if ($Nude2) $Val2 = (float) $Val2;
		if ($Ope==='+-') {
			return ($Val1>$Val2);
		} elseif ($Ope==='-+') {
			return ($Val1 < $Val2);
		} elseif ($Ope==='+=-') {
			return ($Val1 >= $Val2);
		} elseif ($Ope==='-=+') {
			return ($Val1<=$Val2);
		} else {
			return false;
		}
	}

}

// Delete the string delimiters
function tbs_Misc_DelDelimiter(&$Txt,$Delim) {
	$len = strlen($Txt);
	if (($len>1) and ($Txt[0]===$Delim)) {
		if ($Txt[$len-1]===$Delim) $Txt = substr($Txt,1,$len-2);
		return false;
	} else {
		return true;
	}
}

// Actualize the special TBS char
function tbs_Misc_ActualizeChr() {
	$GLOBALS['tbs_ChrVal'] = $GLOBALS['tbs_ChrOpen'].'val'.$GLOBALS['tbs_ChrClose'];
	$GLOBALS['tbs_ChrProtect'] = '&#'.ord($GLOBALS['tbs_ChrOpen']).';';
}

function tbs_Misc_GetStrId($Txt) {
	$Txt = strtolower($Txt);
	$Txt = str_replace('-','_',$Txt);
	$x = '';
	$i = 0;
	$iMax = strlen($Txt2);
	while ($i<$iMax) {
		if (($Txt[$i]==='_') or (($Txt[$i]>='a') and ($Txt[$i]<='z')) or (($Txt[$i]>='0') and ($Txt[$i]<='9'))) {
			$x .= $Txt[$i];
			$i++;
		} else {
			$i = $iMax;
		}
	}
	return $x;
}

function tbs_Misc_ReplaceVal(&$Txt,&$Val) {
	$Txt = str_replace($GLOBALS['tbs_ChrVal'],$Val,$Txt);
}

// Return the cache file path for a given Id.
function tbs_Cache_File($Dir,$CacheId,$Mask) {
	if (strlen($Dir)>0) {
		if ($Dir[strlen($Dir)-1]<>'/') {
			$Dir .= '/';
		}
	}
	return $Dir.str_replace('*',$CacheId,$Mask);
}

// Return True if there is a existing valid cache for the given file id.
function tbs_Cache_IsValide($CacheFile,$TimeOut) {
	if (file_exists($CacheFile)) {
		if (time()-filemtime($CacheFile)>$TimeOut) {
			return false;
		} else {
			return true;
		}
	} else {
		return false;
	}
}

function tbs_Cache_Save($CacheFile,&$Txt) {
	$fid = @fopen($CacheFile, 'w');
	if ($fid===false) {
		tbs_Misc_Alert('Cache System','The cache file \''.$CacheFile.'\' can not be saved.');
		return false;
	} else {
		flock($fid,2); // acquire an exlusive lock
		fwrite($fid,$Txt);
		flock($fid,3); // release the lock
		fclose($fid);
		return true;
	}
}

function tbs_Cache_DeleteAll($Dir,$Mask) {

	if (strlen($Dir)==0) {
		$Dir = '.';
	}
	if ($Dir[strlen($Dir)-1]<>'/') {
		$Dir .= '/';
	}
	$DirObj = dir($Dir);
	$Nbr = 0;
	$PosL = strpos($Mask,'*');
	$PosR = strlen($Mask) - $PosL - 1;

	// Get the list of cache files
	$FileLst = array();
	while ($FileName = $DirObj->read()) {
		$FullPath = $Dir.$FileName;
		if (strtolower(filetype($FullPath))==='file') {
			if (strlen($FileName)>=strlen($Mask)) {
				if ((substr($FileName,0,$PosL)===substr($Mask,0,$PosL)) and (substr($FileName,-$PosR)===substr($Mask,-$PosR))) {
					$FileLst[] = $FullPath;
				}
			}
		}
	}
	// Delete all listed files
	foreach ($FileLst as $FullPath) {
		@unlink($FullPath);
		$Nbr++;
	}

	return $Nbr;

}

?>