<?
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

	$imgsPath = "../../includes/imgs/";
	$hoje = date("Y-m-d H:i:s");
    	$hoje2 = date("d/m/Y");

	print "<HTML><BODY bgcolor='".BODY_COLOR."'>";
	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die ("ERRO AO TENTAR ACESSAR A TABELA CONFIG! CERTIFIQUE-SE DE QUE A TABELA EXISTE!");;
	$row_config = mysql_fetch_array($exec_config);


	$sqlSoluc = "SELECT * FROM solucoes WHERE numero = ".$_REQUEST['numero']." ";
	$execSoluc = mysql_query ($sqlSoluc);
	$regSoluc = mysql_num_rows($execSoluc);
	if ($regSoluc >0) {
		print "<script>".
			"mensagem('ALERTA: Essa ocorrência já foi encerrada uma vez no sistema! Contate o Administrador do Sistema se desejar encerrá-la novamente!');".
			"history.back();";
		print "</script>";
		exit;
	}


	$sqlSub = "select * from ocodeps where dep_pai = ".$_REQUEST['numero']." ";
	$execSub = mysql_query ($sqlSub) or die ('NÃO FOI POSSÍVEL RECUPERAR AS INFORMAÇÕES DE DEPENDÊNCIAS DO CHAMADO!'.$sqlSub);
	$deps = array();
	while ($rowSub = mysql_fetch_array($execSub)) {

		$sqlStatus = "select o.*, s.*  from ocorrencias as o, `status` as s where o.numero = ".$rowSub['dep_filho']." and o.`status`=s.stat_id and s.stat_painel not in (3)  ";
		$execStatus = mysql_query($sqlStatus) or die ('NÃO FOI POSSÍVEL ACESSAR A LISTAGEM DE CHAMADOS FILHOS!'.$sqlStatus);
		$achou = mysql_num_rows ($execStatus);
		if ($achou > 0) {
			$deps[] = $rowSub['dep_filho'];
		}

	}

	if(sizeof($deps)) {
		$saida = "<b>ALERTA: Essa ocorrência não pode ser encerrada pois possui as seguintes dependências:</b><br><br>";
		foreach($deps as $err) {
			$saida.="Chamado <a onClick=\"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$err."')\"><font color='blue'>".$err."</font></a><br>";
		}
		$saida.="<br><a align='center' onClick=\"redirect('mostra_consulta.php?numero=".$_REQUEST['numero']."');\"><img src='".ICONS_PATH."/back.png' width='16px' height='16px'>&nbsp;Voltar</a>";
		print "</table>";
		print "<div class='alerta' id='idAlerta'><table bgcolor='#999999'><tr><td colspan='2' bgcolor='yellow'>".$saida."</td></tr></table></div>";
		exit;
	}



	//$query = "select o.*, u.* from ocorrencias as o, usuarios as u where o.operador = u.user_id and numero=$numero";
	//$query = $QRY["ocorrencias_full_ini"]." where numero in (".$numero.") order by numero";
	$query = $QRY["ocorrencias_full_ini"]." where numero = ".$_REQUEST['numero']." order by numero";
	$resultado = mysql_query($query);
	$rowABS = mysql_fetch_array($resultado);

	$atendimento = "";
	$atendimento = $rowABS['data_atendimento'];

	$query2 = "select a.*, u.* from assentamentos as a, usuarios as u where a.responsavel = u.user_id and ocorrencia='".$_REQUEST['numero']."'";
	$resultado2 = mysql_query($query2);
	$linhas2 = mysql_numrows($resultado2);

	if (!isset($_POST['submit'])) {

		print "<BR><B>Encerramento de Ocorrências</B><BR>";

		print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' name='form1' onSubmit='return valida()'>";
		print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Número:</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>".$rowABS['numero']."<td class='line'>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Problema:</TD>";
				$query_problema = "SELECT * FROM problemas order by problema";
				$exec_problema = mysql_query($query_problema);
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<select class='select' name='prob' id='idProb'>";
					print "<option value=-1>Selecione o problema</option>";
					while($row=mysql_fetch_array($exec_problema)){
						print "<option value=".$row['prob_id']."";
							if ($row['prob_id']== $rowABS['prob_cod']) {
								print " selected";
							}
						print ">".$row['problema']."</option>";
					} // while
				print "</select>";
			print "</TD>";


			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Área responsável:</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".$rowABS['area']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Descrição:</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'><b>".$rowABS['descricao']."</b></TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Unidade:</TD>";
				$qryinst = "select * from instituicao order by inst_nome";
				$exec_inst = mysql_query($qryinst);

			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<select class='select' name='inst'>";
					print "<option value=-1>Selecione a unidade</option>";
						while($row=mysql_fetch_array($exec_inst)){
							print "<option value=".$row['inst_cod']."";
								if ($row['inst_cod']== $rowABS['unidade_cod']) {
									print " selected";
								}
							print ">".$row['inst_nome']."</option>";
						} // while
				print "</select>";

			print "</TD>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'><a onClick=\"checa_etiqueta()\" ".
					"title='Consulta a configuração do equipamento!'><font color='#5E515B'><b>Etiqueta</b></font></a>".
					" do equipamento:</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<input type='text' class='data' name='etiqueta' id='idEtiqueta' value='".$rowABS['etiqueta']."'>";
			print "</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Contato:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><input type='text' class='text' name='contato' id='idContato' value='".$rowABS['contato']."'></TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Telefone:</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".$rowABS['telefone']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Local:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<select class='select' name='loc' id='idLocal'>";

					$qrylocal = "select * from localizacao where loc_status not in (0) order by local";
					$exec_local = mysql_query($qrylocal);
					print "<option value=-1>Selecione o local</option>";
					while($row=mysql_fetch_array($exec_local)){
						print "<option value=".$row['loc_id']."";
							if ($row['loc_id']== $rowABS['setor_cod']) {
								print " selected";
							}
						print ">".$row['local']."</option>";
					} // while

				print "</select><a onClick=\"checa_por_local()\">".
						"<img title='Consulta os equipamentos cadastrados para esse local!' width='15' height='15' ".
						"src='".$imgsPath."consulta.gif' border='0'></a>";
			print "</TD>";

			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Operador:</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".$rowABS['nome']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de Abertura:</TD>";
			print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>".formatDate($rowABS['data_abertura'])."</TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Status:</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='".BODY_COLOR."'>".$rowABS['chamado_status']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Data de Fechamento:</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<INPUT type='text' class='text' name='data_fechamento' id='idData_fechamento' value='".formatDate(date("Y-m-d H:i:s"))."'>";
			print "</TD>";
		print "</tr>";

		if ($linhas2 > 0) { //ASSENTAMENTOS DO CHAMADO
			print "<tr><td colspan='6'><IMG ID='imgAssentamento' SRC='../../includes/icons/open.png' width='9' height='9' ".
					"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Assentamento')\">&nbsp;<b>Existe(m) <font color='red'>".$linhas2."</font>".
					" assentamento(s) para essa ocorrência.</b></td></tr>";

			//style='{padding-left:5px;}'
			print "<tr><td colspan='6'><div id='Assentamento' style='{display:none}'>"; //style='{display:none}'
			print "<TABLE border='0' align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			$i = 0;
			while ($rowAssentamento = mysql_fetch_array($resultado2)){
				$printCont = $i+1;
				print "<TR>";
				print "<TD width='20%' bgcolor='".TD_COLOR."' valign='top'>".
						"Assentamento ".$printCont." de ".$linhas2." por ".$rowAssentamento['nome']." em ".
						"".formatDate($rowAssentamento['data'])."".
					"</TD>";
				print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."' valign='top'>".nl2br($rowAssentamento['assentamento'])."</TD>";
				print "</TR>";
				$i++;
			}
			print "</table></div></td></tr>";
			//print "</div>";
		}



		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Problema:</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				//print "<TEXTAREA class='textarea' id='idProblema' name='problema'>Descrição técnica do problema</textarea>";

			if (!$_SESSION['s_formatBarOco']) {
				print "<TEXTAREA class='textarea' name='problema' id='idProblema'>Descrição técnica do problema</textarea>"; //oFCKeditor.Value = print noHtml($descricao);
			} else
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			?>
			<script type="text/javascript">
				var bar = '<?print $_SESSION['s_formatBarOco'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'problema' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = 'Descrição técnica do problema';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>

			<?

			print "</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Solução:</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				//print "<TEXTAREA class='textarea' id='idSolucao' name='solucao'>Solução para este problema</textarea>";

			if (!$_SESSION['s_formatBarOco']) {
				print "<TEXTAREA class='textarea' name='solucao' id='idSolucao'>Solução para este problema</textarea>"; //oFCKeditor.Value = print noHtml($descricao);
			}
			?>
			<script type="text/javascript">
				var bar = '<?print $_SESSION['s_formatBarOco'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'solucao' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = 'Solução para este problema';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>
			<?

			print "</TD>";
		print "</TR>";

			$qrymail = "SELECT u.*, a.*,o.* from usuarios u, sistemas a, ocorrencias o where ".
						"u.AREA = a.sis_id and o.aberto_por = u.user_id and o.numero = ".$_GET['numero']."";
			$execmail = mysql_query($qrymail);
			$rowmail = mysql_fetch_array($execmail);
			if ($rowmail['sis_atende']==0){
				$habilita = "checked";
			} else $habilita = "disabled";

			print "<tr><td bgcolor='".TD_COLOR."'>Enviar e-mail para:</td>".
					"<td colspan='5'><input type='checkbox' value='ok' name='mailAR' checked title='Envia e-mail para a área de atendimento do chamado'>Área responsável&nbsp;&nbsp;".
						"<input type='checkbox' value='ok' name='mailUS' ".$habilita."><a title='Essa opção só fica habilitada para chamados abertos pelo próprio usuário'>Usuário</a></td>".
				"</tr>";

		print "<TR>";
		print "<BR>";
			print "<input type='hidden' name='data_gravada' value='".date("Y-m-d H:i:s")."'>";

			print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
					"<input type='submit'  class='button' value='  Ok  ' name='submit'>".
					"<input type='hidden' name='rodou' value='sim'>".
					"<input type='hidden' name='numero' value='".$_GET['numero']."'>".
					"<input type='hidden' name='abertopor' value='".$rowmail['user_id']."'>";
			print "</TD>";
			print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
					"<INPUT type='button'  class='button' value='Cancelar' name='desloca' ONCLICK='javascript:history.back()'>";
			print "</TD>";
		print "</TR>";
	} else

	if (isset($_POST['submit'])) {

	#########################################################################################

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

		$query = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values (".$_POST['numero'].",";
		if ($_SESSION['s_formatBarOco']) {
			$query.= " '".$_POST['problema']."',";
		} else {
			$query.= " '".noHtml($_POST['problema'])."',";
		}
		$query.=" '".date('Y-m-d H:i:s')."', ".$responsavel.")"; //VER 25/05/2007
		$resultado = mysql_query($query) or die ('ERRO AO TENTAR INCLUIR ASSENTAMENTO! '.$query);

		$query = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel) values (".$_POST['numero'].", ";

		if ($_SESSION['s_formatBarOco']) {
			$query.= " '".$_POST['solucao']."',";
		} else {
			$query.= " '".noHtml($_POST['solucao'])."',";
		}
		$query.=" '".date('Y-m-d H:i:s')."', ".$responsavel.")";
		$resultado = mysql_query($query)or die ('ERRO AO TENTAR INCLUIR ASSENTAMENTO! '.$query);

		$query1 = "INSERT INTO solucoes (numero, problema, solucao, data, responsavel) values (".$_POST['numero'].", ";

		if ($_SESSION['s_formatBarOco']) {
			$query1.= " '".$_POST['problema']."','".$_POST['solucao']."',";
		} else {
			$query1.= " '".noHtml($_POST['problema'])."','".noHtml($_POST['solucao'])."',";
		}
		$query1.=" '".date('Y-m-d H:i:s')."', ".$responsavel.")";
		$resultado1 = mysql_query($query1)or die ('ERRO AO TENTAR INCLUIR SOLUCÃO! '.$query1);

		$status = 4; //encerrado
		if ($atendimento==null) {
			$query2 = "UPDATE ocorrencias SET status=".$status.", local=".$_POST['loc'].", problema =".$_POST['prob'].",operador=".$_SESSION['s_uid'].", instituicao='".$_POST['inst']."', equipamento='".$_POST['etiqueta']."', contato='".noHtml($_POST['contato'])."', data_fechamento='".date('Y-m-d H:i:s')."', data_atendimento='".date('Y-m-d H:i:s')."' WHERE numero='".$_POST['numero']."'";

		} else {
			$query2 = "UPDATE ocorrencias SET status=".$status.", local=".$_POST['loc'].",problema =".$_POST['prob'].", operador=".$_SESSION['s_uid'].", instituicao='".$_POST['inst']."', equipamento='".$_POST['etiqueta']."', contato='".noHtml($_POST['contato'])."', data_fechamento='".date('Y-m-d H:i:s')."' WHERE numero='".$_POST['numero']."'";

		}
		$resultado2 = mysql_query($query2);

		if (($resultado == 0) or ($resultado1 == 0) or ($resultado2 == 0))
		{
			$aviso = "Um erro ocorreu ao tentar incluir dados no sistema.";
		}
		else {

			$sqlDoc1 = "select * from doc_time where doc_oco = ".$_POST['numero']." and doc_user=".$_SESSION['s_uid']."";
			$execDoc1 = mysql_query($sqlDoc1);
			$regDoc1 = mysql_num_rows($execDoc1);
			$rowDoc1 = mysql_fetch_array($execDoc1);
			if ($regDoc1 >0) {
				$sqlDoc  = "update doc_time set doc_close=doc_close+".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." where doc_id = ".$rowDoc1['doc_id']."";
				$execDoc =mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTAÇÃO DO CHAMADO!<br>').$sqlDoc;
			} else {
				$sqlDoc = "insert into doc_time (doc_oco, doc_open, doc_edit, doc_close, doc_user) values (".$_POST['numero'].", 0, 0, ".diff_em_segundos($_POST['data_gravada'],date("Y-m-d H:i:s"))." ,".$_SESSION['s_uid'].")";
				$execDoc = mysql_query($sqlDoc) or die ('ERRO NA TENTATIVA DE ATUALIZAR O TEMPO DE DOCUMENTAÇÃO DO CHAMADO!!<br>').$sqlDoc;
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
			$execfull = mysql_query($qryfull) or die('ERRO, NÃO FOI POSSÍVEL RECUPERAR AS VARIÁVEIS DE AMBIENTE!'.$qryfull);
			$rowfull = mysql_fetch_array($execfull);

			$VARS = array();
			$VARS['%numero%'] = $rowfull['numero'];
			$VARS['%usuario%'] = $rowfull['contato'];
			$VARS['%contato%'] = $rowfull['contato'];
			$VARS['%descricao%'] = $rowfull['descricao'];
			$VARS['%setor%'] = $rowfull['setor'];
			$VARS['%ramal%'] = $rowfull['telefone'];
			$VARS['%assentamento%'] = $_POST['solucao'];
			$VARS['%site%'] = "<a href='".$row_config['conf_ocomon_site']."'>".$row_config['conf_ocomon_site']."</a>";
			$VARS['%area%'] = $rowfull['area'];
			$VARS['%operador%'] = $rowfull['nome'];
			$VARS['%problema%'] = $_POST['problema'];
			$VARS['%solucao%'] = $_POST['solucao'];
			$VARS['%versao%'] = VERSAO;

			$qryconf = "SELECT * FROM mailconfig";
			$execconf = mysql_query($qryconf) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE ENVIO DE E-MAIL!');
			$rowconf = mysql_fetch_array($execconf);

			if (isset($_POST['mailAR']) ){
				$event = 'encerra-para-area';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
				$rowmsg = mysql_fetch_array($execmsg);
				send_mail($event, $rowSis['sis_email'], $rowconf, $rowmsg, $VARS);

				//$flag = envia_email_fechamento($numero, $rowSis['sis_email'], $nome, $rowSis['sistema'], $problema, $solucao);
			}
			if (isset($_POST['mailUS'])) {
				$event = 'encerra-para-usuario';
				$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
				$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
				$rowmsg = mysql_fetch_array($execmsg);

				$sqlMailUs = "select * from usuarios where user_id = ".$_POST['abertopor']."";
				$execMailUs = mysql_query($sqlMailUs) or die('NÃO FOI POSSÍVEL ACESSAR A BASE DE USUÁRIOS PARA O ENVIO DE EMAIL!');
				$rowMailUs = mysql_fetch_array($execMailUs);

				$qryresposta = "select u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and u.user_id = ".$_SESSION['s_uid']."";
				$execresposta = mysql_query($qryresposta) or die ('NÃO FOI POSSÍVEL IDENTIFICAR O EMAIL PARA RESPOSTA!');
				$rowresposta = mysql_fetch_array($execresposta);

				/*$flag = mail_user_encerramento($rowMailUs['email'], $rowresposta['sis_email'], $rowMailUs['nome'],$_GET['numero'],
													$assentamento,OCOMON_SITE);*/
				send_mail($event, $rowMailUs['email'], $rowconf, $rowmsg, $VARS);
			}

			$aviso = "Ocorrência encerrada com sucesso!";
		}

		print "<script>mensagem('".$aviso."'); redirect('abertura.php');</script>";
        }

?>
<script type="text/javascript">
<!--

	function valida(){
		var ok = validaForm('idProb','COMBO','Problema',1);

		if (ok) var ok = validaForm('idEtiqueta','INTEIROFULL','Etiqueta',0);
		if (ok) var ok = validaForm('idContato','','Contato',1);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);
		if (ok) var ok = validaForm('idData_fechamento','DATAHORA','Data',1);
		if (ok) var ok = validaForm('idProblema','','Descrição técnica',1);
		if (ok) var ok = validaForm('idSolucao','','Solução',1);

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
<?

print "</TABLE>";
print "</FORM>";
print "</body>";
print "</html>";
?>
