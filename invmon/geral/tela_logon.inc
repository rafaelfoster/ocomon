<?php 

print "<HTML><HEAD><TITLE>-=[InvMon - Monitor de Inventário]=-</TITLE></HEAD>";

print "<link REL= 'SHORTCUT ICON' HREF='./includes/icons/fiv.gif'>";
//print "<link REL= 'SHORTCUT ICON' HREF='favicon.ico'>";

print "<body bgcolor=".BODY_COLOR." onLoad='document.logar.login.focus()'>";
print "<FONT FACE='Arial, Helvetica'>";

print "<br><br><br><br><br>";
print "<table  bgcolor='black' cellspacing='1' border='1' cellpadding='1' align='center'>";
print "<Td bgcolor=".TD_COLOR.">";
print "<table  bgcolor=".TAB_COLOR.">";
print "<table width='280' border='0' cellpadding='5' cellspacing='0' align='center' bgcolor=".TAB_COLOR.">";
                print "<tr><td bgcolor=".TD_COLOR." align='center'><IMG src='invmon.gif' width=280 height=30 border=0></td></tr>";
        print "<tr>";
               print "<td>";
                        print "<table width='200' border='0'  cellpadding='5' cellspacing='0' align='left'>";

									if ($inv=="1") {
										echo "<tr align=\"center\">".
											"<td colspan=2 align=\"center\"><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><font color='red'><u>Usuário</u> ou <u>Senha</u> inválido(a)!<font></font></font></td>".
											"</tr>";
									}
                                
								print "<form name='logar' method='post' action='../ocomon/login.php?=".session_id().">";
								print "<tr align='left'>";
                                        print "<td align='left'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>Usuário: </td>
                                        <td align='left'><input type='text' align='left' name='login' size='15' maxlength='40'></td>
                                </tr>
                                <tr>
                                        <td align='left'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>Senha:</td>
                                        <td align='left'><input type=password align='left' name='password' size='15' maxlength='40'></td>
                                		<td align='right'><input type=submit value=Entrar></td>
								</tr>
								</form>
                        </table>
                </td>
        </tr>
        <tr>
                <td bgcolor=".TD_COLOR." align='left'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif>Sistema para controle e gerenciamento de equipamentos de informática.<br> Em desenvolvimento pelo setor de <a href=http://www.intranet.lasalle.tche.br/cinfo/helpdesk TARGET=_blank>Helpdesk Unilasalle</a>.</font></font><br>

                </td>
        </tr>
         <tr>
                <td bgcolor=".TD_COLOR." align='right'><FONT SIZE=2 STYLE=font-size: 11pt><FONT FACE=Arial, sans-serif><i>versão 0.9.5</i></font></font></td>
        </tr>
		
</table>
</table>
</td>
</table>

</BODY>
</HTML>";


?>
