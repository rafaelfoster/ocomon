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

include ("var_sessao.php");
include ("funcoes.inc");
include ("config.inc.php");
include ("logado.php");

?>

<HTML>
<BODY bgcolor=<?php print BODY_COLOR?>>

<?php 
        if ($s_usuario!="admin")
        {
                echo "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
                        URL=index.php\">";
        }

        $query = "select * from modelos where modelo_cod='$modelo_cod'";
        $resultado = mysql_query($query);

?>
<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php print TD_COLOR?>>
                        <TR>
                        <?php 
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - Controle de Inventário  -  Usuário: <font color=red>$s_usuario</font></b></TD>";
                        echo menu_usuario();
                        if ($s_usuario=='admin')
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>

<BR>
<B>Excluir modelo</B>
<BR>

<FORM method="POST" action=<?php _SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>modelo:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print mysql_result($resultado,0,1);?></TD>
        </TABLE>
        </TR>


        <?php 
                $query2 = "select * from monitores where mon_modelo='$modelo_cod'";
                $resultado2 = mysql_query($query2);
                $linhas2 = mysql_numrows($resultado2);

                if ($linhas2!=0)
                {
                        echo mensagem("Existe(m) $linhas2 monitor(es) cadastrado(s) com este modelo.","Não pode ser excluido até que esse(s) monitor(es) seja(m) excluído(s).");
                }
                else
                {
                        ?>


                        <TR>
                        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                        <BR>
                        <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                                <input type="hidden" name="rodou" value="sim">
                        </TD>
                        <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
                        </TABLE>
                        </TR>
                        <?php 
                        if ($rodou == "sim")
                        {
                                $query = "DELETE FROM modelos WHERE modelo_cod='$modelo_cod'";
                                $resultado = mysql_query($query);

                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao excluir registro do sistema.";

                                }
                                else
                                {
                                        $aviso = "OK. Registro excluido com sucesso.";
                                }
                                $origem = "modelos.php";
                                session_register("aviso");
                                session_register("origem");
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                        }

                 }
        ?>

        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
        <TR>
                <TD align="center" width="100%" bgcolor=<?php print BODY_COLOR?>><a href=modelos.php>Voltar</a></TD>
        </TR>
        </TABLE>
</TABLE>
</FORM>
</body>
</html>




