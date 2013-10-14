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
	session_start();
	//session_destroy();
	if (!isset($_SESSION['s_language']))  $_SESSION['s_language']= "pt_BR.php";

	include ("../../includes/classes/conecta.class.php");
	//include ("../../includes/classes/auth.class.php");

	include ("../../includes/functions/funcoes.inc");
	include ("../../includes/javascript/funcoes.js");

	include ("../../includes/config.inc.php");
	//include ("../../includes/languages/".LANGUAGE."");
	include ("../../includes/queries/queries.php");

	print "<link rel='stylesheet' type='text/css' href='../../includes/css/estilos.css.php'>";
	print "<link rel='shortcut icon' href='../../includes/icons/favicon.ico'>";


	print "<html><head></head>";
	print "<body>";

	$conec = new conexao;
	$conec->conecta('MYSQL');
	$qry = $QRY["useropencall"];
	$exec = mysql_query($qry) or die('Erro na busca das informações de configuração!');
	$rowconf = mysql_fetch_array($exec);

	if (!$rowconf['conf_user_opencall']) {
		print "<script>mensagem('A abertura de chamados pelo usuário final não está habilitada');".
				"window.close();</script>";
		exit;
	}

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die ("ERRO AO TENTAR ACESSAR A TABELA CONFIG! CERTIFIQUE-SE DE QUE A TABELA EXISTE!");;
	$row_config = mysql_fetch_array($exec_config);



		print  "<TABLE  STYLE='{border-bottom:  solid #999999; }' cellspacing='1' border='0' cellpadding='1' align='center' width='100%'>".//#5E515B
				"<TR>".
					"<TD nowrap width='100%'><b>OcoMon - Módulo de Ocorrências</b></td>";
		print "</TR>".
			"</TABLE>";

	if (!isset($_POST['submit'])) {
		print "<B>Cadastro de Usuário:<br><br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>Login</td><td class='line'><input type='text' class='text' name='login' id='idLogin'></td>";
		print "</tr>";
		print "<tr>";
		print "<td class='line'>Nome Completo</td><td class='line'><input type='text' class='text' name='nome' id='idNome'></td>";
		print "</tr>";
		print "<tr>";
		print "<td class='line'>E-mail</td><td class='line'><input type='text' class='text' name='email' id='idEmail'></td>";
		print "</tr>";
		print "<tr>";
		print "<td class='line'>Senha</td><td class='line'><input type='password' class='text' name='senha' id='idSenha'></td>";
		print "</tr>";
		print "<tr>";
		print "<td class='line'>Repita a senha</td><td class='line'><input type='password' class='text' name='senha2' id='idSenha2'></td>";
		print "</tr>";



		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='Cadastrar'></td>";

		print "<td class='line'><input type='button' class='button'  name='fecha' value='Fechar' onClick=\"javascript:window.close()\"></td></tr>";

		print "</table>";
		print "</form>";

	} else

	if (isset($_POST['submit'])) {
		$erro = false;

		$query = "SELECT * FROM usuarios u WHERE u.login = '".$_POST['login']."'";
		$exec = mysql_query($query);
		$regs = mysql_num_rows($exec);
		$row = mysql_fetch_array ($exec);
		if ($regs > 0) {
			$msg = "Login \"".$_POST['login']."\" já existe no sistema, por favor escolha outro login!";
		} else {

			//$passwd = md5($_POST['senha']);
			$random = random();
			$query= "INSERT INTO utmp_usuarios (utmp_cod,utmp_login, utmp_nome, utmp_email, utmp_passwd, utmp_rand) values ".
					"('','".noHtml($_POST['login'])."','".noHtml($_POST['nome'])."','".$_POST['email']."', md5('".$_POST['senha']."'),'".$random."')";

			$exec = mysql_query($query) or die ('ERRO NA TENTATIVA DE CRIAR USUÁRIO TEMPORÁRIO. SUA SOLICITAÇÃO NÃO FOI PROCESSADA!');

			$msg = "Sua solicitação foi efetuada com sucesso! Aguarde o e-mail de confirmação.";

			$VARS = array();
			$VARS['%login%'] = $_POST['login'];
			$VARS['%usuario%'] = $_POST['nome'];
			$VARS['%site%'] = "<a href='".$row_config['conf_ocomon_site']."'>".$row_config['conf_ocomon_site']."</a>";
			$VARS['%linkconfirma%'] = "<a href='".$row_config['conf_ocomon_site']."/ocomon/geral/confirma.php?rand=".$random."'>".TRANS('MSG_LINK_CONFIRM_SUBSCRIBE')."</a>";//".TRANS('MSG_LINK_CONFIRM_SUBSCRIBE')."

			$qryconf = "SELECT * FROM mailconfig";
			$execconf = mysql_query($qryconf) or die ('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE ENVIO DE E-MAIL!');
			$rowconf = mysql_fetch_array($execconf);

			$event = 'cadastro-usuario';
			$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
			$execmsg = mysql_query($qrymsg) or die('ERRO NO MSGCONFIG');
			$rowmsg = mysql_fetch_array($execmsg);


			send_mail($event, $_POST['email'], $rowconf, $rowmsg, $VARS);
			//$flag = envia_email_new_user($_POST['login'],$_POST['nome'], $_POST['email'],$random,OCOMON_SITE);
		}
			print "<script>mensagem('".$msg."'); window.close();</script>";
	}


print "</body>";
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


		var ok = validaForm('idLogin','ALFANUM','Login',1);
		if (ok) var ok = validaForm('idNome','','Nome Completo',1);
		if (ok) var ok = validaForm('idEmail','EMAIL','E-mail',1);
		if (ok) var ok = validaForm('idSenha','','Senha',1);
		if (ok) var ok = validaForm('idSenha2','','Senha',1);
		if (ok) var ok = compPass();

		return ok;
	}
-->
</script>
<?php 
print "</html>";

?>