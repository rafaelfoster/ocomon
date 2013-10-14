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

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$cab = new headers;
	$cab->set_title(HTML_TITLE);
	$auth = new auth;
	$auth->testa_user($s_usuario,$s_nivel,$s_nivel_desc,4);


    $query = "select * from materiais where mat_cod='$mat_cod'";
    $resultado = mysql_query($query);

?>

<BR>
<B>Excluir Registro do Sistema:</B>
<BR>

<FORM method="POST" action=<?php _SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Código:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print mysql_result($resultado,0,0);?></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Material:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print mysql_result($resultado,0,1);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?> valign="top">Quantidade:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print mysql_result($resultado,0,2);?></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Caixa:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print mysql_result($resultado,0,3);?></TD>
        </TABLE>
        </TR>

        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Obs:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print mysql_result($resultado,0,5);?></TD>
        </TABLE>
        </TR>

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
                                $query = "DELETE FROM materiais WHERE mat_cod='$mat_cod'";
                                $resultado = mysql_query($query);

                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao excluir registro do sistema.";

                                }
                                else
                                {
                                        $aviso = "OK. Registro excluido com sucesso.";
                                }
                                $origem = "materiais.php";
                                session_register("aviso");
                                session_register("origem");
                                echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                        }


        ?>

        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
        <TR>
                <TD align="center" width="100%" bgcolor=<?php print BODY_COLOR?>><a href=materiais.php>Voltar</a></TD>
        </TR>
        </TABLE>
</TABLE>
</FORM>
</body>
</html>
