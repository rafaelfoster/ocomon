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

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$hoje = date("d-m-Y H:i:s");
	$hoje2 = date("d/m/Y");

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>".TRANS('TLT_BOARD_NOTICE')."</B><BR><br>";
	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


	//Todas as áreas que o usuário percente
	$uareas = $_SESSION['s_area'];
	if ($_SESSION['s_uareas']) {
		$uareas.=",".$_SESSION['s_uareas'];
	}

	$query = "SELECT a.*, u.*, ar.* from usuarios u, avisos a left join sistemas ar on a.area = ar.sis_id where (a.area in (".$uareas.") or a.area=-1) and a.origem=u.user_id";

	if (isset($_GET['aviso_id'])) {
		$query.=" and a.aviso_id = ".$_GET['aviso_id']."";
	}
	$query.=" ORDER BY u.nome";

	$resultado = mysql_query($query);
	$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		print "<tr><TD bgcolor=".BODY_COLOR.">".
				"<input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_NOTICE')."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\">".
			"</TD></tr>";

		if ($registros == 0) {
			echo "<tr><td align='center'>".mensagem("".TRANS('MSG_NO_NOTICE')."")."</td></tr>";
		} else {
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr>";
			print "<td colspan='2'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('TLT_REGISTERS_NOTICES').":<br>";
			print "</td>";
			print "<TR class='header'><td class='line'>".TRANS('OCO_DATE')."</TD><td class='line'>".TRANS('OCO_NOTICE')."</TD><td class='line'>".TRANS('OCO_RESP')."</td><td class='line'>".TRANS('OCO_AREA')."</TD>";
				print "<td class='line'>".TRANS('COL_PRIORITY')."</TD><td class='line'>".TRANS('COL_EDIT')."</TD><td class='line'>".TRANS('COL_DEL')."</TD></TR>";
			$j=2;
			while ($row = mysql_fetch_array($resultado)) {
				if ($j % 2) {
					$trClass = "lin_par";
				} else {
					$trClass = "lin_impar";
				}
				$j++;
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".datab($row['data'])."</td>";
				print "<td class='line'>".$row['avisos']."</td>";
				print "<td class='line'>".$row['nome']."</td>";

				if (isIn($row['sis_id'],$uareas))
					$area = $row['sistema']; else
					$area = "TODAS";

				print "<td class='line'>".$area."</TD>";
				print "<td class='line'>".$row['status']."</TD>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cellStyle=true&aviso_id=".$row['aviso_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"javascript:confirmaAcao('".TRANS('MSG_DEL_REG')."','avisos.php','action=excluir&aviso_id=".$row['aviso_id']."');\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></TD>";
				print "</TR>";
			}
		}
	} else

	if (isset($_GET['action']) && ($_GET['action'] == "incluir") && empty($_POST['submit'])) {

		print "<BR><B>".TRANS('TLT_INSERT_BOARD_NOTICE')."</B><BR>";

        	print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_NOTICE').":</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			if (!$_SESSION['s_formatBarMural']) {
				print "<TEXTAREA class='textarea' name='aviso2' id='idAviso'></textarea>"; //oFCKeditor.Value = print noHtml($descricao);
			} else
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			?>
			<script type="text/javascript">
				var bar = '<?php print $_SESSION['s_formatBarMural'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'aviso2' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>

			<?php 

			print "</TD>";
        	print "</TR>";
        	print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PRIORITY').":</TD>";
			print "<TD width='30%' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<SELECT class='select' name='status'>";
				print "<option value='Normal'>".TRANS('SEL_PRIORITY_NORMAL')."</option>";
				print "<option value='Alta' selected>".TRANS('SEL_PRIORITY_HIGH')."</option>";
	            print "</SELECT>";
			print "</td>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('OCO_FIELD_FOR_AREA').":</TD>";
			print "<TD width='30%' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<Select class='select' name='area'>";
						print "<OPTION value=-1>".TRANS('OPT_ALL')."</OPTION>";
							$qry="select * from sistemas where sis_status not in (0) and sis_atende not in (0) order by sistema";
							$exec=mysql_query($qry);
						while($rowarea=mysql_fetch_array($exec)) {
							print "<option value=".$rowarea['sis_id']."";
							if ($rowarea['sis_id'] == $_SESSION['s_area'])
								print " selected";
							print ">".$rowarea['sistema']."</option>";
						} // while
			 	print "</Select>";
			print "</td>";
        	print "</TR>";
        	print "<tr><td colspan='2'>&nbsp;</td></tr>";
        	print "<TR>";
            	print "<TD align='center' colspan='2' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_CAD')."' name='submit'>";
           	print "</TD>";
            	print "<TD colspan='2' align='center' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onclick=\"javascript:redirect('avisos.php');\"></TD>";
        	print "</TR>";
	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('TLT_ALTER_NOTICE')."</B><br>";

			print "<TR>";
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_NOTICE').":</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor=".BODY_COLOR.">";
			if (!$_SESSION['s_formatBarMural']) {
				print "<TEXTAREA class='textarea' name='aviso2' id='idAviso'>".$row['avisos']."</textarea>";
			} else
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			
			$texto1 = str_replace("\r","\n",$row['avisos']);
			$texto1 = str_replace("\n","",$texto1);
			?>
			<script type="text/javascript">
				var bar = '<?php print $_SESSION['s_formatBarMural'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'aviso2' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = '<?php print $texto1;?>';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>
			<?php 

			print "</TD>";
			print "</tr>";
			print "<TR>";
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('COL_PRIORITY').":</TD>";
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
			print "<SELECT class='select' name='status' size=1>";
				print "<option value='alta' ";
					if (strtoupper($row['status'])==TRANS('SEL_PRIORITY_HIGH'))
						print " selected";
				print ">Alta</option>";
				print "<option value='normal' ";
					if (strtoupper($row['status'])==TRANS('SEL_PRIORITY_NORMAL'))
						print " selected";
				print ">Normal</option>";
			print "</select>";
			print "</TD>";

		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_FOR_AREA').":</TD>";
		print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";

			$query="select * from sistemas where sis_status not in (0) and sis_atende not in (0) order by sistema";
			$result=mysql_query($query);
		print "<select class='select' name='area_esc' size=1>";
			print "<option value=-1 selected".TRANS('OPT_ALL')."</option>";
			while ($rowarea = mysql_fetch_array($result)) {
				print "<option value=".$rowarea['sis_id']." ";
				if ($rowarea['sis_id']==$row['sis_id'])
					print " selected";
				print ">".$rowarea['sistema']."</option>";
			} // while
		print "</select>";
		print "</td>";
		print "</TR>";
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<TR>";
		print "<TD align='center' colspan='2' width='20%' bgcolor=".BODY_COLOR."><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		print "<input type='hidden' name='aviso_id' value='".$_GET['aviso_id']."'>";
		print "</TD>";
		print "<TD colspan='2' align='center' width='80%' bgcolor=".BODY_COLOR."><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onclick=\"javascript:redirect('avisos.php')\"></TD>";
		print "</TR>";
	} else

	if (isset($_GET['action']) &&$_GET['action'] == "excluir"){
		$row = mysql_fetch_array($resultado);

		$query = "DELETE FROM avisos WHERE aviso_id=".$_GET['aviso_id']."";
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY').$query);

		$texto = "".TRANS('MSG_DEL_NOTICE')."= ".$row['avisos']."";
			geraLog(LOG_PATH.'ocomon.txt',$hoje,$_SESSION['s_usuario'],'avisos.php?action=excluir',$texto);

		print "<script>mensagem('".TRANS('OK_DEL')."'); redirect('avisos.php'); </script>";
	} else

	if ($_POST['submit'] == TRANS('BT_CAD')){

		$data = datam($hoje);
		$query = "INSERT INTO avisos (avisos, data, origem, status, area) values (";
		if ($_SESSION['s_formatBarMural']) {
			$query.= " '".$_POST['aviso2']."',";
			//$query.= " '".str_replace("\r\n","",$_POST['aviso2'])."',";
			
		} else {
			$query.= " '".noHtml($_POST['aviso2'])."',";
		}
		$query.=" '".date("Y-m-d H:i:s")."',".$_SESSION['s_uid'].",'".$_POST['status']."', ".$_POST['area'].")";
		$resultado = mysql_query($query) or die (TRANS('ERR_QUERY') .$query);

		print "<script>mensagem('".TRANS('OK_INSERT')."'); redirect('avisos.php'); </script>";

	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')) {

		$query = "UPDATE avisos SET avisos=";
		if ($_SESSION['s_formatBarMural']) {
			$query.= " '".$_POST['aviso2']."',";
			//$query.= " '".str_replace("\r\n","",$_POST['aviso2'])."',";
		} else {
			$query.= " '".noHtml($_POST['aviso2'])."',";
		}
		$query.=" status='".$_POST['status']."', area=".$_POST['area_esc']." WHERE aviso_id = ".$_POST['aviso_id']."";
		$resultado = mysql_query($query) or die (TRANS('ERR_QUERY').$query);

		print "<script>mensagem('".TRANS('OK_EDIT')."'); redirect('avisos.php'); </script>";

	}

print "</TABLE>";
print "</form>";
print "</body>";
print "</html>";

?>
