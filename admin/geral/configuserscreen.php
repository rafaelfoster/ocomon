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
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1,'helpconfig.php');


    print "<BR><B>".TRANS('TTL_CONFIG_USER_SCREEN').":</b><BR>";

		$query = "SELECT c.*, a.*, b.sistema as ownarea, b.sis_id as ownarea_cod ".
					"FROM configusercall as c, sistemas as a, sistemas as b ".
					"WHERE c.conf_opentoarea = a.sis_id and c.conf_ownarea = b.sis_id and c.conf_cod = 1"; //codigo 1 eh reservado para as opcoes globais
        	$resultado = mysql_query($query);
		$row = mysql_fetch_array($resultado);

		$customareas = "";
		$customareas = sepcomma($row['conf_custom_areas'],$customareas);
		$listAreas = "";
		if (count($customareas)==1){
			$qry = "SELECT * FROM sistemas where sis_id=".(int)$customareas."";
			$exec = mysql_query($qry);
			$rowAreas = mysql_fetch_array($exec);
			$listAreas = $rowAreas['sistema'];
		} else {
			for ($i=0; $i<count($customareas); $i++){
				$qry = "SELECT * FROM sistemas where sis_id=".(int)$customareas[$i]."";
				$exec = mysql_query($qry);
				$rowAreas = mysql_fetch_array($exec);
				if (strlen($listAreas)>0) $listAreas.=", ";
				$listAreas.=$rowAreas['sistema'];
			}
		}


	if ((empty($_GET['action'])) and empty($_POST['submit'])){

        print "<br><TD align='left'>".
        		"<input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_EDIT_CONFIG')."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cellStyle=true');\">".
        	"</TD><br><BR>";

        if (mysql_numrows($resultado) == 0)
        {
                echo mensagem(TRANS('ALERT_CONFIG_EMPTY'));
        }
        else
        {
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			$linhas = mysql_numrows($resultado);
			print "<td>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
			print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><td>".TRANS('OPT_ALLOW_USER_OPEN')."</td><td>".transbool($row['conf_user_opencall'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_AREA_ONLY_OPEN')."</td><td>".$row['ownarea']."</td></tr>";
			//print "<tr><td>".TRANS('OPT_AREA_USER_OPENTO')."</td><td>".$row['sistema']."</td></tr>";
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><td colspan='2'>".TRANS('OPT_FIELD_MSG','Mensagem ao abrir chamado').":</td></tr><tr><td colspan='2'>".$row['conf_scr_msg']."</td></tr>";

			print "<tr><td></td><td></td></tr>";

			print "</TABLE>";
        }

	} else

	if ((isset($_GET['action']) && ($_GET['action']=="alter")) && empty($_POST['submit'])){


		print "<form name='alter' action='".$_SERVER['PHP_SELF']."' method='post' onSubmit=\"return valida()\">"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td>".TRANS('OPT_ALLOW_USER_OPEN')."</td><td>";//.transbool($row['conf_user_opencall'])."</td></tr>";
		print "<select name='useropencall' class='select'>";
		print "<option value='0'";
		if ($row['conf_user_opencall'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_user_opencall'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_AREA_ONLY_OPEN')."</td><td>";//.$row['ownarea']."</td></tr>";
		$qryownarea = "SELECT * FROM sistemas where sis_atende = 0 ORDER BY sistema";
		$execownarea = mysql_query($qryownarea);
		print "<select name='ownarea' class='select' id='idOwnarea'>";
		while ($rowownarea = mysql_fetch_array($execownarea)){
			print "<option value='".$rowownarea['sis_id']."'";
			if ($rowownarea['sis_id'] == $row['ownarea_cod']) print " selected";
			print ">".$rowownarea['sistema']."";
		}
		print "</select>";
		print "</td></tr>";
// 		print "<tr><td>".TRANS('OPT_AREA_USER_OPENTO')."</td><td>";//.$row['sistema']."</td></tr>";
// 		$qrytoarea = "SELECT * FROM sistemas where sis_atende = 1 ORDER BY sistema";
// 		$exectoarea = mysql_query($qrytoarea);
// 		print "<select name='toarea' class='select'>";
// 		while ($rowtoarea = mysql_fetch_array($exectoarea)){
// 			print "<option value='".$rowtoarea['sis_id']."'";
// 			if ($rowtoarea['sis_id'] == $row['sis_id']) print " selected";
// 			print ">".$rowtoarea['sistema']."";
// 		}
// 		print "</select>";
// 		print "</td></tr>";



		print "<tr><td colspan='2'></td></tr>";
		print "<tr><td colspan='2'></td></tr>";
		
		print "<tr><td colspan='2'>".TRANS('OPT_FIELD_MSG','Mensagem ao abrir chamado')."&nbsp;(".TRANS('OPT_ENVIRON_AVAIL','variáveis de ambiente disponíveis: %numero%')."):</td><td>";//.$row['conf_scr_msg']."</td></tr>";
		print "<tr><td colspan='2'><textarea name='msg' class='textarea'>".$row['conf_scr_msg']."</textarea></td></tr>";


		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER')."'></td>";
		print "<td><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ($_POST['submit'] = TRANS('BT_ALTER')){

		$qy = "select count(*) areas from sistemas";
		$ex = mysql_query($qy);
		$ro = mysql_fetch_array($ex);

		$levels = "";
		for ($i=0; $i<(int)$ro['areas']; $i++){
			if (!empty($_POST['grupo'][$i])){
				if (strlen($levels)>0) $levels.= ", ";
				$levels.=$_POST['grupo'][$i];
			}
		}

		$qry = "UPDATE configusercall SET ".
				"conf_user_opencall= ".$_POST['useropencall'].", ".
				"conf_ownarea = ".$_POST['ownarea'].", ".
				"conf_scr_msg = '".noHtml($_POST['msg'])."' WHERE conf_cod = 1 ";

		//print $qry;
		//exit;
		$exec= mysql_query($qry) or die(TRANS('ERR_EDIT'));
		//Verifica se a área para abertura de chamados possui permissão ao módulo de ocorrências, se não possuir, cadastra a permissão
		$qrychecapermissao = "select * from permissoes where perm_area=".$_POST['ownarea']." and perm_modulo=1";
		$execcheca = mysql_query($qrychecapermissao) or die(TRANS('ERR_QUERY').$execcheca);
		$regs = mysql_num_rows($execcheca);
		if ($regs == 0) {
			$qrypermissao = "INSERT INTO permissoes (perm_area,perm_modulo,perm_flag) values (".$_POST['ownarea'].",1,1)";
			$execpermissao = mysql_query($qrypermissao) or die (TRANS('ERR_QUERY').$qrypermissao);
		}

		print "<script>mensagem('".TRANS('MSG_SUCCES_ALTER','',0)."'); redirect('configuserscreen.php');</script>";
	}
?>
<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idOwnarea','COMBO','"Área somente abertura"',1);

		return ok;
	}
-->
</script>
<?php 
print "</body>";
print "</html>";

?>