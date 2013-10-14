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

	include ("../../includes/classes/paging.class.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	$PAGE = new paging;
	$PAGE->setRegPerPage(10);


	$fecha = "";
	if (isset($_GET['popup'])) {
		$fecha = "window.close()";
	} else {
		$fecha = "history.back()";
	}



	print "<BR><B>".TRANS('ADM_MANUFACTURES')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";




        	$query = "SELECT f.*, t.* FROM fabricantes as f, tipo_item as t ".
        			"WHERE  f.fab_tipo = t.tipo_it_cod ";
		if (isset($_GET['cod'])) {
			$query.= " AND f.fab_cod = ".$_GET['cod']." ";
		}
		$query .=" ORDER BY tipo_it_desc,fab_nome";
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
			$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));


	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		//print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Cadastrar novo Fabricante</a></TD></TR>";
		print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		if (mysql_num_rows($resultado) == 0)
		{
			print "<tr><td>";
			print mensagem(TRANS('MSG_NO_RECORDS'));
			print "</tr></td>";
		}
		else
		{
			$PAGE->execSQL();

			print "<tr><td class='line'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('RECORDS_IN_SYSTEM').".</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_MANUFACTURE')."</TD><td class='line'>".TRANS('COL_TYPE')."</TD>".
				"<td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";

			$j=2;
			//while ($row = mysql_fetch_array($resultado))
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
				//print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";

				print "<td class='line'>".$row['fab_nome']."</td>";
				print "<td class='line'>".$row['tipo_it_desc']."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['fab_cod']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['fab_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
			print "<tr><td colspan='4'>";
			$PAGE->showOutputPages();
			print "</td></tr>";

		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('CADASTRE_MANUFACTURE')."</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_MANUFACTURE').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='fab_nome' class='text' id='idFabricante'></td>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_TYPE').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='tipo' id='idTipo'>";
				print "<option value=-1 selected>".TRANS('SEL_TYPE')."</option>";
				$select = "select * from tipo_item order by tipo_it_desc";
				$exec = mysql_query($select);
				while($row = mysql_fetch_array($exec)){
					print "<option value=".$row['tipo_it_cod'].">".$row['tipo_it_desc']."</option>";
				} // while
			print "</select>";

		print "</td>";
		print "</tr>";

		print "<TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('bt_cadastrar')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('TTL_EDIT_RECORD')."</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_MANUFACTURE').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='fab_nome' id='idFabricante' value='".$row['fab_nome']."'></td>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_TYPE').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='tipo' id='idTipo'>";

			$sql = "select * from tipo_item where tipo_it_cod=".$row["fab_tipo"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >".TRANS('SEL_TYPE')."</option>";
					$sql="select * from tipo_item order by tipo_it_desc";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["tipo_it_cod"]."";
                        			if ($rowB['tipo_it_cod'] == $row['fab_tipo'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["tipo_it_desc"]."</option>";
					} // while

		print "</select>";
		print "</TD>";
        	print "</TR>";

		print "<TR>";
		print "<BR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:".$fecha."\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";
		$sql_1 = "SELECT * from equipamentos where comp_fab='".$_GET['cod']."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.="equipamentos, ";

		$sql_2 = "SELECT * FROM softwares where soft_fab ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="softwares, ";


		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_CANT_DEL').": ".$texto." ".TRANS('LINKED_TABLE')."!');
				redirect('fabricantes.php');</script>";
		}
		else
		{
			$query2 = "DELETE FROM fabricantes WHERE fab_cod='".$_GET['cod']."'";
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

		$qryl = "SELECT * FROM fabricantes WHERE fab_nome='".$_POST['fab_nome']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_RECORD_EXISTS');
				$erro = true;;
		}

		if (!$erro)
		{

			$query = "INSERT INTO fabricantes (fab_nome, fab_tipo) values ('".noHtml($_POST['fab_nome'])."', ".$_POST['tipo'].")";
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

		$query2 = "UPDATE fabricantes SET fab_nome='".noHtml($_POST['fab_nome'])."', fab_tipo=".noHtml($_POST['tipo'])." WHERE fab_cod='".$_POST['cod']."'";
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
		var ok = validaForm('idFabricante','','Fabricante',1);
		if (ok) var ok = validaForm('idTipo','COMBO','Tipo',1);

		return ok;
	}

-->
</script>


<?
print "</body>";
print "</html>";
