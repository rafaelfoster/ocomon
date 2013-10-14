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

        //$hoje = datab(time());

?>

<HTML>
<BODY bgcolor=<?print BODY_COLOR?>>

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
<B>Relatório de ocorrências por data de abertura</B>
<BR>

<FORM method="POST" action=mostra_relatorio_data_abertura.php>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="15%" align="left" bgcolor=<?print TD_COLOR?>>Período de:</TD>
                <TD width="15%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text name=data_inicial value=\"$hoje\" size=15 maxlength=15>";?></TD>
                <TD width="5%" align="left" bgcolor=<?print TD_COLOR?>>a:</TD>
                <TD width="15%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text name=data_final value=\"$hoje\" size=15 maxlength=15>";?></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok" onclick="ok=sim">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
        </TABLE>
        </TR>

</TABLE>
</FORM>


</BODY>
</HTML>

