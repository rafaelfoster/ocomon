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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$cab = new headers;
	$cab->set_title(TRANS('TTL_INVMON'));

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$hoje = date("Y-m-d H:i:s");


	$cor  = TD_COLOR;
	$cor1 = TD_COLOR;
	$cor3 = BODY_COLOR;

	$queryB = "SELECT count(*) from equipamentos";
	$resultadoB = mysql_query($queryB);
	$total = mysql_result($resultadoB,0);

	// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
	$query= "SELECT count( i.inst_nome ) AS qtd_inst, i.inst_nome AS instituicao, i.inst_cod as inst_cod,
			concat( count( * ) / ".$total." * 100, '%' ) AS porcento
			FROM instituicao AS i, equipamentos AS c
			WHERE c.comp_inst = i.inst_cod
			GROUP BY i.inst_nome order by qtd_inst desc";
		//and (comp_tipo_equip=1 or comp_tipo_equip=2)
		$resultado = mysql_query($query);
		$linhas = mysql_num_rows($resultado);

       print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";

		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td width='60%' align='center'><b>".TRANS('TTL_ESTAT_EQUIP_FOR_UNIT').":</b></td></tr>";


		print "<td class='line'>";
        	print "<fieldset><legend>".TRANS('SUBTTL_INSTIT_BOARD')."</legend>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";
			print "<TR><TD bgcolor='".$cor3."'><b>".TRANS('OCO_FIELD_UNIT')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_QTD')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_PORCENTEGE')."</TD></tr>";


		$i=0;
		$j=2;

		while ($row = mysql_fetch_array($resultado)) {
			$color =  BODY_COLOR;
			$j++;
			print "<TR>";
			print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_inst[]=".$row['inst_cod']."&ordena=local,etiqueta' title='".TRANS('HNT_LIST_EQUIP_CAD_FOR_UNIT')."'>".$row['instituicao']."</a></TD>";
			print "<TD bgcolor='".$color."'>".$row['qtd_inst']."</TD>";
			print "<TD bgcolor='".$color."'>".round($row['porcento'],2)."</TD>";
			print "</TR>";
			$i++;
		}


		print "<TR><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b>".TRANS('TOTAL').": ".$total."</TD><TD bgcolor='".$cor3."'><b>".TRANS('TXT_100')."</b></TD></tr>";
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

		print "<tr><td class='line' align='center'><a href='".$_SERVER['PHP_SELF']."' target='_blank')\">".TRANS('NEW_SCREEN')."</a></TD></tr>";
		print "<tr><td width='80%' align='center'><b>".TRANS('SLOGAN_OCOMON')." <a href='http://www.unilasalle.edu.br' target='_blank'>".TRANS('COMPANY')."</a>.</b></td></tr>";
		print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>