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

        print "<BR><B>".TRANS('ADM_EQUIP_TYPE').":</B><BR>";

	$query = "SELECT * from tipo_equip order by tipo_nome";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) and !isset($_POST['submit'])) {

		//print "<TD align='right'><a href='tipo_equipamentos.php?action=incluir'>Incluir tipo de equipamento.</a></TD><BR>";
		print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('MSG_NO_RECORDS'));
		}
		else
		{
			$linhas = mysql_numrows($resultado);
			print "<td class='line'>";
			print "<br><br>";
			print "".TRANS('THERE_IS_ARE')." <b>".$linhas."</b> ".TRANS('RECORDS_IN_SYSTEM').".<br>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
			print "<TR class='header'><td class='line'><b>".TRANS('COL_TYPE')."</b></TD><td class='line'><b>".TRANS('COL_EDIT')."</b></TD><td class='line'><b>".TRANS('COL_DEL')."</b></TD>";
			$j=2;
			while ($row=mysql_fetch_array($resultado))
			{
				if ($j % 2)
				{
					$trClass = "lin_par";
				}
				else
				{
					$trClass = "lin_impar";
				}
				$j++;

				//print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['tipo_nome']."</TD>";
				print "<td class='line'><a onClick=\"redirect('tipo_equipamentos.php?action=alter&cod=".$row['tipo_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
				print "<td class='line'><a onClick=\"confirma('".TRANS('ENSURE_DEL')."?','tipo_equipamentos.php?action=excluir&cod=".$row['tipo_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
							print "</TR>";
			}
			print "</TABLE>";
		}

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {

		print "<B>".TRANS('CADASTRE_EQUIP_TYPE').":<br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>".TRANS('COL_TYPE')."</td><td class='line'><input type='text' class='text' name='tipo' id='idTipo'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='".TRANS('bt_cadastrar')."'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='".TRANS('bt_cancelar')."' onclick=\"redirect('tipo_equipamentos.php')\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {
		$qry = "SELECT * from tipo_equip where tipo_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>".TRANS('TTL_EDIT_RECORD').":<br>";
		print "<form name='alter' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>".TRANS('COL_TYPE')."</b></td><td class='line'><input type='text' class='text' name='tipo' id='idTipo' value='".$rowAlter['tipo_nome']."'>";
		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='".TRANS('BT_ALTER','',0)."'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='".TRANS('bt_cancelar','',0)."' onclick=\"redirect('tipo_equipamentos.php')\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if (isset($_GET['action']) &&  $_GET['action']=="excluir"){
		$qryBusca = "SELECT E.*, T.* from equipamentos E, tipo_equip T where E.comp_tipo_equip = T.tipo_cod and T.tipo_cod = ".$_GET['cod']."";
		$execBusca = mysql_query($qryBusca);
		$achou = mysql_numrows($execBusca);
		if ($achou) {
			print "<script>mensagem('".TRANS('MSG_CANT_DEL').": ".$texto." ".TRANS('LINKED_TABLE')."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
			exit;
		} else {

			$qry = "DELETE FROM tipo_equip where tipo_cod = ".$_GET['cod']."";
			$exec = mysql_query($qry) or die (TRANS('ERR_DEL'));

			print "<script>mensagem('".TRANS('OK_DEL')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
	} else

	if ($_POST['submit']==TRANS('bt_cadastrar')){
		if (isset($_POST['tipo'])){
			$qry = "select * from tipo_equip where tipo_nome='".$_POST['tipo']."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				print "<script>mensagem('".TRANS('MSG_RECORD_EXISTS','',0)."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
			} else {

				$qry = "INSERT INTO tipo_equip (tipo_nome) values ('".noHtml($_POST['tipo'])."')";
				$exec = mysql_query($qry) or die (TRANS('ERR_QUERY'));

				print "<script>mensagem('".TRANS('OK_INSERT')."!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
			}
		} else {
			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}

	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){
		if (!empty($_POST['tipo'])){
			$qry = "UPDATE tipo_equip set tipo_nome='".noHtml($_POST['tipo'])."' where tipo_cod=".$_POST['cod']."";
			$exec= mysql_query($qry) or die(TRANS('ERR_QUERY'));

			print "<script>mensagem('".TRANS('OK_EDIT')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		} else {
			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
	}

print "</body>";
?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idTipo','','Tipo de equipamento',1);
		//if (ok) var ok = validaForm('idData','DATA-','Data',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);

		return ok;
	}
-->
</script>
<?
print "</html>";

?>