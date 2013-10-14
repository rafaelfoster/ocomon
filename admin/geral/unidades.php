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

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	print "<BR><B>".TRANS('TTL_UNIT').":</B><BR>";

	$query = "SELECT * from instituicao order by inst_nome";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) and !isset($_POST['submit'])){

		//print "<TD align='right'><a href='".$_SERVER['PHP_SELF']."'?action=incluir'>".TRANS('TXT_INCLUDE_UNIT').".</a></TD><BR>";
		print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('TXT_INCLUDE_UNIT','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR><br><br>";
		if (mysql_numrows($resultado) == 0)
		{
			echo mensagem(TRANS('MSG_NOT_UNIT_IN_SYSTEM'));
		}
        else
        {
                $linhas = mysql_numrows($resultado);
                print "<tr>";
                print "<td class='line'>";
                print "".TRANS('THERE_IS_ARE')." <b>".$linhas."</b> ".TRANS('TXT_UNIT_CAD_SYSTEM')."<br>";
                print "</tr><br>";
                print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
                print "<TR class='header'><td class='line'>".TRANS('OCO_FIELD_UNIT')."</TD><td class='line'>".TRANS('OCO_FIELD_STATUS')."</TD><td class='line'><b>".TRANS('OCO_FIELD_ALTER')."</b></TD><td class='line'><b>".TRANS('OCO_FIELD_EXCLUDE')."</b></TD>";
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
                        if ($row['inst_status'] == 0) $status = TRANS('INACTIVE'); else $status = TRANS('ACTIVE');
                        print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
                        print "<td class='line'>".$row['inst_nome']."</TD>";
						print "<td class='line'>".$status."</TD>";
                        print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['inst_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
                        print "<td class='line'><a onClick=\"confirma('".TRANS('MSG_DEL_UNIT_IN_SYSTEM')."','".$_SERVER['PHP_SELF']."?action=excluir&cod=".$row['inst_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
                        print "</TR>";
				}
                print "</TABLE>";
        }

	} else
	if ((isset($_GET['action'])  && $_GET['action']=="incluir") && (!isset($_POST['submit']))) {

		print "<B>".TRANS('SUBTTL_CAD_UNIT').":<br>";
		print "<form name='incluir' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td class='line'>".TRANS('OCO_FIELD_DESC')."</td><td class='line'><input type='text' class='text' name='descricao' id='idDesc'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='".TRANS('BT_INCLUDE')."'></td>";

		print "<td class='line'><input type='reset' class='button'  name='reset' value='".TRANS('BT_CANCEL')."' onClick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";

	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {

		$qry = "SELECT * from instituicao where inst_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>".TRANS('SUBTTL_ALTER_DESC_UNIT').":<br>";
		print "<form name='alter' method='post' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'><b>".TRANS('OCO_FIELD_DESC')."</b></td><td class='line'><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['inst_nome']."'></td>";
		print "</tr>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'><b>".TRANS('OCO_FIELD_STATUS')."</b></td><td class='line'><select name='status' class='select'>";

		//<input type='text' class='text' name='data' value='".$rowAlter['data_feriado']."'>";
			print"<option value=1";
			if ($rowAlter['inst_status']==1) print " selected";
			print ">".TRANS('ACTIVE')."</option>";
			print"<option value=0";
			if ($rowAlter['inst_status']==0) print " selected";
			print">".TRANS('INACTIVE')."</option>";

		print "</select>";
		print " <input type='hidden' name='cod' value='".$_GET['cod']."'></td>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER')."'></td>";
		print "<td class='line'><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){
			$qryAcha = "select * from equipamentos where comp_inst = ".$_GET['cod']."";
			$execAcha = mysql_query($qryAcha);
			$achou = mysql_numrows($execAcha);

			if ($achou){
				print "<script>mensagem('".TRANS('MSG_NOT_DEL_EQUIP_ASSOC')."');".
						" redirect('".$_SERVER['PHP_SELF']."');</script>";
				exit;
			} else {

				$qry = "DELETE FROM instituicao where inst_cod = ".$_GET['cod']."";
				$exec = mysql_query($qry) or die (TRANS('MSG_ERR_DEL_REGISTER'));

				print "<script>mensagem('".TRANS('OK_DEL')."');".
						" redirect('".$_SERVER['PHP_SELF']."');</script>";
			}
	} else

	if ($_POST['submit']==TRANS('BT_INCLUDE')){
		if (!empty($_POST['descricao'])){
			$qry = "select * from instituicao where inst_nome = '".$_POST['descricao']."'";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				print "<script>mensagem('".TRANS('MSG_UNIT_CAD_IN_SYSTEM')."'); redirect('".$_SERVER['PHP_SELF']."'); </script>";

			} else {
				$qry = "INSERT INTO instituicao (inst_nome) values ('".noHtml($_POST['descricao'])."')";
				$exec = mysql_query($qry) or die (TRANS('MSG_ERR_INCLUDE_UNIT') .$qry);
				print "<script>mensagem('".TRANS('MSG_DATA_INCLUDE_OK')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
				}
		} else {
				print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}

	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){
		if (!empty($_POST['descricao'])){

			$qry = "UPDATE instituicao set inst_nome='".noHtml($_POST['descricao'])."', inst_status='".$_POST['status']."' where inst_cod=".$_POST['cod']."";
			$exec= mysql_query($qry) or die(TRANS('MSG_NOT_ALTER_REG') .$qry);

				print "<script>mensagem('".TRANS('MSG_DATA_ALTER_OK')."'); history.go(-2)(); </script>";

		} else {

			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); history.go(-2)(); </script>";

		}
	}




print "</body>";
?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idDesc','','Descrição',1);
		return ok;
	}
-->
</script>
<?php 
print "</html>";

?>