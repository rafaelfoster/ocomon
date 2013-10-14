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

	print "<BR><B>".TRANS('ADM_PRIORITIES')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";




		$query = "select p.*, sl.* from prioridades as p left join sla_solucao as sl on p.prior_sla = sl.slas_cod";
		if (isset($_GET['cod'])) {
			$query.= " WHERE p.prior_cod = ".$_GET['cod']." ";
		}
		$query .=" ORDER  BY p.prior_nivel";
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		//print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir Nível de Prioridade</a></TD></TR>";
		print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		if (mysql_num_rows($resultado) == 0)
		{
			echo mensagem(TRANS('MSG_NO_RECORDS'));
		}
		else
		{
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='4'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('RECORDS_IN_SYSTEM').".</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_LEVEL')."</TD><td class='line'>".TRANS('COL_SLA')."</TD><td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></tr>";

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
				print "<td class='line'>".$row['prior_nivel']."</td>";
				print "<td class='line'>".$row['slas_desc']."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['prior_cod']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['prior_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
			//print "</TABLE>";
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('CADASTRE_PRIORITIES')."</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_LEVEL').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='p_nivel' class='text' id='idNivel'></td>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SLA').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select' name='sla' id='idSla'>";
			print "<option value=-1>".TRANS('SEL_SLA')."</option>";

				$sql="select * from sla_solucao order by slas_tempo";
				$commit = mysql_query($sql);
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['slas_cod'].">".$row["slas_desc"]."</option>";
				} // while
		print "</select>";
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
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_LEVEL').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='p_nivel' id='idNivel' value='".$row['prior_nivel']."'></td>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_SLA').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='sla' id='idSla'>";

			$sql = "select * from sla_solucao where slas_cod=".$row["slas_cod"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >".TRANS('SEL_SLA')."</option>";
					$sql="select * from sla_solucao order by slas_tempo";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["slas_cod"]."";
                        			if ($rowB['slas_cod'] == $row['slas_cod'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["slas_desc"]."</option>";
					} // while

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

		$sql_2 = "SELECT * FROM localizacao where loc_prior ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="localizacao, ";

		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_CANT_DEL').": ".$texto." ".TRANS('LINKED_TABLE')."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM prioridades WHERE prior_cod='".$_GET['cod']."'";
			$resultado2 = mysql_query($query2) or die (TRANS('ERR_QUERY')."!<br>".$query2);

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

		$qryl = "SELECT * FROM prioridades WHERE prior_nivel='".$_POST['p_nivel']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_RECORD_EXISTS');
				$erro = true;
		}

		if (!$erro)
		{

			$query = "INSERT INTO prioridades (prior_nivel, prior_sla) values ('".noHtml($_POST['p_nivel'])."', ".$_POST['sla'].")";
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

		$query2 = "UPDATE prioridades SET prior_nivel='".noHtml($_POST['p_nivel'])."', prior_sla = ".$_POST['sla']." WHERE prior_cod='".$_POST['cod']."'";
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
		var ok = validaForm('idNivel','','Nível',1);
		if (ok) var ok = validaForm('idSla','COMBO','SLA',1);

		return ok;
	}

-->
</script>


<?
print "</body>";
print "</html>";
