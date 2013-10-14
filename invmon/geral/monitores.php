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
  */
        include ("var_sessao.php");      // Tem que estar em primeiro por causa do header!
        include ("funcoes.inc");
        include ("config.inc.php");
        include ("logado.php");

        $hoje = date("Y-m-d H:i:s");

?>

<HTML>
<BODY bgcolor=<?php print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php print TD_COLOR?>>
                        <TR>
                        <?php 
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - controle de inventário  -  Usuário: <font color=red>$s_usuario</font></b></TD>";
                        echo menu_usuario();
                        if ($s_usuario=='admin')
                        {
                                echo menu_admin(TD_COLOR);
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>


        <br>
        <B>Cadastro de monitores:</B>
<?php 

        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";

        print "<TD align=left bgcolor=$cor1><a href=incluir_monitor.php>Incluir monitor</a></TD><TD align=center bgcolor=$cor1><a href=fabricantes.php>Fabricantes</a></TD><BR><TD align=center bgcolor=$cor1><a href=modelos.php>Modelos</a></TD><BR>";

        print "</TABLE>";
        $cor  = TD_COLOR;
        $cor1 = TD_COLOR;
        $cor3 = BODY_COLOR;




        $query = "select mo.*, fo.forn_nome, fab.*, mod.*, c.comp_inv, l.* from
                  monitores as mo, fornecedores as fo, fabricantes as fab,
                  modelos as mod, computadores as c, localizacao as l where
                  ((mo.mon_fornecedor = fo.forn_cod) and (mo.mon_fabricante = fab.fab_cod) and
                  (mo.mon_modelo = mod.modelo_cod) and (mo.mon_comp_inv = c.comp_inv) and (mo.mon_local = l.loc_id))";
        $resultado = mysql_query($query);
        $linhas = mysql_num_rows($resultado);

        if ($linhas == 0)
        {
                echo mensagem("Não foi encontrado nenhum monitor cadastrado no sistema.");
                exit;
        }
        if ($linhas>1)
                print "<TR><TD bgcolor=$cor1><B>Foram encontrados $linhas monitores cadastrados no sistema. </B></TD></TR>";
        else
                print "<TR><TD bgcolor=$cor1><B>Foi encontrado somente 1 monitor cadastrado no sistema.</B></TD></TR>";
        print "</TD>";

        print "<td class='line'>";
        print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
        print "<TR><TD bgcolor=$cor1><b>Inventário</TD><TD bgcolor=$cor1><b>Modelo</TD><TD bgcolor=$cor1><b>Nº de Série</TD><TD bgcolor=$cor1><b>Localização</TD><TD bgcolor=$cor1><b>Associado ao computador</TD><TD bgcolor=$cor1><b>Alterar</TD><TD bgcolor=$cor1><b>Excluir</TD>";
        $i=0;
        $j=2;
        while ($i < $linhas)
        {




                if ($j % 2)
                {
                        $color =  BODY_COLOR;
                }
                else
                {
                        $color = white;
                }
                $j++;
                ?>
                <TR>
                <TD bgcolor=<?php print $color;?>><a href=mostra_consulta_inv.php?comp_inv=<?php print mysql_result($resultado,$i,1);?>&comp_inst=<?php print mysql_result($resultado,$i,9);?>><?php print mysql_result($resultado,$i,1);?></a></TD>
                <td bgcolor=<?php print $color;?>><?php  print mysql_result($resultado,$i,11).mysql_result($resultado,$i,13);?></td>
                <td bgcolor=<?php print $color;?>><?php  print mysql_result($resultado,$i,5)?></td>
                <td bgcolor=<?php print $color;?>><?php  print mysql_result($resultado,$i,16)?></td>
                <td bgcolor=<?php print $color;?>><a href=mostra_consulta_inv.php?comp_inv=<?php  print mysql_result($resultado,$i,7);?>><?php  print mysql_result($resultado,$i,7);?></a>
                <TD bgcolor=<?php print $color;?>><a href=altera_dados_monitor.php?mon_inv=<?php print mysql_result($resultado,$i,1);?>>Alterar</a></TD>
                <TD bgcolor=<?php print $color;?>><a href=exclui_dados_monitor.php?mon_inv=<?php print mysql_result($resultado,$i,1);?>>Excluir</a></TD>


                <?php 
                  /*      $problemas = mysql_result($resultado,$i,1);
                        $query = "SELECT * FROM problemas WHERE prob_id='$problemas'";
                        $resultado3 = mysql_query($query);   */
                print "</TR>";
                $i++;
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
