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

if (!isset($_POST['ok']))
{
	print "<html>";
	print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
	print "	<BR><BR>";
	print "	<B><center>::: Quadro de chamados por área de atendimento :::</center></B><BR><BR>";
	print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit='return valida()'>";
	print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR.">";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Área Responsável:</td>";
	print "					<td class='line'><Select name='area' class='select'>";
	print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
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

	print "					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
	print "					<td class='line'><INPUT name='d_ini' class='data' id='idD_ini' value='01-".date("m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_ini,'dd-mm-yyyy',this)\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
	print "					<td class='line'><INPUT name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_fim,'dd-mm-yyyy',this)\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";

	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Tipo de relatório:</td>";
	print "					<td class='line'><select name='saida' class='data'>";
	print "							<option value=-1 selected>Normal</option>";
	print "						</select>";
	print "					</td>";
	print "				</tr>";
	print "		</TABLE><br>";
	print "		<TABLE align='center'>";
	print "			<tr>";
	print "	            <td class='line'>";
	print "					<input type='submit' value='Pesquisar' name='ok' class='button'>";
	print "	            </TD>";
	print "	            <td class='line'>";
	print "					<INPUT type='reset' value='Limpar campos' name='cancelar' class='button'>";
	print "				</TD>";
	print "			</tr>";
	print "	    </TABLE>";
	print " </form>";
	print "</BODY>";
	print "</html>";
}//if $ok!=Pesquisar
else //if $ok==Pesquisar
{

	$hora_inicio = ' 00:00:00';
	$hora_fim = ' 23:59:59';

	if ((!isset($_POST['d_ini'])) || (!isset($_POST['d_fim'])))
	{
		print "<script>window.alert('O período deve ser informado!'); history.back();</script>";
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
				print "<tr><td colspan='2'><b>PERÍODO DE ".$d_ini." a ".$d_fim."</b></td></tr>";
			print "</table>";


			print "<p align='center'>QUADRO GERAL DE CHAMADOS DO PERÍODO</p>";
			print "<table cellspacing='0' border='1' align='center'>";
			print "<tr><td class='line'><b>ÁREA DE ATENDIMENTO</b></TD><td colspan='3' align='center'><b>CHAMADOS</b></td></tr>";
			print "<tr><td class='line'>&nbsp;</td><td class='line'>ABERTOS</td><td class='line'>FECHADOS</td><td class='line'>CANCELADOS</td></tr>";


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

				$query_fe_sw = "SELECT count(*) AS fechados, s.sistema AS area
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

				print "<tr><td class='line'>".$row['sistema']."</td><td class='line'>".NVL($row_ab_sw['abertos'])."</td><td class='line'>".NVL($row_fe_sw['fechados'])."</td><td class='line'>".NVL($row_ca_sw['cancelados'])."</td></tr>";
			}

			print "<tr><td class='line'><b>TOTAL</b></td><td class='line'><b>".NVL($totalAbertos)."</b></td><td class='line'><b>".NVL($totalFechados)."</b></td><td class='line'><b>".NVL($totalCancelados)."</b></td></tr>";
			print "</table><br><br>";

		}//if  $d_ini_completa <= $d_fim_completa
		else
		{
			print "<script>window.alert('A data final não pode ser menor do que a data inicial!'); history.back();</script>";
		}
	}//if ((empty($d_ini)) and (empty($d_fim)))
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