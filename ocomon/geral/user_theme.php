<? /*                        Copyright 2005 Flávio Ribeiro

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

	if ($_SESSION['s_allow_change_theme'] == 0){
		print "<script>mensagem('".TRANS('MSG_SORRY')."\\n".TRANS('MSG_ALTER_THEME_DISABLE_ADMIN')."'); history.back();</script>";
		exit;
	}

	print "<HTML>";
	print "<head>";
	print "</head>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

	$sqlUserTheme = "SELECT * FROM uthemes WHERE uth_uid = ".$_SESSION['s_uid']."";
	$execUserTheme = mysql_query($sqlUserTheme) or die (TRANS('MSG_ERR_RESCUE_INFO_THEME_USER'));
	$rowUth = mysql_fetch_array($execUserTheme);
	$hasTheme = mysql_num_rows($execUserTheme);


	print "<BR><B>".TRANS('TTL_THEME').": &nbsp;</b><BR>";
	print "<form name='form1' method='post' action='".$_SERVER['PHP_SELF']."' >"; //onSubmit=\"return valida()\"
	print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='60%' >";

	if (!isset($_POST['submit'])) {


		$sql = "SELECT * FROM temas ORDER BY tm_nome";
		$exec = mysql_query($sql) or die(TRANS('MSG_ERR_RESCUE_INFO_THEME'));
		$qtd = mysql_num_rows($exec);
		if ($qtd==0){
			print "<script>mensagem('".TRANS('MSG_NOT_THEME_SAVE')."'); window.self.close();</script>";
			exit;
		}

		print "<tr><td>".TRANS('FIELD_THEME')."</td><td>";
		print "<select name='tema' id='idNomeTema' class='text'>";
			print "<option value='-1'>".TRANS('SEL_NONE')."</option>";

			while ($rowTema = mysql_fetch_array($exec)){
				print "<option value=".$rowTema['tm_id']." ";
				if ($rowTema['tm_id'] == $rowUth['uth_thid']) {
					print " selected";
				}
				print ">".$rowTema['tm_nome']."</option>";
			}
			//print "<option value='-2'>Nenhum</option>";//VAI UTILIZAR O TEMA DEFINIDO PELO ADMINISTRADOR
			//print "<option value='-3'>Default</option>";// VAI UTILIZAR O TEMA DEFAULT DA VERSÃO DO OCOMON
		print "</select>";
		print "</td>";
		print "</tr>";
		//print "</tr><tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr>";
		print "<tr>";
		//print "<input type='hidden' name='marca' value='".$rowTema['tm_color_marca']."'>";
		//print "<input type='hidden' name='destaca' value='".$rowTema['tm_color_destaca']."'>";
		print "<td align='center'><input type='submit' name='submit' class='button' value='".TRANS('BT_LOAD')."'></td>".
				"<td align='center'><input type='button' name='cancelar' class='button' value='".TRANS('BT_CANCEL')."' onClick=\"javascript:history.back();\"></td>";
		print "</tr>";

	} else
	if (isset($_POST['submit']) ) {
		//dump($_REQUEST); exit;

/*		if ($_POST['tema'] == -1){
			print "<script>mensagem('É necessário escolher um tema!'); history.back();</script>";
			exit;
		} else*/
		if ($_POST['tema'] == -1){
			$sqlU = "DELETE FROM uthemes WHERE uth_uid = ".$_SESSION['s_uid']."";
			$execU = mysql_query($sqlU) or die (TRANS('MSG_NOT_ACCESS_INFO_THEME').'<BR>'.$sqlU);
			print "<script>mensagem('".TRANS('MSG_ALTER_THEME_SUCESS')."'); window.open('../../index.php','_parent','');  </script>";
			exit;
		} //else
		//if ($_POST['tema'] == -3){

		//}

		if ($hasTheme>0){
			$sql = "UPDATE uthemes SET uth_thid= ".$_POST['tema']." WHERE uth_uid = ".$_SESSION['s_uid']."";
			$exec = mysql_query($sql) or die(TRANS('MSG_ERR_UPDATE_REG').'<br>'.$sql);
		} else {
			$sql = "INSERT INTO uthemes (uth_uid, uth_thid) values (".$_SESSION['s_uid'].", ".$_POST['tema'].")";
			$exec = mysql_query($sql) or die(TRANS('MSG_ERR_UPDATE_REG').'<br>'.$sql);
		}


		$qry = "SELECT * FROM uthemes u, temas t WHERE uth_uid = ".$_SESSION['s_uid']." and u. uth_thid = t.tm_id";
		$execQry = mysql_query($qry) or die (TRANS('MSG_ERR_RESCUE_INFO_THEME').'<BR>'.$qry);
		$row = mysql_fetch_array($execQry);

		$_SESSION['s_colorDestaca'] = $row['tm_color_destaca'];
		$_SESSION['s_colorMarca'] = $row['tm_color_marca'];

		//print "<script>mensagem('Tema carregado com sucesso! Tecle F5 para atualizar a página!'); window.opener.location.reload(); window.self.close(); </script>";
		print "<script>mensagem('".TRANS('MSG_THEME_LOAD_SUCESS')."'); window.open('../../index.php','_parent','');  </script>"; //?LOAD=ADMIN

	}


	print "<table>";
	print "</form>";


	?>
<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idNomeTema','COMBO','<?print TRANS('MSG_NAME_THEME'); ?>',1);

		return ok;
	}
-->
</script>
	<?

	print "</body>";
	print "</html>";
?>