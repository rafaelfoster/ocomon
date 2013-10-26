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
	include ("../../includes/functions/funcoes_jquery.php");
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	if (!isset($_POST['ok']) && !isset($_GET['action']))
	{
		print "<html>";
		print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
		print "	<BR><BR>";
		print "	<B><center>::: ".TRANS('TTL_BOARD_CALL_AREA_ATTEND')." :::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit='return valida()'>";
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR.">";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_AREA').":</td>";
		print "					<td class='line'><Select name='area' class='select'>";
		print "							<OPTION value=-1 selected>".TRANS('OPT_ALL')."</OPTION>";
										$query="select * from sistemas where sis_status not in (0) order by sistema";
										$resultado=mysql_query($query);
										$linhas = mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											$sis_id=$row['sis_id'];
											$sis_name=$row['sistema'];
											print "<option value='".$sis_id."'>".$sis_name."</option>";
										} // while
		print "		 				</Select>";
		print "					 </td>";
		print "				</tr>";

		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_BEGIN').":</td>";
		print "					<td class='line'><INPUT name='d_ini' class='data' id='idD_ini' value='01-".date("m-Y")."'></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_FINISH').":</td>";
		print "					<td class='line'><INPUT name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'></td>";
		print "				</tr>";

		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('FIELD_REPORT_TYPE').":</td>";
		print "					<td class='line'><select name='saida' class='data'>";
		print "							<option value=-1 selected>".TRANS('SEL_PRIORITY_NORMAL')."</option>";
		print "						</select>";
		print "					</td>";
		print "				</tr>";
		print "		</TABLE><br>";
		print "		<TABLE align='center'>";
		print "			<tr>";
		print "	            <td class='line'>";
		print "					<input type='submit' value='".TRANS('BT_SEARCH')."' name='ok' class='button'>";
		print "	            </TD>";
		print "	            <td class='line'>";
		print "					<INPUT type='reset' value='".TRANS('BT_CLEAR')."' name='cancelar' class='button'>";
		print "				</TD>";
		print "			</tr>";
		print "	    </TABLE>";
		print " </form>";
		print "</BODY>";
		print "</html>";
	}//if $ok!=Pesquisar
	else
	if (isset($_POST['ok'])) {

		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';

		if ((!isset($_POST['d_ini'])) || (!isset($_POST['d_fim'])))
		{
			print "<script>window.alert('".TRANS('MSG_ALERT_PERIOD')."'); history.back();</script>";
		}
		else
		{
			$d_ini = str_replace("-","/",$_POST['d_ini']);
			$d_fim = str_replace("-","/",$_POST['d_fim']);
			$d_ini_nova = converte_dma_para_amd($d_ini);
			$d_fim_nova = converte_dma_para_amd($d_fim);

			$d_ini_completa = $d_ini_nova.$hora_inicio;
			$d_fim_completa = $d_fim_nova.$hora_fim;


			if($d_ini_completa <= $d_fim_completa)
			{

				print "<table class='centro' cellspacing='0' border='0' align='center' >";
					print "<tr><td colspan='2'><b>".TRANS('TTL_PERIOD_FROM')." ".$d_ini." a ".$d_fim."</b></td></tr>";
				print "</table>";


				print "<p align='center'>".TRANS('TTL_GENERAL_BOARD_CALL_ATTEND')."</p>";
				print "<table cellspacing='0' border='1' align='center'>";
				print "<tr bgcolor='#339966'><td class='line'><b>".TRANS('COL_ATTEN_AREA')."</b></TD><td colspan='3' align='center'><b>".TRANS('COL_CALL')."</b></td></tr>";
				print "<tr bgcolor='#339966'><td >&nbsp;</td><td class='line'>".TRANS('COL_OPENED')."</td><td class='line'>".TRANS('COL_CLOSED')."</td><td class='line'>".TRANS('COL_CANCELLED')."</td></tr>";


				if (isset($_POST['area']) && ($_POST['area'] == -1)) {
					$query_areas= "select * from sistemas where sis_status not in (0) order by sistema";
				} else
				if (isset($_POST['area']) && $_POST['area'] != -1)
					$query_areas="select * from sistemas where sis_id in (".$_POST['area'].") order by sistema";

				$exec_qry_areas = mysql_query($query_areas);

				$totalAbertos = 0;
				$totalFechados = 0;
				$totalCancelados = 0;
				while ($row = mysql_fetch_array($exec_qry_areas)){

					$query_ab_sw = "SELECT count(*) AS abertos, s.sistema AS area
										FROM ocorrencias AS o, sistemas AS s
										WHERE o.sistema = s.sis_id AND o.data_abertura >= '".$d_ini_completa."' AND
										o.data_abertura <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].") group by area";
					$exec_ab_sw = mysql_query($query_ab_sw);
					$row_ab_sw = mysql_fetch_array($exec_ab_sw);

					$query_fe_sw = "SELECT count(*) AS fechados, s.sistema AS area, s.sis_id
										FROM ocorrencias AS o, sistemas AS s
										WHERE o.sistema = s.sis_id AND o.data_fechamento >= '".$d_ini_completa."' AND
										o.data_fechamento <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].")  group by area";
					$exec_fe_sw = mysql_query($query_fe_sw);
					$row_fe_sw = mysql_fetch_array($exec_fe_sw);

					$query_ca_sw = "SELECT count(*) AS cancelados, s.sistema AS area
										FROM ocorrencias AS o, sistemas AS s
										WHERE o.sistema = s.sis_id AND o.data_abertura >= '".$d_ini_completa."' AND
										o.data_abertura <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].") and
										status in (12) group by area";
					$exec_ca_sw = mysql_query($query_ca_sw);
					$row_ca_sw = mysql_fetch_array($exec_ca_sw);

					$totalAbertos+=$tt_ab = $row_ab_sw['abertos'];
					$totalFechados+=$tt_fe = $row_fe_sw['fechados'];
					$totalCancelados+=$tt_ca = $row_ca_sw['cancelados'];

					//print "<tr><td class='line'>".$row['sistema']."</td><td class='line'>".NVL($row_ab_sw['abertos'])."</td><td class='line'>".NVL($row_fe_sw['fechados'])."</td><td class='line'>".NVL($row_ca_sw['cancelados'])."</td></tr>";
					print "<tr><td class='line'>".$row['sistema']."</td>".
							"<td class='line'>".NVL($row_ab_sw['abertos'])."</td>".
							"<td class='line'><a onClick=\"javascript: popup_alerta('".$_SERVER['PHP_SELF']."?action=list&area=".$row_fe_sw['sis_id']."&date1=".$d_ini_completa."&date2=".$d_fim_completa."')\">".NVL($row_fe_sw['fechados'])."</a></td>".
							"<td class='line'>".NVL($row_ca_sw['cancelados'])."</td>".
						"</tr>";
				}

				print "<tr><td class='line'><b>".TRANS('TOTAL')."</b></td><td class='line'><b>".NVL($totalAbertos)."</b></td><td class='line'><b>".NVL($totalFechados)."</b></td><td class='line'><b>".NVL($totalCancelados)."</b></td></tr>";
				print "</table><br><br>";

			}//if  $d_ini_completa <= $d_fim_completa
			else
			{
				print "<script>window.alert('".TRANS('MSG_COMPARE_DATE')."'); history.back();</script>";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
	} else

	if (isset($_GET['action']) && $_GET['action']=='list') {

			$query = "SELECT *, s.sistema AS area, l.local as localizacao, p.problema as prob_desc
					FROM ocorrencias AS o, sistemas AS s, usuarios as u, problemas as p, localizacao as l
					WHERE o.sistema = s.sis_id and o.operador = u.user_id and o.problema = p.prob_id and o.local = l.loc_id
						AND o.data_fechamento >= '".$_GET['date1']."' AND
						o.data_fechamento <= '".$_GET['date2']."' and s.sis_id in (".$_GET['area'].") ";
			$exec = mysql_query($query) or die($query);

			$linhas = mysql_num_rows($exec);

			print "<table border='0' cellspacing='1' summary=''";
			print "<TR>";
			print "<TD colspan='3' align='left' ><B>".TRANS('FOUND')." ".$linhas." ".TRANS('TXT_REG_OF_CLOSED_CALLS').":</B></TD>";
			print "</tr>";
			print "<tr><td>&nbsp;</td></tr>";
			print "</table>";



			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";

			print "<TR class='header'>".
					"<TD class='line'>".TRANS('COL_AREA')."</TD>".
					"<TD class='line'>".TRANS('COL_NUMBER')."</TD>".
					"<TD class='line'>".TRANS('OCO_PROB')."</TD>".
					"<TD class='line'>".TRANS('COL_LOCAL')."</TD>".
					"<TD class='line'>".TRANS('TECHNICIAN')."</TD>".
					"<TD class='line'>".TRANS('FIELD_DATE_CLOSING')."</TD>";
			$i=0;
			$j=2;
			while ($rowlist = mysql_fetch_array($exec))
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
				print "<td class='line'>".$rowlist['area']."</td>";
				print "<td class='line'><a onClick=\"javascript: popup_alerta('mostra_consulta.php?numero=".$rowlist['numero']."')\">".NVL($rowlist['numero'])."</a></td>";
				print "<td class='line'>".NVL($rowlist['prob_desc'])."</td>";
				print "<td class='line'>".NVL($rowlist['localizacao'])."</td>";
				print "<td class='line'>".NVL($rowlist['nome'])."</td>";
				print "<td class='line'>".NVL(formatDate($rowlist['data_fechamento']))."</td>";


				print "</TR>";
			}
			print "</table>";
	}
	?>
        <script type='text/javascript'>

		function popup(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'popup','width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
			return false
		}

		function popup_alerta(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'_blank','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
			return false
		}

		function valida(){
			var ok = validaForm('idD_ini','DATA-','Data Inicial',1);
			if (ok) var ok = validaForm('idD_fim','DATA-','Data Final',1);

			if (ok) submitForm();

			return ok;
		}

        </script>

<?php 

	print "</BODY>";
	print "</html>";
?>
