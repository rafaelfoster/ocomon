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

	print "<BR><B>".TRANS('ADM_SITUAC')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


		$query = "select * from situacao ";
		if (isset($_GET['cod'])) {
			$query.= " WHERE situac_cod = ".$_GET['cod']." ";
		}
		$query .=" ORDER  BY situac_nome";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!');
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		//print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir Situação</a></TD></TR>";
		print "<TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\">".
			"</TD>";

		if (mysql_num_rows($resultado) == 0)
		{
			print "<tr><td>";
			print mensagem(TRANS('MSG_NO_RECORDS'));
			print "</tr></td>";
		}
		else
		{
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('RECORDS_IN_SYSTEM').".</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_SITUAC')."</TD><td class='line'>".TRANS('COL_DESC')."</TD><td class='line'>".TRANS('COL_HILIGHT')."</TD><td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";

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
				//print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['situac_nome']."</td>";
				print "<td class='line'>".NVL($row['situac_desc'])."</td>";
				print "<td class='line'>".transbool($row['situac_destaque'])."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['situac_cod']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['situac_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
			//print "</TABLE>";
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('CADASTRE_SITUAC')."</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SITUAC').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='situacao' class='text' id='idSituacao'>".
				"<input type='checkbox' name='destaque'>".TRANS('COL_HILIGHT')."</td>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_DESC').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><textarea class='textarea' name='descricao' id='idDescricao'></textarea></td>";
		print "</td>";
		print "</tr>";

		print "<TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('bt_cadastrar')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('TTL_EDIT_RECORD')."</B><BR>";

		print "<TR>";
			($row['situac_destaque']==0)?$checked="":$checked="checked";
                	print "<TD width='20%' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_SITUAC').":</TD>";
                	print "<TD colspan='3' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='situacao' id='idSituacao' value='".$row['situac_nome']."'>".
                			"<input type='checkbox' name='destaque' ".$checked.">".TRANS('COL_HILIGHT')."</td>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_DESC').":</TD>";
                print "<TD colspan='3'  align='left' bgcolor='".BODY_COLOR."'><textarea class='textarea' name='descricao'  id='idDescricao'>".$row['situac_desc']."</textarea></td>";
        	print "</TR>";


		print "<TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' colspan='3' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";

		$sql_2 = "SELECT * FROM equipamentos where comp_situac ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="equipamentos, ";

		if ($total!=0)
		{
				print "<script>mensagem('".TRANS('MSG_CANT_DEL').": ".$texto." ".TRANS('LINKED_TABLE')."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM situacao WHERE situac_cod='".$_GET['cod']."'";
			$resultado2 = mysql_query($query2) or die ('ERRO NA TENTATIVA DE EXCLUIR O REGISTRO!<br>'.$query2);

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

		$qryl = "SELECT * FROM situacao WHERE situac_nome='".$_POST['situacao']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
			$aviso = TRANS('MSG_RECORD_EXISTS');
			$erro = true;
		}

		if (!$erro)
		{
			(isset($_POST['destaque']))?$destaque=1:$destaque=0;
			$query = "INSERT INTO situacao (situac_nome, situac_desc, situac_destaque) values ".
						"('".noHtml($_POST['situacao'])."', '".noHtml($_POST['descricao'])."', '".$destaque."')";
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

		(isset($_POST['destaque']))?$destaque=1:$destaque=0;
		$query2 = "UPDATE situacao SET situac_nome='".noHtml($_POST['situacao'])."', situac_desc ='".noHtml($_POST['descricao'])."', ".
				"situac_destaque='".$destaque."' ".
				"WHERE situac_cod='".$_POST['cod']."'";
		$resultado2 = mysql_query($query2) or die (TRANS('ERR_QUERY').$query2);

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
		var ok = validaForm('idSituacao','','Situação',1);
		if (ok) var ok = validaForm('idDescricao','','Descrição',1);

		return ok;
	}

-->
</script>


<?
print "</body>";
print "</html>";
