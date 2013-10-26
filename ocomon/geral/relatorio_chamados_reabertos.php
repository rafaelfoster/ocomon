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

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	if (!isset($_POST['ok']) && !isset($_GET['action']))
	{
		print "<html>";
		print "	<BR><BR>";
		print "	<B><center>::: Relatorio de Chamados Reabertos :::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit='return valida()'>";
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR.">";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_BEGIN').":</td>";
		print "					<td class='line'><INPUT name='d_ini' class='data' id='idD_ini' value='01-".date("m-Y")."'></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_FINISH').":</td>";
		print "					<td class='line'><INPUT name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'></td>";
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


				$query = "SELECT ocorrencia, data, responsavel from assentamentos WHERE  assentamento like '%reaberto por%' AND data >= '".$d_ini_completa."' AND data <= '".$d_fim_completa."'";
				$sql = mysql_query($query) or die( mysql_error() );
				$linhas = mysql_num_rows($sql);
				print "<div align='center'>";
				print "<B><center>::: Relatorio de Chamados Reabertos :::</center></B><BR><BR>";
				print "<table border='1 class='tablesorter' align='center''>";
				print "<tr><td><b> Periodo: </b></td>";
				print "<td> $d_ini <b>a</b> $d_fim<br></td></tr>";
				print "<tr><td><b> Total: </b></td>";
				print "<td> $linhas</td></tr>";
				print "</table>";
				print "</div>";

				print "<br><BR>";

//				print "</table>";
				print "<table border='0' id='tabela_consultgeral' class='tablesorter' align='center' >";
				print "<thead>" ;

				$valign = " valign='top center' ";
				print "<TR>";
				print "<TH ".$valign." width='10%'>No.</TH>";
				print "<TH ".$valign." width='20%'>Data</TH>";
				print "<TH ".$valign." width='30%'>Reaberto Por</TH>";

				print "</TR>";
				print "</thead>";

				while ( $row = mysql_fetch_array($sql) ){

					$COD = $row['ocorrencia'];

					print "<tr onclick=\"mostra_ocorrencia($COD);\" align='center' STYLE=\"cursor: pointer;\">";
					print "<td> $COD </td>";
					print "<td>".$row['data']."</td>";
					print "<td>";

					$queryB = mysql_query("SELECT nome FROM usuarios WHERE user_id = '".$row['responsavel']."'");

					while ( $row2 = mysql_fetch_array($queryB) ){
						print $row2['nome'];
					}

					print "</td>";

					print "</tr>";

				} // Fim While

				print "</table>";

			}//if  $d_ini_completa <= $d_fim_completa
			else
			{
				print "<script>window.alert('".TRANS('MSG_COMPARE_DATE')."'); history.back();</script>";
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

		function mostra_ocorrencia(numero){
			var url = "/ocomon/geral/mostra_relatorio_reabertos.php?cod=" + numero;
//			alert(url)
			$(function(){
				$("<p> </p>").dialog({
					title: 'Descricao da Ocorrencia',
					height: 200,
                                	width: 650,
                                	modal: true,
					open: function(){
						$(this).load(url);
					}
				}); // Fim Dialog

			}); // Fim Jquery

		}; // Fim Funcao Mostra_ocorrencia

        </script>

<?php

	print "</BODY>";
	print "</html>";
?>
