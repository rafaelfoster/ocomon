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
	if (!isset($_SESSION['s_language']))  $_SESSION['s_language']= "en.php";

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
	$exec = mysql_query($qry) or die(TRANS('MSG_ERR_RESCUE_DATA').'!');
	$rowconf = mysql_fetch_array($exec);

	if (!$rowconf['conf_user_opencall']) {
		print "<script>mensagem('".TRANS('MSG_DISABLED_END_USER_TICKET','',0)."');".
				"window.close();</script>";
		exit;
	}

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die (TRANS('ERR_TABLE_CONFIG'));
	$row_config = mysql_fetch_array($exec_config);

	$_SESSION['s_language'] = $row_config['conf_language'];

		print  "<TABLE  STYLE='{border-bottom:  solid #999999; }' cellspacing='1' border='0' cellpadding='1' align='center' width='100%'>".//#5E515B
				"<TR>".
					"<TD nowrap width='100%'><b>".TRANS('MENU_TTL_MOD_OCCO')."</b></td>";
		print "</TR>".
			"</TABLE>";

	if (!isset($_POST['submit'])) {
		print "<B>".TRANS('CADASTRE_USERS').":<br><br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>".TRANS('COL_LOGIN')."</td><td class='line'><input type='text' class='text' name='login' id='idLogin'></td>";
		print "</tr>";
		print "<tr>";
		print "<td class='line'>".TRANS('FULL_NAME')."</td><td class='line'><input type='text' class='text' name='nome' id='idNome'></td>";
		print "</tr>";
		print "<tr>";
		print "<td class='line'>".TRANS('COL_EMAIL')."</td><td class='line'><input type='text' class='text' name='email' id='idEmail'></td>";
		print "</tr>";
		print "<tr>";
		print "<td class='line'>".TRANS('COL_PASS')."</td><td class='line'><input type='password' class='text' name='senha' id='idSenha'></td>";
		print "</tr>";
		print "<tr>";
		print "<td class='line'>".TRANS('RETYPE_PASS')."</td><td class='line'><input type='password' class='text' name='senha2' id='idSenha2'></td>";
		print "</tr>";



		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='".TRANS('BT_CAD')."'></td>";

		print "<td class='line'><input type='button' class='button'  name='fecha' value='".TRANS('LINK_CLOSE')."' onClick=\"javascript:window.close()\"></td></tr>";

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
			$msg = "[".$_POST['login']."] ".TRANS('USERNAME_ALREADY_EXISTS')."";
		} else {

			//$passwd = md5($_POST['senha']);
			$random = random();
			$query= "INSERT INTO utmp_usuarios (utmp_cod,utmp_login, utmp_nome, utmp_email, utmp_passwd, utmp_rand) values ".
					"('','".noHtml($_POST['login'])."','".noHtml($_POST['nome'])."','".$_POST['email']."', md5('".$_POST['senha']."'),'".$random."')";

			$exec = mysql_query($query) or die (TRANS('ERROR_TEMP_USER'));

			$msg = TRANS('AUTO_CADASTRE_SUCCESS');

			$VARS = array();
			$VARS['%login%'] = $_POST['login'];
			$VARS['%usuario%'] = $_POST['nome'];
			$VARS['%site%'] = "<a href='".$row_config['conf_ocomon_site']."'>".$row_config['conf_ocomon_site']."</a>";
			$VARS['%linkconfirma%'] = "<a href='".$row_config['conf_ocomon_site']."/ocomon/geral/confirma.php?rand=".$random."'>".TRANS('MSG_LINK_CONFIRM_SUBSCRIBE')."</a>";//".TRANS('MSG_LINK_CONFIRM_SUBSCRIBE')."

			$qryconf = "SELECT * FROM mailconfig";
			$execconf = mysql_query($qryconf) or die (TRANS('MSG_ERR_RESCUE_SEND_EMAIL').'!');
			$rowconf = mysql_fetch_array($execconf);

			$event = 'cadastro-usuario';
			$qrymsg = "SELECT * FROM msgconfig WHERE msg_event like ('".$event."')";
			$execmsg = mysql_query($qrymsg) or die(TRANS('MSG_ERR_MSCONFIG'));
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
			alert('<?php print TRANS('PASS_DONT_MATCH');?>');
			return false;
		} else
			return true;
	}

	function valida(){


		var ok = validaForm('idLogin','ALFANUM','<?php print TRANS('COL_LOGIN');?>',1);
		if (ok) var ok = validaForm('idNome','','<?php print TRANS('FULL_NAME');?>',1);
		if (ok) var ok = validaForm('idEmail','EMAIL','<?php print TRANS('COL_EMAIL');?>',1);
		if (ok) var ok = validaForm('idSenha','','<?php print TRANS('COL_PASS');?>',1);
		if (ok) var ok = validaForm('idSenha2','','<?php print TRANS('COL_PASS');?>',1);
		if (ok) var ok = compPass();

		return ok;
	}
-->
</script>
<?php 
print "</html>";

?>