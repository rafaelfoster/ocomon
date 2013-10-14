<?php 

 /*                        Copyright 2005 Flávio Ribeiro

         This file is part of OCOMON.
         OCOMON is free software; you can redistribute it and/or modify
         it under the terms of the GNU General Public License as published by
         the Free Software Foundation; either version 2 of the License, or
         (at your option) any later version.

         OCOMON is distributed in the hope that it will be useful,
         but WITHOUT ANY WARRANTY; without even the implied warranty of
         MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
         GNU General Public License for more details.

         You should have received a copy of the GNU General Public License
         along with Foobar; if not, write to the Free Software
         Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
  */session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<head>";
	print "</head>";
	print "<BODY bgcolor=".BODY_COLOR." >"; //setBGColor('idTab');

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1, 'helpconfiggeral.php');

	//Configurações gerais do sistema
    	//print "<BR><B>".(!isset($TRANS['TTL_CONFIG_GERAL'])?'TTL_CONFIG_GERAL':$TRANS['TTL_CONFIG_GERAL'] ).":</b><BR>"; //<a><img align='top' src='../../includes/icons/help-16.png' width='16' height='16' onClick=\"return popupS('".HELP_PATH."helpconfiggeral.php')\"></a>
	print "<BR><B>".TRANS('TTL_CONFIG_GERAL').":</b><BR>";
		$query = "SELECT * FROM config ";
        	$resultado = mysql_query($query) or die (TRANS('ERR_QUERY'));
		$row = mysql_fetch_array($resultado);


	if ((empty($_GET['action'])) and empty($_POST['submit'])){

		print "<br><TD align='left'>".
				"<input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_EDIT_CONFIG','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cellStyle=true');\">".
			"</TD><br><BR>";
		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('ALERT_CONFIG_EMPTY'));
		}
		else
		{
				$cor=TD_COLOR;
				$cor1=TD_COLOR;
				$linhas = mysql_numrows($resultado);
				print "<td>";
				print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
				print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td><b>".TRANS('OPT_LANG')."</b></td>";
				print "<td>".$row['conf_language']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td><b>".TRANS('OPT_DATE_FORMAT')."</b></td>";
				print "<td>".$row['conf_date_format']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				//SITE PARA ACESSO AO OCOMON
				print "<tr><td><b>".TRANS('OPT_SITE')."</b></td>";
				print "<td>".$row['conf_ocomon_site']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td><b>".TRANS('OPT_REG_PAG')."</b></td>";
				print "<td>".$row['conf_page_size']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";


				print "<tr><td colspan='2'><b>".TRANS('OPT_DATE_EDIT','EDIÇÃO DE DATAS')."</b></td></tr>";
				print "<tr><td>".TRANS('OPT_ALLOW_DATE_EDIT','PERMITE QUE O ADMINISTRADOR ALTERE AS DATAS MANUALMENTE')."</td>";
					if ($row['conf_allow_date_edit']) {
						$allowDateEd = " checked ";
					} else {
						$allowDateEd = "";
					}
					print "<td><input type='checkbox' name='allowDateEdit' ".$allowDateEd." disabled</td></tr>";


				print "<tr><td colspan='2'>&nbsp;</td></tr>";


				print "<tr><td colspan='2'><b>".TRANS('OPT_SCHEDULE')."</b></td></tr>";

				$sqlStatus = "SELECT * FROM `status` WHERE stat_id=".$row['conf_schedule_status']."";
				$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
				$rowStatus = mysql_fetch_array($execStatus);

				print "<tr><td>".TRANS('OPT_SCHEDULE_STATUS')."</td>";
				print "<td>".$rowStatus['status']."</td>";
				print "</tr>";

				//print "<tr><td colspan='2'>&nbsp;</td></tr>";
				$sqlStatus = "SELECT * FROM `status` WHERE stat_id=".$row['conf_schedule_status_2']."";
				$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
				$rowStatus = mysql_fetch_array($execStatus);

				print "<tr><td>".TRANS('OPT_SCHEDULE_STATUS_2')."</td>";
				print "<td>".$rowStatus['status']."</td>";
				print "</tr>";

				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td colspan='2'><b>".TRANS('OPT_FOWARD_STATUS')."</b></td></tr>";

				print "<tr><td>".TRANS('SEL_FOWARD_STATUS')."</td>";
					$sqlStatus = "SELECT * FROM `status` WHERE stat_id=".$row['conf_foward_when_open']."";
					$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
					$rowStatus = mysql_fetch_array($execStatus);

				print "<td>".$rowStatus['status']."</td>";
				print "</tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";



				print "<tr><td colspan='2'><b>".TRANS('OPT_REOPEN')."</b></td></tr>";
				print "<tr><td>".TRANS('OPT_ALLOW_REOPEN')."</td>";
					if ($row['conf_allow_reopen']) {
						$allow = " checked ";
					} else {
						$allow = "";
					}
					print "<td><input type='checkbox' name='allowReopen' ".$allow." disabled</td></tr>";


				print "<tr><td colspan='2'>&nbsp;</td></tr>";
				print "<tr><td colspan='2'><b>".TRANS('OPT_UPLOAD_TYPE')."</b></td></tr>";


				$IMG = (strpos($row['conf_upld_file_types'],'%IMG%'))?" checked":"";
				$TXT = (strpos($row['conf_upld_file_types'],'%TXT%'))?" checked":"";
				$PDF = (strpos($row['conf_upld_file_types'],'%PDF%'))?" checked":"";
				$ODF = (strpos($row['conf_upld_file_types'],'%ODF%'))?" checked":"";
				$OOO = (strpos($row['conf_upld_file_types'],'%OOO%'))?" checked":"";
				$MSO = (strpos($row['conf_upld_file_types'],'%MSO%'))?" checked":"";
				$RTF = (strpos($row['conf_upld_file_types'],'%RTF%'))?" checked":"";
				$HTML = (strpos($row['conf_upld_file_types'],'%HTML%'))?" checked":"";

				print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_IMG')."</td><td><input type='checkbox' name='upld_img' checked disabled></td></tr>";
				print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_TXT')."</td><td><input type='checkbox' name='upld_txt' disabled ".$TXT."></td></tr>";
				print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_PDF')."</td><td><input type='checkbox' name='upld_pdf' disabled ".$PDF."></td></tr>";
				print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_ODF')."</td><td><input type='checkbox' name='upld_odf' disabled ".$ODF."></td></tr>";
				print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_OOO')."</td><td><input type='checkbox' name='upld_ooo' disabled ".$OOO."></td></tr>";
				print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_MSO')."</td><td><input type='checkbox' name='upld_mso' disabled ".$MSO."></td></tr>";
				print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_RTF')."</td><td><input type='checkbox' name='upld_rtf' disabled ".$RTF."></td></tr>";
				print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_HTML')."</td><td><input type='checkbox' name='upld_html' disabled ".$HTML."></td></tr>";



				print "<tr><td colspan='2'><b>".TRANS('OPT_UPLOAD_IMG')."</b></td></tr>";
					$emKbytes = $row['conf_upld_size']/1024;
				print "<tr><td>".TRANS('OPT_MAXSIZE')."</td><td>".$row['conf_upld_size']."&nbsp;bytes (".$emKbytes." kbytes)</td></tr>";
				print "<tr><td>".TRANS('OPT_MAXWIDTH')."</td><td>".$row['conf_upld_width']."px</td></tr>";
				print "<tr><td>".TRANS('OPT_MAXHEIGHT')."</td><td>".$row['conf_upld_height']."px</td></tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td colspan='2'><b>".TRANS('OPT_BARRA')."</b></td></tr>";

				print "<tr><td>".TRANS('OPT_MURAL')."</td><td>";
				if (strpos($row['conf_formatBar'],'%mural%')) {
					$mural = " checked";
				} else {
					$mural = "";
				}
				print "<input type='checkbox' name='formatMural' ".$mural." disabled</td></tr>";

				print "<tr><td>".TRANS('OPT_OCORRENCIAS')."</td><td>";
				if (strpos($row['conf_formatBar'],'%oco%')) {
					$oco = " checked";
				} else {
					$oco = "";
				}
				print "<input type='checkbox' name='formatOco' ".$oco." disabled</td></tr>";

				print "<tr><td colspan='2'>&nbsp;</td></tr>";
				print "<tr><td colspan='2'><b>".TRANS('OPT_SEND_MAIL_WRTY')."</b></td></tr>";
				print "<tr><td>".TRANS('OPT_DAYS_BEFORE')."</td>";
				print "<td>".$row['conf_days_bf']."</td></tr>";


				$sqlArea = "SELECT * FROM sistemas WHERE sis_id = '".$row['conf_wrty_area']."'";
				$execArea = mysql_query($sqlArea) OR die($sqlArea);
				$rowA = mysql_fetch_array($execArea);

				print "<tr><td>".TRANS('OPT_SEL_AREA','ÁREA QUE RECEBE OS E-MAILS')."</td>";
				print "<td>".$rowA['sistema']."</td>";
				print "</tr>";



				print "<tr><td colspan='2'>&nbsp;</td></tr>";
				print "<tr><td colspan='2'><b>".TRANS('OPT_PROB_CATEG')."</b></td></tr>";
				print "<tr><td>".TRANS('OPT_PROB_LABEL1').":</td><td>".$row['conf_prob_tipo_1']."</td></tr>";
				print "<tr><td>".TRANS('OPT_PROB_LABEL2').":</td><td>".$row['conf_prob_tipo_2']."</td></tr>";
				print "<tr><td>".TRANS('OPT_PROB_LABEL3').":</td><td>".$row['conf_prob_tipo_3']."</td></tr>";
				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "<tr><td colspan='2'><b>".TRANS('OPT_ESQUEMA_CORES')."</b></td></tr>";
				print "<tr><td>".TRANS('OPT_ESQUEMA_PERMITE_USERS')."</td>";
					if ($row['conf_allow_change_theme']) {
						$allow = " checked ";
					} else {
						$allow = "";
					}
					print "<td><input type='checkbox' name='allowChangeTheme' ".$allow." disabled</td></tr>";

				print "<tr><td colspan='2'>&nbsp;</td></tr>";

				print "</TABLE>";
		}

	} else

	if ((isset($_GET['action']) && ($_GET['action']=="alter")) && empty($_POST['submit'])){


		print "<form name='alter' action='".$_SERVER['PHP_SELF']."' method='post' onSubmit=\"return valida()\">"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		$files = array();
		$files = getDirFileNames('../../includes/languages/');
		print "<tr><td><b>".TRANS('OPT_LANG','ARQUIVO DE IDIOMA')."</b></td>";
		print "<td><select name='lang' id='idLang' class='select'>"; //<input type='text' name='lang' id='idLang' class='text' value='".$row['conf_language']."'></td>";

			for ($i=0; $i<count($files); $i++){
				print "<option value='".$files[$i]."' ";
				if ($files[$i]==$row['conf_language'])
					print " selected";
				print ">".$files[$i]."</option>";
			}
		print "</select>";
		print "</td>";
		print "</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td><b>".TRANS('OPT_DATE_FORMAT')."</b></td>";
		print "<td><input type='text' name='date_format' id='idDate_format' class='text' value='".$row['conf_date_format']."'></td>";
		print "</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td><b>".TRANS('OPT_SITE')."</b></td>";
		print "<td><input type='text' name='site' id='idSite' class='text' value='".$row['conf_ocomon_site']."'></td>";
		print "</tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";


		print "<tr><td><b>".TRANS('OPT_REG_PAG')."</b></td>";
		print "<td><input type='text' class='text' name='page' id='idPage' value='".$row['conf_page_size']."'></td>";
		print "</tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";


		print "<tr><td colspan='2'><b>".TRANS('OPT_DATE_EDIT')."</b></td></tr>";
		print "<tr><td>".TRANS('OPT_ALLOW_DATE_EDIT')."</td>";
			if ($row['conf_allow_date_edit']) {
				$allowDateEd = " checked ";
			} else {
				$allowDateEd = "";
			}
			print "<td><input type='checkbox' name='allowDateEdit' ".$allowDateEd." </td></tr>";


		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td colspan='2'><b>".TRANS('OPT_SCHEDULE')."</b></td></tr>";

		print "<tr><td>".TRANS('OPT_SCHEDULE_STATUS')."</td>";
		print "<td><select name='schedule_status' id='idScheduleStatus' class='select'>";
			$sqlStatus = "SELECT * FROM `status` ORDER BY status";
			$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
			while ($rowStatus = mysql_fetch_array($execStatus)) {
				print "<option value='".$rowStatus['stat_id']."' ";
					if ($rowStatus['stat_id'] == $row['conf_schedule_status'])
						print " selected";
					print ">".$rowStatus['status']."</option>";
			}

		print "</select>";
		print "</td>";
		print "</tr>";

		print "<tr><td>".TRANS('OPT_SCHEDULE_STATUS_2')."</td>";
		print "<td><select name='schedule_status_2' id='idScheduleStatus2' class='select'>";
			$sqlStatus = "SELECT * FROM `status` ORDER BY status";
			$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
			while ($rowStatus = mysql_fetch_array($execStatus)) {
				print "<option value='".$rowStatus['stat_id']."' ";
					if ($rowStatus['stat_id'] == $row['conf_schedule_status_2'])
						print " selected";
					print ">".$rowStatus['status']."</option>";
			}

		print "</select>";
		print "</td>";
		print "</tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td colspan='2'><b>".TRANS('OPT_FOWARD_STATUS')."</b></td></tr>";

		print "<tr><td>".TRANS('SEL_FOWARD_STATUS')."</td>";
		print "<td><select name='foward' id='idFoward' class='select'>";
			$sqlStatus = "SELECT * FROM `status` ORDER BY status";
			$execStatus = mysql_query($sqlStatus) OR die($sqlStatus);
			while ($rowStatus = mysql_fetch_array($execStatus)) {
				print "<option value='".$rowStatus['stat_id']."' ";
					if ($rowStatus['stat_id'] == $row['conf_foward_when_open'])
						print " selected";
					print ">".$rowStatus['status']."</option>";
			}

		print "</select>";
		print "</td>";
		print "</tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td colspan='2'><b>".TRANS('OPT_REOPEN')."</b></td></tr>";
		print "<tr><td>".TRANS('OPT_ALLOW_REOPEN')."</td>";
			if ($row['conf_allow_reopen']) {
				$allow = " checked ";
			} else {
				$allow = "";
			}
			print "<td><input type='checkbox' name='allowReopen' ".$allow."</td></tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";


		print "<tr><td colspan='2'><b>".TRANS('OPT_UPLOAD_TYPE')."</b></td></tr>";


		$IMG = (strpos($row['conf_upld_file_types'],'%IMG%'))?" checked":"";
		$TXT = (strpos($row['conf_upld_file_types'],'%TXT%'))?" checked":"";
		$PDF = (strpos($row['conf_upld_file_types'],'%PDF%'))?" checked":"";
		$ODF = (strpos($row['conf_upld_file_types'],'%ODF%'))?" checked":"";
		$OOO = (strpos($row['conf_upld_file_types'],'%OOO%'))?" checked":"";
		$MSO = (strpos($row['conf_upld_file_types'],'%MSO%'))?" checked":"";
		$RTF = (strpos($row['conf_upld_file_types'],'%RTF%'))?" checked":"";
		$HTML = (strpos($row['conf_upld_file_types'],'%HTML%'))?" checked":"";

		print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_IMG')."</td><td><input type='checkbox' name='upld_img' value='IMG' checked disabled></td></tr>";
		print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_TXT')."</td><td><input type='checkbox' name='upld_txt' value='TXT' ".$TXT."></td></tr>";
		print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_PDF')."</td><td><input type='checkbox' name='upld_pdf' value='PDF' ".$PDF."></td></tr>";
		print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_ODF')."</td><td><input type='checkbox' name='upld_odf' value='ODF' ".$ODF."></td></tr>";
		print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_OOO')."</td><td><input type='checkbox' name='upld_ooo' value='OOO' ".$OOO."></td></tr>";
		print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_MSO')."</td><td><input type='checkbox' name='upld_mso' value='MSO' ".$MSO."></td></tr>";
		print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_RTF')."</td><td><input type='checkbox' name='upld_rtf' value='RTF' ".$RTF."></td></tr>";
		print "<tr><td>".TRANS('OPT_UPLOAD_TYPE_HTML')."</td><td><input type='checkbox' name='upld_html' value='HTML' ".$HTML."></td></tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><b>".TRANS('OPT_UPLOAD_IMG')."</b></td></tr>";

		print "<tr><td>".TRANS('OPT_MAXSIZE')."</td>";//.transbool($row['conf_user_opencall'])."</td></tr>";
		print "<td><input type='text' class='text' id='idSize' name='size' value='".$row['conf_upld_size']."'</td></tr>";

		print "<tr><td>".TRANS('OPT_MAXWIDTH')."</td>";//.transbool($row['conf_user_opencall'])."</td></tr>";
		print "<td><input type='text' class='text' id='idWidth' name='width' value='".$row['conf_upld_width']."'</td></tr>";

		print "<tr><td>".TRANS('OPT_MAXHEIGHT')."</td>";//.transbool($row['conf_user_opencall'])."</td></tr>";
		print "<td><input type='text' class='text' id='idHeight' name='height' value='".$row['conf_upld_height']."'</td></tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><b>".TRANS('OPT_BARRA')."</b></td></tr>";

		print "<tr><td>".TRANS('OPT_MURAL')."</td><td>";
		if (strpos($row['conf_formatBar'],'%mural%')) {
			$mural = " checked";
		} else {
			$mural = "";
		}
		print "<input type='checkbox' name='formatMural' ".$mural." </td></tr>";

		print "<tr><td>".TRANS('OPT_OCORRENCIAS')."</td><td>";
		if (strpos($row['conf_formatBar'],'%oco%')) {
			$oco = " checked";
		} else {
			$oco = "";
		}
		print "<input type='checkbox' name='formatOco' ".$oco." </td></tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><b>".TRANS('OPT_SEND_MAIL_WRTY')."</b></td></tr>";
		print "<tr><td>".TRANS('OPT_DAYS_BEFORE')."</td>";
		print "<td><input type='text' class='text' id='idDaysBF' name='daysBF' value='".$row['conf_days_bf']."'</td></tr>";

		print "<tr><td>".TRANS('OPT_SEL_AREA','ÁREA QUE RECEBE OS E-MAILS')."</td>";
		print "<td><select name='areaRcptMail' id='idAreaRcptMail' class='select'>"; //<input type='text' name='lang' id='idLang' class='text' value='".$row['conf_language']."'></td>";
			$sqlArea = "SELECT * FROM sistemas WHERE sis_status = 1";
			$execArea = mysql_query($sqlArea) OR die($sqlArea);
			while ($rowA = mysql_fetch_array($execArea)) {
				print "<option value='".$rowA['sis_id']."' ";
					if ($rowA['sis_id'] == $row['conf_wrty_area'])
						print " selected";
					print ">".$rowA['sistema']."</option>";
			}

		print "</select>";
		print "</td>";
		print "</tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><b>".TRANS('OPT_PROB_CATEG')."</b></td></tr>";
		print "<tr><td>".TRANS('OPT_PROB_LABEL1').":</td><td><input type='text' class='text' name='cat1' id='idCat1' value='".$row['conf_prob_tipo_1']."'></td></tr>";
		print "<tr><td>".TRANS('OPT_PROB_LABEL2').":</td><td><input type='text' class='text' name='cat2' id='idCat2' value='".$row['conf_prob_tipo_2']."'></td></tr>";
		print "<tr><td>".TRANS('OPT_PROB_LABEL3').":</td><td><input type='text' class='text' name='cat3' id='idCat3' value='".$row['conf_prob_tipo_3']."'></td></tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td colspan='2'><b>".TRANS('OPT_ESQUEMA_CORES')."</b></td></tr>";
		print "<tr><td>".TRANS('OPT_ESQUEMA_PERMITE_USERS')."</td>";
			if ($row['conf_allow_change_theme']) {
				$allow = " checked";
			} else {
				$allow = "";
			}
			print "<td><input type='checkbox' name='allowChangeTheme' ".$allow."</td></tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER','',0)."'></td>";
		print "<td><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL','',0)."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){

		$conf_formatBar = "%";
		if (isset($_POST['formatMural'])) {
			$conf_formatBar.="%mural%";
			$_SESSION['s_formatBarMural'] = 1;
		} else {
			$_SESSION['s_formatBarMural'] = 0;
		}
		if (isset($_POST['formatOco'])) {
			$conf_formatBar.="%oco%";
			$_SESSION['s_formatBarOco'] = 1;
		} else {
			$_SESSION['s_formatBarOco'] = 0;
		}

		if (isset($_POST['date_format'])) {
			$_SESSION['s_date_format'] = $_POST['date_format'];
		}

		if (isset($_POST['allowChangeTheme'])){
			$allowChangeTheme = 1;
			$_SESSION['s_allow_change_theme'] =1;
		} else {
			$allowChangeTheme = 0;
			$_SESSION['s_allow_change_theme'] = 0;

			$sqlClean = "TRUNCATE TABLE uthemes ";
			$execClean = mysql_query($sqlClean) or die (TRANS('ERR_QUERY'));
		}

		if (isset($_POST['page'])) {
			$_SESSION['s_page_size'] = $_POST['page'];
		}

		if (isset($_POST['allowReopen'])){
			$allowReopen = 1;
			$_SESSION['s_allow_reopen'] =1;
		} else {
			$allowReopen = 0;
			$_SESSION['s_allow_reopen'] = 0;
		}


		if (isset($_POST['allowDateEdit'])){
			$allowDateEdit = 1;
			$_SESSION['s_allow_date_edit'] =1;
		} else {
			$allowDateEdit = 0;
			$_SESSION['s_allow_date_edit'] = 0;
		}



		$FILE_TYPES = "%%IMG%";
		$FILE_TYPES.=isset($_POST['upld_txt'])?$_POST['upld_txt']."%":"";
		$FILE_TYPES.=isset($_POST['upld_odf'])?$_POST['upld_odf']."%":"";
		$FILE_TYPES.=isset($_POST['upld_ooo'])?$_POST['upld_ooo']."%":"";
		$FILE_TYPES.=isset($_POST['upld_pdf'])?$_POST['upld_pdf']."%":"";
		$FILE_TYPES.=isset($_POST['upld_mso'])?$_POST['upld_mso']."%":"";
		$FILE_TYPES.=isset($_POST['upld_rtf'])?$_POST['upld_rtf']."%":"";
		$FILE_TYPES.=isset($_POST['upld_html'])?$_POST['upld_html']."%":"";

		$qry = "UPDATE config SET ".
				"conf_ocomon_site = '".noHtml($_POST['site'])."', ".
				"conf_upld_size= '".$_POST['size']."', conf_upld_width = '".$_POST['width']."', ".
				"conf_upld_height = '".$_POST['height']."', conf_formatBar='".$conf_formatBar."', ".
				"conf_page_size = '".$_POST['page']."', ".
				"conf_prob_tipo_1 = '".noHtml($_POST['cat1'])."', ".
				"conf_prob_tipo_2 = '".noHtml($_POST['cat2'])."', ".
				"conf_prob_tipo_3 = '".noHtml($_POST['cat3'])."', ".
				"conf_allow_change_theme = '".$allowChangeTheme."', ".
				"conf_language = '".$_POST['lang']."', ".
				"conf_upld_file_types = '".$FILE_TYPES."', ".
				"conf_date_format = '".$_POST['date_format']."', ".
				"conf_days_bf = '".$_POST['daysBF']."', ".
				"conf_wrty_area = '".$_POST['areaRcptMail']."', ".
				"conf_allow_reopen = '".$allowReopen."', ".
				"conf_allow_date_edit = '".$allowDateEdit."', ".
				"conf_schedule_status = '".$_POST['schedule_status']."', ".
				"conf_schedule_status_2 = '".$_POST['schedule_status_2']."', ".
				"conf_foward_when_open = '".$_POST['foward']."' ".
				" ";

		//print $qry;
		//exit;
		$exec= mysql_query($qry) or die(TRANS('ERR_EDIT').$qry);

		$_SESSION['s_language'] = $_POST['lang'];
		//print "<script>mensagem('Configuração alterada com sucesso!'); window.open('../../index.php?LOAD=ADMIN','_parent',''); </script>";
		print "<script>mensagem('".TRANS('OK_EDIT','',0)."!'); window.open('../../index.php','_parent',''); redirect('".$_SERVER['PHP_SELF']."'); </script>";
		//redirect('configGeral.php');
	}

?>

<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idSite','QUALQUER','SITE DO OCOMON',1);
		if (ok) var ok = validaForm('idPage','INTEIRO','REGISTROS POR PÁGINA',1);
		if (ok) var ok = validaForm('idSize','INTEIROFULL','TAMANHO MAXIMO',1);
		if (ok) var ok =  validaForm('idWidth','INTEIROFULL','LARGURA MAXIMA',1);
		if (ok) var ok =  validaForm('idHeight','INTEIROFULL','ALTURA MÁXIMA',1);
		if (ok) var ok =  validaForm('idCat1','QUALQUER','CATEGORIA 1',1);
		if (ok) var ok =  validaForm('idCat2','QUALQUER','CATEGORIA 2',1);
		if (ok) var ok =  validaForm('idCat3','QUALQUER','CATEGORIA 3',1);
		if (ok) var ok =  validaForm('idDaysBF','INTEIROFULL','Dias de antecedência',1);

		return ok;
	}

-->
</script>
<SCRIPT LANGUAGE="JavaScript">cp.writeDiv()</SCRIPT>
<?php 
print "</body>";
print "</html>";

?>