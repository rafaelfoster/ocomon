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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage(5);


	print "<BR><B>".TRANS('TTL_ADMIN_DOC_CAD')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' ENCTYPE='multipart/form-data' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


        	$query = "SELECT mat.* , marc.* \nFROM materiais AS mat LEFT JOIN marcas_comp as marc ON mat.mat_modelo_equip = marc.marc_cod ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE mat.mat_cod = ".$_GET['cod']." ";
		}

      		if (empty($ordena)) {
			$ordena="mat_cod";
		}
		$query.= "\nORDER BY ".$ordena."";

		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
		$registros = mysql_num_rows($resultado);


		if (isset($_GET['LIMIT']))
			$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));


	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		print "<tr><TD align='left'>".
				"<input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','Cadastrar novo registro',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\">".
			"</TD></tr>";
		$PAGE->execSQL();

		if ($registros == 0)
		{
			print "<tr><td>".mensagem(TRANS('MSG_NOT_REG_CAD'))."</td></tr>";
		}
		else
		{
			print "<tr><td class='line'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('TXT_IN_CADASTRE')."</td>";
			print "</tr>";
			print "<TR class='header'>".
					"<td class='line'><a href='".$_SERVER['PHP_SELF']."?ordena=mat_nome'>".TRANS('COL_DESC')."</a></TD>".
					"<td class='line'>".TRANS('COL_MODEL_EQUIP')."</td>".
					"<td class='line'><a href='".$_SERVER['PHP_SELF']."?ordena=mat_qtd'>".TRANS('COL_QTD')."</a></TD>".
					"<td class='line'><a href='".$_SERVER['PHP_SELF']."?ordena=mat_caixa'>".TRANS('COL_BOX')."</a>".
					"<td class='line'><a href='".$_SERVER['PHP_SELF']."?ordena=mat_cod".TRANS('COL_OBS').".</a></TD>".
					"<TD class='line'>".TRANS('OCO_FIELD_ALTER')."</TD><td class='line'>".TRANS('OCO_FIELD_EXCLUDE')."</TD>".
				"</TR>";
			$j=2;
			while ($row = mysql_fetch_array($PAGE->RESULT_SQL))
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
				print "<td class='line'>".$row['mat_nome']."</td>";

				$sqlModelo = "SELECT * FROM marcas_comp WHERE marc_cod = ".$row['mat_modelo_equip']."";
				$execSql = mysql_query($sqlModelo) or die (TRANS('MSG_NOT_ACCESS_MODEL_DATA'));
				$rowModelo = mysql_fetch_array($execSql);


				print "<td class='line'>".NVL($rowModelo['marc_nome'])."</td>";
				print "<td class='line'>".NVL($row['mat_qtd'])."</td>";
				print "<td class='line'>".NVL($row['mat_caixa'])."</td>";
				print "<td class='line'>".NVL($row['mat_obs'])."</td>";
				//print "<td class='line'>".NVL($row['mat_cod'])."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['mat_cod']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['mat_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
			print "<tr><td colspan='6'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") ) && empty($_POST['submit'])) {

		print "<BR><B>".TRANS('SUBTTL_DOC_CAD')."</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('MNL_VIS_DOC').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='documento' class='text' id='idDocumento'></td>";
		print "</TR>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_QTD').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='qtd' class='text' id='idQtd'></td>";
		print "</TR>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_BOX').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='caixa' class='text' id='idCaixa'></td>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('FIELD_ASSOC_MODEL').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='modelo' id='idModelo'>";
				print "<option value=-1 selected>".TRANS('SEL_MODEL')."</option>";
				$select = "select * from marcas_comp order by marc_nome";
				$exec = mysql_query($select);
				while($row = mysql_fetch_array($exec)){
					print "<option value=".$row['marc_cod'].">".$row['marc_nome']."</option>";
				} // while
			print "</select>";

		print "</td>";
		print "</tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_COMMENT').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><textarea name='comentario' class='textarea' id='idComentario'></textarea></td>";


		print "<TR>";
		print "<tr><td>&nbsp;</td></tr>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:fecha();\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('SUBTTL_EDIT_SOFT')."</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('MNL_VIS_DOC').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='documento' id='idDocumento' value='".$row['mat_nome']."'></td>";
        	print "</TR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_QTD').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='qtd' id='idQtd' value='".$row['mat_qtd']."'></td>";
        	print "</TR>";
		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_BOX').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='caixa' id='idCaixa' value='".$row['mat_caixa']."'></td>";
        	print "</TR>";
		print "<TR>";
        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('FIELD_ASSOC_MODEL').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='modelo' id='idModelo'>";

			$sql = "select * from materiais where mat_cod=".$row["mat_cod"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >".TRANS('SEL_MODEL')."</option>";
					$sql="select * from marcas_comp order by marc_nome";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["marc_cod"]."";
                        			if ($rowB['marc_cod'] == $row['mat_modelo_equip'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["marc_nome"]."</option>";
					} // while

		print "</select>";
		print "</TD>";
        	print "</TR>";
		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_OBS').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><textarea class='textarea' name='comentario' id='idComentario'>".$row['mat_obs']."</textarea></td>";
        	print "</TR>";

		print "<TR>";
		print "<BR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";

			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:fecha();\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$query2 = "DELETE FROM materiais WHERE mat_cod='".$_GET['cod']."'";
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

	} else

	if ($_POST['submit'] == TRANS('BT_CAD')){

		$erro=false;

		$qryl = "SELECT * FROM materiais WHERE mat_nome = '".$_POST['documento']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = "Já existe um registro com essa descrição cadastro no sistema!!";
				$erro = true;
		}

			$query = "INSERT INTO materiais (mat_nome, mat_qtd, mat_caixa, mat_modelo_equip, mat_obs, mat_data) values ".
						"('".noHtml($_POST['documento'])."', '".noHtml($_POST['qtd'])."', '".noHtml($_POST['caixa'])."', ".
						"'".noHtml($_POST['modelo'])."', '".noHtml($_POST['comentario'])."', '".date("Y-m-d h:i:s")."')";
			$resultado = mysql_query($query) or die(TRANS('ERR_INSERT').'<br>'.$query);
			$modelCod = mysql_insert_id();


			$aviso = TRANS('OK_INSERT');

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')){

		$query2 = "UPDATE materiais SET mat_nome='".noHtml($_POST['documento'])."', mat_qtd=".$_POST['qtd'].", ".
		"mat_caixa='".noHtml($_POST['caixa'])."', mat_modelo_equip = '".$_POST['modelo']."', mat_obs = '".noHtml($_POST['comentario'])."'".
		"  WHERE mat_cod='".$_POST['cod']."'";
		$resultado2 = mysql_query($query2) or die(TRANS('MSG_ERR_ALTER_INFO_REG').'<br>'.$query2);



		$aviso =  TRANS('OK_EDIT');

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	print "</table>";
	print "<form>";


?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idModelo','','<?php print TRANS('FIELD_NAME_MODEL');?>',1);
		if (ok) var ok = validaForm('idTipo','COMBO','<?php print TRANS('FIELD_TYPE_EQUIP');?>',1);

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
?>
