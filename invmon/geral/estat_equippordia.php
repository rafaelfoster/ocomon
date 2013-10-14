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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$cab = new headers;
	$cab->set_title(TRANS("html_title"));

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$hoje = date("Y-m-d H:i:s");

	$cor  = TD_COLOR;
	$cor1 = TD_COLOR;
	$cor3 = BODY_COLOR;

	$queryB = "SELECT count(*) total from equipamentos";
	$resultadoB = mysql_query($queryB);
	$rowTotal = mysql_fetch_array($resultadoB);
	$total = $rowTotal['total'];

	$queryC = "SELECT count(extract(day from comp_data)) as QTD_DIA, extract(day from comp_data)as DIA, ".
				"extract(month from comp_data) as MES , extract(year from comp_data) as ANO, ".
				"concat(date_format(comp_data,'%d'),'/',date_format(comp_data,'%m'),'/',extract(year from comp_data))as tipo_data ".
				"FROM equipamentos group by ano,mes,dia order by ano desc ,mes desc,dia desc";

	$resultadoC = mysql_query($queryC);
        $linhasC = mysql_num_rows($resultadoC);

	//Tabela de quantidade de equipamentos cadastrados por dia

	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";

		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td width='60%' align='center'><b>Quantidade de equipamentos cadastrados por dia:</b></td></tr>";


        	print "<td class='line'>";
        	print "<fieldset><legend>Cadastros por dia</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";
        	print "<TR><TD bgcolor='".$cor3."'><b>Data</TD><TD bgcolor='".$cor3."'><b>Quantidade</TD></tr>";
		$i=0;
		$j=2;


		while ($rowC = mysql_fetch_array($resultadoC)) {
			$color =  BODY_COLOR;
			$j++;
			print "<TR>";

			$searchDate = converte_dma_para_amd($rowC['tipo_data']);
			print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_data=".$searchDate."&ordena=equipamento,modelo,local,etiqueta'>".$rowC['DIA']."/".$rowC['MES']."/".$rowC['ANO']."</a></TD>";
			print "<TD bgcolor='".$color."'>".$rowC['QTD_DIA']."</TD>";
			print "</TR>";
			$i++;
		}
        	print "<TR><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b>Total: ".$total."</TD></tr>";
		print "</TABLE>";
		print "</fieldset>";

		print "<TABLE width='80%' align='center'>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";

		print "</TABLE>";


		print "<TABLE width='80%' align='center'>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";

		print "<tr><td width='80%' align='center'><b>Sistema em desenvolvimento pelo setor de Helpdesk  do <a href='http://www.unilasalle.edu.br' target='_blank'>Unilasalle</a>.</b></td></tr>";
		print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>