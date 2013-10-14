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
	print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1,'helpconfig.php');


	print "<div id='idLoad' class='loading' style='{display:none}'><img src='../../includes/imgs/loading.gif'></div>";

    print "<BR><B>".TRANS('TTL_CONFIG_USER_SCREEN').":</b><BR>";

		$query = "SELECT c.*, a.*, b.sistema as ownarea, b.sis_id as ownarea_cod ".
					"FROM configusercall as c, sistemas as a, sistemas as b ".
					"WHERE c.conf_opentoarea = a.sis_id and c.conf_ownarea = b.sis_id and c.conf_cod <> 1"; //codigo 1 é reservado para as definicoes globais
        	$resultado = mysql_query($query);
        	$row = mysql_fetch_array($resultado);
        	
        	$resultado2 = mysql_query($query);
		


		$qrymsgdefault = $QRY["useropencall"];
		$execqrydefault = mysql_query($qrymsgdefault);
		$rowmsgdefault = mysql_fetch_array($execqrydefault);

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
        		"<input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD')."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=NEW&cellStyle=true');\">".
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
			//print "<td>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0'  width='50%'>";
			//print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
			//print "<tr><td colspan='2'>&nbsp;</td></tr>";
			
			print "<tr class='header'>";
			print "<td>".TRANS('SCREEN_PROFILE_COD')."</td>";
			print "<td>".TRANS('SCREEN_PROFILE_NAME')."</td>";		
			print "<td>".TRANS('EDIT')."</td>";
			print "<td>".TRANS('DEL')."</td>";
			
			print "</tr>";
				
			$j = 2;
			$i = 0;
			$trClass = "";	
			while ($row = mysql_fetch_array($resultado2)) {
				//print "<tr>";
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
				print "<td class='line'><a onClick=\"exibeEscondeImg('idTr".$j."'); exibeEscondeImg('idDivDetails".$j."'); ajaxFunction('idDivDetails".$j."', 'screen_profile_details.php', 'idLoad', 'cod_profile=idNumero".$j."','INDIV=idINDIV');\">".$row['conf_cod']."</a></TD>";
					print "<input type='hidden' name='numeroAjax".$j."' id='idNumero".$j."' value='".$row['conf_cod']."'>";
					print "<input type='hidden' name='INDIV' id='idINDIV' value='INDIV'>";			
				
				//print "<td>".$row['conf_cod']."</td>";
				print "<td class='line'>".$row['conf_name']."</td>";
				print "<td class='line'><a href='screen_profile_details.php?action=alter&cod_profile=".$row['conf_cod']."'>".TRANS('EDIT')."</a></td>";
				print "<td class='line'><a href='screenprofiles.php?action=DEL&cod_profile=".$row['conf_cod']."'>".TRANS('DEL')."</a></td>";				
				print "</tr>";
				print "<tr><td colspan='4'  id='idTr".$j."' style='{display:none;}'><div id='idDivDetails".$j."' style='{display:none;}'></div></td></tr>";			
	
			}
				
				print "<tr><td></td><td></td></tr>";
	
				print "</TABLE>";
		}

	} else

	if ((isset($_GET['action']) && ($_GET['action']=="NEW")) && empty($_POST['submit'])) {


		print "<form name='NEW' action='".$_SERVER['PHP_SELF']."' method='post' onSubmit=\"return valida()\">"; //onSubmit='return valida()'
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<TR class='header'><td>".TRANS('OPT_DIRETIVA')."</TD><td>".TRANS('OPT_VALOR')."</TD></TD></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		
		print "<tr><td>".TRANS('SCREEN_PROFILE_NAME')."</td><td><input type='text' class='text' name='screen_name' id='idScreen'></td></tr>";
		
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


		print "<tr><td>".TRANS('OPT_AREA_USER_OPENTO')."</td><td>";//.$row['sistema']."</td></tr>";
		$qrytoarea = "SELECT * FROM sistemas where sis_atende = 1 ORDER BY sistema";
		$exectoarea = mysql_query($qrytoarea);
		print "<select name='toarea' class='select'>";
		while ($rowtoarea = mysql_fetch_array($exectoarea)){
			print "<option value='".$rowtoarea['sis_id']."'";
			if ($rowtoarea['sis_id'] == $row['sis_id']) print " selected";
			print ">".$rowtoarea['sistema']."";
		}
		print "</select>";
		print "</td></tr>";

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><b>".TRANS('OPT_FIELD_AVAILABLE').":</b></td></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_AREA')."</td><td>";
		print "<select name='area' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_PROB')."</td><td>";
		print "<select name='problema' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_DESC')."</td><td>";
		print "<select name='descricao' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_UNIT')."</td><td>";
		print "<select name='unidade' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_TAG')."</td><td>";
		print "<select name='etiqueta' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_LNK_TAG')."</td><td>";
		print "<select name='chktag' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_LNK_HIST')."</td><td>";
		print "<select name='chkhist' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_CONTACT')."</td><td>";
		print "<select name='contato' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_PHONE')."</td><td>";
		print "<select name='telefone' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_LOCAL')."</td><td>";
		print "<select name='local' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_BT_LOAD_LOCAL')."</td><td>";
		print "<select name='loadlocal' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_SCH_LOCAL')."</td><td>";
		print "<select name='searchlocal' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_OPERATOR')."</td><td>";//.transbool($row['conf_scr_operator'])."</td></tr>";
		print "<select name='operador' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_DATE')."</td><td>";//.transbool($row['conf_scr_date'])."</td></tr>";
		print "<select name='data' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_SCHEDULE')."</td><td>";
		print "<select name='date_schedule' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";
		
		
		print "<tr><td>".TRANS('OPT_FIELD_FOWARD')."</td><td>";
		print "<select name='foward' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_STATUS')."</td><td>";//.transbool($row['conf_scr_status'])."</td></tr>";
		print "<select name='status' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_REPLICATE')."</td><td>";//.transbool($row['conf_scr_replicate'])."</td></tr>";
		print "<select name='replicar' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";
		
		
		print "<tr><td>".TRANS('OPT_FIELD_ATTACH')."</td><td>";//.transbool($row['conf_scr_replicate'])."</td></tr>";
		print "<select name='upload' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_SEND_EMAIL')."</td><td>";//.transbool($row['conf_scr_mail'])."</td></tr>";
		print "<select name='mail' class='select'>";
		print "<option value='0'>".TRANS('NOT')."</option>";
		print "<option value='1'selected>".TRANS('YES')."</option>";
		print "</select></td></tr>";


		
		print "<tr><td colspan='2'>".TRANS('OPT_FIELD_MSG','Mensagem ao abrir chamado')."&nbsp;(".TRANS('OPT_ENVIRON_AVAIL','variáveis de ambiente disponíveis: %numero%')."):</td><td>";//.$row['conf_scr_msg']."</td></tr>";
		print "<tr><td colspan='2'><textarea name='msg' class='textarea'>".$rowmsgdefault['conf_scr_msg']."</textarea></td></tr>";


		print "<tr><td><input type='submit'  class='button' name='submit' value='".TRANS('BT_CAD')."'></td>";
		print "<td><input type='reset' name='reset'  class='button' value='".TRANS('BT_CANCEL')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else
	
	if (isset($_POST['submit']) && $_POST['submit'] = TRANS('BT_CAD')){


		$qry = "INSERT INTO configusercall (conf_name, conf_user_opencall, conf_opentoarea, conf_scr_area, conf_scr_prob, conf_scr_desc, ".
				"conf_scr_unit, conf_scr_tag, conf_scr_chktag, conf_scr_chkhist, conf_scr_contact, conf_scr_fone, conf_scr_local, ".
				"conf_scr_btloadlocal, conf_scr_searchbylocal, conf_scr_operator, conf_scr_date, conf_scr_schedule, conf_scr_foward, ".
				"conf_scr_status, conf_scr_replicate, conf_scr_upload, conf_scr_mail, conf_scr_msg) ".
				"values ".
				"('".$_POST['screen_name']."', ".$_POST['useropencall'].", ".$_POST['toarea'].", ".$_POST['area'].", ".$_POST['problema'].", ".
				"".$_POST['descricao'].", ".$_POST['unidade'].", ".$_POST['etiqueta'].", ".$_POST['chktag'].", ".$_POST['chkhist'].", ".
				"".$_POST['contato'].", ".$_POST['telefone'].", ".$_POST['local'].", ".$_POST['loadlocal'].", ".$_POST['searchlocal']." , ".
				"".$_POST['operador'].", ".$_POST['data'].", ".$_POST['date_schedule'].", ".$_POST['foward'].", ".$_POST['status'].", ".
				"".$_POST['replicar']." ,".$_POST['upload']." , ".$_POST['mail'].", '".noHtml($_POST['msg'])."' )";
				

		//print $qry;
		//exit;
		//$exec= mysql_query($qry) or die(TRANS('ERR_EDIT'));
		$exec= mysql_query($qry) or die($qry);
		//Verifica se a área para abertura de chamados possui permissão ao módulo de ocorrências, se não possuir, cadastra a permissão
// 		$qrychecapermissao = "select * from permissoes where perm_area=".$_POST['ownarea']." and perm_modulo=1";
// 		$execcheca = mysql_query($qrychecapermissao) or die(TRANS('ERR_QUERY').$execcheca);
// 		$regs = mysql_num_rows($execcheca);
// 		if ($regs == 0) {
// 			$qrypermissao = "INSERT INTO permissoes (perm_area,perm_modulo,perm_flag) values (".$_POST['ownarea'].",1,1)";
// 			$execpermissao = mysql_query($qrypermissao) or die (TRANS('ERR_QUERY').$qrypermissao);
// 		}

		print "<script>mensagem('".TRANS('MSG_SUCCES_ALTER','',0)."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
	} else
	
	if (isset($_GET['action']) && $_GET['action'] == "DEL"){

		$total = 0; $texto = "";
		$sql_1 = "SELECT * from sistemas where sis_screen='".$_GET['cod_profile']."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.="Áreas de atendimento, ";


		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_CANT_DEL','',0).": ".TRANS('LINKED_AREAS','',0)."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM configusercall WHERE conf_cod='".$_GET['cod_profile']."'";
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


	}
?>
<script type="text/javascript">
<!--
	function valida(){

		var ok = validaForm('idScreen','ALFA','<?php print TRANS('SCREEN_PROFILE_NAME')?>',1);

		return ok;
	}
-->
</script>
<?php 
print "</body>";
print "</html>";

?>