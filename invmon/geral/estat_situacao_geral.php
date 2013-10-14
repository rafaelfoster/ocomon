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
	include ("../../includes/jpgraph/src/jpgraph.php");
	include ("../../includes/jpgraph/src/jpgraph_pie.php");
	include ("../../includes/jpgraph/src/jpgraph_pie3d.php");

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$cab = new headers;
	$cab->set_title(TRANS("html_title"));

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

		print "<div id='Layer2' style='position:absolute; left:80%; top:176px; width:15%; height:40%; z-index:2; '>";//  <!-- Ver: overflow: auto    não funciona para o Mozilla-->
			print "<b>Unidade:</font></font></b>";
			print "<FORM name='form1' method='post' action='".$_SERVER['PHP_SELF']."'>";
			$sizeLin = $linhasInst+1;
			print "<select style='background-color: ".$cor3."; font-family:tahoma; font-size:11px;' name='instituicao[]' size='".$sizeLin."' multiple='yes'>";


			print "<option value='-1' selected>TODAS</option>";
			while ($rowInst = mysql_fetch_array($resultadoInst))
			{
				print "<option value='".$rowInst['inst_cod']."'>".$rowInst['inst_nome']."</option>";
			}
			print "</select>";
			print "<br><input style='background-color: ".$cor1."' type='submit' class='button' value='Aplicar' name='OK'>";

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
			$msgInst = "TODAS";
		} else {
			$sqlA ="select inst_nome as inst from instituicao where inst_cod in (".$saida.")";
			$resultadoA = mysql_query($sqlA);
			while ($rowA = mysql_fetch_array($resultadoA)) {
				$msgInst.= $rowA['inst'].', ';
			}
			$msgInst = substr($msgInst,0,-2);

			$clausula = " where comp_inst in (".$saida.")";
			$clausula2 = " and c.comp_inst in (".$saida.") ";
		}


		$queryB = "SELECT count(*) from equipamentos ".$clausula."";
		$resultadoB = mysql_query($queryB);
		$total = mysql_result($resultadoB,0);


		// Select para retornar a quantidade e percentual de equipamentos cadastrados no sistema
		$query= "SELECT s.situac_nome AS situacao, count( s.situac_nome )  AS qtd_situac,
				concat(count( * ) / ".$total." * 100,'%') AS porcento, s.situac_cod as situac_cod
				FROM equipamentos AS c, situacao AS s
				WHERE c.comp_situac = s.situac_cod ".$clausula2."
				GROUP  BY s.situac_nome order  by qtd_situac desc";
		$resultado = mysql_query($query);
		$linhas = mysql_num_rows($resultado);

		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";

			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td width='60%' align='center'><b>Estatística geral da situação dos equipamentos.<p>Unidade: ".$msgInst."</p></b></td></tr>";

			print "<td class='line'>";
			print "<fieldset><legend>Quadro de Situações</legend>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='60%' bgcolor='".$cor3."'>";
			print "<TR><TD bgcolor='".$cor3."'><b>Situação</TD><TD bgcolor='".$cor3."'><b>Quantidade</TD><TD bgcolor='".$cor3."'><b>Percentual</TD></tr>";
			$i=0;
			$j=2;
			$totalFull = 0;
			while ($row = mysql_fetch_array($resultado)) {
				$color =  BODY_COLOR;
				$j++;
				$totalFull+=$row['qtd_situac'];
				print "<TR>";
				print "<TD bgcolor='".$color."'><a href='mostra_consulta_comp.php?comp_situac=".$row['situac_cod']."&ordena=local,etiqueta' title='Exibe a listagem dos equipamentos cadastrados nessa situação.'>".$row['situacao']."</a></TD>";
				print "<TD bgcolor='".$color."'>".$row['qtd_situac']."</TD>";
				print "<TD bgcolor='".$color."'>".$row['porcento']."</TD>";

				$dados[]= $row['qtd_situac'];  //Dados para o gráfico.
				$legenda[]= $row['situacao'];
				print "</TR>";
				$i++;
			}
			print "<TR><TD bgcolor='".$cor3."'><b></TD><TD bgcolor='".$cor3."'><b>Total: <font color='red'>".$totalFull."</font></TD><TD bgcolor='".$cor3."'><b>100%</b></TD></tr>";
			print "</TABLE>";

			$valores ="";
			for ($i=0; $i<count($dados);$i++){
					$valores.="data%5B%5D=".$dados[$i]."&";
			}
			for ($i=0; $i<count($legenda); $i++){
					$valores.="legenda%5B%5D=".$legenda[$i]."&";
			}
				$valores = substr($valores,0,-1);

			print "</fieldset>";

			print "<TABLE width='60%' align='center'>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";
			print "</TABLE>";

			print "<TABLE width='60%' align='center'>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";
			print "<tr><td class='line'></TD></tr>";

			$nome = "titulo=Gráfico de equipamentos por situação.";
			$msgInst= "Unidade: ".$msgInst;
			print "<tr><td width=60% align=center><input type='button' class='button' value='Gráfico' onClick=\"return popup('graph_geral_pizza.php?".$valores."&".$nome."&instituicao=".$msgInst."')\"></td></tr>";
			print "<tr><td width=80% align=center><b>Sistema em desenvolvimento pelo setor de Helpdesk  do <a href='http://www.unilasalle.edu.br' target='_blank'>Unilasalle</a>.</b></td></tr>";
			print "</TABLE>";


print "</BODY>";
print "</HTML>";
?>