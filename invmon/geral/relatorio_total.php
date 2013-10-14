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

        include ("var_sessao.php");      // Tem que estar em primeiro por causa do header!
        include ("funcoes.inc");
        include ("config.inc.php");
        include ("logado.php");

?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?print TD_COLOR?>>
                        <TR>
                        <?
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - Controle de Inventário  -  Usuário: <font color=red>$s_usuario</font></b></TD>";
                        echo menu_usuario();
                        if ($nivel==1)
                        {
                                echo menu_admin();
                        }
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>

<BR>
<B>Relatório de inventário de equipamentos de informática:</B>
<BR>

<FORM method="POST" action=mostra_resultado_relatorio_total.php>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
</FORM>


</BODY>
</HTML>

