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

	print "<FORM name='form1' method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor=".BODY_COLOR.">";

	$query = "SELECT s.*, c.* FROM sistemas as s left join configusercall as c on s.sis_screen = c.conf_cod WHERE s.sis_status = 1 ";
	if (isset($_GET['cod'])) {
		$query.= "AND sis_id = ".$_GET['cod']." ";
	}
	$query .=" ORDER  BY sistema";
	$resultado = mysql_query($query) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!');
	$registros = mysql_num_rows($resultado);
		
	$qtdAreas=0;
	while ($area = mysql_fetch_array($resultado)){
		$areas[$qtdAreas++]=$area;
		
		$queryAreasAbremChamado = "SELECT * FROM areaXarea_abrechamado WHERE area = ".$area['sis_id'];
		$resultadoAreasAbremChamado = mysql_query($queryAreasAbremChamado) or die('ERRO NA EXECUÇÃO DA QUERY DE CONSULTA!');
		while ($areaAbreChamado = mysql_fetch_array($resultadoAreasAbremChamado)){
			$areasAbremChamado[$area['sis_id']][$areaAbreChamado['area_abrechamado']]=true;
		}
	}
	$areas_aux=$areas;

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {
		if (mysql_num_rows($resultado) == 0)
		{
			echo mensagem(TRANS("MSG_NO_RECORDS"));
		}
		else
		{
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td>".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('RECORDS_IN_SYSTEM').".</td>".
				"<td><input type='button' class='button' name='checkall' value='".TRANS('CHECK_ALL')."' onClick=\"check_all(true);\">".
					"&nbsp;<input type='button' class='button' name='checkall' value='".TRANS('CHECK_NONE')."' onClick=\"check_all(false);\"></td>".
				"</tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_AREA','Área')."</TD><td class='line'>".TRANS('AREAS_QUE_PODEM_ABRIR_CHAMADOS')."</TD></tr>";

			$j=2;
			$checked = "";
			foreach ($areas as $area)
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
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\">";
					print "<td class='line'>".$area['sistema']."</td>";
					print "<td class='line'>";
						print "<table>";
							$ctdTD=1;
							$abriu=false;
							$fechar=false;
							foreach ($areas_aux as $area_aux){
								if(!$ctdTD == 1)
									print "<tr>";
									
									print "<td>";
										$checked = isset($areasAbremChamado[$area['sis_id']][$area_aux['sis_id']])?'checked':'';
										if ($area['sis_atende']== 0) $checked = "disabled readonly";
										
										print "<input type='checkbox' name='areaalvo_".$area['sis_id']."_areaabre_".$area_aux['sis_id']."' value='".$area_aux['sis_id']."' ".$checked.">"; //".."
										print $area_aux['sistema'];
									print "</td>";
									
								if($ctdTD == 3){
									print "</tr>";
									$ctdTD=1;
								}else
									$ctdTD++;
							}
						print "</table>";
					print "</td>";
				print "</TR>";
			}
			print "<TD align='center' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		}

	} else
	
	if (isset($_POST['submit']) && $_POST['submit'] == TRANS('BT_ALTER')){
		foreach ($areas as $area){
			foreach ($areas_aux as $area_aux){
				//Inserir a Área que abre chamado na devida área
				if( isset($_REQUEST["areaalvo_".$area['sis_id']."_areaabre_".$area_aux['sis_id']]) && !isset($areasAbremChamado[$area['sis_id']][$area_aux['sis_id']]) ){
					$queryInsert = "INSERT INTO areaXarea_abrechamado (area, area_abrechamado) VALUES (".$area['sis_id'].", ".$area_aux['sis_id'].")";
					$resultado = mysql_query($queryInsert) or die('ADIÇÃO DE ÁREA QUE PODE ABRIR CHAMADO: '.mysql_error());
				}
				//Remover a Área que abre chamado na devida área
				if( !isset($_REQUEST["areaalvo_".$area['sis_id']."_areaabre_".$area_aux['sis_id']]) && isset($areasAbremChamado[$area['sis_id']][$area_aux['sis_id']])){
					$queryDelete = "DELETE FROM areaXarea_abrechamado WHERE area = '".$area['sis_id']."' AND area_abrechamado = '".$area_aux['sis_id']."'";
					$resultado = mysql_query($queryDelete) or die('REMOÇÃO DE ÁREA QUE PODE ABRIR CHAMADO: '.mysql_error());
				}
			}
		}
		$aviso = TRANS('OK_EDIT');
		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
	}

	print "</table>";
	print "</form>";

?>
<script type="text/javascript">
<!--
	function valida(){

		return ok;
	}

//-->
</script>



<?php 
print "</body>";
print "</html>";
