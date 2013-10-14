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
	include ("../../includes/classes/paging.class.php");


	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$cab = new headers;
	$cab->set_title(TRANS("html_title"));

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$traduz = array("mat_cod" => $TRANS["col_codigo"],
		"mat_nome"=> $TRANS["col_descricao"],
		"mat_caixa" =>$TRANS["col_caixa"],
		"mat_qtd"=> $TRANS["col_quantidade"]
		);


	$stilo = "style='{height:17px; width:30px; background-color:#DDDCC5; color:#5E515B; font-size:11px;}'"; //Estilo dos botões de navegação
	$stilo2 = "style='{height:17px; width:50px; background-color:#DDDCC5; color:#5E515B;font-size:11px;}'";


	$queryTotal = "SELECT * from materiais";
	$resultadoTotal = mysql_query($queryTotal);
        $linhasTotal = mysql_num_rows($resultadoTotal);


	$query = "SELECT mat.* , marc.*
			FROM materiais AS mat
			LEFT  JOIN marcas_comp as marc ON mat.mat_modelo_equip = marc.marc_cod ";


      		if (empty($ordena)) {
		$ordena="mat_cod";}
		$query.= " order by $ordena";

		$traduzOrdena = strtr("$ordena", $traduz);
		//$query = "SELECT * from materiais order by $ordena";


	$resultado = mysql_query($query);
        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem($TRANS["alerta_nao_encontrado"]);
        }
        else
        {
                $cor=TD_COLOR;
                $cor1=TD_COLOR;
                $linhas = mysql_numrows($resultado);
                print "<td class='line'>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";
				print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."'>";
				print "<TR>";
				print "<TD width='750' align='left'><B>Foram encontrados <font color='red'>$linhasTotal</font> documentos cadastrados ordenador por <u>$traduzOrdena</u>. Mostrados de <font color=red>$min</font> a <font color=red>$top</font>.</B></font></font></TD>";
				print "<TD width='50' align='left' ></td>";
				print "<TD width='224' align='left' ><input  type='submit' $stilo name='avanca' value='<<' title='Visualiza os $max primeiros registros.'> <input  type='submit' $stilo name='avanca' value='<' title='Visualiza os $max registros anteriores.'> <input  type='submit' $stilo name='avanca' value='>' title='Visualiza os próximos $max registros.'> <input  type='submit' $stilo name='avanca' value='>>' title='Visualiza os últimos $max registros.'> <input  type='submit' $stilo2 name='avanca' value=".$TRANS["bt_todos"]." title='Visualiza todos os $linhasTotal registros.'></td>";
				print "</tr>";
				print "</form>";
                print "</table>";

                print "<TABLE border='0' cellpadding='3' cellspacing='0' align='center' width='100%'>";
				print "<TR class='header'><td class='line'><b><a href='materiais.php?ordena=mat_cod'>Codigo</a></b>
					</TD><td class='line'><b><a href='materiais.php?ordena=mat_nome'>Descrição</a></b></TD>
					<td class='line'><b>Modelo de equipamento</b>
					<td class='line'><b><a href='materiais.php?ordena=mat_qtd'>Quantidade</a></b></TD>
					<td class='line'><b><a href='materiais.php?ordena=mat_caixa'>Caixa</a></b><TD bgcolor='".$cor1."'>Alterar</TD>
					<td class='line'>Excluir</TD></TR>";
                $i=0;
                $j=2;
                while ($row = mysql_fetch_array($resultado))
                {
                        if ($j % 2)
                        {
				$trClass = "lin_par";
                        }
                        else
                        {
				$trClass = "lin_impar";
                        }
                        $j++;
			print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
                        ?>
                        <td class='line'><?print $row['mat_cod'];?></TD>
                        <td class='line'><?print $row['mat_nome'];?></TD>
						<td class='line'><?print $row['marc_nome'];?></TD>
                        <td class='line'><?print $row['mat_qtd'];?></TD>
                        <td class='line'><?print $row['mat_caixa'];?></TD>
						<td class='line'><a href=altera_dados_documento.php?mat_cod=<?print $row['mat_cod'];?>>Alterar</a></TD>
						<td class='line'><a href=exclui_dados_documento.php?mat_cod=<?print $row['mat_cod'];?>>Excluir</a></TD>

                        <?print "</TR>";
                        $i++;
                }

                print "<TABLE border='0' cellpadding='3' cellspacing='0' align='center' width='100%' >";
				?><FORM method="POST" action=<?PHP_SELF?>><?
				print "<TR>";
				print "<TD width='750' bgcolor='white' align='left'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><B>Foram encontrados <font color=red>$linhasTotal</font> documentos cadastrados. Mostrados de <font color=red>$min</font> a <font color=red>$top</font>.</B></font></font></TD>";
				print "<TD width='50' bgcolor='white' align='left'></td>";
				print "<TD width='224' bgcolor='white' align='left'><input type='submit' $stilo name='avanca' value='<<' title='Visualiza os $max primeiros registros.'> <input  type='submit' $stilo name='avanca' value='<' title='Visualiza os $max registros anteriores.'> <input  type='submit' $stilo name='avanca' value='>' title='Visualiza os próximos $max registros.'> <input  type='submit' $stilo name='avanca' value='>>' title='Visualiza os últimos $max registros.'> <input  type='submit' $stilo2 name='avanca' value='Todos' title='Visualiza todos os $linhasTotal registros.'></td>";
				print "</tr>";
				print "</form>";
                print "</table>";
	}
print "</body>";
print "</html>";
?>