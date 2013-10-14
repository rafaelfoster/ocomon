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

	$queryB = "SELECT count(*) from equipamentos";
	$resultadoB = mysql_query($queryB);
	$total = mysql_result($resultadoB,0);



	$query= "select count(m.marc_nome) as qtd, count(*)/".$total."*100 as porcento,
				t.tipo_nome as equipamento, count(f.fab_nome) as qtd_fabricante,
				f.fab_nome as fabricante,  m.marc_nome as modelo, m.marc_cod as tipo_modelo from equipamentos as c,
				marcas_comp as m, tipo_equip as t, fabricantes as f
			WHERE c.comp_marca=m.marc_cod and comp_tipo_equip=t.tipo_cod and
				comp_fab = f.fab_cod group by fabricante,modelo order by qtd desc limit 0,10";


	$resultado = mysql_query($query);
	$linhas = mysql_num_rows($resultado);

	print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='".$cor3."'>";

		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td width=80% align=center><b>Os 10 modelos de equipamento mais cadastrados no sistema:</b></td></tr>";


		print "<td class='line'>";
		print "<fieldset><legend>10 mais</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='".$cor3."'>";
			print "<TR><TD bgcolor='".$cor3."'><b>Ranking</TD>".
				"<TD bgcolor='".$cor3."'><b>Equipamento</TD>".
				"<TD bgcolor='".$cor3."'><b>Modelo</TD>".
				"<TD bgcolor='".$cor3."'><b>Quantidade</TD>".
				"<TD bgcolor='".$cor3."'><b>Percentual</TD></tr>";
		$i=1;
		$j=2;
		while ($row = mysql_fetch_array($resultado)) {
			$color =  BODY_COLOR;
			$j++;
			print "<TR>";

			print "<TD bgcolor='".$color."'>".$i++.".º</TD>";
			print "<TD bgcolor='".$color."'>".$row['equipamento']."</TD>";
			print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_marca=".$row['tipo_modelo']."&ordena=fab_nome,modelo,local,etiqueta' title='Exibe a relação de equipamentos desse modelo cadastrados no sistema.'>".$row['fabricante']." ".$row['modelo']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['qtd']."</TD>";
			print "<TD bgcolor='".$color."'>".$row['porcento']."%</TD>";
			print "</TR>";
			//$i++;
		}
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