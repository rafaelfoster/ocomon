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
//$testi=session_name("teste");
//echo session_name();
//echo $testi;
//echo session_id();
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
<B>Relatório de ocorrências</B>
<BR>

<FORM method="POST" action=mostra_resultado_relatorio.php>
<TABLE border="1"  align="center" width="100%" bgcolor=<?print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Número inicial:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="numero_inicial" maxlength="40" size="40"></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Número final:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="numero_final" maxlength="40" size="40"></TD>
        </TABLE>
        </TR>
        <TR>
        <td class='line'>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Problema:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT name='problema' size=1>";
                print "<option value=-1 selected>-  Selecione um problema -</option>";
                $query = "SELECT * from problemas order by problema";
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
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Sistema:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT name='sistema' size=1>";
                print "<option value=-1 selected>-  Selecione um sistema -</option>";
                $query = "SELECT * from sistemas order by sistema";
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
        </TABLE>
        </TD>

        </TR>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?> valign="top">Descrição:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><TEXTAREA cols="84" rows="8" name="descricao"></textarea></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Equipamento:</TD>
                <TD width="80%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="equipamento" maxlength="100" size="100"></TD>
        </TABLE>
        </TR>
        <td class='line'>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Contato:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><INPUT type="text" name="contato" maxlength=100></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Status:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <?print "<SELECT name='status' size=1>";
                print "<option value='Em aberto' selected>Em aberto</option>";
                $query = "SELECT * from status order by estado";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,0);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>

                </TD>
        </TABLE>
        </TD>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Local:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT name='local' size=1>";
                print "<option value=-1 selected>-  Selecione um local -</option>";
                $query = "SELECT * from localizacao order by local";
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
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Operador:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>

                <?print "<SELECT name='operador' size=1>";
                print "<option value=-1 selected>-  Selecione um operador -</option>";
                $query = "SELECT * from usuarios order by nome";
                $resultado = mysql_query($query);
                $linhas = mysql_numrows($resultado);
                $i=0;
                while ($i < $linhas)
                {
                       ?>
                       <option value="<?print mysql_result($resultado,$i,0);?>">
                                         <?print mysql_result($resultado,$i,0);?>
                       </option>
                       <?
                       $i++;
                }
                ?>
                </SELECT>

                </TD>

        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data abertura (inicial):</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text name=data_inicial value=\"$hoje\" size=15 maxlength=15>";?></TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Data abertura (final):</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>><?print "<INPUT type=text name=data_final value=\"$hoje\" size=15 maxlength=15>";?></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Relatório ordernado por:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <SELECT name='ordem' size=1>";
                <option value='numero' selected>Número</option>";
                <option value='problema'>Problema</option>";
                <option value='sistema'>Sistema</option>";
                <option value='Equipamento'>Equipamento</option>";
                <option value='contato'>Contato</option>";
                <option value='status'>Status</option>
                <option value='local'>Local</option>
                <option value='operador'>Operador</option>
                <option value='data_cres'>Data de abertura (crescente)</option>
                <option value='data_decres'>Data de abertura (decrescente)</option>
                </SELECT>
                </TD>
                <TD width="20%" align="left" bgcolor=<?print TD_COLOR?>>Relatório para:</TD>
                <TD width="30%" align="left" bgcolor=<?print BODY_COLOR?>>
                <SELECT name='relatorio' size=1>";
                <option value='impressao' selected>Impressão</option>";
                <option value='intranet'>Intranet</option>
                </SELECT>
                </TD>
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

