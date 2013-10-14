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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}


	print "<BR><B>".TRANS('ADM_WARE')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


        	$query = "SELECT * FROM estoque, itens, modelos_itens, localizacao WHERE estoq_tipo = item_cod ".
				"and estoq_tipo = mdit_tipo and estoq_desc = mdit_cod and estoq_local = loc_id ";

		if (isset($_GET['cod'])) {
			$query.= " AND estoq_cod = ".$_GET['cod']." ";
		}
		$query .=" ORDER BY item_nome, estoq_desc";
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		//print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Cadastrar itens de estoque</a></TD></TR>";
		//print "<TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\">".
		//	"</TD>";

		print "<TR>".
				"<TD colspan='3'><input type='button' class='minibutton' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD>".
				"<TD colspan='4'><input type='button' class='minibutton' id='idBtShowDefault' value='".TRANS('NEW_COMPONENT','',0)."' onClick=\"redirect('itens.php?action=incluir&cellStyle=true');\"></td>".
			"</TR>";

		if (mysql_num_rows($resultado) == 0)
		{
			print "<tr><td>";
			print mensagem(TRANS('MSG_NO_RECORDS'));
			print "</tr></td>";
		}
		else
		{
			print "<tr>";
			print "<td>".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('RECORDS_IN_SYSTEM').".</td>";
			//print "<TD width='200' align='left' ><a href='itens.php?action=incluir&cellStyle=true'>Incluir componente</a></td>";
			//print "<TD width='224' align='left' ><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir item em estoque</a></td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_TYPE')."</TD><td class='line'>".TRANS('COL_DESC')."</TD><td class='line'>".TRANS('COL_NF')."</TD><td class='line'>".TRANS('COL_LOCAL')."</TD><td class='line'>".TRANS('COL_COMMENT')."</TD><td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD>";
			$j=2;
			while ($row = mysql_fetch_array($resultado))
			{
				($j % 2)?$trClass = "lin_par":$trClass = "lin_impar";
				$j++;
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

				print "<td class='line'>".$row['item_nome']."</td>";
				print "<td class='line'>".$row['mdit_fabricante']."</td>";
				print "<td class='line'>".NVL($row['estoq_sn'])."</td>";
				print "<td class='line'>".$row['local']."</td>";
				print "<td class='line'>".NVL($row['estoq_comentario'])."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['estoq_cod']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['estoq_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('CADASTRE_WARE')."</B><BR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS("cx_tipo_item").":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			$select = "select * from itens order by item_nome";
			$exec = mysql_query($select);

		print "<select class='select' name='estoque_tipo' id='idTipo' onChange=\"fillSelectFromArray(this.form.estoque_desc, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));\">";
			print "<option value=-1>".TRANS("cmb_selec_item")."</option>";
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['item_cod'].">".$row['item_nome']."</option>";
			} // while
		print "</select>";

		print "</TD>";
        	print "</TR>";

       		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('cx_modelo').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='estoque_desc' id='idDesc'>";
			print "<option value='".null."' selected>".TRANS('cmb_selec_modelo')."</option>";
				$select ="select * from itens, modelos_itens where mdit_tipo = item_cod order by ".
					"item_nome, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
				$exec = mysql_query($select);
				while($row = mysql_fetch_array($exec)){
					print "<option value=".$row['mdit_cod'].">".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade']." ".$row['mdit_sufixo']."</option>";
				} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
               	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('cx_sn').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_sn' id='idSN'></TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('cx_local').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_local' id='idLocal'>";
			print "<option value=null selected>".TRANS('cmb_selec_local')."</option>";
			$select = "select * from localizacao order by local";
			$exec = mysql_query($select);
			while($row = mysql_fetch_array($exec)){
				print "<option value=".$row['loc_id'].">".$row['local']."</option>";
			} // while
			print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('cx_coment').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='estoque_comentario' id='idComent'></TD>";
        	print "</TR>";

		NL(); //new line
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('bt_cadastrar')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('TTL_EDIT_RECORD')."</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_TYPE').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='estoque_tipo' id='idTipo'>";
			print "<option value=".$row['estoq_tipo']." selected>".$row['item_nome']."</option>";
			$select = "select * from itens order by item_nome";
			$exec = mysql_query($select);
			while($tipos = mysql_fetch_array($exec)){
				print "<option value =".$tipos['item_cod'].">".$tipos['item_nome']."</option>";
			} // while
			print "</select>";

		print "</TD>";
		print "</tr>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_DESC').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class=select name='estoque_desc' id='idDesc'>";
			print "<option value=".$row['mdit_cod']." selected>".$row['mdit_fabricante']." ".$row['mdit_desc']." ".$row['mdit_desc_capacidade']." ".$row['mdit_sufixo']."</option>";
			$select ="select * from modelos_itens order by mdit_tipo, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
			$exec = mysql_query($select);
			while($desc = mysql_fetch_array($exec)){
				print "<option value=".$desc['mdit_cod'].">".$desc['mdit_fabricante']." ".$desc['mdit_desc']." ".$desc['mdit_desc_capacidade']." ".$desc['mdit_sufixo']."</option>";
			} // while
			print "</select>";

		print "</TD>";
		print "</tr>";


		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_SN').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<INPUT type='text' class='text' name='estoque_sn' id='idSN' value='".$row['estoq_sn']."'>";
		print "</TD>";
		print "</tr>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_LOCAL').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class=select name='estoque_local' id='idLocal'>";
			print "<option value=".$row['estoq_local']." selected>".$row['local']."</option>";
			$select = "select * from localizacao order by local";
			$exec = mysql_query($select);
			while($locais = mysql_fetch_array($exec)){
				print "<option value =".$locais['loc_id'].">".$locais['local']."</option>";
			} // while
			print "</select>";
		print "</TD>";
		print "</tr>";


		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_COMMENT').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<INPUT type='text' class='text' name='estoque_comentario' id='idComent' value='".$row['estoq_comentario']."'>";
		print "</TD>";
		print "</tr>";

		NL(2); //new line colspan =2
		print "<TR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$query = "DELETE FROM estoque WHERE estoq_cod='".$_GET['cod']."'";
		$resultado = mysql_query($query);
		if ($resultado == 0)
		{
			$aviso = TRANS('ERR_DEL');
		}
		else
		{
			$aviso = TRANS('OK_DEL');
		}
		print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == TRANS('bt_cadastrar')){

		$erro=false;

		if (empty($_POST['estoque_desc']) or empty($_POST['estoque_local'])or empty($_POST['estoque_tipo']))
		{
			$aviso = TRANS('alerta_dados_incompletos');
			$erro = true;
		}

		$query = "SELECT * FROM estoque WHERE estoq_sn='".$_POST['estoque_sn']."' and estoq_tipo='".$_POST['estoque_tipo']."'";
		$resultado = mysql_query($query);
		$linhas = mysql_numrows($resultado);

		if ($linhas > 0)
		{
			$aviso = TRANS('alerta_ja_cadastrado');
			$erro = true;
		}

		if (!$erro)
		{
/*                        if ($_POST['estoque_comentario'] == "") {
				$estoque_comentario = "null";
			} else
			 	$estoque_comentario = $_POST['estoque_comentario'];*/
//                         if ($_POST['estoque_sn'] == "") {
// 				$estoque_sn = "null";
//                         } else
// 			 	$estoque_sn = $_POST['estoque_sn'];

			$query = "INSERT INTO estoque (estoq_tipo, estoq_desc, estoq_local,estoq_sn, estoq_comentario ) ".
					"values (".$_POST['estoque_tipo'].", '".$_POST['estoque_desc']."', ".$_POST['estoque_local'].", ".
					"'".noHtml($_POST['estoque_sn'])."', '".noHtml($_POST['estoque_comentario'])."')";
			$resultado = mysql_query($query) or die ('ERRO NA TENTATIVA DE INCLUIR O REGISTRO!<BR>'.$query);
			if ($resultado == 0)
			{
				$aviso = TRANS('alerta_erro_incluir');
			}
			else
			{
				$aviso = TRANS('alerta_sucesso_incluir');
			}
		}

		print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')){
		$row = mysql_fetch_array($resultado);
		$query = "UPDATE estoque SET estoq_tipo = ".$_POST['estoque_tipo']." , estoq_desc = '".noHtml($_POST['estoque_desc'])."', ".
					"estoq_sn = '".noHtml($_POST['estoque_sn'])."', estoq_local = ".$_POST['estoque_local'].", ".
					"estoq_comentario = '".noHtml($_POST['estoque_comentario'])."' ".
					"WHERE estoq_cod=".$_POST['cod']."";
		$resultado = mysql_query($query) or die (TRANS('ERR_QUERY').$query);
		if ($resultado == 0)
		{
			$aviso = TRANS('ERR_EDIT');
		}
		else
		{
			$aviso = TRANS('OK_EDIT');
			$texto = TRANS('WARE_COD').": ".$row['estoq_cod']." ".TRANS('CHANGED')."!";
			geraLog(LOG_PATH.'invmon.txt',date('d-m-Y H:i:s'),$_SESSION['s_usuario'],'altera_dados_estoque.php',$texto);
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}
	print "</table>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idTipo','COMBO','Tipo',1);
		if (ok) var ok = validaForm('idDesc','COMBO','Modelo',1);
		if (ok) var ok = validaForm('idLocal','COMBO','Local',1);

		return ok;
	}

	team = new Array(
	<?
	$conta = 0;
	$conta_sub = 0;

	$sql="select * from itens order by item_nome";
	$sql_result=mysql_query($sql);
	echo mysql_error();
	$num=mysql_numrows($sql_result);
	while ($row_A=mysql_fetch_array($sql_result)){
		$conta=$conta+1;
		$cod_item=$row_A["item_cod"];
			echo "new Array(\n";
			$sub_sql="select * from modelos_itens where mdit_tipo='".$cod_item."' order by mdit_tipo, mdit_fabricante, mdit_desc, mdit_desc_capacidade";
			$sub_result=mysql_query($sub_sql);
			$num_sub=mysql_numrows($sub_result);
			if ($num_sub>=1){
				echo "new Array(\"Todos\", -1),\n";
				while ($rowx=mysql_fetch_array($sub_result)){
					$codigo_sub=$rowx["mdit_cod"];
					$sub_nome=$rowx["mdit_fabricante"]." ".$rowx["mdit_desc"]." ".$rowx["mdit_desc_capacidade"]." ".$rowx["mdit_sufixo"];
					$conta_sub=$conta_sub+1;
					if ($conta_sub==$num_sub){
						echo "new Array(\"$sub_nome\", $codigo_sub)\n";
						$conta_sub="";
					}else{
						echo "new Array(\"$sub_nome\", $codigo_sub),\n";
					}
				}
			}else{
				echo "new Array(\"Qualquer\", -1)\n";
			}
		if ($num>$conta){
			echo "),\n";
		}
	}
	echo ")\n";
	echo ");\n";
	?>

	function fillSelectFromArray(selectCtrl, itemArray, goodPrompt, badPrompt, defaultItem) {
		var i, j;
		var prompt;
		// empty existing items
		for (i = selectCtrl.options.length; i >= 0; i--) {
			selectCtrl.options[i] = null;
		}
		prompt = (itemArray != null) ? goodPrompt : badPrompt;
		if (prompt == null) {
			j = 0;
		}
		else {
			selectCtrl.options[0] = new Option(prompt);
			j = 1;
		}
		if (itemArray != null) {
			// add new items
			for (i = 0; i < itemArray.length; i++) {
				selectCtrl.options[j] = new Option(itemArray[i][0]);
				if (itemArray[i][1] != null) {
					selectCtrl.options[j].value = itemArray[i][1];
				}
				j++;
			}
		// select first item (prompt) for sub list
		selectCtrl.options[0].selected = true;
		}
	}
//-->
</script>
<?

print "</body>";
print "</html>";
?>