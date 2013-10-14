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

	print "<BR><B>".TRANS('ADM_AREAS')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor=".BODY_COLOR.">";




		//$query = "SELECT * FROM sistemas ";
		$query = "SELECT s.*, c.* FROM sistemas as s left join configusercall as c on s.sis_screen = c.conf_cod ";
		if (isset($_GET['cod'])) {
			$query.= "WHERE sis_id = ".$_GET['cod']." ";
		}
		$query .=" ORDER  BY sistema";
		$resultado = mysql_query($query) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!');
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		if (mysql_num_rows($resultado) == 0)
		{
			echo mensagem(TRANS("MSG_NO_RECORDS"));
		}
		else
		{
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='6'>".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('RECORDS_IN_SYSTEM').".</td></tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_AREA','Área')."</TD><td class='line'>".TRANS('COL_ATEND','Atende Chamados')."</TD><td class='line'>".TRANS('COL_EMAIL','E-mail')."</TD>".
				"<td class='line'>".TRANS('COL_SCREEN_PROFILE')."</TD><td class='line'>".TRANS('COL_STATUS','Status')."</TD><td class='line'>".TRANS('COL_EDIT','Alterar')."</TD><td class='line'>".TRANS('COL_DEL','Excluir')."</TD></tr>";

			$j=2;
			$textScreen = "";
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
				if ($row['sis_status'] == 0) $lstatus =TRANS('INACTIVE','INATIVO'); else $lstatus = TRANS('ACTIVE','ATIVO');
				if ($row['conf_name'] == "") $textScreen = TRANS('SCREEN_FULL'); else $textScreen = $row['conf_name'];
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['sistema']."</td>";
				print "<td class='line'>".transbool($row['sis_atende'])."</td>";
				print "<td class='line'>".$row['sis_email']."</td>";
				print "<td class='line'>".NVL($textScreen)."</td>";
				print "<td class='line'>".$lstatus."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['sis_id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('COL_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."','action=excluir&cod=".$row['sis_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('COL_DEL')."'></a></TD>";

				print "</TR>";
			}
			//print "</TABLE>";
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('CADASTRE_AREA')."</B> <font color='red'>".TRANS('ALERT_AREA_PERMISSION')."</font><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('COL_AREA').":</TD>";
		print "<TD width='80%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' name='area' class='text' id='idArea'>".
				"<input type='checkbox' name='areaatende' value='1' checked>".TRANS('COL_ATEND')."</TD>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('COL_EMAIL').":</TD>";
		print "<TD width='80%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' name='email' class='text' id='idEmail'></td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('SCREEN_NAME').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='screen_name' id='idScreen'>";
				print "<option value=NULL>".TRANS('SEL_SCREEN')."</option>";
					$sql="select * from configusercall order by conf_name";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['conf_cod'].">".$row["conf_name"]."</option>";
						$i++;
					} // while
			print "</select>";

		print "</td>";
		print "</tr>";

		
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_STATUS').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select' name='status' id='idStatus'>";
			print "<option value=-1>".TRANS('SEL_STATUS')."</option>";
			print "<option value=1>".TRANS('ACTIVE')."</option>";
			print "<option value=0>".TRANS('INACTIVE')."</option>";
		print "</select>";
		print "</tr>";

		print "<TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit' class='button'  value='".TRANS('BT_CAD')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('TTL_EDIT_RECORD','Edição do registro').":</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_AREA').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='sistema' id='idArea' value='".$row['sistema']."'>";
		 if ($row['sis_atende']) $check = " checked"; else $check = "";
		print "<input type='checkbox' name='areaatende' value='1'  ".$check.">".TRANS('COL_ATEND')."</TD>";
        	print "</TR>";
        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_EMAIL').":</TD>";
                print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='email' id='idEmail' value='".$row['sis_email']."'></TD>";
        	print "</TR>";
        	
        	print "<tr>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('SCREEN_NAME').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='screen_name' id='idScreen'>";

			$sql = "select * from configusercall where conf_cod = '".$row['sis_screen']."'";
			$commit1 = mysql_query($sql);
			$rowR = mysql_fetch_array($commit1);
				print "<option value=NULL>".TRANS('SEL_SCREEN')."</option>";
					$sql2="select * from configusercall order by conf_name";
					$commit2 = mysql_query($sql2);
					while($rowB = mysql_fetch_array($commit2)){
						print "<option value=".$rowB["conf_cod"]."";
                        			if ($rowB['conf_cod'] == $row['sis_screen'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["conf_name"]."</option>";
					} // while

		print "</select>";
		print "</TD>";
		print "</tr>";
        	
        	//print "<tr><td></td></tr>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_STATUS').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='status'>";

			print "<option value=1";
			if ($row['sis_status']==1) print " selected";
			print ">".TRANS('ACTIVE')."</option>";
			print"<option value=0";
			if ($row['sis_status']==0) print " selected";
			print">".TRANS('INACTIVE')."</option>";
		print "</select>";
		print "</TD>";
        	print "</TR>";


		print "<TR>";
		print "<BR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";
		$sql_1 = "SELECT * from usuarios where AREA='".$_GET['cod']."'";
		$exec_1 = mysql_query($sql_1);
		$total+=mysql_numrows($exec_1);
		if (mysql_numrows($exec_1)!=0) $texto.="usuarios, ";

		$sql_2 = "SELECT * FROM ocorrencias where sistema ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="ocorrencias, ";

		$sql_3 = "SELECT * FROM problemas where prob_area ='".$_GET['cod']."'";
		$exec_3 = mysql_query($sql_3);
		$total+= mysql_numrows($exec_3);
		if (mysql_numrows($exec_3)!=0) $texto.="problemas, ";

		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_CANT_DEL','',0).": ".$texto." ".TRANS('LINKED_TABLE','',0)."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM sistemas WHERE sis_id='".$_GET['cod']."'";
			$resultado2 = mysql_query($query2);

			if ($resultado2 == 0)
			{
					$aviso = TRANS('ERR_DEL');
			}
			else
			{
				$queryDelete = "DELETE FROM areaXarea_abrechamado WHERE area = '".$_GET['cod']."' OR area_abrechamado = '".$_GET['cod']."'";
				$resultadoDelete = mysql_query($queryDelete) or die(TRANS('ERR_DEL').'<br>'.mysql_error());					
				$aviso = TRANS('OK_DEL');
			}
			
			$qryAreas = "SELECT * FROM sistemas where sis_atende = 0";
			$execAreas = mysql_query($qryAreas);
			$confNewOwnAreas = "";
			while ($rowAreas = mysql_fetch_array($execAreas)){
				if ($confNewOwnAreas != "") $confNewOwnAreas.=", ";
				$confNewOwnAreas.=$rowAreas['sis_id'];
			}
			$updNewOwnAreas = "UPDATE configusercall SET conf_ownarea_2 = '".$confNewOwnAreas."'";
			$execUpd = mysql_query($updNewOwnAreas) or die(mysql_error());
			
			print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

		}


	} else

	if ($_POST['submit'] == TRANS('BT_CAD')){

		$erro=false;

		$qryl = "SELECT * FROM sistemas WHERE sistema='".$_POST['area']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_RECORD_EXISTS');
				$erro = true;;
		}

		if (!$erro)
		{
			if (isset($_POST['areaatende'])) {
				$areaatende = 1;
			} else
				$areaatende = 0;

			$query = "INSERT INTO sistemas (sistema,sis_status,sis_email,sis_atende, sis_screen) values ".
				"('".noHtml($_POST['area'])."',".$_POST['status'].",'".$_POST['email']."','".$areaatende."', ".$_POST['screen_name'].")";
			$resultado = mysql_query($query);
			
			$id = mysql_insert_id();
			if ($resultado == 0)
			{
				$aviso = TRANS('ERR_INSERT');
			}
			else
			{
				if ($areaatende == 0) {
					$newArea = ", ".$id;
					$queryUpd = "UPDATE configusercall SET conf_ownarea_2 = CONCAT(conf_ownarea_2,'".$newArea."') ";
					$exeUpd = mysql_query($queryUpd) or die('ERROR_TRYING TO UPDATE RECORD'); 
				}
				$aviso = TRANS('OK_INSERT');
			}
			$qryAreas = "SELECT * FROM sistemas where sis_atende = 0";
			$execAreas = mysql_query($qryAreas);
			$confNewOwnAreas = "";
			while ($rowAreas = mysql_fetch_array($execAreas)){
				if ($confNewOwnAreas != "") $confNewOwnAreas.=", ";
				$confNewOwnAreas.=$rowAreas['sis_id'];
			}
			$updNewOwnAreas = "UPDATE configusercall SET conf_ownarea_2 = '".$confNewOwnAreas."'";
			$execUpd = mysql_query($updNewOwnAreas) or die(mysql_error());
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == "Alterar"){

		if (isset($_POST['areaatende'])) {
			$areaatende = 1;
		} else
			$areaatende = 0;
		$query2 = "UPDATE sistemas SET sistema='".noHtml($_POST['sistema'])."',sis_status=".$_POST['status'].", ".
							"sis_email='".$_POST['email']."', sis_screen=".$_POST['screen_name'].", sis_atende='".$areaatende."' WHERE sis_id='".$_POST['cod']."'";
		$resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
			$aviso =  TRANS('ERR_EDIT');
		}
		else
		{
			$aviso = TRANS('OK_EDIT');
			$_SESSION['s_screen'] = $_POST['screen_name'];
		}

		$qryAreas = "SELECT * FROM sistemas where sis_atende = 0";
		$execAreas = mysql_query($qryAreas);
		$confNewOwnAreas = "";
		while ($rowAreas = mysql_fetch_array($execAreas)){
			if ($confNewOwnAreas != "") $confNewOwnAreas.=", ";
			$confNewOwnAreas.=$rowAreas['sis_id'];
		}
		$updNewOwnAreas = "UPDATE configusercall SET conf_ownarea_2 = '".$confNewOwnAreas."'";
		$execUpd = mysql_query($updNewOwnAreas) or die(mysql_error());		
		
		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	print "</table>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idArea','','Área',1);
		if (ok) var ok = validaForm('idEmail','EMAIL','Email',1);
		if (ok) var ok = validaForm('idStatus','COMBO','Status',1);

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
