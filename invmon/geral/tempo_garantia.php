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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

        print "<BR><B>".TRANS('ADM_WARRANTY','Administração de períodos de garantia').":</B><BR>";

	$query = "SELECT * from tempo_garantia order by tempo_meses";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) and !isset($_POST['submit'])) {

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
			print "<TR class='header'><td class='line'><b>".TRANS('FIELD_TIME_MONTH')."</b></TD><td class='line'><b>".TRANS('COL_EDIT')."</b></TD><td class='line'><b>".TRANS('COL_DEL')."</b></TD>";
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

				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['tempo_meses']."</TD>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['tempo_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
				print "<td class='line'><a onClick=\"confirma('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."?action=excluir&cod=".$row['tempo_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
				print "</TR>";
			}
			print "</TABLE>";
		}

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {

		print "<B>".TRANS('CADASTRE_TEMPO_GARANTIA','Cadastro de tempo de garantia').":<br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>".TRANS('FIELD_TIME_MONTH')."</td><td class='line'><input type='text' class='text' name='tempo' id='idTempo'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='".TRANS('bt_cadastrar')."'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='".TRANS('bt_cancelar')."' onclick=\"redirect('".$_SERVER['PHP_SELF']."')\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {
		$qry = "SELECT * from tempo_garantia where tempo_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>".TRANS('TTL_EDIT_RECORD').":<br>";
		print "<form name='alter' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>".TRANS('FIELD_TIME_MONTH')."</b></td><td class='line'><input type='text' class='text' name='tempo' id='idTempo' value='".$rowAlter['tempo_meses']."'>";
		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='".TRANS('BT_ALTER','',0)."'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='".TRANS('bt_cancelar','',0)."' onclick=\"redirect('".$_SERVER['PHP_SELF']."')\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if (isset($_GET['action']) &&  $_GET['action']=="excluir"){
		$texto = "";
		$qryBusca1 = "SELECT * from equipamentos where comp_garant_meses = ".$_GET['cod']."";
		$execBusca1 = mysql_query($qryBusca1);
		$achou1 = mysql_numrows($execBusca1);
		if ($achou1) $texto = "equipamentos";

		$qryBusca2 = "SELECT * from estoque where estoq_warranty = ".$_GET['cod']."";
		$execBusca2 = mysql_query($qryBusca2);
		$achou2 = mysql_numrows($execBusca2);
		if ($achou2) $texto = "estoque";


		if ($achou2||$achou1) {
			print "<script>mensagem('".TRANS('MSG_CANT_DEL').": ".$texto." ".TRANS('LINKED_TABLE')."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
			exit;
		} else {

			$qry = "DELETE FROM tempo_garantia where tempo_cod = ".$_GET['cod']."";
			$exec = mysql_query($qry) or die (TRANS('ERR_DEL'));

			print "<script>mensagem('".TRANS('OK_DEL')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
	} else

	if ($_POST['submit']==TRANS('bt_cadastrar')){
		if (isset($_POST['tempo'])){
			$qry = "select * from tempo_garantia where tempo_meses='".$_POST['tempo']."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				print "<script>mensagem('".TRANS('MSG_RECORD_EXISTS','',0)."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
			} else {

				$qry = "INSERT INTO tempo_garantia (tempo_meses) values ('".noHtml($_POST['tempo'])."')";
				$exec = mysql_query($qry) or die (TRANS('ERR_QUERY'));

				print "<script>mensagem('".TRANS('OK_INSERT')."!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
			}
		} else {
			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}

	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){
		if (!empty($_POST['tempo'])){
			$qry = "UPDATE tempo_garantia set tempo_meses='".noHtml($_POST['tempo'])."' where tempo_cod=".$_POST['cod']."";
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
		var ok = validaForm('idTempo','','Tempo de garantia',1);
		//if (ok) var ok = validaForm('idData','DATA-','Data',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);

		return ok;
	}
-->
</script>
<?php 
print "</html>";

?>