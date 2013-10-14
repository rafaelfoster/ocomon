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
  */session_start();

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1,'helpconfigmsg.php');

    print "<BR><B>".TRANS('TTL_CONFIG_SEND_MAIL','Configuração para envio de e-mails').":</b><BR><br>";


		$query = "SELECT * FROM msgconfig ";
		if (isset($_GET['event'])) {
			$query .= "WHERE msg_cod=".$_GET['event']."";
		}

		$resultado = mysql_query($query) or die (TRANS('ERR_QUERY'));
		//$row = mysql_fetch_array($resultado);



	if ((empty($_GET['action'])) and empty($_POST['submit'])){


		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('ALERT_CONFIG_EMPTY'));
		}
		else
		{
				$cor=TD_COLOR;
				$cor1=TD_COLOR;
				$linhas = mysql_numrows($resultado);

				print "<TABLE border='0' cellpadding='1' cellspacing='0' width='100%'>";
				print "<tr class='header'>";
				print "<td class='line'>".TRANS('OPT_EVENT','Evento')."</td><td class='line'>".TRANS('OPT_FROM','FROM')."</td>".
					"<td class='line'>".TRANS('OPT_REPLY_TO','Responder para')."</td><td class='line'>".TRANS('OPT_SUBJECT','Assunto')."</td>".
					"<td class='line'>".TRANS('OPT_HTML_MSG','Msg HTML')."</td>".
					"<td class='line'>".TRANS('OPT_ALTERNATE_MSG','Msg Alternativa')."</td><td class='line'>".TRANS('ACT_EDIT','Editar')."</td>";
				print "</tr>";

				$j = 2;
				while ($row = mysql_fetch_array($resultado)) {
					if ($j % 2) {
							$trClass = "lin_par";
					}
					else {
							$trClass = "lin_impar";
					}
					$j++;
					print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
					print "<td class='line'>".$row['msg_event']."</td><td class='line'>".$row['msg_fromname']."</td>".
						"<td class='line'>".$row['msg_replyto']."</td><td class='line'>".$row['msg_subject']."</td><td class='line'>".$row['msg_body']."</td>".
						"<td class='line'>".$row['msg_altbody']."</td>";
					print "<td class='line'><a onClick=\"redirect('configmsgs.php?action=alter&event=".$row['msg_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
					print "</tr>";
				}

				print "</table>";
		}

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

		print "<form name='alter' action='".$_SERVER['PHP_SELF']."' method='post'>"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='70%'>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr class='header'><td>".TRANS('TTL_EVENT','EVENTO').":</td><td>".$row['msg_event']."</td></tr>";
		print "<tr><td>".TRANS('OPT_FROM','FROM')."</td><td><input type='text' class='text' name='from' value='".$row['msg_fromname']."'></td></tr>";
		print "<tr><td>".TRANS('OPT_REPLY_TO','Responder para')."</td><td><input type='text' class='text' name='replyto' value='".$row['msg_replyto']."'></td></tr>";


		print "<tr><td>".TRANS('OPT_SUBJECT','Assunto')."</td><td><input type='text' class='text' name='subject' value='".$row['msg_subject']."'></td></tr>";
		//print "<tr><td>Msg HTML</td><td><textarea name='body' class='textarea2'>".$row['msg_body']."</textarea></td></tr>";
		print "<tr><td>".TRANS('OPT_HTML_MSG','Msg HTML')."</td><td>";
		?>
		<script type="text/javascript">
  			var oFCKeditor = new FCKeditor( 'body' ) ;
  			oFCKeditor.BasePath = '../../includes/fckeditor/';
			oFCKeditor.Value = '<?print $row['msg_body'];?>';
			oFCKeditor.ToolbarSet = 'ocomon';
			oFCKeditor.Width = '400px';
			oFCKeditor.Height = '100px';
			oFCKeditor.Create() ;
		</script>
		<?
		print "</td></tr>";
		print "<tr><td>".TRANS('OPT_ALTERNATE_MSG','Msg Alternativa')."</td><td><textarea name='altbody' class='textarea2'>".$row['msg_altbody']."</textarea></td></tr>";


		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER','Alterar')."'></td>";
		print "<input type='hidden' value='".$_GET['event']."' name='event'>";
		print "<td><input type='reset' name='reset' class='button'  value='".TRANS('BT_CANCEL','Cancelar')."' onclick=\"redirect('configmsgs.php')\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] = "Alterar"){


		$qry = "UPDATE msgconfig SET ".
				"msg_fromname= '".$_POST['from']."', msg_replyto = '".noHtml($_POST['replyto'])."', ".
				"msg_subject = '".$_POST['subject']."', msg_body = '".$_POST['body']."', ".
				"msg_altbody = '".noHtml($_POST['altbody'])."' WHERE msg_cod = ".$_POST['event']."";

		$exec= mysql_query($qry) or die(TRANS('ERR_EDIT'));

		print "<script>mensagem('".TRANS('OK_EDIT','',0)."!'); redirect('configmsgs.php');</script>";
	}


print "</body>";
print "</html>";

?>