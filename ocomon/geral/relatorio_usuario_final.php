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
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	if (!isset($_POST['ok'])) {

		print "<script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script>";
		print "	<BR><BR>";
		print "	<B><center>::: ".TRANS('TTL_CALL_OPEN_USER_FINISH_2')." :::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit=\"return valida();\">";
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_AREA').":</td>";
		print "					<td class='line'><Select name='area' class='select'>";
		print "							<OPTION value=-1 selected>".TRANS('OPT_ALL')."</OPTION>";
										$qryArea="select * from sistemas where sis_status not in (0) order by sistema";
										$execArea=mysql_query($qryArea);
										$regAreas = mysql_num_rows($execArea);
										while($rowArea=mysql_fetch_array($execArea))
										{
											print "<option value=".$rowArea['sis_id']."";
											if ($rowArea['sis_id']==$_SESSION['s_area']) print " selected";
											print ">".$rowArea['sistema']."</option>";
										} // while
		print "		 				</Select>";
		print "					 </td>";
		print "				</tr>";

		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_BEGIN').":</td>";
		print "					<td class='line'><INPUT name='d_ini' class='data' id='idD_ini' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_ini,'dd-mm-yyyy',this)\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_FINISH').":</td>";
		print "					<td class='line'><INPUT name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_fim,'dd-mm-yyyy',this)\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
		print "				</tr>";

		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('FIELD_CURRENT_MONTH')."</td>";
		print "					<td class='line'><INPUT type='checkbox' name='mesAtual' id='idMesAtual'></td>";
		print "				</tr>";


		print "		</TABLE><br>";
		print "		<TABLE align='center'>";
		print "			<tr>";
		print "	            <td class='line'>";
		print "					<input type='submit' class='button' value='".TRANS('BT_SEARCH')."' name='ok' >";//onclick='ok=sim'
		print "	            </TD>";
		print "	            <td class='line'>";
		print "					<INPUT type='reset'  class='button' value='".TRANS('BT_CLEAR')."' name='cancelar'>";
		print "				</TD>";
		print "			</tr>";
		print "	    </TABLE>";
		print " </form>";
		?>
				<script language="JavaScript">

					function valida(){
						var ok = validaForm('idData_ini','DATA-','Data Inicial',0);
						if (ok) var ok = validaForm('idData_fim','DATA-','Data Final',0);
						return ok;
					}
				//-->
				</script>
		<?
	//if $ok!=Pesquisar
	} else { //if $ok==Pesquisar

		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';

		$query = "select count(*) as qtd, o.*, u.*, a.*, n.* from ocorrencias as o left join usuarios as u on o.aberto_por = u.user_id ".
					"left join sistemas as a on a.sis_id = u.AREA left join nivel as n on nivel_cod = u.nivel ".
					"WHERE a.sis_atende=0 AND n.nivel_cod=3 ";

		if (isset($_POST['area']) and ($_POST['area'] != -1) and (($_POST['area'] == $_SESSION['s_area'])||($_SESSION['s_nivel']==1)))
		{
			$query .= " and o.sistema = ".$_POST['area']."";
			$getAreaName = "select * from sistemas where sis_id = ".$_POST['area']."";
			$exec = mysql_query($getAreaName);
			$rowAreaName = mysql_fetch_array($exec);
			$nomeArea = $rowAreaName['sistema'];

		} else
		if ($_SESSION['s_nivel']!=1){
			print "<script>window.alert('".TRANS('MSG_CONSULT_FOR_YOU_AREA')."');</script>";
			print "<script>history.back();</script>";
			exit;
		} else {
			$nomeArea = "TODAS";
		}


		if (((!isset($_POST['d_ini'])) and (!isset($_POST['d_fim']))) and !isset($_POST['mesAtual'])) {
			print "<script>window.alert('".TRANS('MSG_PERIOD_INFO')."!'); history.back();</script>";
		} else {

			$d_ini = $_POST['d_ini'];
			$d_fim = $_POST['d_fim'];
			if (isset($_POST['mesAtual'])) {
			//date("Y-m-d H:i:s");
				$d_ini = "01-".date("m-Y");
				$d_fim = date("d-m-Y");
			}

			$d_ini = str_replace("-","/",$d_ini);
			$d_fim = str_replace("-","/",$d_fim);
			$d_ini_nova = converte_dma_para_amd($d_ini);
			$d_fim_nova = converte_dma_para_amd($d_fim);

			$d_ini_completa = $d_ini_nova.$hora_inicio;
			$d_fim_completa = $d_fim_nova.$hora_fim;


			if($d_ini_completa <= $d_fim_completa) {

				print "<table class='centro' cellspacing='0' border='0' align='center'>";
					print "<tr><td colspan='2'><b>".TRANS('TTL_PERIOD_FROM')." ".$d_ini." a ".$d_fim."</b></td></tr>";
				print "</table>";


				$query .= " AND o.data_abertura >= '".$d_ini_completa."' and o.data_abertura <= '".$d_fim_completa."' ".
							"GROUP BY u.nome ORDER BY qtd desc,nome";

				$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
				$linhas = mysql_num_rows($resultado);

				if ($linhas==0) {
					$aviso = "".TRANS('MSG_NOT_DATA_PERIOD')." ";
					echo "<script>mensagem('".$aviso."'); redirect('relatorio_usuario_final.php');</script>";
				} else { //if($linhas==0)
					echo "<br><br>";
					$background = '#CDE5FF';
					print "<p align='center'>".TRANS('TXT_IT_VERIFIES')." <a onClick=\"javascript:popup_alerta('relatorio_slas_usuario_final.php?ini=".$d_ini_completa."&end=".$d_fim_completa."&area=".$_POST['area']."')\"><font color='blue'>".TRANS('TLT_REPORT_SLAS')."</font></a> ".TRANS('TXT_OLD').".</p>";
					print "<p align='center'><b>".TRANS('TTL_CALL_OPEN_USER_AREA').": ".$nomeArea." </b></p>";
					print "<table class='centro' cellspacing='0' border='1' align='center'>";

					print "<tr><td bgcolor='".$background."'><B>".TRANS('COL_AMOUNT')."</td>".
							"<td bgcolor='".$background."' ><B>".TRANS('FIELD_USER')."</td>".
							"<td bgcolor='".$background."' ><B>".TRANS('COL_ATTEN_AREA')."</td>".
						"</tr>";
					$total = 0;
					while ($row = mysql_fetch_array($resultado)) {
						$qryRow = "SELECT numero FROM ocorrencias where aberto_por = ".$row['user_id']." order by numero";
						$execqryRow = mysql_query($qryRow) or die(TRANS('ERR_QUERY'));
						while ($chamados = mysql_fetch_array($execqryRow)) {
							$chamadosUser[]= $chamados['numero'];
						}
						$listaChamados = putcomma($chamadosUser);
						$chamadosUser = "";

						print "<tr>";
						print "<td class='line'><a onClick=\"javascript: popup_alerta('mostra_chamados.php?numero=".$listaChamados."')\">".$row['qtd']."</a></td><td class='line'>".$row['nome']."</td><td class='line'>".$row['sistema']."</td>";

						print "</tr>";
						$total+=$row['qtd'];
					}

					print "<tr><td colspan='2'><b>".TRANS('TOTAL')."</b></td><td class='line'><b>".$total."</b></td></tr>";

				} //if($linhas==0)
			} else {

				$aviso = "".TRANS('MSG_DATE_FINISH_UNDERAGE_DATE_BEGIN')."";
				print "<script>mensagem('".$aviso."'); redirect('relatorio_usuario_final.php');</script>";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
	}
		?>
			<script type='text/javascript'>
			<!--
				function popup(pagina)	{ //Exibe uma janela popUP
					x = window.open(pagina,'popup','width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
					//x.moveTo(100,100);
					x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
					return false
				}

				function popup_alerta(pagina)	{ //Exibe uma janela popUP
					x = window.open(pagina,'_blank','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
					//x.moveTo(100,100);
					x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
					return false
				}

				function valida(){
					var ok = validaForm('idD_ini','DATA-','Data Inicial',0);
					if (ok) var ok = validaForm('idD_fim','DATA-','Data Final',0);

					if (ok) submitForm();

					return ok;
				}


			-->
			</script>
		<?

	print "</BODY>";
	print "</html>";
?>
