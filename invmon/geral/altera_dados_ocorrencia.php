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

$hoje = date("Y-m-d H:i:s");
        $query = "select * from materiais where mat_cod='$mat_cod'";
        $resultado = mysql_query($query);

?>

<html>
<BODY bgcolor=<?php print BODY_COLOR?>>

<?php 
        if ($s_usuario!="admin")
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
<B>Alterar Registro</B>
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
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" name="mat_nome" value="<?php print mysql_result($resultado,0,1);?>" maxlength="100" size="100"></TD>

        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Quantidade:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" name="mat_qtd" value="<?php print mysql_result($resultado,0,2);?>" maxlength="100" size="100"></TD>

        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Caixa:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" name="mat_caixa" value="<?php print mysql_result($resultado,0,3);?>" maxlength="100" size="100"></TD>

        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Data:</TD>
                 <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><?php print mysql_result($resultado,0,4);?></TD>
        </TABLE>
        </TR>
        <TR>
        <TABLE border="1"  align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>>Obs:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="text" name="mat_obs" value="<?php print mysql_result($resultado,0,5);?>" maxlength="100" size="100"></TD>

        </TABLE>
        </TR>



        <TR>
        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
                <BR>
                <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><input type="submit" value="  Ok  " name="Liberar">
                        <input type="hidden" name="rodou" value="sim">
                </TD>
                <TD align="center" width="50%" bgcolor=<?php print BODY_COLOR?>><INPUT type="reset" value="Cancelar" name="cancelar"></TD>
        </TABLE>
        </TR>
        <TABLE  border="0" cellpadding="0" cellspacing="0" align="center" width="100%" bgcolor=<?php print TD_COLOR?>>
        <TR>
                <TD align="center" width="100%" bgcolor=<?php print BODY_COLOR?>><a href=abertura.php>Voltar</a></TD>
        </TR>
        </TABLE>

        <?php 
                if ($rodou == "sim")
                {
                        $erro = "não";

                        if ($erro == "não")
                        {

                          $data = datam($hoje);
                         $query = "UPDATE materiais SET mat_nome='$mat_nome', mat_qtd='$mat_qtd', mat_caixa='$mat_caixa', mat_obs='$mat_obs' WHERE (mat_cod='$mat_cod')";
                         $resultado = mysql_query($query);
                                if ($resultado == 0)
                                {
                                        $aviso = "Um erro ocorreu ao tentar alterar o Registro.";
                                }
                                else
                                {
                                        $aviso = "Registro Alterado com sucesso no sistema.";
                                }
                        }
                        $origem = "abertura.php";
                        session_register("aviso");
                        session_register("origem");
                        echo "<META HTTP-EQUIV=REFRESH CONTENT=\"0;URL=mensagem.php\">";
                }
        ?>

</TABLE>
</FORM>

</body>
</html>
