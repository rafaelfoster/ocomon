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
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	$auth = new auth;


		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);
			$fecha = "window.close()";
		} else {
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);
			$fecha = "history.back()";
		}

        $hoje = date("Y-m-d H:i:s");

		print "<br>";
        print "<TD align=right bgcolor=$cor1><a href='incluir_marca_comp.php'>Incluir Modelo</a></TD><BR>";
        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;

		//select m.marc_nome as modelo, t.tipo_nome as tipo from marcas_comp as m, tipo_equip as t where m.marc_tipo = t.tipo_cod
        $query = "select m.marc_cod as codigo, m.marc_nome as modelo, t.tipo_nome as tipo from marcas_comp as m, tipo_equip as t where m.marc_tipo = t.tipo_cod order by m.marc_nome, t.tipo_nome";
        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);
        $row = mysql_fetch_array($resultado);
		if ($linhas==0)
        {
                echo mensagem("Não foi encontrado nenhum modelo cadastrado no sistema.");
                exit;
        } else
        if ($linhas>1)
                print "<TR><TD bgcolor=$cor1><B>Foram encontrados $linhas modelos cadastrados no sistema. </B></TD></TR>";
        else
                print "<TR><TD bgcolor=$cor1><B>Foi encontrado somente 1  modelo cadastrado no sistema.</B></TD></TR>";
        print "</TD>";

        print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
        print "<TR class='header'><td class='line'><b>Código</TD><td class='line'><b>Modelo</TD><td class='line'><b>Tipo</TD><td class='line'><b>Alterar</TD><td class='line'><b>Excluir</TD>";
        $i=0;
        $j=2;

  		if (($resultado = mysql_query($query)) && (mysql_num_rows($resultado) > 0) ) {
  			while ($row = mysql_fetch_array($resultado)) {

                if ($j % 2)
                {
                        $color =  BODY_COLOR;
						$trClass = "lin_par";
                }
                else
                {
                        $color = white;
						$trClass = "lin_impar";
                }
                $j++;
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";

			$qryImg = "select * from imagens where img_model = ".$row['codigo']."";
			$execImg = mysql_query($qryImg) or die ("ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE IMAGENS!");
			$rowTela = mysql_fetch_array($execImg);
			$regImg = mysql_num_rows($execImg);
			if ($regImg!=0) {
				$linkImg = "<a onClick=\"javascript:popupWH('../../includes/functions/showImg.php?file=".$row['codigo']."&cod=".$rowTela['img_cod']."',".$rowTela['img_largura'].",".$rowTela['img_altura'].")\"><img src='../../includes/icons/attach2.png'></a>";
			} else $linkImg = "";


                ?>
                <td class='line'><a href='mostra_consulta_comp.php?comp_marca=<?print $row['codigo'];?>'><?print $row['codigo'];?></a></TD>
                <td class='line'><? print $linkImg."&nbsp;".$row['modelo'];?></td>
                <td class='line'><? print $row['tipo'];?></td>
                <?
				print "<td class='line'><a onClick=\"redirect('altera_dados_marca_comp.php?marc_cod=".$row['codigo']."')\"><img src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
				print "<td class='line'><a onClick=\"confirma('Tem Certeza que deseja excluir esse registro do sistema?','exclui_dados_marca_comp.php?marc_cod=".$row['codigo']."')\"><img src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
				print "</TR>";
                $i++;
        	}
        }
		print "</TABLE>";


        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='$cor3'>";
        print "<TR width=100%>";
        print "&nbsp;";
        print "</TR>";

        print "<td class='line'>";


?>
</BODY>
</HTML>
