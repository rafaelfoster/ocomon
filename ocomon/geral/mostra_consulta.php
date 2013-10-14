<?php session_start();
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
*/

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");


	print "<HTML><BODY bgcolor='".BODY_COLOR."' onLoad=\"ajaxFunction('divSla', 'sla_standalone.php', 'idLoad', 'numero=idSlaNumero', 'popup=idSlaNumero', 'SCHEDULED=idScheduled'); \">";
	$auth = new auth;

	$menuTable = false;
	
	if (isset($_GET['INDIV'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
		$menuTable = true;
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);


	if (isset($_POST['numero'])) {
		$COD = $_POST['numero'];
	} else
	if (isset($_GET['numero'])){
		$COD = $_GET['numero'];
	} else {
		print "".TRANS('MSG_ERR_NOT_EXECUTE')."";
		exit;
	}

	print "<div id='idLoad' class='loading' style='{display:none}'><img src='../../includes/imgs/loading.gif'></div>";

	$query = $QRY["ocorrencias_full_ini"]." where numero in (".$COD.") order by numero";
	$resultado = mysql_query($query);
	$row = mysql_fetch_array($resultado);
	
	//print $query;
	
	$GLOBALACCESS = false;
	
	$qryId = "SELECT * FROM global_tickets WHERE gt_ticket = ".$COD."";
	$execId = mysql_query($qryId);
	$rowID = mysql_fetch_array($execId);	
	
	if (isset($_GET['id'])){
		if (!strcmp($_GET['id'],$rowID['gt_id'])) $GLOBALACCESS = true; else $GLOBALACCESS = false;
	}
	
	if ($_SESSION['s_nivel'] == 3 && !$GLOBALACCESS){ //SOMENTE ABERTURA
		if ($row['aberto_por_cod'] != $_SESSION['s_uid']){
			print "".TRANS('MSG_ERR_NOT_ALLOWED')."";
			exit;
		}
	}

        if ($_SESSION['s_nivel'] < 3) {
        	$query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and a.ocorrencia=".$COD."";
        } else 
        	$query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and a.ocorrencia=".$COD." and a.asset_privated = 0";
        
        $resultado2 = mysql_query($query2);
        $linhas=mysql_numrows($resultado2);

	
	if (isset($_GET['GEN'])){
		if (empty($rowID['gt_id'])){
			$qryGenLink = "INSERT INTO global_tickets (gt_ticket, gt_id) values (".$COD.", ".random().")";
			$execGenLink = mysql_query($qryGenLink) or die('ERROR TRYING TO GENERATE_GLOBAL_LINK');
		}
	}
	
	
// 	if ($_SESSION['s_nivel'] == 1) $linkEdita = "<td align='right' width='10%' ><a href='altera_dados_ocorrencia.php?numero=".$COD."'>".TRANS('FIELD_EDIT_ADMIN')."</a>&nbsp;|&nbsp;</td>"; else //&nbsp;|&nbsp;
// 		$linkEdita = "";

	$sqlPai = "select * from ocodeps where dep_filho = ".$COD." ";
	$execpai = mysql_query($sqlPai) or die (TRANS('ERR_QUERY'));
	$rowPai = mysql_fetch_array($execpai);
	if ($rowPai['dep_pai']!=""){
		$msgPai = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'><u><a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowPai['dep_pai']."')\">".TRANS('FIELD_OCCO_SUB_CALL')."".$rowPai['dep_pai']."</a></u>";
	} else
		$msgPai = "";

	?>
	<script type='text/javascript'>

		function popup_alerta(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			return false
		}

		function popup_alerta_mini(pagina)	{ //Exibe uma janela popUP
			x=window.open(pagina,'_blank','dependent=yes,width=400,height=250,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(100,100);
			x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			return false
		}

		function popup(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
			return false
		}

	</script>
	<?php 
	print "<div id='idLoad' class='loading' style='{display:none}'><img src='../../includes/imgs/loading.gif'></div>";

	print "<BR><B>".TRANS('TTL_CONS_OCCO')."</B><BR>".$msgPai."</br>";

	if (isset($_GET['justOpened']) && $_GET['justOpened']==true) {
		$msg = TRANS('MSG_OCCO_EDIT_SUCCESS');
		//$msg.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\"><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;Fechar</a>";
		print "</table>";//#EFEFE7
		print "<div class='alerta' id='idAlerta'><table class='divAlerta'><tr><td colspan='2'><a align='center' onClick=\"exibeEscondeImg('idAlerta');\" title='".TRANS('FIELD_HIDE')."'><img src='".ICONS_PATH."/ok.png' width='16px' height='16px'><b>".$msg."</b></a></td></tr></table></div>";
		//exit;
	}

	
	$menuTable? print "<table width='80%'><tr>":"";

	if ($row['status_cod']!=4 && $_SESSION['s_nivel'] < 3) {
		print "<TD align='right' width='10%' ><a href='encerramento.php?numero=".$row['numero']."'>".TRANS('FIELD_FINISH_OCCO')."</a>&nbsp;|&nbsp;</TD>"; //
	}

	print "<TD align='right' width='10%' ><a href='mostra_relatorio_individual.php?numero=".$row['numero']."' target='_blank'>".TRANS('FIELD_PRINT_OCCO')."</a>&nbsp;|&nbsp;</TD>"; //&nbsp;|&nbsp;
	if ($_SESSION['s_nivel'] < 3)
		print "<TD align='right' width='10%' ><a href='encaminhar.php?numero=".$row['numero']."'>".TRANS('FIELD_EDIT_OCCO')."</a>&nbsp;|&nbsp;</TD>"; //".$linkEdita."

	if (($row['status_cod']!=2) && ($row['status_cod']!=4) && ($_SESSION['s_nivel'] < 3)) {
		print "<TD align='right' width='10%' ><a href='atender.php?numero=".$COD."'>".TRANS('FIELD_ADVERT')."</a>&nbsp;|&nbsp;</TD>"; //&nbsp;|&nbsp;
	}

	print "<TD align='right' width='10%' ><a onClick=\"javascript:popup('mostra_sla_definido.php?popup=true&numero=".$row['numero']."')\">".TRANS('COL_SLA')."</a>&nbsp;|&nbsp;</TD>";//&nbsp;|&nbsp;

	if ($row['status_cod']!=4 && $_SESSION['s_nivel'] < 3) {
		print "<TD align='right' width='10%' bgcolor='".BODY_COLOR."' ><a onClick=\"javascript:popup_alerta('incluir.php?popup=true".
				"&pai=".$row['numero']."&invTag=".$row['etiqueta']."&invInst=".$row['unidade_cod']."&invLoc=".$row['setor_cod']."".
				"&contato=".$row['contato']."&telefone=".$row['telefone']."')\">".TRANS('FIELD_OPEN_SUBCALL')."</a>&nbsp;|&nbsp;</TD>";//&nbsp;|&nbsp;
	}

	if ($row['status_cod']==4 && $_SESSION['s_allow_reopen']) {//CHECAGEM PARA PERMITIR QUE O CHAMADO SEJA REABERTO NO SISTEMA.
		print "<TD align='right' width='10%' bgcolor='".BODY_COLOR."' >".
			"<a onClick=\"confirma('".TRANS('ENSURE_REOPEN')."?','".$_SERVER['PHP_SELF']."?action=reopen&numero=".$COD."')\">
				".TRANS('FIELD_REOPEN_CALL')."</a>&nbsp;|&nbsp;</TD>";//&nbsp;|&nbsp;

		if (isset($_GET['action']) && ($_GET['action']=="reopen")) {

			$qryDelSolution = "DELETE FROM solucoes WHERE numero = ".$COD."";
			$execDelSolution = mysql_query($qryDelSolution) or die(TRANS('ERR_QUERY'));

			$qryUpdStatus = "UPDATE ocorrencias SET `status`=1,data_fechamento=NULL WHERE numero=".$COD."";
			$execUpdStatus = mysql_query($qryUpdStatus) or die(TRANS('ERR_QUERY'));

			print "<script>redirect('".$_SERVER['PHP_SELF']."?numero=".$COD."')</script>";
		}
	}


	print "<TD align='right' width='10%' bgcolor='".BODY_COLOR."'  ><a onClick=\"javascript:popup('tempo_doc.php?popup=true".
			"&cod=".$row['numero']."')\">".TRANS('FIELD_TIME_DOCUMENTATION')."</a>&nbsp;|&nbsp;</TD>";//&nbsp;|&nbsp;

	if ($_SESSION['s_nivel'] < 3) {
		print "<TD align='right' width='10%' bgcolor='".BODY_COLOR."'><a onClick=\"javascript:popupS('form_send_mail.php?popup=true".
			"&numero=".$row['numero']."')\">".TRANS('SEND_EMAIL')."</a></TD>"; //&nbsp;|&nbsp;
	}

	$menuTable? print "</tr></table>":"";
	

	print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";

				
		Print "<tr>";
			print "<td colspan='7'>";
				print "<div id='divSla'>";
					print "<input type='hidden' name='slaNumero' id='idSlaNumero' value='".$row['numero']."'>";
					print "<input type='hidden' name='SCHEDULED' id='idScheduled' value='".$row['oco_scheduled']."'>";
				print "</div>";
            		print "</TD>";		
		Print "</tr>";				
				
		
		$getPriorityDesc = "SELECT * FROM prior_atend WHERE pr_cod = '".$row['oco_prior']."'";
		$execGetPrior = mysql_query($getPriorityDesc);
		$rowGet = mysql_fetch_array($execGetPrior);
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>".TRANS('OCO_PRIORITY').":</TD>";
			print "<TD width='30%' align='left'><input class='disable' value='".$rowGet['pr_desc']."' disabled></TD>";
			//print "<TD width='30%' align='left'><input class='disable' value='".$rowGet['pr_desc']."' style='{background-color:".$rowGet['pr_color'].";}'; disabled></TD>";
		print "</TR>";
	
	print "<TR>";
		print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>".TRANS('OCO_FIELD_NUMBER').":</TD>";
		print "<TD width='30%' align='left'><input class='disable' value='".$row['numero']."' disabled></TD>";
		print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>".TRANS('OCO_FIELD_AREA').":</TD>";
		print "<TD colspan='3' width='30%' align='left'  ><input class='disable' value='".$row['area']."' disabled></TD>";
	print "</TR>";
        print "<TR>";

		$ShowlinkScript = "";
		$qryScript = "SELECT * FROM prob_x_script WHERE prscpt_prob_id = ".$row['prob_cod']."";
		$execQryScript = mysql_query($qryScript);
		if (mysql_num_rows($execQryScript)>0)
			$ShowlinkScript = "<a onClick=\"popup_alerta('../../admin/geral/scripts.php?action=popup&prob=".$row['prob_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."help-16.png' title='".TRANS('HNT_SCRIPT_PROB')."'></a>";
		
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_PROB').":</TD>";
		print "<TD width='30%' align='left' ><input class='disable' value='".$row['problema']."' disabled>".$ShowlinkScript."</TD>";
		print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>".TRANS('FIELD_OPEN_BY').":</TD>";
		print "<TD colspan='3' width='30%' align='left' ><input class='disable' value='".$row['aberto_por']."' disabled></TD>";
        print "</TR>";

        		$qryCatProb = "SELECT * FROM problemas as p ".
					"LEFT JOIN sistemas as s on p.prob_area = s.sis_id ".
					"LEFT JOIN sla_solucao as sl on sl.slas_cod = p.prob_sla ".
					"LEFT JOIN prob_tipo_1 as pt1 on pt1.probt1_cod = p.prob_tipo_1 ".
					"LEFT JOIN prob_tipo_2 as pt2 on pt2.probt2_cod = p.prob_tipo_2 ".
					"LEFT JOIN prob_tipo_3 as pt3 on pt3.probt3_cod = p.prob_tipo_3 ".
					" WHERE p.prob_id = ".$row['prob_cod']." ";
			$execCatprob = mysql_query($qryCatProb) or die ($qryCatProb);
			$rowCatProb = mysql_fetch_array($execCatprob);

		print "<tr><TD width='20%' class='cborda' bgcolor='".TD_COLOR."'>".TRANS('COL_CAT_PROB')."</td><td colspan='3'>".$rowCatProb['probt1_desc']." | ".$rowCatProb['probt2_desc']." | ".$rowCatProb['probt3_desc']."</td></tr>";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_DESC').":</TD>";

		if (isset($_GET['destaca'])){
			print "<TD  colspan='2' valign='top' align='left'  class='wide'>".destaca($_GET['destaca'], toHtml(nl2br($row['descricao'])))."</TD>";
		} else
                	//print "<TD  colspan='2' valign='top' align='left'  class='textareaDisable'>".nl2br($row['descricao'])."</TD>";//textareaDisable
                	print "<TD  colspan='4' valign='top' align='left' class='wide'>".toHtml(nl2br($row['descricao']))."</TD>";//textareaDisable
                //print "<TD width='40%' align='left' >&nbsp;</TD>";
        print "</TR>";


	print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_UNIT').":</TD>";
                print "<TD width='30%' align='left'><input class='disable' value='".$row['unidade']."' disabled></TD>";

                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_TAG_EQUIP').":</TD>";
		print "<TD  width='30%' align='left'>".
				"<a onClick=\"popup_alerta('../../invmon/geral/mostra_consulta_inv.php?".
				"comp_inst=".$row['unidade_cod']."&comp_inv=".$row['etiqueta']."&popup=true')\">".
				"<font color='blue'><u>".$row['etiqueta']."</u></font></a>".
			"</TD>";
		print "<TD colspan='2' align='left'>&nbsp;</td>";
		print "</TR>";
		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_CONTACT').":</TD>";
                print "<TD width='30%' align='left'><input class='disable' value='".$row['contato']."' disabled></TD>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_PHONE').":</TD>";
                print "<TD colspan='3' width='30%' align='left'><input class='disable' value='".$row['telefone']."' disabled></TD>";
	print "</TR>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_LOCAL').":</TD>";
                print "<TD width='30%' align='left'><input class='disable' value='".$row['setor']."' disabled></TD>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_LAST_OPERATOR').":</TD>";
                print "<TD colspan='3' width='30%' align='left' ><input class='disable' value='".$row['nome']."' disabled></TD>";
	print "</TR>";

        if ($row['status_cod'] == 4)
	{

		if ($row['data_abertura'] != $row['oco_real_open_date'] && $row['oco_real_open_date']!='0000-00-00 00:00:00'){
			print "<TR>";
				print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_LAST_SCHEDULE').":</TD>";
				print "<TD  align='left' ><input class='disable' value='".formatDate($row['data_abertura'])."' disabled></TD>";
				print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_DATE_CLOSING').":</TD>";
				print "<TD  width='30%' colspan='3' align='left' ><input class='disable' value='".formatDate($row['data_fechamento'])."' disabled></TD>";
			print "</tr>";

			print "<TR>";
				print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_REAL_DATE_OPEN').":</TD>";
				print "<TD  align='left' ><input class='disable' value='".formatDate($row['oco_real_open_date'])."' disabled></TD>";
			print "</tr>";
		} else {
			print "<TR>";
				print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_OPEN').":</TD>";
				print "<TD  align='left' ><input class='disable' value='".formatDate($row['data_abertura'])."' disabled></TD>";
				print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_DATE_CLOSING').":</TD>";
				print "<TD  width='30%' colspan='3' align='left' ><input class='disable' value='".formatDate($row['data_fechamento'])."' disabled></TD>";
			print "</tr>";
		}



		print "<TR>";
			print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SCRIPT_SOLUTION').":</TD>";
			print "<TD  align='left' colspan='6'>".$row['script_desc']."</TD>";
		print "</tr>";

		print "<tr>";
			print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_STATUS').":</TD>";
			print "<TD colspan='5' align='left'  >".
					"<font color='blue'><u><a onClick=\"popup_alerta_mini('mostra_hist_status.php?numero=".$COD."&popup=true')\">".
					"".$row['chamado_status']."</u></a></font>".
				"</TD>";
				//print "<TD colspan='2' align='left'>".

		print "</TR>";
	}
        else
	{

		if ($row['oco_scheduled']==1){
			$os_DataAbertura = formatDate($row['oco_real_open_date']);
			$os_DataAgendamento = formatDate($row['data_abertura']);
		} else {
			$os_DataAbertura = formatDate($row['data_abertura']);
			$os_DataAgendamento = formatDate($row['data_abertura']);
		}

		print "<tr>";
		if ($row['data_abertura'] != $row['oco_real_open_date'] && $row['oco_real_open_date']!='0000-00-00 00:00:00' && !empty($row['oco_real_open_date'])){
				print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_LAST_SCHEDULE').":</TD>";
				print "<TD  align='left' ><input class='disable' value='".formatDate($row['data_abertura'])."' disabled></TD>";
				print "<TD  align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_REAL_DATE_OPEN').":</TD>";
				print "<TD  width='30%' colspan='3' align='left' ><input class='disable' value='".formatDate($row['oco_real_open_date'])."' disabled></TD>";
		} else {
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_OPEN').":</TD>";
			print "<TD width='30%' align='left' ><input class='disable' value='".$os_DataAbertura."' disabled></TD>";
		}
		print "</tr>";

		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_STATUS').":</TD>";
			print "<TD width='30%' align='left'  >".
					"<b><font color='blue'><u><a onClick=\"popup_alerta_mini('mostra_hist_status.php?numero=".$COD."&popup=true')\">".
					"".$row['chamado_status']."</u></a></font></b>".
				"</TD>";
			
			
			$qryId = "SELECT * FROM global_tickets WHERE gt_ticket = ".$COD."";
			$execId = mysql_query($qryId);
			$rowID = mysql_fetch_array($execId);			
			
			$global_link = "";
			if (!empty($rowID['gt_id'])) {
				$global_link = $_SESSION['s_ocomon_site']."ocomon/geral/mostra_consulta.php?numero=".$COD."&id=".$rowID['gt_id'];
			} else {
				$global_link = "<a href='".$_SERVER['PHP_SELF']."?numero=".$COD."&GEN=1'>".TRANS('GENERATE_GLOBAL_LINK')."</a>";
			}
			
			
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('GLOBAL_LINK').":</td>".
				"<td>".$global_link."</td>";
		print "</TR>";

		
		if ($row['oco_scheduled']==1){
			print "<tr>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_SCHEDULED_TO').":</TD>";
			print "<TD width='30%' align='left' ><input class='disable' value='".$os_DataAgendamento."' disabled></TD>";
			print "</tr>";
		}
		
		
// 		Print "<tr>";
// 			print "<td colspan='7'>";
// 				print "<div id='divSla'>";
// 					print "<input type='hidden' name='slaNumero' id='idSlaNumero' value='".$row['numero']."'>";
// 				print "</div>";
//             		print "</TD>";		
// 		Print "</tr>";
		

	}

	if ($linhas != 0) { //ASSENTAMENTOS DO CHAMADO
		print "<tr><td colspan='6'><IMG ID='imgAssentamento2' SRC='../../includes/icons/close.png' width='9' height='9' ".
				"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Assentamento2')\">&nbsp;<b>".TRANS('THERE_IS_ARE')." <font color='red'>".$linhas."</font>".
				" ".TRANS('FIELD_NESTING_FOR_OCCO').".</b></td></tr>";

		//style='{padding-left:5px;}'
		print "<tr><td colspan='6'><div id='Assentamento2'>"; //style='{display:none}'
		print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
		$i = 0;
		while ($rowAssentamento2 = mysql_fetch_array($resultado2)){
			$printCont = $i+1;
			$transAssetText = "";
			if ($rowAssentamento2['asset_privated']==1) $transAssetText = TRANS('CHECK_ASSET_PRIVATED'); else $transAssetText = "";
			print "<TR>";
			print "<TD width='20%' ' bgcolor='".TD_COLOR."' valign='top'>";
				if($rowAssentamento2['tipo_assentamento']==3){//SE FOR JUSTIFICATIVA
					print "<b>".TRANS('TXT_JUSTIFICATION')."</b> ";
				} else {
					print "".TRANS('FIELD_NESTING')." ".$printCont." ".TRANS('SHORT_OF')." ".$linhas." ";
				}
				print "".TRANS('SHORT_BY')." ".$rowAssentamento2['nome']." ".TRANS('SHORT_IN')." ".formatDate($rowAssentamento2['data'])."".
				"<br/><b>".$transAssetText."</b></TD>";

			if (isset($_GET['destaca'])){
				print "<TD colspan='4' align='left'  class='textareaDisable' valign='top'>".destaca($_GET['destaca'], nl2br($rowAssentamento2['assentamento']))."</TD>";
			} else
				print "<TD colspan='4' align='left'  class='textareaDisable' valign='top'>".nl2br($rowAssentamento2['assentamento'])."</TD>";
			print "<TD width='20%'  valign='top'>&nbsp;</td>";
			print "</TR>";
			$i++;
		}
		print "</table></div></td></tr>";
	}

	
	if ($_SESSION['s_nivel']== 3) {
		print "<form name='short' method='post' action='".$_SERVER['PHP_SELF']."'>";
		print "<input type='hidden' name='hidNumero' id='idNumero' value='".$COD."'>";
		if (isset($_GET['id'])){
			print "<input type='hidden' name='urlid' id='idUrl' value='".$_GET['id']."'>";
			print "<tr><td colspan='4'><input type='button' class='button' onClick=\"ajaxFunction('idDivDetails', 'insert_comment.php', 'idLoad', 'numero=idNumero', 'urlid=idUrl');\" value='".TRANS('INSERT_COMMENT','Inserir comentário',0)."'></td></tr>";
		} else
			print "<tr><td colspan='4'><input type='button' class='button' onClick=\"ajaxFunction('idDivDetails', 'insert_comment.php', 'idLoad', 'numero=idNumero');\" value='".TRANS('INSERT_COMMENT','Inserir comentário',0)."'></td></tr>";
		//print "<tr><td colspan='4'><div id='idDivDetails'></div></td></tr>";//style='{display:none;}'
		print "</form>";
		print "<tr><td colspan='4'><div id='idDivDetails'></div></td></tr>";	
	}
		######################################################
		## E-MAILS ENVIADOS SOBRE ESSA OCORRï¿½NCIA
		$qryMail = "SELECT * FROM mail_hist m, usuarios u WHERE m.mhist_technician=u.user_id AND ".
					"m.mhist_oco=".$_REQUEST['numero']." ORDER BY m.mhist_date";
		$execMail = mysql_query($qryMail) or die (TRANS('ERR_QUERY'));

		if (mysql_num_rows($execMail) != 0)
		{


			print "<tr STYLE=\"{cursor: pointer;}\" onClick=\"invertView('idMail');\"><TD width='20%' bgcolor='".TD_COLOR."'>".
					"<img src='../../includes/icons/mail_generic.png' width='16px' height='16px' border='0'>&nbsp;".
					"".TRANS('MAIL_SENT').":&nbsp;<IMG ID='imgidMail' SRC='../../includes/icons/open.png' width='9' height='9'>&nbsp;</td>".
					"<td colspan='3'></td></tr>";


			print "<tr><td colspan='6' ><div id='idMail' style='{display:none}'>"; //style='{display:none}'
			print "<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>";



					print "<TABLE border='0' cellpadding='1' cellspacing='0' width='90%'>";
					print "<tr class='header'>";
					print "<td class='line'>".TRANS('MHIST_SUBJECT')."</td><td class='line'>".TRANS('MHIST_LISTS')."</td>".
						"<td class='line'>".TRANS('MHIST_BODY')."</td>".
						"<td class='line'>".TRANS('MHIST_DATE')."</td>".
						"</td><td class='line'>".TRANS('MHIST_TECHNICIAN')."</td>";
					print "</tr>";

					$j = 2;
					while ($rowMail = mysql_fetch_array($execMail)) {
						if ($j % 2) {
								$trClass = "lin_par";
						}
						else {
								$trClass = "lin_impar";
						}
						$j++;

						$limite = 30;
						$shortBody = trim($rowMail['mhist_body']);
						if (strlen($shortBody)>$limite) {
							$shortBody = substr($shortBody,0,($limite-4))."...";
						}

						print "<tr class=".$trClass." id='imglinhax".$j."' onMouseOver=\"destaca('imglinhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('imglinhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('imglinhax".$j."','".$_SESSION['s_colorMarca']."');\" onClick=\"invertView('linhax".$j."');\" STYLE=\"{cursor: pointer;}\">";
						print "<td class='line'>".$rowMail['mhist_subject']."</td><td class='line'>".NVL($rowMail['mhist_listname'])."</td>".
							"<td class='line'>".$shortBody."</td>".
							"<td class='line'>".formatDate($rowMail['mhist_date'])."</td><td class='line'>".$rowMail['nome']."</td>";
						print "</tr>";


						print "<tr><td colspan='6' ><div id='linhax".$j."' style='{display:none}'>"; //style='{display:none}'
						print "<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>";

							print "<tr><td class='line'><b>".TRANS('MAIL_FIELD_TO').":</b> ".toHtml($rowMail['mhist_address'])."</td></tr>";
							print "<tr><td class='line'><b>".TRANS('MAIL_FIELD_CC').":</b> ".toHtml($rowMail['mhist_address_cc'])."</td></tr>";
							print "<tr><td class='textarea'>".nl2br(toHtml($rowMail['mhist_body']))."</td></tr>";
							//print "<tr><td>".$rowMail['mhist_body']."</td></tr>";
							NL();

						print "</table></div></td></tr>";
					}

					print "</table>";
			print "</table></div></td></tr>";
		}
		//FIM DO TRECHO SOBRE OS E-MAIL ENVIADOS
		#############################################################


	$qryTela = "select * from imagens where img_oco = ".$row['numero']."";
	$execTela = mysql_query($qryTela) or die (TRANS('MSG_ERR_NOT_INFO_IMAGE'));
	//$rowTela = mysql_fetch_array($execTela);
	$isTela = mysql_num_rows($execTela);
	$cont = 0;
	print "<table>";
	while ($rowTela = mysql_fetch_array($execTela)) {
	//if ($isTela !=0) {
		$cont++;
		print "<tr>";
		$size = round($rowTela['img_size']/1024,1);
		print "<TD  bgcolor='".TD_COLOR."' >".TRANS('FIELD_ATTACH')." ".$cont."&nbsp;[".$rowTela['img_tipo']."](".$size."k):</td>";

		if(isImage($rowTela["img_tipo"])) {
			$viewImage = "&nbsp;<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?".
				"file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\" ".
				"title='View the file'><img src='../../includes/icons/kghostview.png' width='16px' height='16px' border='0'></a>";
		} else {
			$viewImage = "";
		}
		print "<td colspan='5' ><a onClick=\"redirect('../../includes/functions/download.php?".
				"file=".$row['numero']."&cod=".$rowTela['img_cod']."')\" title='Download the file'>".
				"<img src='../../includes/icons/attach2.png' width='16px' height='16px' border='0'>".
				"".$rowTela['img_nome']."</a>".$viewImage."</TD>";
		print "</tr>";
	}
	print "</table>";
	print "<br>";


        $qrySubCall = "select * from ocodeps where dep_pai = ".$row['numero']."";
        $execSubCall = mysql_query($qrySubCall) or die(TRANS('MSG_ERR_RESCUE_INFO_SUBCALL').'<br>'.$qrySubCall);
	$existeSub = mysql_num_rows($execSubCall);
	if ($existeSub>0) {
		$comDeps = false;
		while ($rowSubPai = mysql_fetch_array($execSubCall)){
			$sqlStatus = "select o.*, s.* from ocorrencias o, `status` s  where o.numero=".$rowSubPai['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3) ";
			$execStatus = mysql_query($sqlStatus) or die (TRANS('MSG_ERR_RESCUE_INFO_STATUS_CALL_SON').'<br>'.$sqlStatus);
			$regStatus = mysql_num_rows($execStatus);
			if ($regStatus > 0) {
				$comDeps = true;
			}
		}
		if ($comDeps) {
			$imgSub = ICONS_PATH."view_tree_red.png";
		} else {
			$imgSub = ICONS_PATH."view_tree_green.png";
		}

		print "<tr><td  colspan='6'><IMG ID='imgSubCalls' SRC='../../includes/icons/open.png' width='9' height='9' ".
				"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('SubCalls')\">&nbsp;<b><img src='".$imgSub."' width='16' height='16' title='".TRANS('FIELD_CALL_SUBCALL_DEPEND').":</b></td></tr>";//<span style=\"background:yellow\">

		print "<tr><td colspan='6'></td></tr>";
		print "<tr><td colspan='6'><div id='SubCalls' style='{display:none}'>"; //style='{display:none}'	//style='{padding-left:5px;}'

		print "<TABLE border='0' style='{padding-left:10px;}' cellpadding='5' cellspacing='0' align='left' width='90%'>";
		print "<tr class='header'><td class='line'>".TRANS('OCO_FIELD_NUMBER')."<br>".TRANS('OCO_AREA')."</td><td class='line'>".TRANS('OCO_FIELD_PROB')."</td><td class='line'>".TRANS('OCO_FIELD_CONTACT')."<br>".TRANS('OCO_PHONE')."</td><td class='line'>".TRANS('OCO_LOCAL')."<br>".TRANS('OCO_DESC')."</td><td class='line'>".TRANS('FIELD_LAST_OPERATOR')."<br>".TRANS('OCO_STATUS')."</td></tr>";
		$j=2;
		$execSubCall = mysql_query($qrySubCall);
		while ($rowSub = mysql_fetch_array($execSubCall)) {
			if ($j % 2) {
					$trClass = "lin_par";
			}
			else {
					$trClass = "lin_impar";
			}
			$j++;

			$qryDetail = $QRY["ocorrencias_full_ini"]." WHERE  o.numero = ".$rowSub['dep_filho']." ";
			$execDetail = mysql_query($qryDetail) or die (TRANS('MSG_ERR_RESCUE_DATA_OCCO').$qryDetail);
			$rowDetail = mysql_fetch_array($execDetail);

			print "<tr class=".$trClass." id='linhaxy".$j."' onMouseOver=\"destaca('linhaxy".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhaxy".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhaxy".$j."','".$_SESSION['s_colorMarca']."');\">";

			print "<td class='line'><a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowDetail['numero']."')\"><b>".$rowDetail['numero']."</b></a><br>".$rowDetail['area']."</TD>";
			print "<td class='line'>".$rowDetail['problema']."</TD>";
			print "<td class='line'><b>".$rowDetail['contato']."</b><br>".$rowDetail['telefone']."</TD>";
			$texto = trim($rowDetail['descricao']);
			if (strlen($texto)>200){
				$texto = substr($texto,0,195)." ..... ";
			};
			print "<td class='line'><b>".$rowDetail['setor']."</b><br>".$texto."</TD>";
			print "<td class='line'><b>".$rowDetail['nome']."</b><br>".$rowDetail['chamado_status']."</TD>";
			print "</tr>";
		}
		print "</table></div></td></tr>";
	}

print "</TABLE>";


?>
<script type="text/javascript">
	desabilitaLinks(<?php print $_SESSION['s_ocomon'];?>);

	function invertView(id) {
		var element = document.getElementById(id);
		var elementImg = document.getElementById('img'+id);
		var address = '../../includes/icons/';

		if (element.style.display=='none'){
			element.style.display='';
			elementImg.src = address+'close.png';
		} else {
			element.style.display='none';
			elementImg.src = address+'open.png';
		}
	}
</script>
<?php 
print "</body>";
print "</html>";
?>