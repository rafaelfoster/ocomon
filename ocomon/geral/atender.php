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

        if (isset($_POST['numero'])) {
        	$numero = inteiro($_POST['numero']);
        } else
        if (isset($_GET['numero'])) {
        	$numero = inteiro($_GET['numero']);
        } else {
		echo "This script cannot run out of OcoMon interface!!";
		exit;
        }


        $query = $QRY["ocorrencias_full_ini"]." where numero =".$numero." order by numero";
	$resultado = mysql_query($query);
	$row = mysql_fetch_array($resultado);

	//print $query;

	$data_atend = $row['data_atendimento']; //Data de atendimento!!!

	$query2 = "select a.*, u.* from assentamentos a, usuarios u where a.responsavel=u.user_id and a.ocorrencia='".$numero."'";
        $resultado2 = mysql_query($query2);
        $linhas=mysql_numrows($resultado2);
        $hoje = date("Y-m-d H:i:s");

	print "<HTML><BODY bgcolor='".BODY_COLOR."'>";
	$auth = new auth;
	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	if (!isset($_POST['submit'])) {

		print "<BR><B>".TRANS('TTL_ATTEND_OCCO').":</B><BR>";

		print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0'  align='center' cellpadding='3' cellspacing='2' width='100%' bgcolor='".BODY_COLOR."'>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_NUMBER').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='WHITE'>".$row['numero']."<td class='line'>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_PROB').":</TD>";
			print "<TD width='40%' align='left' bgcolor='WHITE'>".$row['problema']."</TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_AREA').":</TD>";
			print "<TD colspan='3' width='30%' align='left' bgcolor='WHITE'>".$row['area']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('OCO_DESC').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='WHITE'>".$row['descricao']."</TD>";
		print "</TR>";

		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_UNIT').":</TD>";
			print "<TD width='40%' align='left' bgcolor='WHITE'>".$row['unidade']."</TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_TAG_EQUIP').":</TD>";
			print "<TD colspan='3' width='40%' align='left' bgcolor='WHITE'>".$row['etiqueta']."</TD>";
		print "</TR>";

		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_CONTACT').":</TD>";
			print "<TD width='40%' align='left' bgcolor='WHITE'>".$row['contato']."</TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_PHONE').":</TD>";
			print "<TD colspan='3' width='40%' align='left' bgcolor='WHITE'>".$row['telefone']."</TD>";
		print "</TR>";

		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_LOCAL').":</TD>";
			print "<TD width='40%' align='left' bgcolor='WHITE'>".$row['setor']."</TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_OPERATOR').":</TD>";
			print "<TD colspan='3' width='40%' align='left' bgcolor='WHITE'>".$row['nome']."</TD>";
		print "</TR>";

		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_DATE_OPEN').":</TD>";
			print "<TD width='40%' align='left' bgcolor='WHITE'>".formatDate($row['data_abertura'])."</TD>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_STATUS').":</TD>";
			print "<TD colspan='3' width='40%' align='left' bgcolor='WHITE'>".$row['chamado_status']."</TD>";
		print "</TR>";


		if ($linhas !=0) { //ASSENTAMENTOS DO CHAMADO
			print "<tr><td colspan='6'><IMG ID='imgAssentamento' SRC='../../includes/icons/open.png' width='9' height='9' ".
					"STYLE=\"{cursor: pointer;}\" onClick=\"invertView('Assentamento')\">&nbsp;<b>".TRANS('THERE_IS_ARE')." <font color='red'>".$linhas."</font>".
					" ".TRANS('FIELD_NESTING_FOR_OCCO').".</b></td></tr>";

			//style='{padding-left:5px;}'
			print "<tr><td colspan='6' ><div id='Assentamento' style='display:none'>"; //style='{display:none}'
			print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
			$i = 0;
			while ($rowAssentamento = mysql_fetch_array($resultado2)){
				$printCont = $i+1;
				print "<TR>";
				print "<TD width='20%' ' bgcolor='".TD_COLOR."' valign='top'>".
						"".TRANS('FIELD_NESTING')." ".$printCont." de ".$linhas." por ".$rowAssentamento['nome']." em ".
						"".formatDate($rowAssentamento['data'])."".
					"</TD>";
				print "<TD colspan='5' align='left' bgcolor='white' valign='top'>".nl2br($rowAssentamento['assentamento'])."</TD>";
				print "</TR>";
				$i++;
			}
			print "</table></div></td></tr>";
		}



		print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('FIELD_NESTING').":</TD>";
			print "<TD colspan='5' width='80%' align='left' bgcolor='WHITE'>";

				if (!$_SESSION['s_formatBarOco']) {
//					print "<TEXTAREA class='textarea' name='assentamento' id='idAssentamento'>".TRANS('TXTAREA_IN_ATTEND_BY')." ".$_SESSION['s_usuario']."</textarea>";
				        print "<TEXTAREA class='textarea' name='assentamento' id='idAssentamento'>".TRANS('TXTAREA_IN_ATTEND_BY')." ".$_SESSION['user_name']."</textarea>";
				} else
					print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
				?>
				<script type="text/javascript">
					var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
					if (bar ==1) {
						var oFCKeditor = new FCKeditor( 'assentamento' ) ;
						oFCKeditor.BasePath = '../../includes/fckeditor/';
						oFCKeditor.Value = '<?php print "".TRANS('TXTAREA_IN_ATTEND_BY')." ".$_SESSION['s_usuario']."";?>';
						oFCKeditor.ToolbarSet = 'ocomon';
						oFCKeditor.Width = '570px';
						oFCKeditor.Height = '100px';
						oFCKeditor.Create() ;
					}
				</script>
				<?php
		print "</TR>";

		print "<TR>";
			print "<TABLE border='0'  align='center' width='100%' bgcolor='".BODY_COLOR."'>";
				print "<input type='hidden' name='data_gravada' value='".formatDate(date("Y-m-d H:i:s"))."'>";
				//print "<input type='hidden' name='numero' value='".$_GET['numero']."'>";
				print "<input type='hidden' name='numero' value='".$numero."'>";
			print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
					"<input type='submit' class='button' value='  ".TRANS('BT_OK')."  ' name='submit'>";
			print "</TD>";
			print "<TD colspan='3' align='center' width='50%' bgcolor='".BODY_COLOR."'>".
				"<INPUT type='button' class='button' value='".TRANS('BT_CANCEL')."' name='desloca' ONCLICK='javascript:history.back()'>".
				"</TD>";
			print "</table>";
			print "</TR>";

	} else
	if (isset($_POST['submit']))
	{
		//$data = date("Y-m-d H:i:s");
		$responsavel=$_SESSION['s_uid'];

		$queryA = "";
		$queryA = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel)".
				" values (".$_POST['numero'].",";

		if ($_SESSION['s_formatBarOco']) {
			$queryA.= " '".$_POST['assentamento']."',";
		} else {
			$queryA.= " '".noHtml($_POST['assentamento'])."',";
		}

		$queryA.=" '".date("Y-m-d H:i:s")."', ".$responsavel.")";
		$resultado = mysql_query($queryA);

		$status = 2; //Em atendimento

		//CASO O CHAMADO ESTEJA AGENDADO
		if ($row['data_abertura'] >= date("Y-m-d H:i:s")){
			$data_abertura = date("Y-m-d H:i:s");
		} else {
			$data_abertura = $row['data_abertura'];
		}


		if ($data_atend!="") {
			$query2 = "UPDATE ocorrencias SET status=".$status.", operador=".$_SESSION['s_uid'].", data_abertura = '".$data_abertura."', oco_scheduled=0 WHERE numero='".$_POST['numero']."'";

		} else {
			$query2 = "UPDATE ocorrencias SET status=".$status.", operador=".$_SESSION['s_uid'].", data_atendimento='".date("Y-m-d H:i:s")."', data_abertura = '".$data_abertura."', oco_scheduled=0 WHERE numero='".$_POST['numero']."'";
		}

		$resultado2 = mysql_query($query2);


		if (($resultado == 0) or ($resultado2 == 0))
		{
			if ($resultado == 0)
				$aviso = TRANS('MSG_ERR_INSERT_DATA_SYSTEM').$query;
			if ($resultado2 == 0)
				$aviso = TRANS('MSG_ERR_UPDATE_DATA_SYSTEM');
		}
		else
		{

			$sqlDoc1 = "select * from doc_time where doc_oco = ".$_POST['numero']." and doc_user=".$_SESSION['s_uid']."";
			$execDoc1 = mysql_query($sqlDoc1);
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


			//$status = novo status (2)  $row['status_cod'] = Status anterior
			if ($status != $row['status_cod']) { //O status foi alterado
				##TRATANDO O STATUS ANTERIOR
				//Verifica se o status 'atual' já foi gravado na tabela 'tempo_status' , em caso positivo, atualizo o tempo, senão devo gravar ele pela primeira vez.
				$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$row['status_cod']." ";
				$exec_sql = mysql_query($sql_ts_anterior);

				if ($exec_sql == 0) $error= " erro 1";

				$achou = mysql_num_rows($exec_sql);
				if ($achou >0){ //esse status já esteve setado em outro momento
					$row_ts = mysql_fetch_array($exec_sql);

					// if (array_key_exists($row['sistema'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
						// $areaT = $row['sistema']; //Recebe o valor da área de atendimento do chamado
					// } else $areaT = 1; //Carga horária default definida no arquivo config.inc.php
					$areaT = 0;
					$areaT = testaArea($areaT,$row['area_cod'],$H_horarios);

					$dt = new dateOpers;
					$dt->setData1($row_ts['ts_data']);
					$dt->setData2(date("Y-m-d H:i:s"));
					$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaT][0],$H_horarios[$areaT][1],$H_horarios[$areaT][2],$H_horarios[$areaT][3],"H");
					$segundos = $dt->diff["sValido"]; //segundos válidos


					$sql_upd = "update tempo_status set ts_tempo = (ts_tempo+".$segundos.") , ts_data ='".date("Y-m-d H:i:s")."' where ts_ocorrencia = ".$row['numero']." and
							ts_status = ".$row['status_cod']." ";
					$exec_upd = mysql_query($sql_upd);
					if ($exec_upd ==0) $error.= " erro 2";

				} else {
					$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$row['status_cod'].", 0, '".date("Y-m-d H:i:s")."' )";
					$exec_ins = mysql_query ($sql_ins);
					if ($exec_ins == 0) $error.= " erro 3 ";
				}
				##TRATANDO O NOVO STATUS
				//verifica se o status 'novo' já está gravado na tabela 'tempo_status', se estiver eu devo atualizar a data de início. Senão estiver gravado então devo gravar pela primeira vez
				$sql_ts_novo = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = $status ";
				$exec_sql = mysql_query($sql_ts_novo);
				if ($exec_sql == 0) $error.= " erro 4";

				$achou_novo = mysql_num_rows($exec_sql);
				if ($achou_novo > 0) { //status já existe na tabela tempo_status
					$sql_upd = "update tempo_status set ts_data = '".date("Y-m-d H:i:s")."' where ts_ocorrencia = ".$row['numero']." and ts_status = $status ";
					$exec_upd = mysql_query($sql_upd);
					if ($exec_upd == 0) $error.= " erro 5";
				} else {//status novo na tabela tempo_status
					$sql_ins = "insert into tempo_status (ts_ocorrencia, ts_status, ts_tempo, ts_data) values (".$row['numero'].", ".$status.", 0, '".date("Y-m-d H:i:s")."' )";
					$exec_ins = mysql_query($sql_ins);
					if ($exec_ins == 0) $error.= " erro 6 ";
				}
			}

			$aviso = TRANS('MSG_INCLUDE_NESTING_OK')."<br><a href='encerramento.php?numero=".$_POST['numero']."'>".TRANS('TXT_FINISH')."</a>";
		}

		$_SESSION['aviso'] = $aviso;
		$_SESSION['origem'] = "abertura.php";

		//print "<script>redirect('mensagem.php')</script>";
		print "<script>redirect('mostra_consulta.php?numero=".$_POST['numero']."&justOpened=true');</script>";
	}

print "</TABLE>";
print "</FORM>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idAssentamento','','Assentamento',1);

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

-->
</script>
<?php 
print "</BODY>";
print "</HTML>";
?>
