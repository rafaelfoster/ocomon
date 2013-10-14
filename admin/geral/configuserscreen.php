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
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1,'helpconfig.php');


    print "<BR><B>".TRANS('TTL_CONFIG_USER_SCREEN').":</b><BR>";

		$query = "SELECT c.*, a.*, b.sistema as ownarea, b.sis_id as ownarea_cod ".
					"FROM configusercall as c, sistemas as a, sistemas as b ".
					"WHERE c.conf_opentoarea = a.sis_id and c.conf_ownarea = b.sis_id ";
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
			print "<tr><td>".TRANS('OPT_AREA_USER_OPENTO')."</td><td>".$row['sistema']."</td></tr>";
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><td colspan='2'><b>".TRANS('OPT_TTL_CUSTON_OPEN').":</b></td></tr>";
			print "<tr><td>".TRANS('OPT_AREAS_CUSTON')."</td><td>".$listAreas."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_AREA','Campo: ÁREA')."</td><td>".transbool($row['conf_scr_area'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_PROB','Campo: PROBLEMA')."</td><td>".transbool($row['conf_scr_prob'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_DESC','Campo: DESCRIÇÃO')."</td><td>".transbool($row['conf_scr_desc'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_UNIT','Campo: UNIDADE')."</td><td>".transbool($row['conf_scr_unit'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_TAG','Campo: ETIQUETA')."</td><td>".transbool($row['conf_scr_tag'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_LNK_TAG','Link: CHECA ETIQUETA')."</td><td>".transbool($row['conf_scr_chktag'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_LNK_HIST','Link: CHECA HISTÓRICO')."</td><td>".transbool($row['conf_scr_chkhist'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_CONTACT','Campo: CONTATO')."</td><td>".transbool($row['conf_scr_contact'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_PHONE','Campo: TELEFONE')."</td><td>".transbool($row['conf_scr_fone'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_LOCAL','Campo: LOCAL')."</td><td>".transbool($row['conf_scr_local'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_BT_LOAD_LOCAL','Botão: CARREGAR LOCAL')."</td><td>".transbool($row['conf_scr_btloadlocal'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_SCH_LOCAL','Link: PESQUISA POR LOCAL')."</td><td>".transbool($row['conf_scr_searchbylocal'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_OPERATOR','Campo: OPERADOR')."</td><td>".transbool($row['conf_scr_operator'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_DATE','Campo: DATA')."</td><td>".transbool($row['conf_scr_date'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_SCHEDULE','Campo: AGENDAR')."</td><td>".transbool($row['conf_scr_schedule'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_FOWARD','Campo: ENCAMINHAR')."</td><td>".transbool($row['conf_scr_foward'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_STATUS','Campo: STATUS')."</td><td>".transbool($row['conf_scr_status'])."</td></tr>";

			print "<tr><td>".TRANS('OPT_FIELD_ATTACH','Campo: ANEXAR IMAGEM')."</td><td>".transbool($row['conf_scr_upload'])."</td></tr>";

			print "<tr><td>".TRANS('OPT_FIELD_REPLICATE','Campo: REPLICAR')."</td><td>".transbool($row['conf_scr_replicate'])."</td></tr>";
			print "<tr><td>".TRANS('OPT_FIELD_SEND_EMAIL','Campo: ENVIAR E-MAIL')."</td><td>".transbool($row['conf_scr_mail'])."</td></tr>";
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
		print "<tr><td colspan='2'><b>".TRANS('OPT_TTL_CUSTON_OPEN').":</b></td></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'>".TRANS('OPT_AREAS_CUSTON').":</td></tr>";

		$qrycustom = "select * from sistemas order by sistema";
		$execcustom = mysql_query($qrycustom);
		$i = 0;
		$checked = array();
		while ($rowcustom=mysql_fetch_array($execcustom)){
			if (count($customareas)==1){
				if ($customareas== $rowcustom['sis_id']) {
					$checked[$i] = "checked";
				} else $checked[$i] = "";
			} else {
				for ($j=0; $j<count($customareas); $j++){
					if ($customareas[$j]== $rowcustom['sis_id']) {
						$checked[$i] = "checked";
					} //else $checked[$i] = "";
				}
			}
			print "<tr><td>".$rowcustom['sistema']."</td><td><input type='checkbox' name='grupo[".$i."]' value='".$rowcustom['sis_id']."' ".$checked[$i].">";
			print "</td></tr>";
			$i++;
		}

		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<tr><td colspan='2'><b>".TRANS('OPT_FIELD_AVAILABLE').":</b></td></tr>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_AREA')."</td><td>";
		print "<select name='area' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_area'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_area'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_PROB')."</td><td>";
		print "<select name='problema' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_prob'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_prob'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_DESC')."</td><td>";
		print "<select name='descricao' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_desc'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_desc'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_UNIT')."</td><td>";
		print "<select name='unidade' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_unit'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_unit'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_TAG')."</td><td>";
		print "<select name='etiqueta' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_tag'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_tag'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_LNK_TAG')."</td><td>";
		print "<select name='chktag' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_chktag'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_chktag'] == 1) print " selected";
		print ">".TRANS('SIM')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_LNK_HIST')."</td><td>";
		print "<select name='chkhist' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_chkhist'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_chkhist'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_CONTACT')."</td><td>";
		print "<select name='contato' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_contact'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_contact'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_PHONE')."</td><td>";
		print "<select name='telefone' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_fone'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_fone'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_LOCAL')."</td><td>";
		print "<select name='local' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_local'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_local'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_BT_LOAD_LOCAL')."</td><td>";
		print "<select name='loadlocal' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_btloadlocal'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_btloadlocal'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_SCH_LOCAL')."</td><td>";
		print "<select name='searchlocal' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_searchbylocal'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_searchbylocal'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_OPERATOR')."</td><td>";//.transbool($row['conf_scr_operator'])."</td></tr>";
		print "<select name='operador' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_operator'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_operator'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_DATE')."</td><td>";//.transbool($row['conf_scr_date'])."</td></tr>";
		print "<select name='data' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_date'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_date'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_SCHEDULE')."</td><td>";
		print "<select name='date_schedule' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_schedule'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_schedule'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_FOWARD')."</td><td>";
		print "<select name='foward' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_foward'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_foward'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";



		print "<tr><td>".TRANS('OPT_FIELD_STATUS')."</td><td>";//.transbool($row['conf_scr_status'])."</td></tr>";
		print "<select name='status' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_status'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_status'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_REPLICATE')."</td><td>";//.transbool($row['conf_scr_replicate'])."</td></tr>";
		print "<select name='replicar' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_replicate'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_replicate'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";

		print "<tr><td>".TRANS('OPT_FIELD_ATTACH')."</td><td>";//.transbool($row['conf_scr_replicate'])."</td></tr>";
		print "<select name='upload' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_upload'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_upload'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";


		print "<tr><td>".TRANS('OPT_FIELD_SEND_EMAIL')."</td><td>";//.transbool($row['conf_scr_mail'])."</td></tr>";
		print "<select name='mail' class='select'>";
		print "<option value='0'";
		if ($row['conf_scr_mail'] == 0) print " selected";
		print ">".TRANS('NOT')."</option>";
		print "<option value='1'";
		if ($row['conf_scr_mail'] == 1) print " selected";
		print ">".TRANS('YES')."</option>";
		print "</select></td></tr>";



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
				"conf_user_opencall= ".$_POST['useropencall'].", conf_custom_areas = '".$levels."', ".
				"conf_ownarea = ".$_POST['ownarea'].", conf_opentoarea = ".$_POST['toarea'].", ".
				"conf_scr_area = ".$_POST['area'].", conf_scr_prob = ".$_POST['problema'].", ".
				"conf_scr_desc = ".$_POST['descricao'].", conf_scr_unit = ".$_POST['unidade'].", ".
				"conf_scr_tag = ".$_POST['etiqueta'].", conf_scr_chktag = ".$_POST['chktag'].", ".
				"conf_scr_chkhist = ".$_POST['chkhist'].", conf_scr_contact = ".$_POST['contato'].", ".
				"conf_scr_fone = ".$_POST['telefone'].", conf_scr_local = ".$_POST['local'].", ".
				"conf_scr_btloadlocal = ".$_POST['loadlocal'].", conf_scr_searchbylocal = ".$_POST['searchlocal']." ,".
				"conf_scr_operator = ".$_POST['operador'].", conf_scr_date = ".$_POST['data'].", ".
				"conf_scr_schedule = ".$_POST['date_schedule'].", ".
				"conf_scr_foward = ".$_POST['foward'].", ".
				"conf_scr_status = ".$_POST['status'].", conf_scr_replicate = ".$_POST['replicar']." ,".
				"conf_scr_upload = ".$_POST['upload']." ,".
				"conf_scr_mail = ".$_POST['mail'].", conf_scr_msg = '".noHtml($_POST['msg'])."' ";

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
<?
print "</body>";
print "</html>";

?>