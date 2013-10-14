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
        $hoje = date("Y-m-d H:i:s");

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
<B>Relatório para a Intranet do LaSalle</B>
<BR>

<FORM method="POST" action=mostra_relatorio_intranet.php>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data Inicial:</TD>
                <TD width="20%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text name=data_inicial  size=15 maxlength=15>";?></TD>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Data Final:</TD>
                <TD width="20%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text name=data_final  size=15 maxlength=15>";?></TD>
                <TD width="10%" align="left" bgcolor=<?print TD_COLOR?>>Data de:</TD>
                <TD width="20%" align="left" bgcolor=<?print BODY_COLOR?>>
                <SELECT name="tipo_data" size=1>";
                <option value="abertura" selected>Abertura</option>";
                <option value="fechamento">Fechamento</option>
                </SELECT>
                </TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Status:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT name='status' size=1>";
                print "<option value='Em aberto' selected>Em aberto</option>";
                $query = "SELECT * from status order by status";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>

                </TD>
               <!-- ****************-->
               <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Área:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT name='area' size=1>";
                print "<option value='-1' selected>-----Todos-----</option>";
                $query = "SELECT sis_id, sistema from sistemas Where sis_id not in (3,4,5) order by sistema";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,1);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>

                </TD>
               <!-- ****************-->
        </TABLE>
        </TR>

        <TR>
        <TABLE border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><input type="submit" value="    Ok    " name="ok" onclick="ok=sim">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TABLE>
        </TR>

</TABLE>
</FORM>

</BODY>
</HTML>

