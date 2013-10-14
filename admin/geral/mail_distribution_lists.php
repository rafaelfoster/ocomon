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
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

    	print "<BR><B>".TRANS('TTL_CONFIG_MAIL_DISTR_LISTS').":</b><BR><br>";
        print "<TD align='left'>".
        		"<input type='button' class='button' id='idBtIncluir' value='".TRANS('ACT_NEW')."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=new&cellStyle=true');\">".
        	"</TD><br><BR>";

		print "<form name='form1' action='".$_SERVER['PHP_SELF']."' method='post' onSubmit='return valida()';>"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='70%'>";



		$query = "SELECT * FROM mail_list ";
		if (isset($_GET['cod'])) {
			$query .= "WHERE ml_cod=".$_GET['cod']."";
		}
		$resultado = mysql_query($query) or die (TRANS('ERR_QUERY'));

	if ((empty($_GET['action'])) and empty($_POST['submit'])){


		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('MSG_NO_RECORDS'));
		}
		else
		{
				$cor=TD_COLOR;
				$cor1=TD_COLOR;
				$linhas = mysql_numrows($resultado);

				print "<TABLE border='0' cellpadding='1' cellspacing='0' width='100%'>";
				print "<tr class='header'>";
				print "<td class='line'>".TRANS('ML_SIGLA')."</td><td class='line'>".TRANS('ML_DESC')."</td>".
					"<td class='line'>".TRANS('ML_ADDRESS_TO')."</td>".
					"<td class='line'>".TRANS('ML_ADDRESS_CC')."</td>".
					"</td><td class='line'>".TRANS('ACT_EDIT')."</td>";
				print "<td class='line'>".TRANS('COL_DEL','')."</TD>";
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
					print "<td class='line'>".$row['ml_sigla']."</td><td class='line'>".$row['ml_desc']."</td>".
						"<td class='line'>".$row['ml_addr_to']."</td>".
						"<td class='line'>".NVL($row['ml_addr_cc'])."</td>";
					print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['ml_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
					print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['ml_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
					print "</tr>";
				}

				print "</table>";
		}

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td>".TRANS('ML_SIGLA')."</td><td><input type='text' class='text' name='ml_sigla' id='idSigla' value='".$row['ml_sigla']."'></td></tr>";
		print "<tr><td>".TRANS('ML_DESC')."</td><td><textarea class='textarea2' name='ml_desc' id='idMlDesc'>".$row['ml_desc']."</textarea></td></tr>";

		print "<tr><td>".TRANS('ML_ADDRESS_TO')."</td><td><textarea name='ml_address' class='textarea2' id='idMlList'>".$row['ml_addr_to']."</textarea></td></tr>";

		print "<tr><td>".TRANS('ML_ADDRESS_CC')."</td><td><textarea name='ml_address_cc' class='textarea2' id='idMlListCc'>".$row['ml_addr_cc']."</textarea></td></tr>";

		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_EDIT')."'></td>";
		print "<input type='hidden' value='".$_GET['cod']."' name='cod'>";
		print "<td><input type='reset' name='reset' class='button'  value='".TRANS('BT_CANCEL')."' onclick=\"redirect('".$_SERVER['PHP_SELF']."')\"></td></tr>";

	} else
	if (isset($_GET['action']) && $_GET['action']=="new"){

		//$row = mysql_fetch_array($resultado);

		print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td>".TRANS('ML_SIGLA').":</td><td><input type='text' name='ml_sigla' id='idSigla' class='text'></td></tr>";
		print "<tr><td>".TRANS('ML_DESC').":</td><td><textarea name='ml_desc' id='idMlDesc' class='textarea2'></textarea></td></tr>";
		print "<tr><td>".TRANS('ML_ADDRESS_TO')."</td><td><textarea name='ml_address' class='textarea2' id='idMlList'></textarea></td></tr>";
		print "<tr><td>".TRANS('ML_ADDRESS_CC')."</td><td><textarea name='ml_address_cc' class='textarea2' id='idMlListCc'></textarea></td></tr>";


		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_CAD')."'></td>";
		print "<td><input type='reset' name='reset' class='button'  value='".TRANS('BT_CANCEL')."' onclick=\"redirect('".$_SERVER['PHP_SELF']."')\"></td></tr>";


	} else
	if (isset($_POST['submit']) && $_POST['submit'] == TRANS('BT_CAD')){

		$qry = "INSERT INTO mail_list (ml_sigla, ml_desc, ml_addr_to, ml_addr_cc) values ".
				"('".noHtml($_POST['ml_sigla'])."', '".noHtml($_POST['ml_desc'])."', '".noHtml($_POST['ml_address'])."', ".
				"'".noHtml($_POST['ml_address_cc'])."');";
		$execQry = mysql_query($qry) or die(mysql_error());

		print "<script>mensagem('".TRANS('OK_CAD','',0)."!'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else
	if (isset($_POST['submit']) && $_POST['submit'] == TRANS('BT_EDIT')){


		$qry = "UPDATE mail_list SET ".
				"ml_sigla = '".noHtml($_POST['ml_sigla'])."', ml_desc = '".noHtml($_POST['ml_desc'])."', ".
				"ml_addr_to = '".noHtml($_POST['ml_address'])."', ".
				"ml_addr_cc = '".noHtml($_POST['ml_address_cc'])."' ".
			" WHERE ml_cod = ".$_POST['cod']."";

		$exec= mysql_query($qry) or die(TRANS('ERR_EDIT'));

		print "<script>mensagem('".TRANS('OK_EDIT')."!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$query2 = "DELETE FROM mail_list WHERE ml_cod='".$_GET['cod']."'";
		$resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
				$aviso = TRANS('ERR_DEL');
		}
		else
		{
				$aviso = TRANS('OK_DEL');
		}
		print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}


	print "</table>";
	print "</form>";

	?>
	<script language="JavaScript">

		function valida(){
			var ok = false;

			var ok = validaForm('idSigla','','<?print TRANS('TPL_SIGLA')?>',1);
			if (ok) var ok = validaForm('idMlDesc','','<?print TRANS('ML_DESC')?>',1);
			if (ok) var ok = validaForm('idMlList','MULTIEMAIL','<?print TRANS('ML_ADDRESS_TO')?>',1);
			if (ok) var ok = validaForm('idMlListCc','MULTIEMAIL','<?print TRANS('ML_ADDRESS_CC')?>',1);

			return ok;
		}
	//-->
	</script>
	<?


print "</body>";
print "</html>";

?>