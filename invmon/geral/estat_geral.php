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

	$dados = array(); //Array que irá guardar os valores para montar o gráfico
	$legenda = array ();

	$queryInst = "SELECT * from instituicao order by inst_nome";
	$resultadoInst = mysql_query($queryInst);
	$linhasInst = mysql_num_rows($resultadoInst);

	if (isset($_POST['checkprint'])){
		$div_hide = "display: none;";
		$hide_buttom = true;
	} else {
		$div_hide = "";
		$hide_buttom = false;
	}

		print "<div id='Layer2' style='position:absolute; left:80%; top:176px; width:15%; height:40%; z-index:2; ".$div_hide." '>";//  <!-- Ver: overflow: auto    não funciona para o Mozilla-->
			print "<b>".TRANS('OCO_FIELD_UNIT').":</font></font></b>";
			print "<FORM name='form1' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit=\"newTarget()\">";
			$sizeLin = $linhasInst+1;
			print "<select style='background-color: ".$cor3."; font-family:tahoma; font-size:11px;' name='instituicao[]' size='".$sizeLin."' multiple='yes'>";


			print "<option value='-1' selected>".TRANS('ALL')."</option>";
			while ($rowInst = mysql_fetch_array($resultadoInst))
			{
				print "<option value='".$rowInst['inst_cod']."'>".$rowInst['inst_nome']."</option>";
			}
			print "</select>";

			print "<br><input style='background-color: ".$cor1."' type='submit' class='button' value='".TRANS('BT_APPLY')."' name='OK'>";
			print "<input type='checkbox' name='checkprint'>".TRANS('PRINT')."";

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
				//print "<pre> array: ".$rowA["inst"]."</pre>";
				}
				$msgInst = substr($msgInst,0,-2);

			$clausula = "where comp_inst in (".$saida.")";
			$clausula2 = " and C.comp_inst in (".$saida.") ";

		}

		$queryB = "SELECT count(*) from equipamentos ".$clausula."";
		$resultadoB = mysql_query($queryB);
		$total = mysql_result($resultadoB,0);

		// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
		$query = "SELECT count(*) as Quantidade, count(*)*100/".$total." as Percentual,
					T.tipo_nome as Equipamento, T.tipo_cod as tipo
					FROM equipamentos as C, tipo_equip as T
					WHERE C.comp_tipo_equip = T.tipo_cod ".$clausula2."
					GROUP by C.comp_tipo_equip ORDER BY Quantidade desc,Equipamento" ;

		$resultado = mysql_query($query);
		$linhas = mysql_num_rows($resultado);

		//Tabela de quantidade de equipamentos cadastrados por dia
		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='$cor3'>";

			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td width=60% align=center><b>".TRANS('TTL_ESTAT_CAD_EQUIP').". <p>".TRANS('OCO_FIELD_UNIT').": $msgInst</p></b></td></tr>";


			print "<td class='line'>";
			print "<fieldset><legend>".TRANS('TTL_GENERAL_BOARD')."</legend>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";
			print "<TR><TD bgcolor='".$cor3."'><b>".TRANS('MNL_VIS_EQUIP')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_QTD')."</TD><TD bgcolor='".$cor3."'><b>".TRANS('COL_PORCENTEGE')."</TD></tr>";
			$i=0;
			$j=2;

		while ($row = mysql_fetch_array($resultado)) {
			$color =  BODY_COLOR;
			$j++;
			print "<TR>";
			print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_tipo_equip=".$row['tipo']."&ordena=fab_nome,modelo,local,etiqueta' title='".TRANS('HNT_LIST_EQUIP_THIS_TYPE')."'>".$row['Equipamento']."</a></TD>";
			print "<TD bgcolor='".$color."'>".$row['Quantidade']."</TD>";
			print "<TD bgcolor='".$color."'>".round($row['Percentual'],2)."%</TD>";
			print "</TR>";
			$dados[]=$row['Quantidade'];
			$legenda[]=$row['Equipamento'];
			$i++;
		}
        	print "<TR><TD bgcolor='".$cor3."'><b>".TRANS('TOTAL')."</TD><TD bgcolor='".$cor3."'><font color='red'><b>$total</font></TD><TD bgcolor='".$cor3."'><b>".TRANS('TXT_100')."</TD></tr>";
		print "</TABLE>";

		$valores = "";
		for ($i=0; $i<count($dados);$i++){
				$valores.="data%5B%5D=".$dados[$i]."&";
		}
		for ($i=0; $i<count($legenda); $i++){
				$valores.="legenda%5B%5D=".$legenda[$i]."&";
		}
			$valores = substr($valores,0,-1);
		print "</fieldset>";

		print "<TABLE width=80% align=center>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";

		print "</TABLE>";

		print "<TABLE width=80% align=center>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";
		print "<tr><td class='line'></TD></tr>";

		$nome = "titulo=".TRANS('TTL_GRAPH_EQUIP_CAD').".";
		$msgInst= "".TRANS('OCO_FIELD_UNIT').": ".$msgInst;

		if (!$hide_buttom) {
			print "<tr><td width=60% align=center><input type='button' class='button' value='".TRANS('BT_GRAPH')."' onClick=\"return popup('graph_geral_barras.php?".$valores."&".$nome."&instituicao=".$msgInst."')\"></td></tr>";
			print "<tr><td width=80% align=center><b>".TRANS('SLOGAN_OCOMON')." <a href='http://www.unilasalle.edu.br' target='_blank'>".TRANS('COMPANY')."</a>.</b></td></tr>";
		}
		print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>

<script type='text/javascript'>
<!--
	function newTarget()
	{
		if (document.form1.checkprint.checked) {
			document.form1.target = "_blank";
			document.form1.submit();
		} else {
			document.form1.target = "";
			document.form1.submit();
		}
	}
	-->
</script>
