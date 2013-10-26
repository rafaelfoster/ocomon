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

	include ("../../includes/classes/paging.class.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);


	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage(10);


	print "<BR><B>".TRANS('MNL_COMPONENTES_MODEL','Modelos de componentes').":</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


	$tipo = "";
	if (!isset($_GET['tipo'])) {
		$select = "select * from itens";
		$exec = mysql_query($select);
		while($vet = mysql_fetch_array($exec)){
			$tipo.=$vet['item_cod'].',';
		} // while
		$tipo = substr($tipo,0,-1);
	} else {
		$tipo = $_GET['tipo'];
	}

	if (isset($_POST['filtro'])){
		$PAGE->setPost($_POST['filtro']);
		$P_FILTRO = $_POST['filtro'];
	} else {
		$P_FILTRO = "";
	}


// 	$TMP = "LIMIT=1&TIPO=2&POST=filtro|intel|var|teste";
// 	$teste = array ();
// 	$teste = explode ("POST=",$TMP);
// 	dump ($teste);


	if ($PAGE->POST != "") {
	//if (isset($_POST['filtro'])){
		//$qryFiltro = " AND lower(mdit_fabricante) like (lower('%".$_POST['filtro']."%')) ";
		$qryFiltro = " AND lower(mdit_fabricante) like (lower('%".$PAGE->POST."%')) ";
	} else
		$qryFiltro = "";

	$query = "SELECT * FROM modelos_itens, itens where mdit_tipo in (".$tipo.") ".
				"AND mdit_tipo = item_cod ".$qryFiltro."".
				"ORDER BY item_nome, mdit_fabricante, mdit_desc, mdit_desc_capacidade";

        $resultado = mysql_query($query) or die (TRANS('FIELD_ERROR').':<BR>'.$query);


	if (isset($_GET['LIMIT']))
		$PAGE->setLimit($_GET['LIMIT']);
	$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));


	if ((!isset($_GET['action'])) and !isset($_POST['submit'])){

		print "<TR><TD bgcolor='".BODY_COLOR."'>".
				//"<a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true&tipo=".$tipo."'>".TRANS('TTL_INCLUDE_COMP_MODEL','Incluir modelo de componente').".</a>".
				"<input type='button' class='button' id='idBtIncluir' value='".TRANS('TTL_INCLUDE_COMP_MODEL','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true&tipo=".$tipo."');\">".
				"</TD></TR>";
		if (mysql_numrows($resultado) == 0)
		{
			echo "<tr><td>".mensagem(TRANS('MSG_NO_EXIST_COMP_CAD_SYSTEM'))."</td></tr>";
		}
        else
        {
                $linhas = mysql_numrows($resultado);
		print "<tr><td colspan='4'>";
		//$PAGE->getSQL();
		$PAGE->execSQL();



		//print "<tr><td>Filtro:</td><td><input type='text' class='text' name='filtro' value='".$P_FILTRO."'><input type='submit' class='button' value='Filtrar'></td></tr>";
		print "".TRANS('THERE_IS_ARE')." <b>".$PAGE->NUMBER_REGS."</b> ".TRANS('TXT_COMP_MODEL_CAD_SYSTEM')." ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</td></tr>";
		//print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
		print "<TR class='header'><td class='line'>".TRANS('COL_TYPE')."</TD><td class='line'>".TRANS('COL_MODEL')."</TD><td class='line'><b>".TRANS('COL_EDIT')."</b></TD><td class='line'><b>".TRANS('COL_DEL')."</b></TD>";
		$j=2;
		//while ($row=mysql_fetch_array($resultado))
		while ($row=mysql_fetch_array($PAGE->RESULT_SQL))
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
			print "<td class='line'>".$row['item_nome']."</TD>";
			print "<td class='line'>".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade']." ".$row['mdit_sufixo']."</TD>";
			print "<td class='line'><a onClick=\"redirect('itens.php?action=alter&cod=".$row['mdit_cod']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
			print "<td class='line'><a onClick=\"confirma('".TRANS('MSG_DEL_UNIT_IN_SYSTEM')."','".$_SERVER['PHP_SELF']."?action=excluir&cod=".$row['mdit_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
			print "</TR>";
		}
		//print "</TABLE>";
		print "<tr><td colspan='4'>";
		$PAGE->showOutputPages();
		print "</td></tr>";

        }

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {

		print "<B>".TRANS('SUBTTL_CAD_COMP_MODEL','Cadastro de modelos de componentes').":<br>";
		//print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		//print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<TD width='30%'  bgcolor='".TD_COLOR."'>".TRANS('COL_TYPE')."</td><td>";

			$select = "select * from itens order by item_nome";
			$exec = mysql_query($select);
			print "<select  class='select' name=item_tipo id='idItem'>";
			print "<option value=-1 selected>".TRANS('SEL_TYPE_ITEM')."</option>";
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['item_cod']."";
				if ($row['item_cod']==$tipo) print " selected";
				print ">".$row['item_nome']."</option>";
			} // while

		print "</td>";
		print "</tr>";

		print "<TR>";
		print "<TD width='30%'  bgcolor='".TD_COLOR."'>".TRANS('COL_MANUFACTURE')."*:</TD>";
		print "<TD width='70%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='item_fabricante' id='idFabricante'></TD>";
		print "</TR>";

		print "<TR>";
			print "<TD width='30%'  bgcolor='".TD_COLOR."'><a title='modelo do componente'>".TRANS('COL_MODEL')."*:</a></TD>";
			print "<TD width='70%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text'  class='text' name='item_descricao' id='idModelo'></TD>";
		print "</TR>";

		print "<TR>";
			print "<TD width='30%'  bgcolor='".TD_COLOR."'><a title='".TRANS('HNT_COMPONENT_POWER')."'>".TRANS('COL_POWER').":</a></TD>";
			print "<TD width='70%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text'  name='item_capacidade' id='idCapacidade'></TD>";
		print "</TR>";
		print "<TR>";
			print "<TD width='30%'  bgcolor='".TD_COLOR."'>".TRANS('COL_SUFIX').":</TD>";
			print "<TD width='70%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text'  class='text' name='item_sufixo' id='idSufixo'></TD>";
		print "</TR>";


		print "<tr><td><input type='submit' class='button' name='submit' value='".TRANS('BT_INCLUDE')."'></td>";

		print "<td><input type='reset' class='button' name='reset' value='".TRANS('BT_CANCEL')."' onClick=\"javascript:fecha();\"></td></tr>";

		//print "</table>";
		//print "</form>";

	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {

		$qry = "select * from modelos_itens as m, itens as i where m.mdit_cod='".$_GET['cod']."'  and m.mdit_tipo = i.item_cod";
		$exec = mysql_query($qry);
		//$rowAlter = mysql_fetch_array($exec);
		$row = mysql_fetch_array($exec);

		print "<B>".TRANS('SUBTTL_EDIT_COMP').":<br>";
		//print "<form name='alter' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		//print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";

	        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TYPE').":</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'>";

					print "<select class='select' name='item_tipo' id='idItem'>";
					print "<option value=".$row['item_cod']." selected>".$row['item_nome']."</option>";
					$select = "select * from itens order by item_nome";
					$exec = mysql_query($select);
					while($tipos = mysql_fetch_array($exec)){
						print "<option value =".$tipos['item_cod'].">".$tipos['item_nome']."</option>";
					} // while

				print "</TD>";
		print "</tr>";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor= '".TD_COLOR."'>".TRANS('COL_MANUFACTURE').":</TD>";
                print "<TD width='30%' align='left' bgcolor= '".BODY_COLOR."'><INPUT type='text' class='text' name='item_fabricante' id='idFabricante' value='".$row['mdit_fabricante']."' maxlength='100' size='100'></TD>";
		print "</tr>";
        print "<TR>";
                print "<TD width='20%' align='left' bgcolor= '".TD_COLOR."'>".TRANS('FIELD_DESC_MODEL').":</TD>";
                print "<TD width='30%' align='left' bgcolor= '".BODY_COLOR."'><INPUT type='text' class='text' name='item_descricao' id='idModelo' value='".$row['mdit_desc']."' maxlength='100' size='100'></TD>";
        print "</TR>";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_POWER').":</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='item_capacidade' id='idCapacidade' value='".$row['mdit_desc_capacidade']."' maxlength='100' size='100'></TD>";
        print "</TR>";

        print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_SUFIX').":</TD>";
                print "<TD width='30%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='item_sufixo' id='idSufixo' value='".$row['mdit_sufixo']."' maxlength='100' size='100'></TD>";
        print "</TR>";

		print "<tr> <td colspan='2'>";
		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit' class='button' name='submit' value='".TRANS('BT_ALTER')."'></td>";
		print "<td class='line'><input type='reset' name='reset' class='button' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:fecha()\"></td></tr>";

		//print "</table>";
		//print "</form>";
	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){
			$qryAcha = "select * from equipamentos where comp_cdrom = ".$_GET['cod']."";
			$execAcha = mysql_query($qryAcha);
			$achou = mysql_numrows($execAcha);

			if ($achou){
				print "<script>mensagem('".TRANS('MSG_NOT_DEL_EQUIP_ASSOC')."');".
						" redirect('".$_SERVER['PHP_SELF']."');</script>";
				exit;
			} else {

				$qry = "DELETE FROM modelos_itens WHERE mdit_cod = ".$_GET['cod']."";
				$exec = mysql_query($qry) or die (TRANS('MSG_ERR_DEL_REGISTER'));
				print "<script>mensagem('".TRANS('OK_DEL')."');".
						" redirect('".$_SERVER['PHP_SELF']."?tipo=".$tipo."');</script>";
			}
	} else

	if ($_POST['submit']== TRANS('BT_INCLUDE')){

		if (isset($_POST['item_tipo']) && isset($_POST['item_descricao'])) {

			if (isset($_POST['item_capacidade']) && $_POST['item_capacidade']=="") {
				$mdit_desc_capacidade = 'null';
			} else
				$mdit_desc_capacidade = $_POST['item_capacidade'];

			// Adicionado a verificacao com o Fabricante
			$qry = "select * from modelos_itens where mdit_fabricante = '".$_POST['item_fabricante']."' AND ".
					"mdit_desc = '".$_POST['item_descricao']."' AND ".
					"mdit_desc_capacidade = '".$_POST['item_capacidade']."' ";

			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){

				print "<script>mensagem('".TRANS('MSG_COMP_CAD_SYSTEM')."'); redirect('".$_SERVER['PHP_SELF']."?tipo=". $_POST['item_tipo']."'); </script>";

			} else {

				$qry = "INSERT INTO modelos_itens (mdit_fabricante, mdit_desc, mdit_desc_capacidade,mdit_sufixo, mdit_tipo )".
							" values ('".noHtml($_POST['item_fabricante'])."','".noHtml($_POST['item_descricao'])."', ".
							"".noHtml($mdit_desc_capacidade).", '".noHtml($_POST['item_sufixo'])."', '".$_POST['item_tipo']."')";

				$exec = mysql_query($qry) or die (TRANS('MSG_ERR_INCLUDE_COMP').'<br>'.$qry);
				print "<script>mensagem('".TRANS('MSG_DATA_INCLUDE_OK')."'); redirect('".$_SERVER['PHP_SELF']."?tipo=".$_POST['item_tipo']."');</script>";
				}
		} else {
				print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); redirect('".$_SERVER['PHP_SELF']."?tipo=".$_POST['item_tipo']."');</script>";
		}

	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){
		if (isset($_POST['item_tipo']) && isset($_POST['item_descricao'])) {

			if (isset($_POST['item_capacidade']) && $_POST['item_capacidade']=="") {
				$mdit_desc_capacidade = 'null';
			} else
				$mdit_desc_capacidade = $_POST['item_capacidade'];


			$qry = "UPDATE modelos_itens SET mdit_fabricante = '".noHtml($_POST['item_fabricante'])."' , ".
					"mdit_desc = '".noHtml($_POST['item_descricao'])."', mdit_desc_capacidade = ".noHtml($mdit_desc_capacidade).",".
					" mdit_sufixo = '".noHtml($_POST['item_sufixo'])."' ,mdit_tipo = '".$_POST['item_tipo']."'".
						"WHERE mdit_cod=".$_POST['cod']."";

			$exec= mysql_query($qry) or die(TRANS('MSG_NOT_ALTER_REG'). $qry);


			print "<script>mensagem('".TRANS('MSG_DATA_ALTER_OK')."'); redirect('".$_SERVER['PHP_SELF']."?tipo=".$_POST['item_tipo']."');</script>";

		} else {

			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); history.back(); </script>";

		}
	}

print "</table>";
print "</form>";


print "</body>";
?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idItem','COMBO','Tipo',1);
		if (ok) var ok = validaForm('idFabricante','','Fabricante',1);
		if (ok) var ok = validaForm('idModelo','','Modelo',1);
		if (ok) var ok = validaForm('idCapacidade','INTEIRO','Capacidade',0);

		return ok;
	}
-->
</script>
<?php 
print "</html>";

?>
