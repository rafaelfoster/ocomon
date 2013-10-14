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
  */session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$auth = new auth;

	if (isset($_GET['popup'])) {
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
?>

		<p align='center'><b><?php print TRANS('TTL_ALTER_PASS');?></b></p>
		<FORM method="POST" action="<?php print $_SERVER['PHP_SELF'];?>" onSubmit="return valida()">
		<center><TABLE border="0"  align="center" width="10%" bgcolor=<?php print BODY_COLOR?>>
        <TR>
                <TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><?php print TRANS('TTL_CURRENT_PASS'); ?>:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="password" name="passwordAtual" class='logon' id='idSenhaAtual'></TD>
	</tr>
	<tr>
         		<TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><?php print TRANS('TTL_NEWS_PASS');?></TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><input type="password" name="password" class='logon' id='idSenha'></TD>
	</TR>
	<tr>
        		<TD width="20%" align="left" bgcolor=<?php print TD_COLOR?>><?php print TRANS('TTL_CONFIRM');?>:</TD>
                <TD width="80%" align="left" bgcolor=<?php print BODY_COLOR?>><INPUT type="password" name="password2" class='logon' id='idSenha2'>

			</TD>
	</tr>
	<tr>

                <TD align="center" width="25%" bgcolor=<?php print BODY_COLOR?>><input type="submit" class='button'  value="<?php print TRANS('BT_ALTER');?>" name="ok">
                </TD>
			<TD align="left" width="25%" bgcolor=<?php print BODY_COLOR?>><INPUT type="button"  class='button' value="<?php print TRANS('BT_CANCEL');?>" name="desloca" ONCLICK="javascript:history.back()">
                </TD>


        </TR>
		</table></center>
	</form>

<?php 
	if (isset($_POST['ok']))
        {
		$erro=false;

		if (($_POST['password'] != $_POST['password2'])or (!isset($_POST['password']))) {
			$erro=true;
			$aviso = "Você não digitou a mesma senha nas duas vezes!";
		}

		if (!$erro) {
			$password = md5($_POST['password']);
			if ($_POST['passwordAtual'] != $_SESSION['s_senha']) {
				$erro = true;
				$aviso = "Senha atual não confere";
			}
		}

		if (!isset($_POST['password']) or !isset($_POST['password2']))
		{
			$aviso = "Dados incompletos";
			$erro = true;
		}

		if (!$erro)
		{
			$query = "UPDATE usuarios SET password='".$password."' WHERE login = '".$_SESSION['s_usuario']."'";
			$resultado = mysql_query($query);
			if ($resultado == 0)
			{
				$aviso = "ERRO ao alterar senha no sistema.";
			}
			else
			{
				$aviso = "OK. Senha alterada com sucesso.";
			}
		}

       		?>
		<script language="javascript">
		<!--
			mensagem('<?php print $aviso;?>');
			history.back();
		//-->
		</script>
		<?php 

        }
        ?>
<script type="text/javascript">
<!--
	function compPass (){
		var obj = document.getElementById('idSenha');
		var obj2 = document.getElementById('idSenha2');
		if (obj.value != obj2.value) {
			alert('As senhas digitadas não conferem!');
			return false;
		} else
			return true;
	}

	function valida(){


		var ok = validaForm('idSenhaAtual','','Senha Atual',1);
		if (ok) var ok = validaForm('idSenha','ALFANUM','Senha',1);
		if (ok) var ok = validaForm('idSenha2','ALFANUM','Senha',1);
		if (ok) var ok = compPass();

		return ok;
	}
-->
</script>

	</body>
	</html>
<?php 
?>