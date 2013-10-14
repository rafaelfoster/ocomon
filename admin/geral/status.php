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
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	print "<BR><B>".TRANS('ADM_STATUS')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";




		$query = "SELECT S.*, STC.*  FROM `status`  as S left join status_categ as STC on S.stat_cat = STC.stc_cod ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE S.stat_id = ".$_GET['cod']." ";
		}
		$query .=" ORDER  BY S.status";
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY')."!");
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		//print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir novo Status</a></TD></TR>";
		print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		if (mysql_num_rows($resultado) == 0)
		{
			echo mensagem(TRANS('MSG_NO_RECORDS','',0));
		}
		else
		{
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='5'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('RECORDS_IN_SYSTEM').".</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_STATUS')."</TD><td class='line'>".TRANS('COL_DEPS','Dependência')."</TD><td class='line'>".TRANS('COL_PANEL','Painel')."</TD>".
				"<td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";

			$j=2;
			while ($row = mysql_fetch_array($resultado))
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

				if ($row['stat_painel'] == 1) $painel = TRANS('PANEL_UPPER'); else
				if ($row['stat_painel'] == 2) $painel = TRANS('PANEL_MAIN'); else
				if ($row['stat_painel'] == 3) $painel = TRANS('PANEL_OCULT'); else
					$painel = "";


				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['status']."</td>";
				print "<td class='line'>".$row['stc_desc']."</td>";
				print "<td class='line'>".$painel."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['stat_id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['stat_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
			//print "</TABLE>";
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('CADASTRE_STAT')."</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_STATUS').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='status' class='text' id='idStatus'></td>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_DEPS').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			$sql = "select * from status_categ order by stc_desc";
			$exec_sql = mysql_query($sql);
			print "<select class='text' name='categoria' id='idCategoria'>";
				print "<option value='null' selected>".TRANS('SEL_DEPS')."</option>";
				while ($rowCateg = mysql_fetch_array($exec_sql)) {
					print "<option value=".$rowCateg['stc_cod'].">".$rowCateg['stc_desc']."</option>";
				}
				print "</select>";

		print "</td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PANEL').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='painel' id='idPainel'>";
			print "<option value='null' selected>Selecione o painel de exibição</option>";
			print "<option value='1'>".TRANS('PANEL_UPPER')."</option>";
			print "<option value='2'>".TRANS('PANEL_MAIN')."</option>";
			print "<option value='3'>".TRANS('PANEL_OCULT')."</option>";
			print "</select>";

		print "</td>";
		print "</tr>";

		print "<TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);
		if ($row['stat_id'] == 1 || $row['stat_id'] == 2 || $row['stat_id'] == 4) { //These are hard status, it should not have its codes changed
			$STATUS = "disabled";
		} else {
			$STATUS = "";
		}

		print "<BR><B>".TRANS('TTL_EDIT_RECORD')."</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_STATUS').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='status' id='idStatus' value='".$row['status']."'></td>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_DEPS').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			$sql = "select * from status_categ order by stc_desc";
			$exec_sql = mysql_query($sql);
			print "<select class='select' name='categoria' id='idCategoria' ".$STATUS.">";
			print "<option value='null'>".TRANS('SEL_DEPS')."</option>";
			while ($rowCateg = mysql_fetch_array($exec_sql)) {
				print "<option value=".$rowCateg['stc_cod']." ";
				if ($rowCateg['stc_cod'] == $row['stat_cat']){
					print " selected";
				}
				print ">".$rowCateg['stc_desc']."</option>";
			}
			print "</select>";

		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_PANEL').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='painel' id='idPainel' ".$STATUS.">";
				print "<option value='null'>".TRANS('SEL_PANEL')."</option>";
				print "<option value='1' ";
					if ($row['stat_painel'] == 1) print "selected";
				print ">".TRANS('PANEL_UPPER')."</option>";
				print "<option value='2' ";
					if ($row['stat_painel'] == 2) print "selected";
				print ">".TRANS('PANEL_MAIN')."</option>";
				print "<option value='3' ";
					if ($row['stat_painel'] == 3) print "selected";
				print ">".TRANS('PANEL_OCULT')."</option>";
			print "</select>";

		print "</TD>";
        	print "</TR>";



		print "<TR>";
		print "<BR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";

		$sql_2 = "SELECT * FROM ocorrencias where `status` ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="ocorrencias, ";

		if ($_GET['cod'] == 1 || $_GET['cod'] == 2 || $_GET['cod'] == 4) {
			print "<script>mensagem('".TRANS('MSG_DEFAULT_STATUS')."');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		} else
		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_CANT_DEL').": ".$texto." ".TRANS('LINKED_TABLE')."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM status WHERE stat_id='".$_GET['cod']."'";
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


	} else

	if ($_POST['submit'] == TRANS('bt_cadastrar')){

		$erro=false;

		$qryl = "SELECT * FROM `status` WHERE status='".$_POST['status']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
			$aviso = TRANS('MSG_RECORD_EXISTS');
			$erro = true;
		}

		if (!$erro)
		{

			$query = "INSERT INTO status (status, stat_cat, stat_painel) values ('".noHtml($_POST['status'])."',".$_POST['categoria'].",".$_POST['painel'].")";
			$resultado = mysql_query($query);
			if ($resultado == 0)
			{
				$aviso = TRANS('ERR_INSERT');
			}
			else
			{
				$aviso = TRANS('OK_INSERT');
			}
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')){

		$query2 = "UPDATE status SET status='".noHtml($_POST['status'])."', stat_cat=".$_POST['categoria'].", stat_painel=".$_POST['painel']."  WHERE stat_id='".$_POST['cod']."'";

		$resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
			$aviso =  TRANS('ERR_EDIT');
		}
		else
		{
			$aviso =  TRANS('OK_EDIT');
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	print "</table>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idStatus','','Status',1);
		if (ok) var ok = validaForm('idCategoria','COMBO','Dependência',1);
		if (ok) var ok = validaForm('idPainel','COMBO','Painel',1);

		return ok;
	}

-->
</script>


<?
print "</body>";
print "</html>";
