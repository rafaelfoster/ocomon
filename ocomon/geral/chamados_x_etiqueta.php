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
	print "	<B><center>::: ".TRANS('TLT_REPORT_CALL_FOR_EQUIP')." :::</center></B><BR><BR>";
	print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1'>";
	print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR.">";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_AREA').":</td>";
	print "					<td class='line'><Select name='area' class='select'>";
	print "							<OPTION value=-1 selected>".TRANS('OPT_ALL_2')."</OPTION>";
									$query= "SELECT * FROM sistemas WHERE sis_status not in (0) order by sistema";
									$resultado=mysql_query($query);
									$linhas = mysql_num_rows($resultado);
									while($row=mysql_fetch_array($resultado))
									{
										$sis_id=$row['sis_id'];
										$sis_name=$row['sistema'];
										print "<option value=$sis_id>$sis_name</option>";
									} // while
	print "		 				</Select>";
	print "					 </td>";
	print "				</tr><tr>";

	print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_BEGIN').":</td>";
	print "					<td class='line'><INPUT id='idD_ini' name='d_ini' class='data' value='01-".date("m-Y")."'></td>";
	print "				</tr>";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_FINISH').":</td>";
	print "					<td class='line'><INPUT id='idD_fim' name='d_fim' class='data' value='".date("d-m-Y")."'></td>";
	print "				</tr>";

	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">".TRANS('FIELD_REPORT_TYPE').":</td>";
	print "					<td class='line'><select name='saida' class='data'>";
	print "							<option value=-1 selected>".TRANS('SEL_PRIORITY_NORMAL')."</option>";
//	print "							<option value=1>Relatório 1 linha</option>";
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
else //if $ok==Pesquisar
{

	$hora_inicio = ' 00:00:00';
	$hora_fim = ' 23:59:59';
	$query = "SELECT count(*) AS total, I.inst_nome as instituicao, I.inst_cod as inst_cod, ".
				"O.equipamento as etiqueta, S.sistema as area, L.local as local ".
			"FROM ocorrencias AS O, instituicao as I, sistemas as S, localizacao as L ".
			"WHERE (instituicao IS NOT NULL OR instituicao NOT IN (0)) AND equipamento NOT IN (0) ".
				"and O.instituicao = I.inst_cod and S.sis_id =O.sistema and L.loc_id = O.local";

	if (isset($_POST['area']) and ($_POST['area'] != -1)) // variavel do select name
	{
		$query .= " and O.sistema = ".$_POST['area']."";
	}

	if ((!($_POST['d_ini'])) || !$_POST['d_fim'])
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
			$query .= " and O.data_abertura >= '".$d_ini_completa."' and O.data_abertura <= '".$d_fim_completa."' ".
					"GROUP BY inst_nome, equipamento,area ORDER BY total DESC";
			$resultado = mysql_query($query);
			$linhas = mysql_num_rows($resultado);

			if($linhas==0)
			{
				print "<script>window.alert('".TRANS('MSG_ALERT_NO_PERIOD')."'); history.back();</script>";
			}
			else //if($linhas==0)
			{
				$campos=array();
				switch($_POST['saida'])
				{
					case -1:
						echo "<br><br>";
						$background = '#339966';
						print "<table class='centro' cellspacing='0' border='1' align='center'>";
						print "<tr><td bgcolor='".$background."' colspan='5' align='center'><b>".TRANS('TLT_REP_TOT_CALL_LABEL')."</b><br>".TRANS('FIELD_PERIOD_2').": ".$d_ini." a ".$d_fim."</td>";
						print "<tr><td bgcolor='".$background."' width='255'><B>   ".TRANS('COL_AMOUNT')."</td>".
								"<td bgcolor='".$background."' ><B>".TRANS('FIELD_INSTITUTION')."</td>".
								"<td bgcolor='".$background."' ><B>".TRANS('FIELD_TAG').";</td>".
								"<td bgcolor='".$background."' ><B>".TRANS('FIELD_LOCALIZATION')."</td>".
								"<td bgcolor='".$background."' ><B>".TRANS('FIELD_AREA_2')."</td>".
								"</tr>";
						$conta = 0;
						while ($row = mysql_fetch_array($resultado))
						{
							$conta +=$row['total'];
							print "<tr><td class='line'>".$row['total']."</td>".
									"<td class='line'>".$row['instituicao']."</td>".
									"<td class='line'><a onClick= \"popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst=".$row['inst_cod']."&comp_inv=".$row['etiqueta']."&popup=true')\">".$row['etiqueta']."</a></td>".
									"<td class='line'>".$row['local']."</td>".
									"<td class='line'>".$row['area']."</td>".
								"</tr>";
						}//while

						print "<tr><td bgcolor='".$background."' align='center'><b>".TRANS('FIELD_TOTAL_CALL').":</b></TD><td bgcolor='".$background."' colspan='4' align='center'><b>".$conta."</b></td></tr>";
						print "</table>";

						break;
				} // switch
			} //if($linhas==0)
				//print $query;
		}//if  $d_ini_completa <= $d_fim_completa
		else
		{
			$aviso = "A data final não pode ser menor do que a data inicial.";
			print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
	}//if ((empty($d_ini)) and (empty($d_fim)))
	//print "<br>".$query;
}//if $ok==Pesquisar
?>
<script type='text/javascript'>

	function popup_alerta(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Alerta','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}

</script>
