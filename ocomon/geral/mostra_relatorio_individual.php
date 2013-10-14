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


print "<html>";
print "<body>";

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

        //$query  = "SELECT * FROM ocorrencias WHERE numero='$numero'";
	$query = $QRY["ocorrencias_full_ini"]." where numero in (".$_GET['numero'].") order by numero";
	$resultado = mysql_query($query);
	$row = mysql_fetch_array($resultado);
	$linhas = mysql_numrows($resultado);


	$query2 = "select * from assentamentos where ocorrencia='".$_GET['numero']."'";
	$resultado2 = mysql_query($query2);
	$linhas2=mysql_numrows($resultado2);

	if ($linhas==0)
	{
		$_SESSION['aviso'] = "Nenhuma_ocorrencia_localizada.";
		$_SESSION['origem'] = "relatorio_individual.php";

		print "<script>redirect('mensagem.php')</script>";
	}

	$linhas = 0;

	print "<BR><B>OcoMon - Relatório para atendimento.</B><BR>";

	print "<TABLE border='0' align='center' width='100%'>";
		print "<TR>";
		print "<hr>";
			print "<TD width='20%' align='left'><b>Número:</b></TD>";
			print "<TD colspan='3' width='80%' align='left'>".$row['numero']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left'><b>Problema:<b></TD>";
			print "<TD style=\"{text-align:justify;}\" width='30%' align='left'>".$row['problema']."</TD>";
			print "<TD width='20%' align='left'><b>Área de Atendimento:</b></TD>";
			print "<TD width='30%' align='left'>".$row['area']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left' valign='top'><b>Descrição:</b></TD>";
			print "<TD  colspan='3' width='80%' align='left'>".nl2br($row['descricao'])."</TD>";
		print "</TR>";

		if ($linhas2!=0)
		{
		$i=0;
			while ($i < $linhas2)
			{
				$OP = mysql_result($resultado2,$i,4);
				$qryOP = "select * from usuarios where user_id = ".$OP."";
				$execOP = mysql_query($qryOP);
				$rowOP = mysql_fetch_array($execOP) or die($qryOP);
				$countAssentamento = $i+1;
				print "<TR>";
					print "<TD width='20%' align='left' valign='top'>Assentamento ".$countAssentamento." de ".$linhas2." por ".
							"".$rowOP['nome']." em ".formatDate(mysql_result($resultado2,$i,3))."</TD>";
					print "<TD style=\"{text-align:justify;}\" colspan='3' width='40%' align='left' valign='top'>".nl2br(mysql_result($resultado2,$i,2))."</TD>";
				print "</TR>";
			$i++;
			}
		}
		print "<TR>";
			print "<TD width='20%' align='left' valign='top'><b>Unidade:</b></TD>";
			print "<TD width='30%' align='left' valign='top'>".$row['unidade']."</TD>";

			print "<TD width='20%' align='left' valign='top'><b>Etiqueta do equipamento:</b></TD>";
			print "<TD width='30%' align='left' valign='top'>".$row['etiqueta']."</TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='20%' align='left'><b>Contato:</b></TD>";
			print "<TD width='30%' align='left'>".$row['contato']."</TD>";
			print "<TD width='20%' align='left'><b>Ramal:</b></TD>";
			print "<TD width='30%' align='left'>".$row['telefone']."</TD>";
		print "</tr>";
		print "<TR>";
			print "<TD width='20%' align='left'><b>Local:</b></TD>";
			print "<TD width='30%' align='left'>".$row['setor']."</TD>";
			print "<TD width='20%' align='left'><b>Operador:</b></TD>";
			print "<TD width='30%' align='left'>".$row['nome']."</TD>";
		print "</TR>";

		if ($row['status_cod']== 4)
		{
			print "<TR>";
				print "<TD width='20%' align='left'><b>Data de abertura:</b></TD>";
				print "<TD width='30%' align='left'>".formatDate($row['data_abertura'])."</TD>";
				print "<TD width='20%' align='left'><b>Data de encerramento:</b></TD>";
				print "<TD width='30%' align='left'>".formatDate($row['data_fechamento'])."</TD>";
			print "</tr>";
			print "<tr>";
				print "<TD width='20%' align='left'><b>Status:</b></TD>";
				print "<TD colspan='3' width='80%' align='left' bgcolor='white'>".$row['chamado_status']."</TD>";
			print "</TR>";
		}
		else
		{
			print "<TR>";
				print "<TD width='20%' align='left'><b>Data de abertura:</b></TD>";
				print "<TD width='30%' align='left'>".formatDate($row['data_abertura'])."</TD>";
				print "<TD width='20%' align='left'><b>Status:<b></TD>";
				print "<TD width='30%' align='left' bgcolor='white'>".$row['chamado_status']."</TD>";
			print "</TR>";
		}

		print "<TR>";
		print "<TABLE border='0'  align='center' width='100%'>";
		print "<hr>";
			print "<tr>";
				print "<TD width='20%' align='left'><b>Atendimento em:</b></TD>";
				print "<TD width='30%' align='left'>&nbsp;</TD>";
				print "<TD width='20%' align='left'><b>Operador:</b></TD>";
				print "<TD width='30%' align='left'>&nbsp;</TD>";
			print "</tr>";
		print "</table>";
		print "<hr>";
		print "</TR>";


		print "<TR>";
		print "<TABLE border='0'  align='center' width='100%'>";
		for ($i =0; $i<10; $i++)
		{
			print "<tr>";
				print "<TD class='bordaprint' colspan='4' width='100%' align='center'>&nbsp;";
				print "</TD>";
			print "</tr>";
		}
		print "</table>";
		print "</TR>";


		print "<TR>";
			print "<TABLE border='0'  align='center' width='100%'>";
			//print "<hr>";
				print "<tr><td colspan='4'>&nbsp;</td></tr>";
				print "<tr>";
					print "<TD width='20%' align='left'>Nome do usuário:</TD>";
					print "<TD width='30%' align='left'>&nbsp;</TD>";
					print "<TD width='20%' align='left'>Assinatura do usuário:</TD>";
					print "<TD width='30%' align='left'>&nbsp;</TD>";
				print "</tr>";
			print "</TABLE>";
			print "<hr>";
		print "</TR>";
	print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>