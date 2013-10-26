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
	//print "<script src='../../includes/javascript/ajax_request.js'></script>";

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

	$imgsPath = "../../includes/imgs/";
	$hoje = date("Y-m-d H:i:s");
    	$hoje2 = date("d/m/Y");


	print "<HTML><BODY bgcolor='".BODY_COLOR."' ".
		"onLoad=\"ajaxFunction('Problema', 'showSelProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'area_habilitada=idAreaHabilitada'); ajaxFunction('divProblema', 'showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea'); ajaxFunction('divSla', 'sla_standalone.php', 'idLoad', 'numero=idSlaNumero', 'popup=idSlaNumero', 'SCHEDULED=idScheduled'); ajaxFunction('divInformacaoProblema', 'showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea');\">";
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die (TRANS('ERR_TABLE_CONFIG'));;
	$row_config = mysql_fetch_array($exec_config);


	$qryGlobal = mysql_query("SELECT * FROM global_tickets WHERE gt_ticket = ".$_REQUEST['numero']."");
	$arrayGlobal = mysql_fetch_array($qryGlobal);

	$sqlSoluc = "SELECT * FROM solucoes WHERE numero = ".$_REQUEST['numero']." ";
	$execSoluc = mysql_query ($sqlSoluc);
	$regSoluc = mysql_num_rows($execSoluc);
	if ($regSoluc >0) {
		print "<script>".
			"mensagem('".TRANS('MSG_ALERT_OCCO_IS_LOCKED_UP')."');".
			"history.back();";
		print "</script>";
		exit;
	}


	$sqlSub = "select * from ocodeps where dep_pai = ".$_REQUEST['numero']." ";
	$execSub = mysql_query ($sqlSub) or die (TRANS('MSG_ERR_NOT_RESCUE_INFO_DEPEND_OCCO').$sqlSub);
	$deps = array();
	while ($rowSub = mysql_fetch_array($execSub)) {

		$sqlStatus = "select o.*, s.*  from ocorrencias as o, `status` as s where o.numero = ".$rowSub['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3)  ";
		$execStatus = mysql_query($sqlStatus) or die (TRANS('MSG_ERR_NOT_ACCESS_CALL_SON').$sqlStatus);
		$achou = mysql_num_rows ($execStatus);
		if ($achou > 0) {
			$deps[] = $rowSub['dep_filho'];
		}

	}

	if(sizeof($deps)) {
		$saida = "<b>".TRANS('MSG_ALERT_OCCO_NOT_LOCKED_UP').":</b><br><br>";
		foreach($deps as $err) {
			$saida.="Chamado <a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$err."')\"><font color='blue'>".$err."</font></a><br>";
		}
		$saida.="<br><a align='center' onClick=\"redirect('mostra_consulta.php?numero=".$_REQUEST['numero']."');\"><img src='".ICONS_PATH."/back.png' width='16px' height='16px'>&nbsp;".TRANS('TXT_RETURN')."</a>";
		print "</table>";
		print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$saida."</td></tr></table></div>";
		exit;
	}



	//$query = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and numero=$numero";
	//$query = $QRY["ocorrencias_full_ini"]." where numero in (".$numero.") order by numero";
	$query = $QRY["ocorrencias_full_ini"]." where numero = ".$_REQUEST['numero']." order by numero";
	$resultado = mysql_query($query);
	$rowABS = mysql_fetch_array($resultado);


	//print $query;

	$atendimento = "";
	$atendimento = $rowABS['data_atendimento'];

	$query2 = "select a.*, u.* from assentamentos as a, usuarios as u where a.responsavel = u.user_id and ocorrencia='".$_REQUEST['numero']."'";
	$resultado2 = mysql_query($query2);
	$linhas2 = mysql_numrows($resultado2);

	if (!isset($_POST['submit'])) {



		if (isset($_POST['carrega'])){
			$prob = $_POST['prob'];

			if (isset($_POST['radio_prob'])) {
				$radio_prob = $_POST['radio_prob'];
			} else $radio_prob = $_POST['prob'];

			$inst = $_POST['inst'];
			$etiqueta = $_POST['etiqueta'];
			$contato = $_POST['contato'];
			$loc = $_POST['loc'];
			$problema = $_POST['problema'];
			$solucao = $_POST['solucao'];
			$numero = $_POST['numero'];

			$script_sol = $_POST['script_sol'];
		} else {
			$prob = $rowABS['prob_cod'];

			$radio_prob = $rowABS['prob_cod'];

			$inst = $rowABS['unidade_cod'];
			$etiqueta = $rowABS['etiqueta'];
			$contato = $rowABS['contato'];
			$loc = $rowABS['setor_cod'];

			$script_sol = $rowABS['oco_script_sol'];
			//$problema = $_POST['problema'];
			//$solucao = $_POST['solucao'];
			//$numero = $_POST['numero'];
		}


		print "<BR><B>".TRANS('SUBTTL_CLOSING_OCCO')."</B><BR>";
		print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' name='form1' onSubmit='return valida()'>";
		print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";

		Print "<tr>";
			print "<td colspan='7'>";
				print "<div id='divSla'>";

				print "</div>";
            		print "</TD>";
		Print "</tr>";

		print "<input type='hidden' name='slaNumero' id='idSlaNumero' value='".$_REQUEST['numero']."'>";
		print "<input type='hidden' name='SCHEDULED' id='idScheduled' value='".$rowABS['oco_scheduled']."'>";

		$getPriorityDesc = "SELECT * FROM prior_atend WHERE pr_cod = '".$rowABS['oco_prior']."'";
		$execGetPrior = mysql_query($getPriorityDesc);
		$rowGet = mysql_fetch_array($execGetPrior);
// 		print "<TR>";
// 			print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>".TRANS('OCO_PRIORITY').":</TD>";
// 			print "<TD width='30%' align='left'><input class='disable' value='".$rowGet['pr_desc']."' disabled></TD>";
// 		print "</TR>";

		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_NUMBER').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".$rowABS['numero']."' disabled></td>";
			print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'>".TRANS('OCO_PRIORITY').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".$rowGet['pr_desc']."' disabled></TD>";			
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_PROB').": ";

				//print "<input type='submit' class='btPadrao' id='idBtLoadCat' title='".TRANS('LOAD_EQUIP_LOCAL')."'onClick=\"LOAD=1;\"".
					//"style=\"{align:center; valign:middle; width:19px; height:19px; background-image: url('../../includes/icons/key_enter.png'); background-repeat:no-repeat;}\" value='' name='carrega'>";

			print "</TD>";

				//$query_problema = "SELECT * FROM problemas order by problema";

				$query_problema = "SELECT * FROM problemas as p ".
					"LEFT JOIN sistemas as s on p.prob_area = s.sis_id ".
					"LEFT JOIN sla_solucao as sl on sl.slas_cod = p.prob_sla ".
					"LEFT JOIN prob_tipo_1 as pt1 on pt1.probt1_cod = p.prob_tipo_1 ".
					"LEFT JOIN prob_tipo_2 as pt2 on pt2.probt2_cod = p.prob_tipo_2 ".
					"LEFT JOIN prob_tipo_3 as pt3 on pt3.probt3_cod = p.prob_tipo_3 ";

				if ($rowABS['area_cod'] != -1){
					$query_problema.= " WHERE (p.prob_area = ".$rowABS['area_cod']." OR (p.prob_area is null OR p.prob_area = -1)) ";
				} /*else
					$clausula = "";*/

				$query_problema.= "GROUP BY  p.problema".
					" ORDER BY p.problema";
				$exec_problema = mysql_query($query_problema);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
/*				print "<select class='select' name='prob' id='idProb' onChange=\"ajaxFunction('Problema', 'showProbs.php', 'prob=idProb', 'area_cod=idFieldArea')\">";
					print "<option value=-1>Selecione o problema</option>";
					while($row=mysql_fetch_array($exec_problema)){
						print "<option value=".$row['prob_id']."";
							if ($row['prob_id']== $prob) {
								print " selected";
							}
						print ">".$row['problema']."</option>";
					} // while
				print "</select>";*/
				print "<div id='Problema'>";
					print "<input type='hidden' name='prob' id='idProblema' value='".$prob."'>";
				print "</div>";

				print "<div id='idLoad' class='loading'><img src='../../includes/imgs/loading.gif'></div>";

			print "</TD>";


			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_AREA').":</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".$rowABS['area']."' disabled></TD>";
			print "<input type='hidden' name='fieldArea' id='idArea' value='".$rowABS['area_cod']."'></TD>";
			print "<input type='hidden' name='areaHabilitada' id='idAreaHabilitada' value='sim'>";
		print "</TR>";


################################################################

		print "<tr><td colspan='6' ><div id='divProblema'>"; //style='{display:none}'  //<td colspan='6' >
			//print "<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>";
			//print "<input type='hidden' name='problema' id='idProb' value='".$rowABS['problema']."'>";
			//print "</table>";
			print "</div></td></tr>";  //</td>
		
		print "<tr><td colspan='6' ><div id='divInformacaoProblema'></div></td></tr>";	


################################################################


		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_DESC').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'><b>".$rowABS['descricao']."</b></TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_UNIT').":</TD>";
				$qryinst = "select * from instituicao order by inst_nome";
				$exec_inst = mysql_query($qryinst);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<select class='select' name='inst'>";
					print "<option value=-1>".TRANS('OCO_SEL_UNIT')."</option>";
						while($row=mysql_fetch_array($exec_inst)){
							print "<option value=".$row['inst_cod']."";
								if ($row['inst_cod']== $inst) {
									print " selected";
								}
							print ">".$row['inst_nome']."</option>";
						} // while
				print "</select>";

			print "</TD>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><a onClick=\"checa_etiqueta()\" ".
					"title='".TRANS('CONS_CONFIG_EQUIP')."'><font color='#5E515B'><b>".TRANS('OCO_FIELD_TAG')."</b></font></a>".
					" ".TRANS('OCO_FIELD_OF_EQUIP').":</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<input type='text' class='data' name='etiqueta' id='idEtiqueta' value='".$etiqueta."'>";
			print "</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_CONTACT').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><input type='text' class='text' name='contato' id='idContato' value='".$contato."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PHONE').":</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".$rowABS['telefone']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_LOCAL').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<select class='select' name='loc' id='idLocal'>";

					$qrylocal = "select * from localizacao where loc_status not in (0) order by local";
					$exec_local = mysql_query($qrylocal);
					print "<option value=-1>".TRANS('OCO_SEL_LOCAL')."</option>";
					while($row=mysql_fetch_array($exec_local)){
						print "<option value=".$row['loc_id']."";
							if ($row['loc_id']== $loc) {
								print " selected";
							}
						print ">".$row['local']."</option>";
					} // while

				print "</select><a onClick=\"checa_por_local()\">".
						"<img title='".TRANS('CONS_EQUIP_LOCAL')."' width='15' height='15' ".
						"src='".$imgsPath."consulta.gif' border='0'></a>";
			print "</TD>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_OPERATOR').":</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".$rowABS['nome']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_OPEN').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".formatDate($rowABS['data_abertura'])."</TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_STATUS').":</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".$rowABS['chamado_status']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_DATE_CLOSING').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<INPUT type='text' class='text' name='data_fechamento' id='idData_fechamento' value='".formatDate(date("Y-m-d H:i:s"))."'>";
			print "</TD>";
		print "</tr>";

		if ($linhas2 > 0) { //ASSENTAMENTOS DO CHAMADO
			print "<tr><td colspan='6'><IMG ID='imgAssentamento' SRC='../../includes/icons/open.png' width='9' height='9' ".
					"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Assentamento')\">&nbsp;<b>".TRANS('THERE_IS_ARE')." <font color='red'>".$linhas2."</font>".
					" ".TRANS('FIELD_NESTING_FOR_OCCO').".</b></td></tr>";

			//style='{padding-left:5px;}'
			print "<tr><td colspan='6'><div id='Assentamento' style='display:none'>"; //style='{display:none}'
			print "<TABLE border='0' align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			$i = 0;
			while ($rowAssentamento = mysql_fetch_array($resultado2)){
				$printCont = $i+1;
				print "<TR>";
				print "<TD width='20%' bgcolor='".TD_COLOR."' valign='top'>".
						"".TRANS('FIELD_NESTING')." ".$printCont." de ".$linhas2." por ".$rowAssentamento['nome']." em ".
						"".formatDate($rowAssentamento['data'])."".
					"</TD>";
				print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."' valign='top'>".nl2br($rowAssentamento['assentamento'])."</TD>";
				print "</TR>";
				$i++;
			}
			print "</table></div></td></tr>";
			//print "</div>";
		}

		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		print "<TR ID='linha_assentamento'>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_NESTING').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<TEXTAREA class='textarea' name='assentamento' id='idAssentamento'>".
					"".TRANS('TXTAREA_OCCO_DIRECT_MODIFY')." ".$_SESSION['s_usuario']."</textarea>";
			print "</TD>";
		print "</tr>";
		//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------		
		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		//print "<TR>";
		print "<input type='hidden' value='' name='alimenta_banco' id='alimenta_banco'>";
		print "<TR ID='linha_desc_solucao'>";
		//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------

		//print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SCRIPT_SOLUTION').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			$qry_script = "SELECT * FROM script_solution ORDER BY script_desc";
			$exec_qry_script = mysql_query($qry_script) or die (mysql_error());

			print "<select class='select_sol' name='script_sol'>";
			print "<option value=null selected>".TRANS('SEL_SCRIPT')."</option>";
			while ($rowScript = mysql_fetch_array($exec_qry_script)){
				//print "<option value='".$rowScript['script_cod']."'>".$rowScript['script_desc']."</option>";
				print "<option value=".$rowScript['script_cod']."";
					if ($rowScript['script_cod']== $script_sol) {
						print " selected";
					}
				print ">".$rowScript['script_desc']."</option>";

			}
			print "</select>";
		print "</td>";
		print "</tr>";


		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		//print "<TR>";
		print "<TR ID='linha_problema'>";
		//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_PROB').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				//print "<TEXTAREA class='textarea' id='idProblema' name='problema'>Descrição técnica do problema</textarea>";

			if (!$_SESSION['s_formatBarOco']) {
				print "<TEXTAREA class='textarea' name='problema' id='idDesc'>".TRANS('TXT_DESC_TEC_PROB')."</textarea>"; //oFCKeditor.Value = print noHtml($descricao);
			} else
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			?>
			<script type="text/javascript">
				var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'problema' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = '<?php print TRANS('TXT_DESC_TEC_PROB');?>';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>

			<?php 

			print "</TD>";
		print "</TR>";
		
		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		//print "<TR>";
		print "<TR ID='linha_solucao'>";
		//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TIT_SOLUTION').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				//print "<TEXTAREA class='textarea' id='idSolucao' name='solucao'>Solução para este problema</textarea>";

			if (!$_SESSION['s_formatBarOco']) {
				print "<TEXTAREA class='textarea' name='solucao' id='idSolucao'>".TRANS('TXT_SOLUTION_PROB')."</textarea>"; //oFCKeditor.Value = print noHtml($descricao);
			}
			?>
			<script type="text/javascript">
				var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'solucao' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = '<?php print TRANS('TXT_SOLUTION_PROB');?>';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>
			<?php 

			print "</TD>";
		print "</TR>";

		//SE TIVER QUE JUSTIFICAR O ESTOURO DO SLA
		$descricaoMinima = strlen(TRANS('TXT_JUSTIFICATION'))+5;
		if ($row_config['conf_desc_sla_out']){
			$qryTmp = "SELECT * FROM sla_out WHERE out_numero = ".$_REQUEST['numero']." ";
			$execTmp = mysql_query($qryTmp) OR die(mysql_error());					
			$rowOut = mysql_fetch_array($execTmp);
			
			if($rowOut['out_sla']==1){//CHAMADO ESTOUROU
				
				//$descricaoMinima = strlen(TRANS('TXT_JUSTIFICATION'))+5;
				print "<TR>";
					print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_JUSTIFICATION').":</TD>";
					print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
						//print "<TEXTAREA class='textarea' id='idSolucao' name='solucao'>Solução para este problema</textarea>";
		
					if (!$_SESSION['s_formatBarOco']) {
						print "<TEXTAREA class='textarea' name='justificativa' id='idJustificativa'>".TRANS('TXT_JUSTIFICATION')."</textarea>"; //oFCKeditor.Value = print noHtml($descricao);
					}
					?>
					<script type="text/javascript">
						var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
						if (bar ==1) {
							var oFCKeditor = new FCKeditor( 'justificativa' ) ;
							oFCKeditor.BasePath = '../../includes/fckeditor/';
							oFCKeditor.Value = '<?php print TRANS('TXT_JUSTIFICATION');?>';
							oFCKeditor.ToolbarSet = 'ocomon';
							oFCKeditor.Width = '570px';
							oFCKeditor.Height = '100px';
							oFCKeditor.Create() ;
						}
					</script>
					<?php

					print "</TD>";
				print "</TR>";

			}
		}


		//-----------------------------------------
			$qrymail = "SELECT u.*, a.*,o.* from usuarios u, sistemas a, ocorrencias o where ".
						//"u.AREA = a.sis_id and o.aberto_por = u.user_id and o.numero = ".$_GET['numero']."";
						"u.AREA = a.sis_id and o.aberto_por = u.user_id and o.numero = ".$_REQUEST['numero']."";
			$execmail = mysql_query($qrymail);
			$rowmail = mysql_fetch_array($execmail);
			if ($rowmail['sis_atende'] == 0){
				$habilita = "checked";
			} else $habilita = "disabled";

			print "<tr><td bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_SEND_MAIL_TO').":</td>".
					"<td colspan='5'><input type='checkbox' value='ok' name='mailAR' title='".TRANS('MSG_SEND_EMAIL_AREA_ATTEND_CALL')."'>".TRANS('OCO_FIELD_AREA')."&nbsp;&nbsp;".
						"<input type='checkbox' value='ok' name='mailUS' checked ><a title='".TRANS('MSG_OPT_CALL_OPEN_USER')."'>".TRANS('OCO_FIELD_USER')."</a></td>".
				"</tr>";

		print "<TR>";
		print "<BR>";
			print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";

			print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
					"<input type='submit'  class='button' value='".TRANS('BT_OK')."' name='submit'>".
					"<input type='hidden' name='rodou' value='sim'>".
					"<input type='hidden' name='numero' value='".$_REQUEST['numero']."'>".
					"<input type='hidden' name='abertopor' value='".$rowmail['user_id']."'>";
			print "</TD>";
			print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
					"<INPUT type='button'  class='button' value='".TRANS('BT_CANCEL')."' name='desloca' ONCLICK='javascript:history.back()'>";
			print "</TD>";
		print "</TR>";
	} else

	if (isset($_POST['submit'])) {

	#########################################################################################

		if (isset($_POST['radio_prob'])) {
			$radio_prob = $_POST['radio_prob'];
		} else $radio_prob = $_POST['prob'];


		$queryB = "SELECT sis_id,sistema, sis_email FROM sistemas WHERE sis_id = ".$rowABS['area_cod']."";
		$sis_idB = mysql_query($queryB);
		$rowSis = mysql_fetch_array($sis_idB);

		$queryC = "SELECT local from localizacao where loc_id = ".$_POST['loc']."";
		$loc_idC = mysql_query($queryC);
		$setor = mysql_result($loc_idC,0);

		$queryD = "SELECT nome from usuarios where login like '".$_SESSION['s_usuario']."'";
		$loginD = mysql_query($queryD);
		$nome = mysql_result($loginD,0);

	##########################################################################################

		//$data = datam($hoje2);
		$responsavel = $_SESSION['s_uid'];
		
		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		//So insere a solucao no banco se o tipo do problema permitir alimentar o banco de solucoes
		if(isset($_POST['alimenta_banco']) && $_POST['alimenta_banco']=="SIM"){
		//--------------------------------------------------------------- FIM ALTERACAO ---------------------------------------------------------------		
		
		
			$query = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values (".$_POST['numero'].",";
			if ($_SESSION['s_formatBarOco']) {
				$query.= " '".$_POST['problema']."',";
				$query.= " '".$assentamentoProb."',";
			} else {
				$query.= " '".noHtml($_POST['problema'])."',";
			}
			$query.=" '".date('Y-m-d H:i:s')."', ".$responsavel.")"; //VER 25/05/2007
			$resultado = mysql_query($query) or die (TRANS('MSG_ERR_INSERT_NESTING').$query);
	
			$query = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values (".$_POST['numero'].", ";
	
			if ($_SESSION['s_formatBarOco']) {
				$query.= " '".$_POST['solucao']."',";
			} else {
				$query.= " '".noHtml($_POST['solucao'])."',";
			}
			$query.=" '".date('Y-m-d H:i:s')."', ".$responsavel.")";
			$resultado = mysql_query($query)or die (TRANS('MSG_ERR_INSERT_NESTING').$query);
	
			$query1 = "INSERT INTO solucoes (numero, problema, solucao, data, responsavel) values (".$_POST['numero'].", ";
	
			if ($_SESSION['s_formatBarOco']) {
				$query1.= " '".$_POST['problema']."','".$_POST['solucao']."',";
			} else {
				$query1.= " '".noHtml($_POST['problema'])."','".noHtml($_POST['solucao'])."',";
			}
			$query1.=" '".date('Y-m-d H:i:s')."', ".$responsavel.")";
			$resultado1 = mysql_query($query1)or die (TRANS('MSG_ERR_INSERT_SOLUTION').$query1);
		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		}else{
			$query = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values (".$_POST['numero'].",'".$_POST['assentamento']."',";
			$query.=" '".date('Y-m-d H:i:s')."', ".$responsavel.")";
			$resultado = mysql_query($query) or die (TRANS('MSG_ERR_INSERT_NESTING').$query);
			$resultado = $resultado1 = $resultado2 = 1;
		}
		//--------------------------------------------------------------- FIM ALTERACAO ---------------------------------------------------------------		//---------------------------------------------
		
		//JUSTIFICATIVA PARA O ESTOURO DO SLA
		if(isset($_POST['justificativa']) && $row_config['conf_desc_sla_out']){
			$queryJust = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel, tipo_assentamento) values (".$_POST['numero'].", ";
	
			if ($_SESSION['s_formatBarOco']) {
				$queryJust.= " '".$_POST['justificativa']."',";
			} else {
				$queryJust.= " '".noHtml($_POST['justificativa'])."',";
			}
			$queryJust.=" '".date('Y-m-d H:i:s')."', ".$responsavel.", 3)";
			$execJust = mysql_query($queryJust)or die (TRANS('MSG_ERR_INSERT_NESTING').$queryJust);	
		}
		//REMOVE O NÚMERO DO CHAMADO DA TABELA DE CHECAGEM DO SLAS			
		$qryClear = "DELETE FROM sla_out WHERE out_numero = ".$_POST['numero']."";
		$execClear = mysql_query($qryClear);		
		//----------------------------------------------

		$status = 4; //encerrado
		if ($atendimento==null) {
			$query2 = "UPDATE ocorrencias SET status=".$status.", local=".$_POST['loc'].", problema ='".$radio_prob."', ".
				"operador=".$_SESSION['s_uid'].", instituicao='".$_POST['inst']."', equipamento='".$_POST['etiqueta']."', ".
				"contato='".noHtml($_POST['contato'])."', data_fechamento='".date('Y-m-d H:i:s')."', ".
				"data_atendimento='".date('Y-m-d H:i:s')."', oco_script_sol=".$_POST['script_sol']." WHERE numero='".$_POST['numero']."'";

		} else {
			$query2 = "UPDATE ocorrencias SET status=".$status.", local=".$_POST['loc'].",problema ='".$radio_prob."', ".
				"operador=".$_SESSION['s_uid'].", instituicao='".$_POST['inst']."', equipamento='".$_POST['etiqueta']."', ".
				"contato='".noHtml($_POST['contato'])."', data_fechamento='".date('Y-m-d H:i:s')."', oco_script_sol=".$_POST['script_sol']." ".
				"WHERE numero='".$_POST['numero']."'";

		}
		$resultado2 = mysql_query($query2);

		if (($resultado == 0) or ($resultado1 == 0) or ($resultado2 == 0))
		{
			$aviso = TRANS('MSG_ERR_INSERT_DATA_SYSTEM');
			print $aviso;
			exit;
		}
		else {

			$sqlDoc1 = "select * from doc_time where doc_oco = ".$_POST['numero']." and doc_user=".$_SESSION['s_uid']."";
			$execDoc1 = mysql_query($sqlDoc1);
			$regDoc1 = mysql_num_rows($execDoc1);
			$rowDoc1 = mysql_fetch_array($execDoc1);
			if ($regDoc1 >0) {
				$sqlDoc  = "update doc_time set doc_close=doc_close+".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." where doc_id = ".$rowDoc1['doc_id']."";
				$execDoc =mysql_query($sqlDoc) or die (TRANS('MSG_ERR_UPDATE_TIME_DOC_CALL').'<br>').$sqlDoc;
			} else {
				$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$_POST['numero'].", 0, 0, ".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." ,".$_SESSION['s_uid'].")";
				$execDoc = mysql_query($sqlDoc) or die (TRANS('MSG_ERR_UPDATE_TIME_DOC_CALL').'<br>').$sqlDoc;
			}

			##ROTINAS PARA GRAVAR O TEMPO DO CHAMADO EM CADA STATUS
			if ($status != $rowABS['status_cod']) { //O status foi alterado
				##TRATANDO O STATUS ANTERIOR (atual) -antes da mudança
				//Verifica se o status 'atual' já foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, senão devo gravar ele pela primeira vez.
				$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$rowABS['numero']." and ts_status = ".$rowABS['status_cod']." ";
				$exec_sql = mysql_query($sql_ts_anterior);

				if ($exec_sql == 0) $error= " erro 1".$sql_ts_anterior;

				$achou = mysql_num_rows($exec_sql);
				if ($achou >0){ //esse status já esteve setado em outro momento
					$row_ts = mysql_fetch_array($exec_sql);
					// if (array_key_exists($rowABS['sistema'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
						// $areaT = $rowABS['sistema']; //Recebe o valor da área de atendimento do chamado
					// } else $areaT = 1; //Carga horária default definida no arquivo config.inc.php
					$areaT = "";
					$areaT=testaArea($areaT,$rowABS['area_cod'],$H_horarios);

					$dt = new dateOpers;
					$dt->setData1($row_ts['ts_data']);
					$dt->setData2(date('Y-m-d H:i:s'));
					$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
					$segundos = $dt->diff["sValido"]; //segundos válidos

					$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+".$segundos.") , ts_data ='".date('Y-m-d H:i:s')."' where ts_ocorrencia = ".$rowABS['numero']." and
							ts_status = ".$rowABS['status_cod']." ";
					$exec_upd = mysql_query($sql_upd);
					if ($exec_upd ==0) $error.= " erro 2";

				} else {
					$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$rowABS['numero'].", ".$rowABS['status_cod'].", 0, '".date('Y-m-d H:i:s')."' )";
					$exec_ins = mysql_query ($sql_ins);
					if ($exec_ins == 0) $error.= " erro 3 ".$sql_ins;
				}
			}

			$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$_POST['numero']."";
			$execfull = mysql_query($qryfull) or die(TRANS('MSG_ERR_RESCUE_VARIA_SURROU').$qryfull);
			$rowfull = mysql_fetch_array($execfull);

			$globallink = $row_config['conf_ocomon_site']."/ocomon/geral/mostra_consulta.php?numero=".$_POST['numero']."&id=".$arrayGlobal['gt_id'];

			$VARS = array();
			$VARS['%numero%'] = $rowfull['numero'];
			$VARS['%usuario%'] = $rowfull['contato'];
			$VARS['%contato%'] = $rowfull['contato'];
			$VARS['%descricao%'] = $rowfull['descricao'];
			$VARS['%setor%'] = $rowfull['setor'];
			$VARS['%ramal%'] = $rowfull['telefone'];
			$VARS['%assentamento%'] = $_POST['solucao'];
			$VARS['%site%'] = "<a href='".$row_config['conf_ocomon_site']."'>".$row_config['conf_ocomon_site']."</a>";
			$VARS['%linkglobal%'] = "<a href='$globallink'>".$globallink."</a>";
			$VARS['%area%'] = $rowfull['area'];
			$VARS['%operador%'] = $rowfull['nome'];
			$VARS['%problema%'] = $_POST['problema'];
			$VARS['%solucao%'] = $_POST['solucao'];
			$VARS['%versao%'] = VERSAO;

			$qryconf = "SELECT * FROM mailconfig";
			$execconf = mysql_query($qryconf) or die (TRANS('MSG_ERR_RESCUE_SEND_EMAIL'));
			$rowconf = mysql_fetch_array($execconf);

			if (isset($_POST['mailAR']) ){
				$event = 'encerra-para-area';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die(TRANS('MSG_ERR_MSCONFIG'));
				$rowmsg = mysql_fetch_array($execmsg);
				send_mail($event, $rowSis['sis_email'], $rowconf, $rowmsg, $VARS);

				//$flag = envia_email_fechamento($numero, $rowSis['sis_email'], $nome, $rowSis['sistema'], $problema, $solucao);
			}
			if (isset($_POST['mailUS'])) {
				$event = 'encerra-para-usuario';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die(TRANS('MSG_ERR_MSCONFIG'));
				$rowmsg = mysql_fetch_array($execmsg);

				$sqlMailUs = "select * from usuarios where user_id = ".$_POST['abertopor']."";
				$execMailUs = mysql_query($sqlMailUs) or die(TRANS('MSG_ERR_NOT_ACCESS_USER_SENDMAIL'));
				$rowMailUs = mysql_fetch_array($execMailUs);

				$qryresposta = "select u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and u.user_id = ".$_SESSION['s_uid']."";
				$execresposta = mysql_query($qryresposta) or die (TRANS('MSG_ERR_NOT_IDENTIFY_EMAIL'));
				$rowresposta = mysql_fetch_array($execresposta);

				/*$flag = mail_user_encerramento($rowMailUs['email'], $rowresposta['sis_email'], $rowMailUs['nome'],$_GET['numero'],
													$assentamento,OCOMON_SITE);*/
				send_mail($event, $rowMailUs['email'], $rowconf, $rowmsg, $VARS);
			}

			$aviso = TRANS('MSG_OCCO_FINISH_SUCESS');
		}

		print "<script>mensagem('".$aviso."'); redirect('abertura.php');</script>";
        }

?>
<script type="text/javascript">
<!--

	function valida(){
		var ok = validaForm('idProblema','COMBO','Problema',1);

		if (ok) var ok = validaForm('idEtiqueta','INTEIROFULL','Etiqueta',0);
		if (ok) var ok = validaForm('idContato','','Contato',1);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		if (ok) var ok = validaForm('idData_fechamento','DATAHORA','Data',1);
		if (ok) var ok = validaForm('idDesc','','Descrição técnica',1);
		if (ok) var ok = validaForm('idSolucao','','Solução',1);
		
		if (ok) {
			var justification = document.getElementById('idJustificativa');
			if (justification != null){
				if (ok) var ok = validaForm('idJustificativa','','Justificativa',1);
				if (ok) {
					if(justification.value.length <= <?php print $descricaoMinima;?>) {
						alert('<?php print TRANS('ALERT_TOO_SHORT_JUSTIFICATION');?>');
						ok = false;
						document.form1.justificativa.focus();
					}
				}
			}
		}		
		

		return ok;
	}

	function popup_alerta(pagina)	{ //Exibe uma janela popUP
      		x = window.open(pagina,'Alerta','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
     	}

	 function checa_etiqueta(){
	 	var inst = document.form1.inst.value;
		var inv = document.form1.etiqueta.value;
		if (inst=='null' || !inv){
			window.alert('Os campos Unidade e etiqueta devem ser preenchidos!');
		} else
			popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst='+inst+'&comp_inv='+inv+'&popup='+true);

		return false;
	 }

	function checa_chamados(){
	 	var inst = document.form1.inst.value;
		var inv = document.form1.etiqueta.value;
		if (inst=='null' || !inv){
			window.alert('Os campos Unidade e etiqueta devem ser preenchidos!');
		} else
			popup_alerta('../../invmon/geral/ocorrencias.php?comp_inst='+inst+'&comp_inv='+inv+'&popup='+true);

		return false;
	}

	function checa_por_local(){
		var local = document.form1.loc.value;
		if (local==-1){
			window.alert('O local deve ser preenchido!');
		} else
			popup_alerta('../../invmon/geral/mostra_consulta_comp.php?comp_local='+local+'&popup='+true);

		return false;
	}

</script>
<?php 

		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		//So exibe os campos "solucao" e "problema" se o tipo do problema permitir alimentar o banco de solucoes
		//Isso é feito via javascript suprimindo o TR da página
		$query_problema_banco_solucao = "SELECT * FROM problemas order by problema";
		$exec_problema_banco_solucao = mysql_query($query_problema_banco_solucao);
		mysql_data_seek($exec_problema_banco_solucao, 0);
		?>
		<script>
			var alimentaSolucao = new Array();
			alimentaSolucao[alimentaSolucao.length] = 0;
			<?php while($row=mysql_fetch_array($exec_problema_banco_solucao)){ ?>
				alimentaSolucao[<?php print $row['prob_id'] ?>] = <?php print $row['prob_alimenta_banco_solucao'] ?>;
			<?php } ?>
			function habilitarBancoSolucao(){
				var indice = document.getElementById('idProblema').value;
				if(alimentaSolucao[indice] == 1){
					document.getElementById('linha_assentamento').style.display = 'none';
					document.getElementById('linha_desc_solucao').style.display = '';				
					document.getElementById('linha_problema').style.display = '';
					document.getElementById('linha_solucao').style.display = '';
					document.getElementById('alimenta_banco').value = 'SIM';
				}else{
					document.getElementById('linha_assentamento').style.display = '';
					document.getElementById('linha_desc_solucao').style.display = 'none';
					document.getElementById('linha_problema').style.display = 'none';
					document.getElementById('linha_solucao').style.display = 'none';
					document.getElementById('alimenta_banco').value = '';
				}
			}
			habilitarBancoSolucao();
		</script>
		<?php
		//--------------------------------------------------------------- FIM ALTERACAO ---------------------------------------------------------------



print "</TABLE>";
print "</FORM>";
print "</body>";
print "</html>";
?>
