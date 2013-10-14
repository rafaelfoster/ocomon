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

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$hoje = date("d-m-Y H:i:s");
	$hoje2 = date("d/m/Y");

 	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<BR><B>Mural de Avisos</B><BR><br>";
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
				//"<a href='avisos.php?action=incluir&cellStyle=true'>Incluir Aviso</a>".
				"<input type='button' class='button' id='idBtIncluir' value='Novo Aviso' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\">".
			"</TD></tr>";

		if ($registros == 0) {
			echo "<tr><td align='center'>".mensagem("Não há nenhum aviso cadastrado no sistema.")."</td></tr>";
		} else {
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr>";
			print "<td colspan='2'>";
			print "Existe(m) <b>".$registros."</b> aviso(s) cadastrado(s) no Mural:<br>";
			print "</td>";
			print "<TR class='header'><td class='line'>Data</TD><td class='line'>Aviso</TD><td class='line'>Responsável</td><td class='line'>Área</TD>";
				print "<td class='line'>Prioridade</TD><td class='line'>Alterar</TD><td class='line'>Excluir</TD></TR>";
			$j=2;
			while ($row = mysql_fetch_array($resultado)) {
				if ($j % 2) {
					$trClass = "lin_par";
				} else {
					$trClass = "lin_impar";
				}
				$j++;
				print "<tr class=".$trClass." id='linha".$j."' onMouseOver=\"destaca('linha".$j."');\" onMouseOut=\"libera('linha".$j."');\"  onMouseDown=\"marca('linha".$j."');\">";
				print "<td class='line'>".datab($row['data'])."</td>";
				print "<td class='line'>".$row['avisos']."</td>";
				print "<td class='line'>".$row['nome']."</td>";

				if (isIn($row['sis_id'],$uareas))
					$area = $row['sistema']; else
					$area = "TODAS";

				print "<td class='line'>".$area."</TD>";
				print "<td class='line'>".$row['status']."</TD>";
				print "<td class='line'><a onClick=\"redirect('avisos.php?action=alter&cellStyle=true&aviso_id=".$row['aviso_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='Alterar o registro'></a></td>";
				print "<td class='line'><a onClick=\"javascript:confirmaAcao('Tem certeza que deseja excluir esse aviso?','avisos.php','action=excluir&aviso_id=".$row['aviso_id']."');\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='Excluir o registro'></TD>";
				print "</TR>";
			}
		}
	} else

	if (isset($_GET['action']) && ($_GET['action'] == "incluir") && empty($_POST['submit'])) {

		print "<BR><B>Inclusão de avisos no Mural</B><BR>";

        	print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Aviso:</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			if (!$_SESSION['s_formatBarMural']) {
				print "<TEXTAREA class='textarea' name='aviso2' id='idAviso'></textarea>"; //oFCKeditor.Value = print noHtml($descricao);
			} else
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			?>
			<script type="text/javascript">
				var bar = '<?print $_SESSION['s_formatBarMural'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'aviso2' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>

			<?

			print "</TD>";
        	print "</TR>";
        	print "<TR>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Prioridade:</TD>";
			print "<TD width='30%' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<SELECT class='select' name='status'>";
				print "<option value='Normal'>Normal</option>";
				print "<option value='Alta' selected>Alta</option>";
	            print "</SELECT>";
			print "</td>";
			print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>Para a área:</TD>";
			print "<TD width='30%' width='80%' align='left' bgcolor='".BODY_COLOR."'>";
				print "<Select class='select' name='area'>";
						print "<OPTION value=-1>-->Todas<--</OPTION>";
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
            	print "<TD align='center' colspan='2' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='Cadastrar' name='submit'>";
           	print "</TD>";
            	print "<TD colspan='2' align='center' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='Cancelar' name='cancelar' onclick=\"javascript:redirect('avisos.php');\"></TD>";
        	print "</TR>";
	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>Alterar dados do aviso</B><br>";

			print "<TR>";
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Aviso:</TD>";
			print "<TD colspan='3' width='80%' align='left' bgcolor=".BODY_COLOR.">";
			if (!$_SESSION['s_formatBarMural']) {
				print "<TEXTAREA class='textarea' name='aviso2' id='idAviso'>".$row['avisos']."</textarea>";
			} else
				print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			?>
			<script type="text/javascript">
				var bar = '<?print $_SESSION['s_formatBarMural'];?>'
				if (bar ==1) {
					var oFCKeditor = new FCKeditor( 'aviso2' ) ;
					oFCKeditor.BasePath = '../../includes/fckeditor/';
					oFCKeditor.Value = '<?print $row['avisos'];?>';
					oFCKeditor.ToolbarSet = 'ocomon';
					oFCKeditor.Width = '570px';
					oFCKeditor.Height = '100px';
					oFCKeditor.Create() ;
				}
			</script>
			<?

			print "</TD>";
			print "</tr>";
			print "<TR>";
			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Prioridade:</TD>";
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
			print "<SELECT class='select' name='status' size=1>";
				print "<option value='alta' ";
					if (strtoupper($row['status'])=='ALTA')
						print " selected";
				print ">Alta</option>";
				print "<option value='normal' ";
					if (strtoupper($row['status'])=='NORMAL')
						print " selected";
				print ">Normal</option>";
			print "</select>";
			print "</TD>";

		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">Área:</TD>";
		print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";

			$query="select * from sistemas where sis_status not in (0) and sis_atende not in (0) order by sistema";
			$result=mysql_query($query);
		print "<select class='select' name='area_esc' size=1>";
			print "<option value=-1 selected>-->Todas<--</option>";
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
		print "<TD align='center' colspan='2' width='20%' bgcolor=".BODY_COLOR."><input type='submit'  class='button' value='Alterar' name='submit'>";
		print "<input type='hidden' name='aviso_id' value='".$_GET['aviso_id']."'>";
		print "</TD>";
		print "<TD colspan='2' align='center' width='80%' bgcolor=".BODY_COLOR."><INPUT type='reset'  class='button' value='Cancelar' name='cancelar' onclick=\"javascript:redirect('avisos.php')\"></TD>";
		print "</TR>";
	} else

	if (isset($_GET['action']) &&$_GET['action'] == "excluir"){
		$row = mysql_fetch_array($resultado);

		$query = "DELETE FROM avisos WHERE aviso_id=".$_GET['aviso_id']."";
		$resultado = mysql_query($query) or die('Erro ao excluir o aviso do mural'.$query);

		$texto = "Excluído: Aviso= ".$row['avisos']."";
			geraLog(LOG_PATH.'ocomon.txt',$hoje,$_SESSION['s_usuario'],'avisos.php?action=excluir',$texto);

		print "<script>mensagem('Aviso excluído com sucesso!'); redirect('avisos.php'); </script>";
	} else

	if ($_POST['submit'] == "Cadastrar"){

		$data = datam($hoje);
		$query = "INSERT INTO avisos (avisos, data, origem, status, area) values (";
		if ($_SESSION['s_formatBarMural']) {
			$query.= " '".$_POST['aviso2']."',";
		} else {
			$query.= " '".noHtml($_POST['aviso2'])."',";
		}
		$query.=" '".date("Y-m-d H:i:s")."',".$_SESSION['s_uid'].",'".$_POST['status']."', ".$_POST['area'].")";
		$resultado = mysql_query($query) or die ('ERRO AO TENTAR INCLUIR NOVO AVISO! '.$query);

		print "<script>mensagem('Aviso incluído com sucesso no mural!'); redirect('avisos.php'); </script>";

	} else

	if ($_POST['submit'] == "Alterar") {

		$query = "UPDATE avisos SET avisos=";
		if ($_SESSION['s_formatBarMural']) {
			$query.= " '".$_POST['aviso2']."',";
		} else {
			$query.= " '".noHtml($_POST['aviso2'])."',";
		}
		$query.=" status='".$_POST['status']."', area=".$_POST['area_esc']." WHERE aviso_id = ".$_POST['aviso_id']."";
		$resultado = mysql_query($query) or die ('ERRO AO TENTAR ALTERAR OS DADOS DO REGISTRO! '.$query);

		print "<script>mensagem('Registro alterado com sucesso!'); redirect('avisos.php'); </script>";

	}

print "</TABLE>";
print "</form>";
print "</body>";
print "</html>";

?>
