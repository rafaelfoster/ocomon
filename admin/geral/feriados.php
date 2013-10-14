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
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html><head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
	print "<body>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);


        print "<BR><B>".TRANS('ADM_HOLIDAYS').":</B><BR>";

	$query = "SELECT * from feriados order by data_feriado DESC";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) && !isset($_POST['submit'])) {

        	//print "<TD align='right'><a href='".$_SERVER['PHP_SELF']."?action=incluir'>Incluir feriado.</a></TD><BR>";
        	print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('MSG_NO_RECORDS')."!");
		}
        	else
		{
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			$linhas = mysql_numrows($resultado);

			print "<br><br><TR><td class='line'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$linhas."</b> ".TRANS('RECORDS_IN_SYSTEM').".</TD></TR>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
			print "<TR class='header'><td class='line'>".TRANS('COL_DATE')."</TD><td class='line'>".TRANS('COL_DESC')."</TD><td class='line'>".TRANS('COL_PERSISTANT','PERMANENTE')."</TD><td class='line'><b>".TRANS('COL_EDIT')."</b></TD><td class='line'><b>".TRANS('COL_DEL')."</b></TD>";
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
				print "<td class='line'>".datab2($row['data_feriado'])."</TD>";
				print "<td class='line'>".$row['desc_feriado']."</TD>";
				print "<td class='line'>".transbool($row['fixo_feriado'])."</TD>";
				print "<td class='line'><a onClick=\"redirect('feriados.php?action=alter&cod=".$row['cod_feriado']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
				print "<td class='line'><a onClick=\"confirma('".TRANS('ENSURE_DEL')."?','feriados.php?action=excluir&cod=".$row['cod_feriado']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
				print "</TR>";
			}
			print "</TABLE>";
		}

	} else
	if ((isset($_GET['action']) && ($_GET['action'] == "incluir")) && !isset($_POST['submit']) ) {

		print "<B>".TRANS('CADASTRE_HOLIDAYS').":<br>";
		print "<form method='post' name='incluir' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>".TRANS('COL_DESC')."</td><td class='line'><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";

		print "<tr>";
		print "<td class='line'>".TRANS('COL_DATE')."</td>".
			"<td class='line'><input type='text' class='data' name='data' id='idData'>".
				"<a onclick=\"displayCalendar(document.forms[0].data,'dd-mm-yyyy',this)\">".
				"<img height='16' width='16' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('SEL_DATE')."'>".
				"</a>&nbsp;<input type='checkbox' name='permanente'>".TRANS('COL_PERSISTANT')."".
			"</td>";
		print "</tr>";
		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='".TRANS('bt_cadastrar')."'></td>";

		print "<td class='line'><input type='reset'  class='button' name='reset' value='".TRANS('bt_cancelar')."' onClick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ( (isset($_GET['action']) && $_GET['action']=="alter") && !isset($_POST['submit'])) {
		$qry = "SELECT * from feriados where cod_feriado = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>".TRANS('TTL_EDIT_RECORD').":<br>";
		print "<form method='post' name='alter' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>".TRANS('COL_DESC')."</b></td><td class='line'><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['desc_feriado']."'></td>";
		print "</tr>";
		print "<tr nowrap>";
		print "<td bgcolor=".TD_COLOR."><b>".TRANS('COL_DATE')."</b></td><td class='line'>".
				"<input type='text' class='text' name='data' id='idData' value='".str_replace("/","-",datab2($rowAlter['data_feriado']))."'>".
				"<a onclick=\"displayCalendar(document.forms[0].data,'dd-mm-yyyy',this)\">".
				"<img height='16' width='16' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('SEL_DATE')."'>".
				"</a><input type='checkbox' name='permanente' ".($rowAlter['fixo_feriado']?'checked':'').">".TRANS('COL_PERSISTANT')."".
			"</td>";
		//$data = str_replace("-","/",$data);
		print " <input type='hidden' name='cod' value='".$_GET['cod']."'>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER')."'></td>";
		print "<td class='line'><input type='reset'  class='button' name='reset' value='".TRANS('bt_cancelar')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){
			$qry = "DELETE FROM feriados where cod_feriado = ".$_GET['cod']."";
			$exec = mysql_query($qry) or die (TRANS('ERR_DEL')."!");
			if ($exec == 0)
			{
				$aviso = TRANS('ERR_DEL');
			}
			else
			{
				$aviso = TRANS('OK_DEL');
			}
			print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if (isset($_POST['submit']) && $_POST['submit'] == "Incluir") {
		if ((!empty($_POST['descricao'])) && (!empty($_POST['data']))){
			$qry = "select * from feriados where desc_feriado = '".$_POST['descricao']."' and data_feriado = '".$_POST['data']."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				print "<script>mensagem('".TRANS('MSG_RECORD_EXISTS','',0)."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
			} else {

				$data = str_replace("-","/",$_POST['data']);
				$data = converte_dma_para_amd($data);

				if (isset($_POST['permanente'])){
					$permanente = 1;
				} else
					$permanente = 0;

				$qry = "INSERT INTO feriados (desc_feriado,data_feriado, fixo_feriado) ".
						"values ('".noHtml($_POST['descricao'])."','".$data."', ".$permanente.")";
				$exec = mysql_query($qry) or die ('Erro na inclusão do feriado!'.$qry);
				print "<script>mensagem('".TRANS('OK_INSERT')."!'); redirect('feriados.php');</script>";
				}
		} else {
				print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}

	} else

	if (isset($_POST['submit']) && $_POST['submit'] = "Alterar") {
		if ((!empty($_POST['descricao'])) && (!empty($_POST['data']))){

			$data = str_replace("-","/",$_POST['data']);
			$data = converte_dma_para_amd($data);

			if (isset($_POST['permanente'])){
				$permanente = 1;
			} else
				$permanente = 0;


			//$qry = "UPDATE feriados set desc_feriado='".noHtml($descricao)."', data_feriado='".$data."' where cod_feriado=".$cod."";
			$qry = "UPDATE feriados set desc_feriado='".noHtml($_POST['descricao'])."', ".
					"data_feriado='".$data."', fixo_feriado=".$permanente." ".
				"WHERE cod_feriado=".$_POST['cod']."";
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
		var ok = validaForm('idDesc','','Descrição',1);
		if (ok) var ok = validaForm('idData','DATA-','Data',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);

		return ok;
	}
-->
</script>
<?
print "</html>";

?>