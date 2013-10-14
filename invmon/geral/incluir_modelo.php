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

        if ($s_nivel!=1)
        {
                echo "<META HTTP-EQUIV=REFRESH   CONTENT=\"0;
                        URL=index.php\">";
        }
?>
<TABLE  bgcolor="black" cellspacing="1" border="1" cellpadding="1" align="center" width="100%">
        <TD bgcolor=<?php print TD_COLOR?>>
                <TABLE  cellspacing="0" border="0" cellpadding="0" bgcolor=<?php print TD_COLOR?>>
                        <TR>
                        <?php 
                        $cor1 = TD_COLOR;
                        print  "<TD bgcolor=$cor1 nowrap><b>InvMon - Controle de Inventário  -  Usuário: <font color=red>$s_usuario</font></b></TD>";
                        if ($s_nivel==1)
                        {
                                
								echo menu_usuario_admin(TD_COLOR);
                        } 
						else
						        echo menu_usuario();
                        ?>
                        </TR>
                </TABLE>
        </TD>
</TABLE>

<BR>
<B>Inclusão de modelos de equipamentos</B>
<BR>

<FORM method="POST" action=<?php _SELF?>>
<TABLE border="1"  align="center" width="100%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Modelo:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" name="modelo_desc" maxlength="30" size="100"></TD>
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
                        $erro="não";

                        if (empty($modelo_desc))
                        {
                                $aviso = "Dados incompletos";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM modelos WHERE modelo_desc='$modelo_desc'";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);

                        if ($linhas > 0)
                        {
                                $aviso = "Esse modelo já está cadastrado!";
                                $erro = "sim";
                        }

                        $query = "SELECT * FROM modelos";
                        $resultado = mysql_query($query);
                        $linhas = mysql_numrows($resultado);
                        $num=0;
                        if ($linhas>0)
                                $num = mysql_result($resultado,$linhas-1,0);
                        $num++;

                        if ($erro == "não")
                        {
                                $query = "INSERT INTO modelos (modelo_desc) values ('$modelo_desc')";
                                $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "ERRO ao incluir modelo.";
                                }
                                else
                                {
                                        $aviso = "OK. Modelo incluido com sucesso.";
                                }
                        }
                        $origem = "incluir_modelo.php";
                        session_register("aviso");
                        session_register("origem");
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                }
        ?>


</TABLE>
</FORM>

</body>
</html>
