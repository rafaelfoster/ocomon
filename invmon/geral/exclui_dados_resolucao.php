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
include ("var_sessao.php");
include ("funcoes.inc");
include ("config.inc.php");
include ("logado.php");

?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

<?
        if ($s_usuario!="admin")
        {
                echo "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
                        URL=index.php\">";
        }

        $query = "select * from resolucao where resol_cod='$resol_cod'";
        $resultado = mysql_query($query);

?>
<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TD_COLOR?>>
                        <TR>
                        <?
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
<B>Excluir resolução</B>
<BR>

<FORM method="POST" action=<?PHP_SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Resolução:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><?print mysql_result($resultado,0,1);?></TD>
        </TABLE>
        </TR>



        <?
                $query2 = "select * from computadores where comp_resolucao='$resol_cod'";
                $resultado2 = mysql_query($query2);
                $linhas2 = mysql_numrows($resultado2);

                if ($linhas2!=0)
                {
                        echo mensagem("Existe(m) $linhas2 cadastros com essa resolução .","Não pode ser excluido até que esse(s) equipamento(s) seja(m) excluído(s).");
                }
                else
                {
                        ?>


                        <TR>
                        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                        <BR>
                        <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="  Ok  " name="ok">
                                <input type="hidden" name="rodou" value="sim">
                        </TD>
                        <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
                        </TABLE>
                        </TR>
                        <?
                        if ($rodou == "sim")
                        {
                                $query = "DELETE FROM resolucao WHERE resol_cod='$resol_cod'";
                                $resultado = mysql_query($query);

                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao excluir resolução do sistema.";

                                }
                                else
                                {
                                        $aviso = "OK. Resolução excluida com sucesso.";
                                }
                                $origem = "resolucao.php";
                                session_register("aviso");
                                session_register("origem");
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                        }

                   }
        ?>

        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TD_COLOR?>>
        <TR>
                <TD align="center" width="100%" bgcolor=<?print BODY_COLOR?>><a href=resolucao.php>Voltar</a></TD>
        </TR>
        </TABLE>
</TABLE>
</FORM>
</body>
</html>
