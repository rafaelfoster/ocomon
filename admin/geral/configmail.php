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


	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1, 'helpconfigmail.php');

	print "<BR><B>".TRANS('TTL_CONFIG_MAIL').":</b><BR>";


	$query = "SELECT * FROM mailconfig";
	$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
	$row = mysql_fetch_array($resultado);



	if ((empty($_GET['action'])) and empty($_POST['submit'])){

                print "<br><TD align='left'>".
        		"<input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_EDIT_CONFIG')."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cellStyle=true');\">".
        	"</TD><br><BR>";

        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem(TRANS('ALERT_CONFIG_EMPTY'));
        }
        else
        {
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			$linhas = mysql_numrows($resultado);
			print "<td>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
			print "<tr><td colspan='2'><b>".TRANS('TTL_CONFIG','Configuração').":</b></td></tr>";
			print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><td>".TRANS('OPT_USE_SMTP','Utiliza SMTP')."</td><td>".transbool($row['mail_issmtp'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_SMTP_ADDRESS','Endereço SMTP')."</td><td>".$row['mail_host']."</td></tr>";
			print "<tr><td>".TRANS('OPT_NEED_AUTH','Precisa de autenticação')."</td><td>".transbool($row['mail_isauth'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_USER','Usuário')."</td><td>".$row['mail_user']."</td></tr>";
			print "<tr><td>".TRANS('OPT_ADDRESS_FROM','Endereço de envio (FROM)')."</td><td>".$row['mail_from']."</td></tr>";
			print "<tr><td>".TRANS('OPT_ADDRESS_FROM_NAME','Nome do From (alias)')."</td><td>".$row['mail_from_name']."</td></tr>";
			print "<tr><td>".TRANS('OPT_CONTENT_HTML','Conteúdo HTML')."</td><td>".transbool($row['mail_ishtml'])."</td></tr>";

			print "<tr><td></td><td></td></tr>";

			print "</TABLE>";
        }

	} else

	if ( ((isset($_GET['action']) &&$_GET['action']=="alter")) && empty($_POST['submit'])){


		print "<form name='alter' action='".$_SERVER['PHP_SELF']."' method='post'>"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td>".TRANS('OPT_USE_SMTP')."</td><td>";//.transbool($row['conf_user_opencall'])."</td></tr>";
		print "<select name='issmtp' class='select'>";
		print "<option value='0'";
		if ($row['mail_issmtp'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['mail_issmtp'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_SMTP_ADDRESS')."</td><td>";
		print "<input type='text' class='text' name='host' value='".$row['mail_host']."'>";
		print "</td></tr>";

		print "<tr><td>".TRANS('OPT_NEED_AUTH')."</td><td>";
		print "<select name='isauth' class='select'>";
		print "<option value='0'";
		if ($row['mail_isauth'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['mail_isauth'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_USER_TO_AUTH','Usuário para autenticação')."</td><td>";
		print "<input type='text' class='text' name='user' value='".$row['mail_user']."'>";
		print "</td></tr>";

		print "<tr><td>".TRANS('OPT_PASS_TO_AUTH','Senha para autenticação')."</td><td>";
		print "<input type='password' class='text' name='pass' value='".$row['mail_pass']."'>";
		print "</td></tr>";

		print "<tr><td>".TRANS('OPT_ADDRESS_FROM')."</td><td>";
		print "<input type='text' class='text' name='from' value='".$row['mail_from']."'>";
		print "</td></tr>";

		print "<tr><td>".TRANS('OPT_ADDRESS_FROM_NAME')."</td><td>";
		print "<input type='text' class='text' name='from_name' value='".$row['mail_from_name']."'>";
		print "</td></tr>";


		print "<tr><td>".TRANS('OPT_CONTENT_HTML')."</td><td>";
		print "<select name='ishtml' class='select'>";
		print "<option value='0'";
		if ($row['mail_ishtml'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['mail_ishtml'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";



		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER')."'></td>";
		print "<td><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){


		$qry = "UPDATE mailconfig SET ".
				"mail_issmtp= ".$_POST['issmtp'].", mail_host = '".noHtml($_POST['host'])."', ".
				"mail_isauth = ".$_POST['isauth'].", mail_user = '".noHtml($_POST['user'])."', ".
				"mail_pass = '".noHtml($_POST['pass'])."', mail_from = '".noHtml($_POST['from'])."', ".
				"mail_from_name = '".noHtml($_POST['from_name'])."', mail_ishtml = ".$_POST['ishtml']."";

		$exec= mysql_query($qry) or die(TRANS('ERR_EDIT'));

		print "<script>mensagem('".TRANS('OPT_SUCCES_CONFIG','',0)."!'); redirect('configmail.php');</script>";
	}


print "</body>";
print "</html>";

?>