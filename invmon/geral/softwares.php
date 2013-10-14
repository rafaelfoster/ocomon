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
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "redirect('".basename($_SERVER['PHP_SELF'])."')";
	}


	print "<BR><B>".TRANS('TTL_ADMIN_SOFT')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

        	$query = "SELECT s.*, l.*, c.*, f.*, fo.* FROM licencas as l, categorias as c, fabricantes as f, softwares as s ".
			"left join fornecedores as fo on fo.forn_cod = s.soft_forn ".
			"WHERE s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod ";
		if (isset($_GET['cod'])) {
			$query.= " AND soft_cod = ".$_GET['cod']." ";
		}
		$query .=" ORDER BY soft_desc";
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY').'<br>'.$query);
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {


		print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>".
				"".TRANS('LINK_CAD_SOFT')."</a></TD>".
				"<TD bgcolor='".BODY_COLOR."'><a href='sw_padrao.php'>".TRANS('SUBTTL_LST_STAND')."</a></TD>".
			"</TR>";
		if (mysql_num_rows($resultado) == 0)
		{
			print "<tr><td align='center'>";
			echo mensagem(TRANS('MSG_NOT_REG_CAD'));
			print "</td>";
			print "</tr>";
		}
		else
		{
			print "<tr><td class='line'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('SW_SOFT_LIST')."</td>";
			print "</tr>";

			print "<TR class='header'><td class='line'>".TRANS('COL_SOFT')."</TD><td class='line'>".TRANS('COL_CAT')."</TD><td class='line'>".TRANS('COL_LICENSE')."</TD><td class='line'>".TRANS('COL_NUMBER_OF_LICENSES')."</TD>".
				"<td class='line'>".TRANS('COL_AVAILABLE')."</TD><td class='line'>".TRANS('COL_VENDOR')."</TD><td class='line'>".TRANS('COL_NF')."</TD><td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD>";

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
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

				$sqlAux = "select count(*) total from hw_sw where hws_sw_cod = ".$row['soft_cod']." ";
				$commitAux = mysql_query($sqlAux);
				$rowAux = mysql_fetch_array($commitAux);
				$dispo = $row['soft_qtd_lic'] - $rowAux['total'];

				print "<td class='line'>".$row['fab_nome']." ".$row['soft_desc']." ".$row['soft_versao']."</TD>";
				print "<td class='line'>".$row['cat_desc']."</TD>";
				print "<td class='line'>".$row['lic_desc']."</TD>";
				print "<td class='line'>".NVL($row['soft_qtd_lic'])."</TD>";
				print "<td class='line'>".$dispo."</TD>";
				print "<td class='line'>".NVL($row['forn_nome'])."</TD>";
				print "<td class='line'>".NVL($row['soft_nf'])."</TD>";

				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['soft_cod']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['soft_cod']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
				print "</TR>";

			}
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('SUBTTL_CAD_SOFT')."</B><BR>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_SOFT').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR."><input type='text' class='text' name='software' id='idSoftware'>";
			print "</td>";
    		print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_VERSION').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR."><input type='text' class='text' name='versao' id='idVersao'>";
			print "</td>";
    		print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_MANUFACTURE').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";
					print "<select class='select' name=fabricante id='idFabricante'>";
					print "<option value=".null." selected>".TRANS('SEL_MANUFACTURE')."</option>";
					$select = "select * from fabricantes where fab_tipo in (2,3) order by fab_tipo,fab_nome";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['fab_cod'].">".$row['fab_nome']."</option>";
					} // while
					print "</select>";
			print "<input type='button' value='".TRANS('ACT_NEW')."' name='fab' class='minibutton' onClick=\"javascript:popup_alerta('fabricantes.php?popup=true&action=incluir')\"></td>";
		print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_CAT').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";
					print "<select class='select' name='categoria' id='idCategoria'>";
					print "<option value=".null." selected>".TRANS('SEL_CAT')."</option>";
					$select = "select * from categorias order by cat_desc";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['cat_cod'].">".$row['cat_desc']."</option>";
					} // while
					print "</select>";
			print "<input type='button' value='".TRANS('ACT_NEW')."' name='cat' class='minibutton' onClick=\"javascript:popup_alerta('categorias.php?popup=true&action=incluir')\"></td>";
		print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_LICENSE').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";
					print "<select class='select' name='licenca' id='idLicenca'>";
					print "<option value='-1' selected>".TRANS('SEL_LICENSE')."</option>";
					$select = "select * from licencas order by lic_desc";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['lic_cod'].">".$row['lic_desc']."</option>";
					} // while
					print "</select>";
			print "<input type='button' value='".TRANS('ACT_NEW')."' name='lic' class='minibutton' onClick=\"javascript:popup_alerta('licencas.php?action=incluir&popup=true')\"></td>";
		print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_QTD').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR."><input type='text' class='text' name='quantidade' id='idQtd'>";
			print "</td>";
    	print "</tr>";


		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_VENDOR').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR.">";
					print "<select class='select' name='fornecedor' id='idFornecedor'>";
					print "<option value=".null." selected>".TRANS('SEL_VENDOR')."</option>";
					$select = "select * from fornecedores order by forn_nome";
					$exec = mysql_query($select);
					while($row = mysql_fetch_array($exec)){
						print "<option value=".$row['forn_cod'].">".$row['forn_nome']."</option>";
					} // while
					print "</select>";
			print "<input type='button' value='".TRANS('ACT_NEW')."' name='forn' class='minibutton' onClick=\"javascript:popup_alerta('fornecedores.php?popup=true&action=incluir')\"></td>";
		print "</tr>";

		print "<tr>";
			print "<td width='20%' align='right' bgcolor=".TD_COLOR.">".TRANS('COL_NF').":</TD>";
			print "<td width='80%' align='left' bgcolor=".BODY_COLOR."><input type='text' class='text' name='nf' id='idNf'>";
			print "</td>";
    	print "</tr>";

		print "<TR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";
		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('SUBTTL_EDIT_SOFT')."</B><BR>";

			print "<tr><td bgcolor=".TD_COLOR.">".TRANS('COL_MANUFACTURE')."</td><td bgcolor=".BODY_COLOR."><select name='fabricante' class='select' id='idFabricante'>";
			print "<option value=-1 selected>".TRANS('SEL_MANUFACTURE')."</option>";
			$qry = "SELECT * from fabricantes order by fab_nome";
			$exec_qry = mysql_query($qry);
			while ($row_fab = mysql_fetch_array($exec_qry)){
				print "<option value='".$row_fab['fab_cod']."'";
				if ($row_fab['fab_cod']==$row['soft_fab']) print " selected";
				print ">".$row_fab['fab_nome']."</option>";

			}
			print "</select>";
			print "</td></tr>";
			print "<tr><td bgcolor=".TD_COLOR.">".TRANS('COL_SOFT')."</td><td class='line'><input type='text' class='text' name='software' id='idSoftware' value='".$row['soft_desc']."'></td></tr>";

			print "<tr><td bgcolor=".TD_COLOR.">".TRANS('COL_VERSION')."</td><td class='line'><input type='text' class='text' name='versao' id='idVersao' value='".$row['soft_versao']."'></td></tr>";

			print "<tr><td bgcolor=".TD_COLOR.">".TRANS('COL_CAT')."</td><td class='line'><select name='categoria' class='select' id='idCategoria'>";
			print "<option value=-1 selected>".TRANS('SEL_SOFT')."</option>";
			$qry = "SELECT * from categorias order by cat_desc";
			$exec_qry = mysql_query($qry);
			while ($row_cat = mysql_fetch_array($exec_qry)){
				print "<option value='".$row_cat['cat_cod']."'";
				if ($row_cat['cat_cod']==$row['soft_cat']) print " selected";
				print ">".$row_cat['cat_desc']."</option>";

			}
			print "</select>";
			print "</td></tr>";

			print "<tr><td bgcolor=".TD_COLOR.">".TRANS('COL_LICENSE')."</td><td class='line'><select name='licenca' class='select' id='idLicenca'>";
			print "<option value=-1 selected>".TRANS('SEL_LICENSE')."</option>";
			$qry = "SELECT * from licencas order by lic_desc";
			$exec_qry = mysql_query($qry);
			while ($row_lic = mysql_fetch_array($exec_qry)){
				print "<option value='".$row_lic['lic_cod']."'";
				if ($row_lic['lic_cod']==$row['soft_tipo_lic']) print " selected";
				print ">".$row_lic['lic_desc']."</option>";

			}
			print "</select>";
			print "</td></tr>";

			print "<tr><td bgcolor=".TD_COLOR.">".TRANS('COL_NUMBER_OF_LICENSES')."</td><td class='line'><input type='text' class='text' name='n_licencas' id='idQtd' value='".$row['soft_qtd_lic']."'></td></tr>";

			print "<tr><td bgcolor=".TD_COLOR.">".TRANS('COL_VENDOR')."</td><td class='line'><select name='fornecedor' class='select' id='idFornecedor'>";
			print "<option value=-1 selected>".TRANS('SEL_VENDOR')."</option>";
			$qry = "SELECT * from fornecedores order by forn_nome";
			$exec_qry = mysql_query($qry);
			while ($row_forn = mysql_fetch_array($exec_qry)){
				print "<option value='".$row_forn['forn_cod']."'";
				if ($row_forn['forn_cod']==$row['soft_forn']) print " selected";
				print ">".$row_forn['forn_nome']."</option>";

			}
			print "</select>";
			print "</td></tr>";

			print "<tr><td bgcolor=".TD_COLOR.">".TRANS('COL_NF')."</td><td class='line'><input type='text' class='text' name='nf' id='idNf' value='".$row['soft_nf']."'></td></tr>";

			print "<TR>";
			print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
			print "</TD>";
			print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";
			print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";
		$sql_1 = "select * from hw_sw where hws_sw_cod = '".$_GET['cod']."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.=" de equipamentos, ";

		$sql_2 = "select * from sw_padrao where swp_sw_cod = '".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+=mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.=", Softwares padrão";



		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_CANT_DEL'). $texto. TRANS('MSG_ASSOC_IT')."');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM softwares where soft_cod=".$_GET['cod']."";
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

	if ($_POST['submit'] == TRANS('BT_CAD'))  {

		$erro=false;

		$qryl = "select s.*, l.*, c.*, f.* from softwares as s, licencas as l, categorias as c, fabricantes as f ".
				"where s.soft_tipo_lic = l.lic_cod and s.soft_cat = c.cat_cod and s.soft_fab = f.fab_cod ".
				"and s.soft_desc like ('".$_POST['software']."') and s.soft_fab = ".$_POST['fabricante']."  ".
				"and s.soft_versao = '".$_POST['versao']."' ".
				"order by f.fab_nome, s.soft_desc, s.soft_versao";
		$resultado = mysql_query($qryl) or die(TRANS('MSG_ERR_CHECK_REG').'<BR>'.$qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_EXIST_REG_CAD_SYSTEM');
				$erro = true;
		}

		if (!$erro)
		{

			$query = "INSERT into softwares (soft_desc, soft_versao, soft_fab, soft_cat, soft_tipo_lic, soft_qtd_lic,soft_forn,soft_nf) values ".
				"('".noHtml($_POST['software'])."', '".noHtml($_POST['versao'])."', ".$_POST['fabricante'].", ".$_POST['categoria'].", ".
				"".$_POST['licenca'].", '".$_POST['quantidade']."', '".$_POST['fornecedor']."', '".noHtml($_POST['nf'])."')";

			$resultado = mysql_query($query) or die (TRANS('ERR_INSERT').'<BR>'.$query);

			$aviso = TRANS('OK_INSERT');

		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."'); window.opener.location.reload();</script>";

	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')){


		$query2 = "UPDATE softwares set soft_fab=".noHtml($_POST['fabricante']).", soft_desc='".noHtml($_POST['software'])."', ".
				"soft_versao='".noHtml($_POST['versao'])."', soft_cat=".$_POST['categoria'].", soft_tipo_lic=".$_POST['licenca'].", ".
				"soft_qtd_lic='".$_POST['n_licencas']."', soft_forn='".$_POST['fornecedor']."', soft_nf='".noHtml($_POST['nf'])."' ".
				"where soft_cod=".$_POST['cod']."";

		$resultado2 = mysql_query($query2) or die(TRANS('ERR_EDIT').'<BR>'.$query2);
			$aviso =  TRANS('OK_EDIT');

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	print "</table>";
	print "</form>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idSoftware','','<?php print TRANS('COL_SOFT');?>',1);
		if (ok) var ok = validaForm('idVersao','','<?php print TRANS('COL_VERSION');?>',1);
		if (ok) var ok = validaForm('idFabricante','COMBO','<?php print TRANS('COL_MANUFACTURE');?>',1);
		if (ok) var ok = validaForm('idCategoria','COMBO','<?php print TRANS('COL_CAT');?>',1);
		if (ok) var ok = validaForm('idLicenca','COMBO','<?php print TRANS('COL_LICENSE');?>',1);

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
?>