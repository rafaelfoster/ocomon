<?php
 /*      Copyright 2005 Flávio Ribeiro

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

 session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	include ("../../includes/classes/lock.class.php");

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

	print "<html>";
	print "<body onLoad=\"ajaxFunction('divSelProblema', 'showSelProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'area_habilitada=idAreaHabilitada'); ajaxFunction('divProblema', 'showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea'); checarSchedule(''); ajaxFunction('divInformacaoProblema', 'showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea'); \">";
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$hoje = date("Y-m-d H:i:s");
        $hoje2 = date("d/m/Y");

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die (TRANS('ERR_TABLE_CONFIG'));
	$row_config = mysql_fetch_array($exec_config);

	$LOCK = new lock();

	if (isset($_GET['numero'])) {
		if (isset($_GET['FORCE_EDIT']) && $_GET['FORCE_EDIT'] == 1)
			$FORCE_EDIT = 1; else $FORCE_EDIT = 0;
		$LOCK->setLock($_GET['numero'], $_SESSION['s_uid'], $FORCE_EDIT);
	}

	if ($_SESSION['s_nivel'] ==1) {
		$admin = true;
		if ($_SESSION['s_allow_date_edit'] == 0){
			$allowDateEdit = "readonly";
		} else {
			$allowDateEdit = "";
		}
	} else {
		$admin = false;
	}

	if (!isset($_POST['submit'])) {

		$query = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and numero=".$_GET['numero']."";
		$resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);
		$linhas = mysql_numrows($resultado);

		$data_atend = $row['data_atendimento']; //Data de atendimento!!!
		$problema_ocorrencia = $row['problema'];

		$query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and ocorrencia=".$_GET['numero']."";
		$resultado2 = mysql_query($query2);
		$linhas2 = mysql_num_rows($resultado2);

		if ($_SESSION['s_nivel'] == 1) $linkEdita = "<br><b><a href='altera_dados_ocorrencia.php?numero=".$_GET['numero']."'>".TRANS('FIELD_EDIT_FOR_ADMIN').":</a></b><br>"; else
			$linkEdita = "<br><b>Editar ocorrência:</b><br>";

		print $linkEdita;

		//dump($row,'$row');

		print "<FORM name='formulario' method='POST' action='".$_SERVER['PHP_SELF']."' ENCTYPE='multipart/form-data' onSubmit=\"return valida()\">";//
		print "<input type='hidden' name='MAX_FILE_SIZE' value='".$row_config['conf_upld_size']."' />";

		print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";

			print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_PRIORITY').":</TD>";
			print "<TD  width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<select name='prioridade' class='select'>";

				$sql = "select * from prior_atend where pr_cod = '".$row['oco_prior']."'";
				$commit1 = mysql_query($sql);
				$rowR = mysql_fetch_array($commit1);
					print "<option value=-1>".TRANS('OCO_PRIORITY')."</option>";
						$sql2="select * from prior_atend order by pr_nivel";
						$commit2 = mysql_query($sql2);
						while($rowB = mysql_fetch_array($commit2)){
							print "<option value=".$rowB["pr_cod"]."";
							if ($rowB['pr_cod'] == $rowR['pr_cod'] ) {
								print " selected";
							}
							print ">".$rowB["pr_desc"]."</option>";
						} // while

				print "</select>";
        		print "</td>";
        	print "</tr>";

        	print "<TR>";
                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_NUMBER').":</TD>";
                	print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."' ><input class='disable' value='".$row['numero']."' disabled></TD>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_STATUS').":</TD>";
			print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";

				if ($row['status'] == 4){$stat_flag="";} else $stat_flag =" where stat_id<>4 ";

				print "<SELECT class='select' name='status' id='idStatus' size='1' onchange=\"registra_status();\">";
	        		        print "<option value= '-1'>".TRANS('SEL_STATUS')."</option>";
	                		$query_stat = "SELECT * from status ".$stat_flag." order by status";
			                $exec_stat = mysql_query($query_stat);
					while ($row_stat = mysql_fetch_array($exec_stat))
					{
						print "<option value=".$row_stat['stat_id']."";
							if ($row_stat['stat_id'] == $row['status']) {
								print " selected";
							}
						print " >".$row_stat['status']." </option>";
					}
				print "</select>";
			print "</TD>";
		print "</TR>";

        	print "<TR>";

                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_AREA').":</TD>";
	                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<SELECT class='select' name='sistema' id='idArea' onChange=\"ajaxFunction('divSelProblema', 'showSelProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'area_habilitada=idAreaHabilitada'); ajaxFunction('divProblema', 'showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea'); \" >"; 

				//ajaxFunction('divInformacaoProblema', 'showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea');
	        		print "<option value= '2'>".TRANS('OCO_SEL_AREA')."</option>";
	                	$query = "SELECT * from sistemas order by sistema";

//             		$query = "SELECT s.* from sistemas s, o.problema from ocorrencias o, areaXarea_abrechamado a WHERE s.sis_status NOT IN (0) ".
//              			"AND s.sis_atende = 1 AND s.sis_id = a.area AND a.area_abrechamado IN (".$_SESSION['s_uareas'].") ".
//	                			"GROUP BY sistema ORDER BY sistema";

			                $exec_sis = mysql_query($query);
			                while ($row_sis = mysql_fetch_array($exec_sis))
               				{
						print "<option value=".$row_sis['sis_id']."";
							if ($row_sis['sis_id'] == $row['sistema']) {
								print " selected";
							}
						print " >".$row_sis['sistema']." </option>";
            		    		}
				print "</select>";
				print "</TD>";
				print "<input type='hidden' name='areaHabilitada' id='idAreaHabilitada' value='sim'>";

//			echo "<br><br><font color='red'>$this_problema</font>";

            		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_PROB').":</TD>";
	                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<div id='divSelProblema'>";
				print "<input type='hidden' name='problema' id='idProblema' value='".$row['problema']."'>";
			print "</div>";

			print "</TD>";

			print "</tr>";
			#########################################################

			print "<tr><td colspan='6' ><div id='divProblema'>"; //style='{display:none}'
			//print "<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>";

			print "</div></td></tr>";

			print "<tr><td colspan='6' ><div id='divInformacaoProblema'></div></td></tr>";
			print "<div id='idLoad' class='loading'><img src='../../includes/imgs/loading.gif'></div>";

			#################################################



			print "</TR>";
			print "<TR>";
				print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('OCO_DESC').":</TD>";
				print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";

				if (!$_SESSION['s_formatBarOco']) {
					print "<TEXTAREA class='textarea' name='descricao' id='idDescricao'>".$row['descricao']."</textarea>";//nl2br()
				} else
					print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

				$texto1 = str_replace("\r","\n",$row['descricao']);
				$texto1 = str_replace("\n","",$texto1);
				?>
				<script type="text/javascript">
					var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
					if (bar ==1) {
						var oFCKeditor = new FCKeditor( 'descricao' ) ;
						oFCKeditor.BasePath = '../../includes/fckeditor/';
						oFCKeditor.Value =  '<?php print $texto1;?>';
						oFCKeditor.ToolbarSet = 'ocomon';
						oFCKeditor.Width = '570px';
						oFCKeditor.Height = '100px';
						oFCKeditor.Create() ;
					}
				</script>
				<?php

				print "</TD>";
			print "</TR>";
			print "<TR>";


			print "<TD width='20%' align='left' valign='top' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_UNIT').":</TD>";
			print "<TD  width='30%' align='left' bgcolor='".BODY_COLOR."'>";

			$instituicao = $row['instituicao'];

			if ($instituicao != null)
			{
				$query = "SELECT * FROM instituicao WHERE inst_cod=".$instituicao."";
			}
			else
			{
				$query = "SELECT * FROM instituicao WHERE inst_cod is null";
			}
			$resultado3 = mysql_query($query);
			$nomeinst = "";
			if (mysql_numrows($resultado3) > 0)
			{
				$nomeinst=mysql_result($resultado3,0,1);
			}
			print "<select  class='select' name='institui' id='idUnidade'>";
			$query_todas="select * from instituicao order by inst_cod";
			$result_todas=mysql_query($query_todas);

			if ($nomeinst=="")
			{
				print "<option value='' selected> </option>";
			}

			while($row_todas=mysql_fetch_array($result_todas))
			{
				if ($row_todas['inst_cod']==$instituicao)
				{
					$s='selected ';
				}
				else
				{
					$s='';
				}
					print "<option value='".$row_todas['inst_cod']."' $s>".$row_todas['inst_nome']."</option>";
			} // while
			print "</select>";
	                print "</TD>";


	                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_TAG_EQUIP').":</TD>";
        	        print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".
        	        		"<INPUT type='text'  class='text' name='etiq' id='idEtiqueta' value ='".$row['equipamento']."'' size='15'>".
        	        	"</TD>";
        	print "</TR>";
    	    	
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_CONTACT').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>"."
					<input type='text' class='text' name='contato' id='idContato' value='".$row['contato']."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_PHONE').":</TD>";
			print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>".
					"<input type='text' class='text' name='ramal' id='idRamal' value='".$row['telefone']."'></TD>";
		print "</TR>";

    	    	print "<TR>";
                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_LOCAL').":</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

				print "<SELECT  class='select' name='local' id='idLocal' size=1>";
	        		        print "<option value= '-1'>".TRANS('SEL_SECTOR')."</option>";
	                		$query = "SELECT * from localizacao order by local";
			                $exec_loc = mysql_query($query);
					while ($row_loc = mysql_fetch_array($exec_loc))
					{
						print "<option value=".$row_loc['loc_id']."";
							if ($row_loc['loc_id'] == $row['local']) {
								print " selected";
							}
						print " >".$row_loc['local']." </option>";
					}
					print "</select>";
			print "</TD>";
            	    	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_OPERATOR').":</TD>";
                	print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>";

				print "<SELECT class='select' name='operador'>";
                    	    	$query = "SELECT u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and a.sis_atende='1' and u.nivel not in (3,4,5) order by login";
                        	$exec_oper = mysql_query($query);
        	                while ($row_oper = mysql_fetch_array($exec_oper))
            	            	{
					print "<option value=".$row_oper['user_id']." ";
					if ($row_oper['user_id']== $_SESSION['s_uid'])
						print " selected";
					print ">".$row_oper['nome']."</option>";
				}
                	        print "</SELECT>";
                    	print "</TD>";
	        print "</TR>";

		$antes = $row['status'];
		if ($row['status'] == 4) //Encerrado
		{
			$antes = 4;
			print "<TR>";
                	print "<TD align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_OPEN').":</TD>";
                    	print "<TD align='left' bgcolor='".BODY_COLOR."'><input type='text' name='data_abertura' class='disable' value='".formatDate($row['data_abertura'])."' readonly></TD>";//disabled
			print "<TD align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_DATE_CLOSING').":</TD>";
                    	print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'><input class='disable' value='".formatDate($row['data_fechamento'])."' disabled></TD>";
          		print "</TR>";
		}
        		else //chamado não encerrado
		{

			if ($row['oco_scheduled']==1){
				$os_DataAbertura = formatDate($row['oco_real_open_date']);
				$os_DataAgendamento = formatDate($row['data_abertura']);
			} else {
				$os_DataAbertura = formatDate($row['data_abertura']);
				$os_DataAgendamento = formatDate($row['data_abertura']);
			}


			print "<TR>";
			print "<TD align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_OPEN').":</TD>";
			print "<TD align='left' bgcolor='".BODY_COLOR."'><input type='text' name='data_abertura' class='disable' value='".$os_DataAbertura."' readonly></TD>";


			if ($row['oco_scheduled']==1){
				print "<TD align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_SCHEDULED_TO').":&nbsp;<input type='checkbox' checked value='ok' name='chk_squedule'  onChange=\"checarSchedule('idDataAgendamento');\">".TRANS('RE-SCHEDULE')."</TD>";
				print "<TD align='left' bgcolor='".BODY_COLOR."'><input type='text' name='data_agendamento' id='idDataAgendamento' class='text' value='".$os_DataAgendamento."' disabled></TD>"; //disabled
				print "</tr>";
			} else {
					print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_SCHEDULE').": <input type='checkbox' value='ok' name='chk_squedule' onChange=\"checarSchedule('');\"></TD>";
					print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><input type='text' name='data_agendamento' id='idDataAgendamento' class='text' value='".date("d/m/Y H:i:s")."' disabled></TD>"; //disabled
				print "</TR>";
			}
		}

		print "<TR>";
                	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('FIELD_NESTING').":<br /><br />".
                		"".TRANS('CHECK_ASSET_PRIVATED')."<input type='checkbox' name='check_asset_privated' value='1'></TD>";
                	print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			if (!$_SESSION['s_formatBarOco']) {
				print "<TEXTAREA class='textarea' name='assentamento' id='idAssentamento'>".
						"".TRANS('TXTAREA_OCCO_DIRECT_MODIFY')." ".$_SESSION['s_usuario']."</textarea>";
			} else
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			?>
			<script type="text/javascript">
				var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'assentamento' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = '<?php print "".TRANS('TXTAREA_OCCO_DIRECT_MODIFY')." ".$_SESSION['s_usuario']."";?>';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>
			<?php
			print "</TD>";
        	print "</TR>";



			$qryTela = "select * from imagens where img_oco = ".$row['numero']."";
			$execTela = mysql_query($qryTela) or die (TRANS('MSG_ERR_NOT_INFO_IMAGE'));
			//$rowTela = mysql_fetch_array($execTela);
			$isTela = mysql_num_rows($execTela);
			$cont = 0;

			while ($rowTela = mysql_fetch_array($execTela)) {
			//if ($isTela !=0) {
				$cont++;
				print "<tr>";
				$size = round($rowTela['img_size']/1024,1);
				print "<TD  bgcolor='".TD_COLOR."' >".TRANS('FIELD_ATTACH')." ".$cont."&nbsp;[".$rowTela['img_tipo']."]<br>(".$size."k):</td>";

				if(eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $rowTela["img_tipo"])) {
					$viewImage = "&nbsp;<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?".
						"file=".$row['numero']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\" ".
						"title='View the file'><img src='../../includes/icons/kghostview.png' width='16px' height='16px' border='0'></a>";
				} else {
					$viewImage = "";
				}
				print "<td colspan='5' ><a onClick=\"redirect('../../includes/functions/download.php?".
						"file=".$row['numero']."&cod=".$rowTela['img_cod']."')\" title='Download the file'>".
						"<img src='../../includes/icons/attach2.png' width='16px' height='16px' border='0'>".
						"".$rowTela['img_nome']."</a>".$viewImage."".
						"<input type='checkbox' name='delImg[".$cont."]' value='".$rowTela['img_cod']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'>".
						"</TD>";
				print "</tr>";
			}




			/* ----------------- INICIO ALTERACAO ----------------- */
			print "<tr>";
			print "<td colspan='4'>";
			if ((!empty($rowconf) && $rowconf['conf_scr_upload']) || empty($rowconf)) {
				for($i=1;$i<=$row_config['conf_qtd_max_anexos']; $i++){
					$estilo = 'width: 100%; margin: 0; height: 20px; margin-bottom: 2px;';
					if($i > 1)
						$estilo .= " display: none;";
					print "<div id='tr_anexo_$i' style=' $estilo '>";
					//print "<tr id='tr_anexo_$i' $estilo>";
						print "<div style='width: 20%; height: 100%; background-color: ".TD_COLOR."; float: left; margin: 0;'>".TRANS('OCO_FIELD_ATTACH_FILE','Anexar arquivo').":</div>";
						print "<div style='width: 70%; background-color: ".BODY_COLOR."; float: left; margin-left: 2px;'>";
						print "		<INPUT type='file' class='text' name='anexo_$i' id='id_anexo_$i' />";
						if($i != $row_config['conf_qtd_max_anexos']){
							print "		&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
							print "<a id='link_adic_$i'
										onclick=\"
										javascript:document.getElementById('tr_anexo_".($i+1)."').style.display='block';
										document.getElementById('link_adic_".($i)."').style.display='none';
									\">&nbsp;&nbsp;".TRANS('ATTACH_ANOTHER')."</a>";
						}
						print "</div>";
					print "</div>";
				}
			}
			print "</td>";
			print "</tr>";
			/* ----------------- FIM ALTERACAO ----------------- */


			$qrymail = "SELECT u.*, a.*,o.* from usuarios u, sistemas a, ocorrencias o where ".
						"u.AREA = a.sis_id and o.aberto_por = u.user_id and o.numero = ".$_GET['numero']."";
			$execmail = mysql_query($qrymail);
			$rowmail = mysql_fetch_array($execmail);
			if ($rowmail['sis_atende']==0){
				$habilita = "";
			} else $habilita = "disabled";

			print "<tr><td bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_SEND_MAIL_TO').":</td>".
					"<td colspan='2'><input type='checkbox' value='ok' name='mailAR' title='".TRANS('HNT_SENDMAIL_AREA_SEL_CALL')."'>".TRANS('OCO_FIELD_AREA')."&nbsp;&nbsp;".
									"<input type='checkbox' value='ok' name='mailOP' title='".TRANS('HNT_SENDMAIL_OPERATOR_SEL_CALL')."'>".TRANS('OCO_FIELD_OPERATOR')."&nbsp;&nbsp;".
									"<input type='checkbox' value='ok' name='mailUS' title='teste' ".$habilita."><a title='".TRANS('MSG_OPT_CALL_OPEN_USER')."'>".TRANS('OCO_FIELD_USER')."</a></td>".
					"</tr>";

			//print "<tr><td colspan='3'>&nbsp;</td></tr>";
			print "<tr><td colspan='3' align='center'>";
			if ($data_atend =="") {
				print "<input type='checkbox' value='ok' name='resposta' checked title='".TRANS('HNT_NOT_MARK_OPT_FIRST_REPLY_CALL')."'>".TRANS('FIELD_FIRST_REPLY')."";
			}
			//print "</td><td colspan='3'></td></tr>";

		$printCont = 0;
		if ($linhas2 > 0) { //ASSENTAMENTOS DO CHAMADO
			print "<tr><td colspan='6'><IMG ID='imgAssentamento' SRC='../../includes/icons/open.png' width='9' height='9' ".
					"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Assentamento')\">&nbsp;<b>".TRANS('THERE_IS_ARE')." <font color='red'>".$linhas2."</font>".
					" ".TRANS('FIELD_NESTING_FOR_OCCO').".</b></td></tr>";

			//style='{padding-left:5px;}'
			print "<tr><td colspan='6' ><div id='Assentamento' style='display:none'>"; //style='{display:none}'
			print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			$i = 0;
			$asset_checked = "";

			while ($rowAssentamento = mysql_fetch_array($resultado2)){
				$printCont = $i+1;

				if ($rowAssentamento['asset_privated']== 1) $asset_checked = " checked"; else $asset_checked = "";

				print "<TR>";
				print "<TD width='20%' ' bgcolor='".TD_COLOR."' valign='top'>".
						"".TRANS('FIELD_NESTING')." ".$printCont." de ".$linhas2." por ".$rowAssentamento['nome']." em ".
						"".formatDate($rowAssentamento['data'])."".
					"<br/>".TRANS('CHECK_ASSET_PRIVATED')." <input type='checkbox' name='asset".$printCont."' ".$asset_checked." value='".$rowAssentamento['numero']."'></TD>";
				print "<TD colspan='5' align='left' bgcolor='".BODY_COLOR."' valign='top'>".nl2br($rowAssentamento['assentamento'])."</TD>";
				print "</TR>";
				$i++;
			}
			print "</table></div></td></tr>";
		}


		//VERIFICA SE EXISTE UM CHAMADO ORIGEM
		$sqlPaiCall = "select * from ocodeps where dep_filho = ".$row['numero']." ";// or dep_filho=".$row['numero']."";
		$execPaiCall = mysql_query($sqlPaiCall) or die (TRANS('MSG_ERR_RESCUE_INFO_SUBCALL').'<br>'.$sqlPaiCall);
		$regPai = mysql_num_rows($execPaiCall);
		$rowPai = mysql_fetch_array($execPaiCall);
		if ($regPai > 0) {
			$headerLine = "<tr><td colspan='5'>".TRANS('TXT_BONDS_OTHER_CALL').":</td></tr>";
			$imgPai = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'>";
		} else {
			$imgPai = "";
			$headerLine = "";
		}


		//VERIFICA SE EXISTEM SUB-CHAMADOS
		$sqlSubCall = "select * from ocodeps where dep_pai = ".$row['numero']." ";// or dep_filho=".$row['numero']."";
		$execSubCall = mysql_query($sqlSubCall) or die (TRANS('MSG_ERR_RESCUE_INFO_SUBCALL').'<br>'.$sqlSubCall);
		$regSub = mysql_num_rows($execSubCall);
		if ($regSub > 0) {
			if ($headerLine=="" ) $headerLine = "<tr><td colspan='5'>".TRANS('TXT_BONDS_OTHER_CALL').":</td></tr>";
			$imgSub = "<img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'>";
		} else {
			$imgSub = "";
			//$headerLine = "";
		}
		print $headerLine;

		if ($regPai>0){
			print "<tr>";
			print "<td colspan='5' bgcolor='".BODY_COLOR."'><img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'>".
				"<a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowPai['dep_pai']."')\">".$rowPai['dep_pai']."</a>";
			print "<input type='checkbox' name='delPai'  value='".$rowPai['dep_pai']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('TXT_DEL_BONDS')."'></TD>";
			print "</tr>";
		}

		$contSub = 0;
		while ($rowSub = mysql_fetch_array($execSubCall)) {
			$contSub++;
			print "<tr>";
			print "<td colspan='5' bgcolor='".BODY_COLOR."'><img src='".ICONS_PATH."view_tree.png' width='16' height='16' title='".TRANS('FIELD_CALL_BOND')."'>".
				"<a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$rowSub['dep_filho']."')\">".$rowSub['dep_filho']."</a>";
			print "<input type='checkbox' name='delSub[".$contSub."]' value='".$rowSub['dep_filho']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('TXT_DEL_BONDS')."'></TD>";
			print "</tr>";

		}






		print "<tr>";
		print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>";
			print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";
			print "<input type='submit' class='button' value='".TRANS('BT_OK')."' name='submit'>";
			print "<input type='hidden' name='numero' value='".$_GET['numero']."'>";
			print "<input type='hidden' name='cont' value='".$cont."'>";
			print "<input type='hidden' name='contSub' value='".$contSub."'>";
			print "<input type='hidden' name='antes' value='".$antes."'>";
			print "<input type='hidden' name='data_atend' value='".$data_atend."'>";
			print "<input type='hidden' name='abertopor' value='".$rowmail['user_id']."'>";

			print "<input type='hidden' name='total_asset' value='".$printCont."'>";

			print "<input type='hidden' name='data_abertura_hidden' value='".$row['data_abertura']."'>";

                print "</TD>";
                print "<TD colspan='3' align='center' width='25%' bgcolor='".BODY_COLOR."'>";
			print "<INPUT type='reset' class='button' value='".TRANS('BT_CANCEL')."' onClick='javascript:history.back()' name='cancelar'>";
		print "</TD>";

		print "</TR>";

		print "</TABLE>";
		print "</FORM>";
	} else
	if (isset($_POST['submit']) && $_POST['submit']== TRANS('BT_OK') ) {

		//dump($_POST,'DUMP POST'); exit;

		$agendado = 0;

		$qryChkDate = "SELECT * FROM ocorrencias WHERE numero = ".$_POST['numero']."";
		$execChkDate = mysql_query($qryChkDate);
		$rowChkDate = mysql_fetch_array($execChkDate);

		//dump($rowChkDate,'$rowChkDate'); exit;

		//CONTROLE PARA PEGAR AS DATAS CORRETAS: DE AGENDAMENTO E ABERTURA
		if (isset($_POST['chk_squedule']) && $_POST['chk_squedule']!=""){

			#AVALIANDO QUAL SERÁ O STATUS PARA O CHAMADO AGENDADO
			if ($rowChkDate['data_abertura'] < date("Y-m-d H:i:s")){
				$depois = $row_config['conf_schedule_status_2'];//STATUS ALTERADO NA EDIÇÃO
			} else {
				$depois = $row_config['conf_schedule_status'];//STATUS ALTERADO NA ABERTURA
			}


			if ($rowChkDate['oco_real_open_date'] != "") {
				$realOpenDate = $rowChkDate['oco_real_open_date'];
			} else {
				$realOpenDate = FDate($_POST['data_abertura']);
			}

			$data_agendamento = FDate($_POST['data_agendamento']);
			$agendado = 1;

		} else {
			$depois = $_POST['status'];//NOVO STATUS

			if ($rowChkDate['oco_real_open_date'] != "") {
				$realOpenDate = $rowChkDate['oco_real_open_date'];
			} else {
				$realOpenDate = $_POST['data_abertura'];
			}

			$data_agendamento = FDate($_POST['data_abertura']);
			$agendado = $rowChkDate['oco_scheduled'];
		}

		$erro= false;
		if (!$erro)  {
			$sqlPost = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and numero=".$_POST['numero']."";
			$resultadoPost = mysql_query($sqlPost);
			$row = mysql_fetch_array($resultadoPost);


		//CONTROLE PARA GARANTIR QUE NÃO EXISTA DATA DE ABERTURA ZERADA
		if ( (FDate($data_agendamento) == '0000-00-00 00:00:00') ){
			if ($row['data_abertura'] != '0000-00-00 00:00:00' && !empty($row['data_abertura']) ) {
				$data_agendamento = $row['data_abertura'];
			} else
			if ($row['oco_real_open_date'] != '0000-00-00 00:00:00' && !empty($row['oco_real_open_date']) ) {
				$data_agendamento = $row['oco_real_open_date'];
			} else
			if ($row['data_atendimento'] != '0000-00-00 00:00:00' && !empty($row['data_atendimento']) ) {
				$data_agendamento = $row['data_atendimento'];
			}
		}



			/* ----------------- INICIO ALTERACAO ----------------- */
			$gravaImg = false;
			$qryConf = "SELECT * FROM config";
			$execConf = mysql_query($qryConf) or die (TRANS('ERR_QUERY').", A TABELA CONF FOI CRIADA?");
			$rowConf = mysql_fetch_array($execConf);
			$arrayConf = array();
			$arrayConf = montaArray($execConf,$rowConf);
			for($i=1;$i<=$row_config['conf_qtd_max_anexos']; $i++){
				$nomeAnexo = 'anexo_'.$i;
				if (isset($_FILES[$nomeAnexo]) and $_FILES[$nomeAnexo]['name']!="") {
					$upld = upload($nomeAnexo,$arrayConf,$rowConf['conf_upld_file_types']);
					if ($upld =="OK") {
						$gravaImg[$i] = true;
					} else {
						$gravaImg[$i] = false;
						$upld.="<br><a align='center' onClick=\"exibeEscondeImg('idAlerta');\"><img src='".ICONS_PATH."/stop.png' width='16px' height='16px'>&nbsp;".TRANS('LINK_CLOSE','Fechar')."</a>";
						print "</table>";
						print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$upld."</td></tr></table></div>";
						exit;
					}
				}
			}
			/* ----------------- FIM ALTERACAO ----------------- */

			//Exclui os anexos marcados			
			if (isset($_POST['cont'])) {
				for ($j=1; $j<=$_POST['cont']; $j++) {
					if (isset($_POST['delImg'][$j])){
						$qryDel = "DELETE FROM imagens WHERE img_cod = ".$_POST['delImg'][$j]."";
						$execDel = mysql_query($qryDel) or die (TRANS('MSG_NOT_DEL_IMAGE'));
					}
				}			
			}
			
			
			if (isset($_POST['delPai'])){
				$qryDel = "DELETE FROM ocodeps WHERE dep_filho= ".$_POST['numero']." and dep_pai = ".$_POST['delPai']."";
				$execDel = mysql_query($qryDel) or die (TRANS('MSG_NOT_DEL_BOND').$qryDel);
			}

			for ($j=1; $j<=$_POST['contSub']; $j++) {
				if (isset($_POST['delSub'][$j])){
					$qryDel = "DELETE FROM ocodeps WHERE dep_pai= ".$_POST['numero']." and dep_filho = ".$_POST['delSub'][$j]."";
					$execDel = mysql_query($qryDel) or die (TRANS('MSG_NOT_DEL_BOND').$qryDel);
				}
			}


			//$data = datam($hoje2);
			$responsavel = $_SESSION['s_uid'];

			$aviso = "";
			$queryA = "";


			if (isset($_POST['check_asset_privated']))
				$post_check_asset = $_POST['check_asset_privated']; 
			else
				$post_check_asset = 0;

			$queryA = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel, asset_privated)".
					" values (".$_POST['numero'].",";

			if ($_SESSION['s_formatBarOco']) {
				$queryA.= " '".$_POST['assentamento']."',";
			} else {
				$queryA.= " '".noHtml($_POST['assentamento'])."',";
			}

			$queryA.=" '".date('Y-m-d H:i:s')."', ".$responsavel.", ".$post_check_asset.")";


			$queryCleanAssets = "UPDATE assentamentos SET asset_privated = 0 WHERE ocorrencia = ".$_POST['numero']."";
			$execCleanAssets = mysql_query($queryCleanAssets) or die (TRANS('ERR_EDIT'));

			for ($i=1; $i<=$_POST['total_asset'];$i++){

				if (isset($_POST['asset'.$i])) {
					$queryUpdateAsset = "UPDATE assentamentos SET asset_privated = 1 WHERE numero = ".$_POST['asset'.$i]."";
					$execUpdAsset = mysql_query($queryUpdateAsset) or die(TRANS('ERR_EDIT'));
				}

			}



			/* ----------------- INICIO ALTERACAO ----------------- */
			for($i=1;$i<=$row_config['conf_qtd_max_anexos']; $i++){
				if ($gravaImg[$i]) {
					$nomeAnexo = 'anexo_'.$i;
					//INSERSAO DO ARQUIVO NO BANCO
					$fileinput=$_FILES[$nomeAnexo]['tmp_name'];
					$tamanho = getimagesize($fileinput);
					$tamanho2 = filesize($fileinput);

					if(chop($fileinput)!=""){
						// $fileinput should point to a temp file on the server
						// which contains the uploaded image. so we will prepare
						// the file for upload with addslashes and form an sql
						// statement to do the load into the database.
						$image = addslashes(fread(fopen($fileinput,"r"), 1000000));
						$SQL = "Insert Into imagens (img_nome, img_oco, img_tipo, img_bin, img_largura, img_altura, img_size) values ".
								"('".noSpace($_FILES[$nomeAnexo]['name'])."',".$_POST['numero'].", '".$_FILES[$nomeAnexo]['type']."', ".
								"'".$image."', '".$tamanho[0]."', '".$tamanho[1]."', '".$tamanho2."')";
						// now we can delete the temp file
						unlink($fileinput);
					} /*else {
						echo "".TRANS('MSG_NOT_IMAGE_SELECT')."";
						exit;
					}*/
					$exec = mysql_query($SQL); //or die ("N?O FOI POSS?VEL GRAVAR O ARQUIVO NO BANCO DE DADOS! ");
					if ($exec == 0) 
						$aviso.= TRANS('MSG_ATTACH_IMAGE')."<br>";	
				}
			}
			/* ----------------- FIM ALTERACAO ----------------- */

			$sqlMailLogado = "select * from usuarios where login = '".$_SESSION['s_usuario']."'";
			$execMailLogado = mysql_query($sqlMailLogado) or die(TRANS('MSG_ERR_RESCUE_INFO_USER'));
			$rowMailLogado = mysql_fetch_array($execMailLogado);

			$qryLocal = "select * from localizacao where loc_id=".$_POST['local']."";
			$execLocal = mysql_query($qryLocal);
			$rowLocal = mysql_fetch_array($execLocal);

			$qryfull = $QRY["ocorrencias_full_ini"]." WHERE o.numero = ".$_POST['numero']."";
			$execfull = mysql_query($qryfull) or die(TRANS('MSG_ERR_RESCUE_VARIA_SURROU').$qryfull);
			$rowfull = mysql_fetch_array($execfull);

			$VARS = array();
			$VARS['%numero%'] = $rowfull['numero'];
			$VARS['%usuario%'] = $rowfull['contato'];
			$VARS['%contato%'] = $rowfull['contato'];
			$VARS['%descricao%'] = $rowfull['descricao'];
			$VARS['%setor%'] = $rowfull['setor'];
			$VARS['%ramal%'] = $rowfull['telefone'];
			$VARS['%assentamento%'] = $_POST['assentamento'];
			$VARS['%site%'] = "<a href='".$row_config['conf_ocomon_site']."'>".$row_config['conf_ocomon_site']."</a>";
			$VARS['%area%'] = $rowfull['area'];
			$VARS['%operador%'] = $rowfull['nome'];
			$VARS['%editor%'] = $rowMailLogado['nome'];
			$VARS['%aberto_por%'] = $rowfull['aberto_por'];
			$VARS['%problema%'] = $rowfull['problema'];
			$VARS['%versao%'] = VERSAO;

			$qryconf = "SELECT * FROM mailconfig";
			$execconf = mysql_query($qryconf) or die (TRANS('MSG_ERR_RESCUE_SEND_EMAIL'));
			$rowconf = mysql_fetch_array($execconf);

			if (isset($_POST['mailOP']) ){
				$event = 'edita-para-operador';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die(TRANS('MSG_ERR_MSCONFIG'));
				$rowmsg = mysql_fetch_array($execmsg);

				$sqlMailOper = "select * from usuarios where user_id =".$_POST['operador']."";
				$execMailOper = mysql_query($sqlMailOper);
				$rowMailOper = mysql_fetch_array($execMailOper);

				$VARS['%operador%'] = $rowMailOper['nome'];
				send_mail($event, $rowMailOper['email'], $rowconf, $rowmsg, $VARS);
			}
			if (isset($_POST['mailAR'])){
				$event = 'edita-para-area';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die(TRANS('MSG_ERR_MSCONFIG'));
				$rowmsg = mysql_fetch_array($execmsg);

				$sqlMailArea = "select * from sistemas where sis_id = ".$_POST['sistema']."";
				$execMailArea = mysql_query($sqlMailArea);
				$rowMailArea = mysql_fetch_array($execMailArea);

				send_mail($event, $rowMailArea['sis_email'], $rowconf, $rowmsg, $VARS);
			}
			if (isset($_POST['mailUS'])){
				$event = 'edita-para-usuario';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die(TRANS('MSG_ERR_MSCONFIG'));
				$rowmsg = mysql_fetch_array($execmsg);


				$sqlMailUs = "select * from usuarios where user_id = ".$_POST['abertopor']."";
				$execMailUs = mysql_query($sqlMailUs) or die(TRANS('MSG_ERR_RESCUE_SEND_EMAIL'));
				$rowMailUs = mysql_fetch_array($execMailUs);

				$qryresposta = "select u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and u.user_id = ".$_SESSION['s_uid']."";
				$execresposta = mysql_query($qryresposta) or die (TRANS('MSG_ERR_NOT_IDENTIFY_EMAIL'));
				$rowresposta = mysql_fetch_array($execresposta);

				send_mail($event, $rowMailUs['email'], $rowconf, $rowmsg, $VARS);
			}


			$resultado3 = mysql_query($queryA) or die(TRANS('MSG_NOT_SAVE_INFO_EDIT_CALL').'<br>'.$queryA);


			if (!isset($_POST['radio_prob'])){
				//$catProb = $problema;
				$catProb = $_POST['problema'];
			} else {
				$catProb = $_POST['radio_prob'];
			}

			if ($_SESSION['s_formatBarOco']){
				$description = $_POST['descricao'];
			} else {
				$description = noHtml($_POST['descricao']);
			}

			if ($_POST['antes'] != $depois) //Status alterado!!   $_POST['antes']: status anterior
			{   //$status!=1 and
				if (($_POST['data_atend']=="") and ($depois!=4) and (isset($_POST['resposta'])) ) //para verificar se já foi setada a data do inicio do atendimento. //Se eu incluir um assentamento seto a data de atendimento
				{
					$query = "UPDATE ocorrencias SET operador=".$_POST['operador'].", problema = ".$catProb.", instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local=".$_POST['local'].", data_fechamento=NULL, status=".$depois.", data_atendimento='".date('Y-m-d H:i:s')."', ".
								"data_abertura = '".$data_agendamento."', oco_real_open_date='".$realOpenDate."', oco_scheduled=".$agendado.", descricao='".$description."', contato='".noHtml($_POST['contato'])."', telefone='".$_POST['ramal']."', oco_prior='".$_POST['prioridade']."' WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query);
				}  else
				{
					$query = "UPDATE ocorrencias SET operador=".$_POST['operador'].", problema = ".$catProb." , instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local=".$_POST['local'].", data_fechamento=NULL, status=".$depois.", ".
								"data_abertura = '".$data_agendamento."', oco_real_open_date='".$realOpenDate."', oco_scheduled=".$agendado.", descricao='".$description."', contato='".noHtml($_POST['contato'])."', telefone='".$_POST['ramal']."', oco_prior='".$_POST['prioridade']."' WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query);
				}
			} else
			{
			if (($_POST['data_atend']=="") and ($depois!=4) and (isset($_POST['resposta']) )) //para verificar se já foi setada a data do inicio do atendimento. //Se eu incluir um assentamento seto a data de atendimento
				{
				$query = "UPDATE ocorrencias SET operador=".$_POST['operador'].", problema = ".$catProb.", instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local=".$_POST['local'].", data_fechamento=NULL, status=".$depois.", data_atendimento='".date('Y-m-d H:i:s')."', ".
					"data_abertura = '".$data_agendamento."', oco_real_open_date='".$realOpenDate."', oco_scheduled=".$agendado.", descricao='".$description."', contato='".noHtml($_POST['contato'])."', telefone='".$_POST['ramal']."', oco_prior='".$_POST['prioridade']."' WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query);
				} else {
					$query = "UPDATE ocorrencias SET operador=".$_POST['operador'].", problema = ".$catProb.", instituicao='".$_POST['institui']."', equipamento = '".$_POST['etiq']."', sistema = '".$_POST['sistema']."', local=".$_POST['local'].", status=".$depois.", ".
						"data_abertura = '".$data_agendamento."', oco_real_open_date='".$realOpenDate."', oco_scheduled=".$agendado.", descricao='".$description."', contato='".noHtml($_POST['contato'])."', telefone='".$_POST['ramal']."', oco_prior='".$_POST['prioridade']."' WHERE numero=".$_POST['numero']."";
					$resultado4 = mysql_query($query);
				}
			}

			if (($resultado3==0) OR ($resultado4 == 0))
			{
				$aviso = TRANS('MSG_ERR_ACCESS').$query;
			}
			else
			{
				$sqlDoc1 = "select * from doc_time where doc_oco = ".$_POST['numero']." and doc_user=".$_SESSION['s_uid']."";
				$execDoc1 = mysql_query($sqlDoc1) or die('ERRO<br>'.$sqlDoc1);
				$regDoc1 = mysql_num_rows($execDoc1);
				$rowDoc1 = mysql_fetch_array($execDoc1);
				if ($regDoc1 >0) {
					$sqlDoc  = "update doc_time set doc_edit=doc_edit+".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." where doc_id = ".$rowDoc1['doc_id']."";
					$execDoc =mysql_query($sqlDoc) or die (TRANS('MSG_ERR_UPDATE_TIME_DOC_CALL').'<br>').$sqlDoc;
				} else {
					$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$_POST['numero'].", 0, ".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." , 0, ".$_SESSION['s_uid'].")";
					$execDoc = mysql_query($sqlDoc) or die (TRANS('MSG_ERR_UPDATE_TIME_DOC_CALL').'<br>').$sqlDoc;
				}

				##ROTINAS PARA GRAVAR O TEMPO DO CHAMADO EM CADA STATUS
				//if ($_POST['status'] != $row['status']) { //O status foi alterado
				if ($_POST['antes'] != $depois) { //Status alterado!!
				##TRATANDO O STATUS ANTERIOR
				//Verifica se o status 'atual' já foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, senão devo gravar ele pela primeira vez.
					$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$_POST['antes']." ";
					$exec_sql = mysql_query($sql_ts_anterior);

					if ($exec_sql == 0) $error= " erro 1";

					$achou = mysql_num_rows($exec_sql);
					if ($achou >0){ //esse status já esteve setado em outro momento
						$row_ts = mysql_fetch_array($exec_sql);

						// if (array_key_exists($row['sistema'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
							// $areaT = $row['sistema']; //Recebe o valor da área de atendimento do chamado
						// } else $areaT = 1; //Carga horária default definida no arquivo config.inc.php

						$areaT = "";
						$areaT=testaArea($areaT,$row['sistema'],$H_horarios);

						$dt = new dateOpers;
						$dt->setData1($row_ts['ts_data']);
						$dt->setData2(date("Y-m-d H:i:s"));
						$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
						$segundos = $dt->diff["sValido"]; //segundos válidos

						$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+".$segundos.") , ts_data ='".date("Y-m-d H:i:s")."' where ts_ocorrencia = ".$row['numero']." and
								ts_status = ".$_POST['antes']." ";
						$exec_upd = mysql_query($sql_upd);
						if ($exec_upd ==0) $error.= " erro 2";

					} else {
						$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$_POST['antes'].", 0, '".date("Y-m-d H:i:s")."' )";
						$exec_ins = mysql_query ($sql_ins);
						if ($exec_ins == 0) $error.= " erro 3 ";
					}
					##TRATANDO O NOVO STATUS
					//verifica se o status 'novo' já está gravado na tabela 'tempo_status', se estiver eu devo atualizar a data de início. Senão estiver gravado então devo gravar pela primeira vez
					$sql_ts_novo = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$depois." ";
					$exec_sql = mysql_query($sql_ts_novo);
					if ($exec_sql == 0) $error.= " erro 4";

					$achou_novo = mysql_num_rows($exec_sql);
					if ($achou_novo > 0) { //status já existe na tabela tempo_status
						$sql_upd = "update tempo_status set ts_data = '".date('Y-m-d H:i:s')."' where ts_ocorrencia = ".$row['numero']." and ts_status = ".$depois." ";
						$exec_upd = mysql_query($sql_upd);
						if ($exec_upd == 0) $error.= " erro 5";
					} else {//status novo na tabela tempo_status
						$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$depois.", 0, '".date("Y-m-d H:i:s")."' )";
						$exec_ins = mysql_query($sql_ins);
						if ($exec_ins == 0) $error.= " erro 6 ";
					}
				}

				$aviso = "";
				$aviso = TRANS('MSG_OCCO_MODIFY_SUCESS');

			}
		} //fecha if erro=nao

		$LOCK->unlock($_POST['numero']);
		//print "<script>mensagem('".$aviso."'); redirect('mostra_consulta.php?numero=".$_POST['numero']."');</script>";
		print "<script>redirect('mostra_consulta.php?numero=".$_POST['numero']."&justOpened=true');</script>";

	}//fecha if submit

	//$LOCK->unlock();

?>
<script type="text/javascript">
<!--
        function registra_status(){
                var Selecao = document.getElementById("idStatus");
                var Area = document.getElementById("idAssentamento");
                var TextoAtual = Area.value;
                var status = false;
                var lines = Area.value.split("\n");
                for(var i = 0;i < lines.length;i++){
                        if ( lines[i].search("Status Alterado") > 0 ){
                                Area.value = "(Status Alterado para: " + Selecao.options[Selecao.selectedIndex].text + " )\n";
                                status = true;
                                continue;
                        }
                        Area.value += lines[i] + "\n";
                }
                if (!status){
                        Area.value = "(Status Alterado para: " + Selecao.options[Selecao.selectedIndex].text + " )\n";
                        Area.value += TextoAtual;
                }
        }

	function valida(){
		var ok = validaForm('idStatus','COMBO','<?php print TRANS('OCO_FIELD_STATUS')?>',1);
		if (ok) var ok = validaForm('idArea','COMBO','<?php print TRANS('OCO_FIELD_AREA')?>',1);
		if (ok) var ok = validaForm('idProblema','COMBO','<?php print TRANS('OCO_FIELD_PROB')?>',1);
		if (ok) var ok = validaForm('idDescricao','','<?php print TRANS('OCO_DESC')?>',1);
		if (ok) var ok = validaForm('idUnidade','COMBO','<?php print TRANS('OCO_FIELD_UNIT')?>',0);
		if (ok) var ok = validaForm('idEtiqueta','INTEIROFULL','<?php print TRANS('FIELD_TAG_EQUIP')?>',0);
		if (ok) var ok = validaForm('idContato','','<?php print TRANS('OCO_FIELD_CONTACT')?>',1);
		if (ok) var ok = validaForm('idRamal','FONE','<?php print TRANS('OCO_FIELD_PHONE')?>',1);
		if (ok) var ok = validaForm('idLocal','COMBO','<?php print TRANS('OCO_FIELD_LOCAL')?>',1);
		if (ok) var ok = validaForm('idDataAgendamento','DATAHORA','<?php print TRANS('OCO_FIELD_SCHEDULE')?>',0);
		if (ok) var ok = validaForm('idAssentamento','','<?php print TRANS('FIELD_NESTING')?>',1);

		return ok;
	}

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


	function checarSchedule(id) {
		var checado = false;
		var obj = document.getElementById(id);

		if (document.formulario.chk_squedule.checked){
			checado = true;
			disable_schedule(false);
			document.formulario.status.disabled = true;

		} else {
			checado = false;
			disable_schedule(true);
			if (id!='') {
				//document.formulario.data_agendamento.value=obj.value;
				document.formulario.data_agendamento.value='<?php print $os_DataAgendamento?>';
			} else {
				document.formulario.data_agendamento.value='<?php print date("d/m/Y H:i:s")?>';
			}
			document.formulario.status.disabled = false;
		}
		return checado;
	}

	function disable_schedule(v) {
		document.formulario.data_agendamento.disabled = v;
		document.formulario.data_agendamento.focus();
	}


-->
</script>
<?php 

print "</body>";
print "</html>";
?>
