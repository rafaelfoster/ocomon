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

	$queryInst = "SELECT * from instituicao order by inst_nome";
	$resultadoInst = mysql_query($queryInst);
	$linhasInst = mysql_num_rows($resultadoInst);

		print "<div id='Layer2' style='position:absolute; left:80%; top:176px; width:15%; height:40%; z-index:2; '>";//  <!-- Ver: overflow: auto    não funciona para o Mozilla-->
			print "<b>".TRANS('OCO_FIELD_UNIT').":</font></font></b>";
			print "<FORM name='form1' method='post' action='".$_SERVER['PHP_SELF']."'>";
			$sizeLin = $linhasInst+1;
			print "<select style='background-color: ".$cor3."; font-family:tahoma; font-size:11px;' name='instituicao[]' size='".$sizeLin."' multiple='yes'>";


			print "<option value='-1' selected>".TRANS('ALL')."</option>";
			while ($rowInst = mysql_fetch_array($resultadoInst))
			{
				print "<option value='".$rowInst['inst_cod']."'>".$rowInst['inst_nome']."</option>";
			}
			print "</select>";
			print "<br><input style='background-color: ".$cor1."' type='submit' class='button' value='".TRANS('BT_APPLY')."' name='OK'>";

			print "</form>";
		print "</div>";

		$saida="";
		if (isset ($_POST['instituicao'])) {
			for ($i=0; $i<count($_POST['instituicao']); $i++){
				$saida.= $_POST['instituicao'][$i].",";
			}
		}
		if (strlen($saida)>1) {
			$saida = substr($saida,0,-1);
		}

		$msgInst = "";
		if (($saida=="")||($saida=="-1")) {
			$clausula = "";
			$clausula2 = "";
			$msgInst = TRANS('ALL');
		} else {
			$sqlA ="select inst_nome as inst from instituicao where inst_cod in (".$saida.")";
			$resultadoA = mysql_query($sqlA);
			while ($rowA = mysql_fetch_array($resultadoA)) {
				$msgInst.= $rowA['inst'].', ';
			}
			$msgInst = substr($msgInst,0,-2);

			$clausula = " where comp_inst in (".$saida.")";
			$clausula2 = " and comp_inst in (".$saida.") ";
		}


		$queryB = "SELECT count(*) from equipamentos $clausula";
		$resultadoB = mysql_query($queryB);
		$total = mysql_result($resultadoB,0);

		//Query para retornar a quantidade individual de cada tipo de equipamento
  		$queryAux = "SELECT count(*) as Quantidade, T.tipo_nome as Equipamento, T.tipo_cod as tipo
  					FROM equipamentos as C, tipo_equip as T
  					WHERE C.comp_tipo_equip = T.tipo_cod ".$clausula2."
  					GROUP by C.comp_tipo_equip ORDER BY Equipamento";

		$resultadoAux = mysql_query($queryAux);
		$linhasAux = mysql_num_rows($resultadoAux);

		//Monta o cabeçalho do quadro de estatística
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='80%' bgcolor='".$cor3."'>";

			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td width='80%' align='center'><b>".TRANS('TTL_ESTAT_SITUAC_GENERAL')."<p>".TRANS('OCO_FIELD_UNIT').": ".$msgInst."</p></b></td></tr>";


			print "<td class='line'>";
			print "<fieldset><legend>".TRANS('TTL_EQUIP_X_SITUAC')."</legend>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='80%' bgcolor='".$cor3."'>";
			print "<TR><TD bgcolor='".$cor3."'><b>".TRANS('MNL_CAD_EQUIP')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_SITUAC')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_QTD')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_PORCENTEGE_FOR_TYPE')."</TD></tr>";


			while ($rowAux = mysql_fetch_array($resultadoAux)) {

				$tipo_equip = $rowAux['tipo'];
				$qtd_equip = $rowAux['Quantidade'];


				//Monsta os percentuais de cada tipo de equipamento de acordo com a sua situação
				$query= "SELECT t.tipo_nome AS equipamento, t.tipo_cod as tipo_cod, s.situac_nome AS situacao,
				s.situac_cod as situac_cod, count( t.tipo_nome ) AS qtd_equip, count( s.situac_nome )  AS qtd_situac,
				concat(count( * ) / ".$qtd_equip." * 100,'%') AS porcento
				FROM equipamentos AS c, situacao AS s, tipo_equip as t
				WHERE c.comp_situac = s.situac_cod AND c.comp_tipo_equip = t.tipo_cod ".$clausula2."
				and t.tipo_cod = ".$tipo_equip."
				GROUP  BY t.tipo_nome, s.situac_nome
				ORDER  BY equipamento,qtd_situac DESC ";
				//and (comp_tipo_equip=1 or comp_tipo_equip=2)
				$resultado = mysql_query($query);
				$linhas = mysql_num_rows($resultado);

				while ($row = mysql_fetch_array($resultado)) {
					$color =  BODY_COLOR;
					print "<TR>";
					print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo_cod']."&comp_situac=".$row['situac_cod']."&ordena=local,etiqueta' title='Exibe a listagem dos equipamentos desse tipo.'>".$row['equipamento']."</a></TD>";
					print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_situac=".$row['situac_cod']."&ordena=local,etiqueta' title='Exibe a listagem dos equipamentos cadastrados nessa situação.'>".$row['situacao']."</a></TD>";
					print "<TD bgcolor='".$color."'>".$row['qtd_situac']."</TD>";
					print "<TD bgcolor='".$color."'>".$row['porcento']."</TD>";
				} //Fim do loop interno
			} //Fim do loop externo


			print "<TR><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b>".TRANS('TOTAL').": ".$total."</TD><TD bgcolor='".$cor3."'><b>".TRANS('TXT_100')."%</b></TD></tr>";
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

		print "<tr><td width='80%' align='center'><b>".TRANS('SLOGAN_OCOMON')." <a href='http://www.unilasalle.edu.br' target='_blank'>".TRANS('COMPANY')."</a>.</b></td></tr>";
		print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>