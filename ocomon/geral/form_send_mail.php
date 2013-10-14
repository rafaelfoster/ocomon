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
	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";


 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>".TRANS('TLT_SEND_MAIL').":</B><BR>";

	print "<FORM name='form1' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";  //onSubmit='return valida()'
	print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'";


	if (!isset($_REQUEST['numero'])) {
		print TRANS('MSG_ERR_NOT_EXECUTE');
		exit;
	} else {
		$numero = $_REQUEST['numero'];
	}


	if (!isset($_POST['submit']) && !isset($_GET['action'])) {

		$field_to = "";
		//$check_mlist1 = "";
		$field_to_editable = "";
		$field_cc = "";
		$field_subject = "";
		$field_body = "";
		$search = "";
		$radio_tpl = "";

		if (isset($_POST['BT_SEARCH'])) { //Pesquisa

			isset($_POST['field_to'])?$field_to=$_POST['field_to']:$field_to="";
			//isset($_POST['check_mlist1'])?$check_mlist1=$_POST['check_mlist1']:$check_mlist1="";
			isset($_POST['field_to_editable'])?$field_to_editable=$_POST['field_to_editable']:$field_to_editable="";
			isset($_POST['field_cc'])?$field_cc=$_POST['field_cc']:$field_cc="";
			isset($_POST['field_subject'])?$field_subject=$_POST['field_subject']:$field_subject="";
			isset($_POST['field_body'])?$field_body=$_POST['field_body']:$field_body="";
			isset($_POST['search'])?$search=$_POST['search']:$search="";
			isset($_POST['radio_tpl'])?$radio_tpl=$_POST['radio_tpl']:$radio_tpl="";
			//isset($_POST['numero'])?$numero=$_POST['numero']:$numero="";

			//dump($_POST,'POST');

		}


		##############################################################
		//  DIV DA EXIBIÇÃO DAS LISTAS
		print "<tr STYLE=\"{cursor: pointer;}\" onClick=\"invertView('idMailList');\">".
				"<TD width='20%' bgcolor='".TD_COLOR."'>".TRANS('MAIL_FIELD_TO').":&nbsp;".
				"<IMG ID='imgidMailList' SRC='../../includes/icons/open.png' width='9' height='9'>".
				"&nbsp;</td><td colspan='3'><textarea class='textarea' name='field_to' id='idFieldTo' readonly>".$field_to."</textarea>".
				"</td></tr>";

		print "<tr><td colspan='6' ><div id='idMailList' style='{display:none}'>"; //style='{display:none}'
		print "<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>";

			$qry_config = "SELECT * FROM config ";
			$exec_config = mysql_query($qry_config) or die (TRANS('ERR_TABLE_CONFIG'));
			$row_config = mysql_fetch_array($exec_config);

			$qryMailList = "SELECT * FROM mail_list ORDER BY ml_sigla ";

			$execMailList = mysql_query($qryMailList) or die(TRANS('ERR_QUERY'));
			$totalLists = mysql_num_rows($execMailList);

			if (mysql_num_rows($execMailList) == 0)
			{
				//--
			}
			else
			{
				print "<tr><td colspan='4'></tr>";
				print "<TR class='header'><td class='line'>".TRANS('COL_SIGLA')."<td class='line'>".TRANS('COL_SUBJECT')."</TD>".
						"<td class='line'>".TRANS('COL_ADDRESS_TO')."</TD><td class='line'>".TRANS('COL_ADDRESS_CC')."</TD></tr>";

				$j=2;
				$i=1;
				while ($row = mysql_fetch_array($execMailList))
				{
					if ($j % 2)
					{
						$trClass = "lin_par";
					}
					else
					{
						$trClass = "lin_impar";
					}
					$j++;
					print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

					$checado = "";
					isset($_POST["check_mlist".$i])?$checado=" checked":$checado="";

					print "<td class='line' width='10%'><input type='checkbox' name='check_mlist".$i."' id='idCheckMlist".$i."' value='".$row['ml_cod']."' ".$checado."  onChange=\"fill_values('idDivChk".$i."','idDivChkCC".$i."','idDivChkCod".$i."',this);\">&nbsp;".$row['ml_sigla']."</td>";//onChange=\"submitForm(this);\"

					print "<td class='line'><div id='idDivChkCod".$i."'>".NVL($row['ml_desc'])."</div></td>";
					print "<td class='line'><div id='idDivChk".$i."'>".NVL($row['ml_addr_to'])."</div></td>";
					print "<td class='line'><div id='idDivChkCC".$i."'>".NVL($row['ml_addr_cc'])."</div></td>";

					print "</TR>";
					$i++;
				}
			}
		print "</table></div></td></tr>";
		// FIM DA DIV PARA EXIBIÇÃO DAS LISTAS
		#########################################################



		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('MAIL_FIELD_TO_EDITABLE').":</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>".
					"<INPUT type='text' class='select_sol' name='field_to_editable' id='idFieldToEdit' value='".$field_to_editable."'>".
				//"<textarea class='textarea' name='field_to_editable' id='idFieldToEdit'></textarea>".
				"</TD>";
		print "</TR>";


		print "<TR STYLE=\"{cursor: pointer;}\" onClick=\"invertView('idMailList');\">";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('MAIL_FIELD_CC').":</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>".
					//"<INPUT type='text' class='text' name='field_cc' id='idFieldCc'>".
				"<textarea class='textarea' name='field_cc' id='idFieldCc' readonly>".$field_cc."</textarea>".
				"</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('MAIL_FIELD_SUBJECT').":</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
					print "<INPUT type='text' class='select_sol' name='field_subject' id='idFieldSubject' value='".TRANS('OCO_CALL').": ".$numero."'>";

			print "</TD>";
		print "</TR>";

			##############################################################
			//  DIV DA EXIBIÇÃO DOS TEMPLATES
			print "<tr><TD width='20%' bgcolor='".TD_COLOR."' STYLE=\"{cursor: pointer;}\" onClick=\"invertView('idBody');\">".TRANS('MAIL_FIELD_BODY').":&nbsp;".
				"<IMG ID='imgidBody' SRC='../../includes/icons/open.png' width='9' height='9'>&nbsp;</td><td colspan='3'>";
				print "<TEXTAREA class='textarea' name='field_body' id='idFieldBody'>".$field_body."</textarea>";

/*				?>
				<script type="text/javascript">

					var oFCKeditor = new FCKeditor( 'field_body' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = '';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;

				</script>
				<?php */

				print "</td></tr>";

			$style = "{display:none}";
			if (isset($_POST['search'])) {
				$style = "";
			}
			print "<tr><td colspan='6' ><div id='idBody' style='".$style."'>"; //style='{display:none}'
			print "<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>";

				$qry_config = "SELECT * FROM config ";
 				$exec_config = mysql_query($qry_config) or die (TRANS('ERR_TABLE_CONFIG'));
 				$row_config = mysql_fetch_array($exec_config);

				$qryTpl = "";
				$qryTpl = "SELECT * FROM mail_templates ";
				if (isset($_POST['search'])) {
					$qryTpl.= " WHERE (lower(tpl_sigla) like lower(('%".noHtml($_POST['search'])."%'))) OR ".
							"  (lower(tpl_subject) like lower(('%".noHtml($_POST['search'])."%')))";
				}
				$qryTpl.= " ORDER BY tpl_sigla ";

				$execTpl = mysql_query($qryTpl) or die(TRANS('ERR_QUERY'));


				print "<tr><td colspan='4'></tr>";
				print "<tr>".//<td>".TRANS('FIELD_SEARCH')."</td>".
						"<td colspan='4'><input type='text' class='text' name='search' id='idSearch' value='".$search."'>&nbsp;".
							"<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."' onClick=\"LOAD=1;\">".
						"</td></tr>";
				if (isset($_POST['search'])) {
					print "<script>foco('idSearch');</script>";
				}
				if (mysql_num_rows($execTpl) == 0)
				{
					//--
				}
				else
				{
					print "<TR class='header'><td class='line'>".TRANS('COL_SIGLA')."<td class='line'>".TRANS('COL_SUBJECT')."</TD>".
							"<td class='line'>".TRANS('COL_TEMPLATE')."</TD></tr>";

					$j=2;
					while ($rowTpl = mysql_fetch_array($execTpl))
					{
						if ($j % 2)
						{
							$trClass = "lin_par";
						}
						else
						{
							$trClass = "lin_impar";
						}
						$j++;
						print "<tr class=".$trClass." id='linhaz".$j."' onMouseOver=\"destaca('linhaz".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhaz".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhaz".$j."','".$_SESSION['s_colorMarca']."');\">";

						$radio_checado = "";
						if (isset($_POST['radio_tpl'])) {
							if ($_POST['radio_tpl']==$rowTpl['tpl_cod'])
								$radio_checado = " checked";
						}

						print "<td class='line' width='10%'><input type='radio' name='radio_tpl' value='".$rowTpl['tpl_cod']."' ".$radio_checado." id='idRadio' onChange=\"fill_msg('idDivRadio".$j."','idDivRadioSub".$j."',this);\">&nbsp;".$rowTpl['tpl_sigla']."</td>";

						print "<td class='line'><div id='idDivRadioSub".$j."'>".NVL($rowTpl['tpl_subject'])."</div></td>";
						print "<td class='line'><div id='idDivRadio".$j."'>".NVL($rowTpl['tpl_msg_text'])."</div></td>";

						print "</TR>";
					}
				}
			print "</table></div></td></tr>";
			// FIM DA DIV PARA EXIBIÇÃO DOS TEMPLATES
			#########################################################


		NL(4);


		print "<TR>";
			print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
					"<input type='hidden' value='".$totalLists."' name='totalLists'>";
				if (isset($_REQUEST['numero'])) {
					print "<input type='hidden' value='".$_REQUEST['numero']."' name='numero'>";
				}
					print "<input type='submit'  class='button' value='".TRANS('BT_SEND')."' name='submit' onClick=\"LOAD=0;\">";
			print "</TD>";
			print "<TD colspan='2' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
					"<INPUT type='button'  class='button' value='".TRANS('BT_CANCEL')."' name='desloca' onClick=\"window.close();\">".
				"</TD>";
		print "</TR>";

	} else
	if (isset($_POST['submit']) && $_POST['submit'] == TRANS("BT_SEND")){


		$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$_POST['numero']."";
		$execfull = mysql_query($qryfull) or die(TRANS('MSG_ERR_RESCUE_VARIA_SURROU').$qryfull);
		$rowfull = mysql_fetch_array($execfull);
		
		$qryId = "SELECT * FROM global_tickets WHERE gt_ticket = ".$_POST['numero']."";
		$execId = mysql_query($qryId);
		$rowID = mysql_fetch_array($execId);		

		$VARS = array();
		$VARS['%numero%'] = $rowfull['numero'];
		$VARS['%usuario%'] = strtoupper($rowfull['contato']);
		$VARS['%contato%'] = strtoupper($rowfull['contato']);
		$VARS['%descricao%'] = $rowfull['descricao'];
		$VARS['%setor%'] = $rowfull['setor'];
		$VARS['%ramal%'] = $rowfull['telefone'];
		//$VARS['%assentamento%'] = $_POST['assentamento'];
		$VARS['%site%'] = $_SESSION['s_ocomon_site'];
		$VARS['%area%'] = $rowfull['area'];
		$VARS['%operador%'] = $rowfull['nome'];
		//$VARS['%editor%'] = $rowMailLogado['nome'];
		$VARS['%aberto_por%'] = $rowfull['aberto_por'];
		$VARS['%problema%'] = $rowfull['problema'];
		$VARS['%versao%'] = VERSAO;
		$VARS['%url%'] = $_SESSION['s_ocomon_site']."ocomon/geral/mostra_consulta.php?numero=".$_POST['numero']."&id=".$rowID['gt_id'];
		$VARS['%patrimonio%'] = $rowfull['unidade']."&nbsp;".$rowfull['etiqueta'];
		$VARS['%data_abertura%'] = datam($rowfull['data_abertura']);
		$VARS['%status%'] = $rowfull['chamado_status'];
		$VARS['%data_agendamento%'] = datam($rowfull['data_abertura']);
		
		$bodyTranslated = "";
		$subjectTranslated = "";
		$subjectTranslated = noHtml(transvars($_POST['field_subject'],$VARS));
		$bodyTranslated = noHtml(transvars($_POST['field_body'],$VARS));	
			
		$qryMailArea = "SELECT u.nome, s.sistema, s.sis_email as replyto FROM usuarios u, sistemas s ".
						"WHERE u.user_id='".$_SESSION['s_uid']."' and s.sis_id = u.AREA";
		$execMailArea = mysql_query($qryMailArea);
		$rowMailArea = mysql_fetch_array($execMailArea);


		$qryconfmail = "SELECT * FROM mailconfig";
		$execconfmail = mysql_query($qryconfmail) or die (TRANS('ERR_QUERY'));
		$rowconfmail = mysql_fetch_array($execconfmail);

		$msg = "";

		for ($i =1; $i<=$_POST['totalLists']; $i++){

			if (isset($_POST["check_mlist".$i])) {

				$qryFindList = "SELECT * FROM mail_list WHERE ml_cod = ".$_POST["check_mlist".$i]."";
				$execFindList = mysql_query($qryFindList);
				$rowList = mysql_fetch_array($execFindList);

				if (mail_send($rowconfmail,$rowList['ml_addr_to'],$rowList['ml_addr_cc'],$_POST['field_subject'],$_POST['field_body'], $rowMailArea['replyto'], $VARS)) {
					//transvars($body,$envVars)
					$sqlHist = "INSERT INTO mail_hist (mhist_oco, mhist_listname, mhist_address, mhist_address_cc, mhist_subject, mhist_body, mhist_date, mhist_technician) ".
								" values ('".$_POST['numero']."', '".$rowList['ml_sigla']."', '".$rowList['ml_addr_to']."', '".$rowList['ml_addr_cc']."', '".$subjectTranslated."', '".$bodyTranslated."', '".date("Y-m-d H:i:s")."', '".$_SESSION['s_uid']."')";

					$execHist = mysql_query($sqlHist) or die (mysql_error());

					$msg.=TRANS('MAIL_SENT_TO').": ".$rowList['ml_sigla']." ";

				}else {
					echo "A mensagem não pôde ser enviada. <p>";
					echo "Mailer Error: " . $mail->ErrorInfo;
					//exit;
					$msg.=TRANS('MAIL_NOT_SENT_TO').": ".$rowList['ml_sigla']." ";
				}
			}
		}

		if (isset($_POST['field_to_editable']) && $_POST['field_to_editable']!=""){

			if (mail_send($rowconfmail,$_POST['field_to_editable'],'',$_POST['field_subject'],$_POST['field_body'], $rowMailArea['replyto'], $VARS)) {

				$sqlHist = "INSERT INTO mail_hist (mhist_oco, mhist_listname, mhist_address, mhist_address_cc, mhist_subject, mhist_body, mhist_date, mhist_technician) ".
							" values ('".$_POST['numero']."', '', '".noHtml($_POST['field_to_editable'])."', '', '".$subjectTranslated."', '".$bodyTranslated."', '".date("Y-m-d H:i:s")."', '".$_SESSION['s_uid']."')";

				$execHist = mysql_query($sqlHist) or die (mysql_error());

				$msg.=TRANS('MAIL_SENT_TO').": ".$_POST['field_to_editable']." ";
			} else {
				$msg.=TRANS('MAIL_NOT_SENT_TO').": ".$_POST['field_to_editable']." ";
			}
		}
		print "<script>mensagem('".$msg."'); window.opener.location.reload(); window.close();</script>";
	}


	print "</TABLE>";
	print "</FORM>";


	?>
	<script language="JavaScript">

		var array = new Array();

		function valida(){
			var ok = true;
			if (!LOAD) {
				var ok = false;
				if (document.form1.field_to_editable.value != ''){
					var ok = validaForm('idFieldTo','','<?php print TRANS('MAIL_FIELD_TO')?>',0);
					if (ok ) var ok = validaForm('idFieldToEdit','MULTIEMAIL','<?php print TRANS('MAIL_FIELD_TO_EDITABLE')?>',0);
				} else {
					var ok = validaForm('idFieldTo','','<?php print TRANS('MAIL_FIELD_TO')?>',1);
				}

				if (ok) var ok = validaForm('idFieldSubject','','<?php print TRANS('MAIL_FIELD_SUBJECT')?>',1);
				if (ok) var ok = validaForm('idFieldBody','','<?php print TRANS('MAIL_FIELD_BODY')?>',1);
			}

			return ok;
		}


		function fill_array(value){
			size = array.length;
			var last;

			if (size == 0) {
				last = '';
			} else {
				last = array.pop();
			}

			newsize = array.length;
			array[newsize] = value;

			return last;
		}


		function fill_msg(id,id2,objChk) {
			obj = document.getElementById(id);
			obj2 = document.getElementById(id2);
			var tmpValue;
			var lastValue;
			var newvalue;
			var ind;

			tmpValue = document.form1.field_subject.value;

			if (objChk.checked){
				document.form1.field_body.value = obj.innerHTML;

				lastValue = fill_array(obj2.innerHTML);

				if (lastValue != '') {
					ind = tmpValue.indexOf(lastValue);
					if (ind==-1) {
						newvalue =tmpValue.replace(lastValue,'');
					} else {
						newvalue =tmpValue.replace(lastValue,'');
					}
				} else {
					newvalue = tmpValue;
				}

				document.form1.field_subject.value = trim(newvalue)+' '+obj2.innerHTML;
			} else {
				document.form1.field_body.value = '';
				document.form1.field_subject.value = tmpValue;
			}
		}


		function fill_values(id, id2, id3, objChk) {

			obj = document.getElementById(id);
			obj2 = document.getElementById(id2);
			obj3 = document.getElementById(id3);

			var ind;
			var ind2;
			var ind3;
			var indSep;
			var indSep2;
			var indSep3;
			var sep = ', ';
			var expr;
			var expr2;
			var expr3;
			var newvalue;
			var newvalue2;
			var newvalue3;

			var sepSub = ' - ';

			if (objChk.checked){
				if (document.form1.field_to.value == '')
					document.form1.field_to.value += obj.innerHTML; else
					document.form1.field_to.value += sep+obj.innerHTML;
				if (document.form1.field_cc.value == '')
					document.form1.field_cc.value += obj2.innerHTML; else
					document.form1.field_cc.value += sep+obj2.innerHTML;
				if (document.form1.field_subject.value == '')
					document.form1.field_subject.value += obj3.innerHTML; else
					document.form1.field_subject.value += sepSub+obj3.innerHTML;
			} else {
				ind = document.form1.field_to.value.indexOf(sep+obj.innerHTML);
				ind2 = document.form1.field_cc.value.indexOf(sep+obj2.innerHTML);
				ind3 = document.form1.field_subject.value.indexOf(sepSub+obj3.innerHTML);

				expr = obj.innerHTML;
				expr2 = obj2.innerHTML;
				expr3 = obj3.innerHTML;


				if (ind==-1) {
					newvalue =document.form1.field_to.value.replace(expr,'');
				} else {
					newvalue =document.form1.field_to.value.replace(sep+expr,'');
				}
				if (ind2==-1) {
					newvalue2 =document.form1.field_cc.value.replace(expr2,'');
				} else {
					newvalue2 =document.form1.field_cc.value.replace(sep+expr2,'');
				}
				if (ind3==-1) {
					newvalue3 =document.form1.field_subject.value.replace(expr3,'');
				} else {
					newvalue3 =document.form1.field_subject.value.replace(sepSub+expr3,'');
				}

				indSep = newvalue.indexOf(sep);
				indSep2 = newvalue2.indexOf(sep);
				indSep3 = newvalue3.indexOf(sepSub);
				if (indSep==0){
					newvalue = newvalue.slice(2);
				}
				document.form1.field_to.value = newvalue;

				if (indSep2==0){
					newvalue2 = newvalue2.slice(2);
				}
				document.form1.field_cc.value = newvalue2;

				if (indSep3==0){
					newvalue3 = newvalue3.slice(2);
				}
				document.form1.field_subject.value = newvalue3;
			}
		}

	//-->
	</script>
	<?php 

print "</BODY>";
print "</HTML>";
?>
