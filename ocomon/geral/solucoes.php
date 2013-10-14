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

?>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TD_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>OcoMon - Módulo de Ocorrências</b></TD>";
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
<B>Manutenção de Soluções e Problemas</B>
<BR>

<FORM method="POST" action=<?PHP_SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Número:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="numero" maxlength="100" size="10"></TD>
        </TABLE>
        </TR>
        <TR>
        <TR>
        <TABLE border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok" onclick="ok=sim"></TD>
                        <input type="hidden" name="rodou" value="sim">
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TABLE>
        </TR>
        <?
                if ($rodou == "sim")
                {
                        $query  = "SELECT * FROM solucoes WHERE numero='$numero'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        if ($linhas==0)
                        {
                                $aviso = "ERRO_Nenhuma_solucao_encontrada";
                                $origem = "solucoes.php";
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php?aviso=$aviso&origem=$origem\">";
                                exit;
                        }
                        else
                        {
                                $cor=TD_COLOR;
                                $cor1=TD_COLOR;

                                print "<BR>";
                                print "<td class='line'>";
                                print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%' bgcolor='$cor'>";
                                print "<TR><TD bgcolor=$cor1><b>Data</b></TD><TD bgcolor=$cor1><b>Operador</b></TD><TD bgcolor=$cor1><b>Alterar</b></TD><TD bgcolor=$cor1><b>Excluir</b></TD>";
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
                                        <TD bgcolor=<?print $color;?>><?print datab(mysql_result($resultado,$i,3));?></TD>
                                        <TD bgcolor=<?print $color;?>><?print mysql_result($resultado,$i,4);?></TD>
                                        <TD bgcolor=<?print $color;?>><a href=altera_dados_solucoes.php?numero=<?print mysql_result($resultado,$i,0);?>>Alterar</a></TD>
                                        <TD bgcolor=<?print $color;?>><a href=exclui_dados_solucoes.php?numero=<?print mysql_result($resultado,$i,0);?>>Excluir</a></TD>
                                        <?print "</TR>";
                                        $i++;
                                }
                        }
                }
        ?>
</TABLE>
</FORM>

</BODY>
</HTML>
