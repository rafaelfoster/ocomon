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
  */
	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	$cab = new headers;
	$cab->set_title(HTML_TITLE);

	$auth = new auth;
		if ($popup) {
			$auth->testa_user_hidden($s_usuario,$s_nivel,$s_nivel_desc,2);

		} else
			$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,2);



        $cor  = TAB_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;


        $query = "SELECT * FROM fornecedores  ORDER BY forn_nome";
        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);   

        if ($linhas == 0)
        {
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";				
				print "<tr>";
				print "<TD width='400' align='left'><B>Nenhum fornecedore cadastrado. </B></font></font></TD>";
				print "<TD width='200' align='left' ><a href=incluir_fornecedor.php>Incluir Fornecedor</font></font></a></td>";
				print "<TD width='224' align='left' ></td>";
				print "</tr>";
        } else
        if ($linhas>1)
		{
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";				
				print "<tr>";
				print "<TD width='400' align='left'><B>Foram encontrados <font color=red>$linhas</font> Fornecedores cadastrados. </B></font></font></TD>";
				print "<TD width='200' align='left' ><a href=incluir_fornecedor.php>Incluir Fornecedor</font></font></a></td>";
				print "<TD width='224' align='left' ></td>";
				print "</tr>";
		
		}
        else {
                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' >";				
				print "<tr>";
				print "<TD width='400' align='left'><B>Apenas 1 fornecedore cadastrado. </B></font></font></TD>";
				print "<TD width='200' align='left' ><a href=incluir_fornecedor.php>Incluir Fornecedor</font></font></a></td>";
				print "<TD width='224' align='left' ></td>";
				print "</tr>";
		
		}
        print "</TD>";

        print "<TD>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
        print "<TR><TD bgcolor=$cor1><b>Código</TD><TD bgcolor=$cor1><b>Fornecedor</TD><TD bgcolor=$cor1><b>Telefone</TD><TD bgcolor=$cor1><b>Alterar</TD><TD bgcolor=$cor1><b>Excluir</TD>";
        $i=0;
        $j=2;
        while ($i < $linhas)
        {
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
                ?>
                <TD><a href='mostra_consulta.php?=emBreve<?print mysql_result($resultado,$i,0);?>'><?print mysql_result($resultado,$i,0);?></a></TD>
                <td><? print mysql_result($resultado,$i,1);?></td>
                <td><? print mysql_result($resultado,$i,2);?></td>
                <?
				print "<td><a onClick=\"redirect('altera_dados_fornecedor.php?forn_cod=".mysql_result($resultado,$i,0)."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Editar o registro'></a></TD>";
				print "<td><a onClick=\"confirma('Tem Certeza que deseja excluir esse registro do sistema?','exclui_dados_fornecedor.php?forn_cod=".mysql_result($resultado,$i,0)."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></a></TD>";
				print "</TR>";
                $i++;
        }
        print "</TABLE>";


        print "<TABLE border='0' cellpadding='0' cellspacing='0' align='center' width='100%' bgcolor='$cor3'>";
        print "<TR width=100%>";
        print "&nbsp;";
        print "</TR>";

        print "<TD>";


?>
</BODY>
</HTML>
