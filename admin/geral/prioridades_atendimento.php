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
	//print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";
	
	print "<SCRIPT LANGUAGE='Javascript' SRC='../../includes/javascript/ColorPicker2.js'></SCRIPT>";
	print "<SCRIPT LANGUAGE='Javascript' SRC='../../includes/javascript/PopupWindow.js'></SCRIPT>";
	print "<SCRIPT LANGUAGE='Javascript' SRC='../../includes/javascript/AnchorPosition.js'></SCRIPT>";
	
	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<html>";
	print "<head>";
	?><script language="javascript"> var cp = new ColorPicker(); // DIV style</script><?php 
	print "</head>";	
	print "<body onClick=\"setBGColor('idCor');\">";



	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);


        print "<BR><B>".TRANS('ADM_PRIORIDADES_ATEND').":</B><BR>";

	$query = "SELECT * from prior_atend order by pr_nivel";
        $resultado = mysql_query($query);

	if ((!isset($_GET['action'])) && !isset($_POST['submit'])) {

        	$qryUpdateDefault = "SELECT oco_prior FROM ocorrencias WHERE oco_prior is null";
        	$execUpdate = mysql_query($qryUpdateDefault);
        	if (mysql_numrows($execUpdate) > 0) {
			print "<TR><TD><a href='update_old_tickets_prior.php'>".TRANS('LINK_UPDATE_TICKETS_PRIOR')."</a></TD></TR><BR/>";
        	}
        	
        	
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
			print "<TR class='header'><td class='line'>".TRANS('COL_LEVEL')."</TD><td class='line'>".TRANS('COL_DESC')."</TD><td class='line'>".TRANS('COL_DEFAULT','PADRAO')."</TD><td class='line'>".TRANS('COL_COLOR','COR')."</TD><td class='line'><b>".TRANS('COL_EDIT')."</b></TD><td class='line'><b>".TRANS('COL_DEL')."</b></TD>";
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

				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['pr_nivel']."</TD>";
				print "<td class='line'>".$row['pr_desc']."</TD>";
				print "<td class='line'>".transbool($row['pr_default'])."</TD>";
				//print "<td class='line' style='{background-color:".$row['pr_color'].";}';>".$row['pr_color']."</TD>";
				print "<td class='line'><input type='text' class='quadro' style='{background-color:".$row['pr_color'].";}'; disabled></TD>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['pr_cod']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></TD>";
				print "<td class='line'><a onClick=\"confirma('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."?action=excluir&cod=".$row['pr_cod']."&cod_nivel=".$row['pr_nivel']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";
				print "</TR>";
			}
			print "</TABLE>";
		}

	} else
	if ((isset($_GET['action']) && ($_GET['action'] == "incluir")) && !isset($_POST['submit']) ) {

		print "<B>".TRANS('CADASTRE_PRIORITY_ATEND').":<br>";
		print "<form id='form1' method='post' name='incluir' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='5' cellspacing='1' width='50%'>";
		print "<tr>";
		print "<td bgcolor='".TD_COLOR."'>".TRANS('COL_DESC')."</td><td class='line'><input type='text' class='text' name='descricao' id='idDesc'><input type='checkbox' name='permanente'>".TRANS('COL_DEFAULT')."</td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_LEVEL').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='nivel' id='idNivel'>";
				print "<option value=-1>".TRANS('COL_LEVEL')."</option>";
					$sql="select * from prior_nivel WHERE prn_used=0 order by prn_level";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['prn_cod'].">".$row["prn_level"]."</option>";
						$i++;
					} // while
			print "</select>";

		print "</td>";
		print "</tr>";		
		
		
		print "<tr><td bgcolor='".TD_COLOR."'>".TRANS('COL_COLOR').":</td><td>".
				"<input type='text' class='mini2' name='cor' id='idCor'> ";
					print "<a href='#' onClick=\"cp.select(document.forms[0].cor,'pickCor');return false;\" name='pickCor' id='pickCor' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";		
		
		
		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='".TRANS('BT_CAD')."'></td>";

		print "<td class='line'><input type='reset'  class='button' name='reset' value='".TRANS('BT_CANCEL')."' onClick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if ( (isset($_GET['action']) && $_GET['action']=="alter") && !isset($_POST['submit'])) {
		$qry = "SELECT * from prior_atend where pr_cod = ".$_GET['cod']."";
		$exec = mysql_query($qry);
		$rowAlter = mysql_fetch_array($exec);

		print "<B>".TRANS('TTL_EDIT_RECORD').":<br>";
		print "<form method='post' name='alter' action='".$_SERVER['PHP_SELF']."' onSubmit='return valida()'>";
		print "<TABLE border='0' cellpadding='1' cellspacing='0' width='50%'>";
		print "<tr>";
		print "<td bgcolor=".TD_COLOR."><b>".TRANS('COL_DESC')."</b></td><td class='line'><input type='text' class='text' name='descricao' id='idDesc' value='".$rowAlter['pr_desc']."'><input type='checkbox' name='permanente' ".($rowAlter['pr_default']?'checked':'').">".TRANS('COL_DEFAULT')."</td>";
		print "</tr>";
		
/*		print "<tr>";
		print "<td class='line'>".TRANS('COL_LEVEL')."</td><td class='line'><input type='text' class='text' name='nivel' id='idNivel' value='".$rowAlter['pr_nivel']."'></td>";
		print "</tr>";*/
		
        	print "<tr>";
        	print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_LEVEL').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='nivel' id='idNivel'>";

			
		$nivel_anterior = $rowAlter['pr_nivel'];
		
			$sql = "select * from prior_nivel where prn_level = '".$rowAlter['pr_nivel']."'";
			$commit1 = mysql_query($sql);
			$rowR = mysql_fetch_array($commit1);
				print "<option value=-1>".TRANS('SEL_LEVEL')."</option>";
					$sql2="select * from prior_nivel WHERE (prn_used = 0 OR prn_level = '".$rowAlter['pr_nivel']."') order by prn_level";
					$commit2 = mysql_query($sql2);
					while($rowB = mysql_fetch_array($commit2)){
						print "<option value=".$rowB["prn_cod"]."";
                        			if ($rowB['prn_cod'] == $rowR['prn_cod'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["prn_level"]."</option>";
					} // while

			print "</select>";
		print "</TD>";
		print "</tr>";		
		
		
		print "<tr><td>".TRANS('COL_COLOR').":</td><td>".
				"<input type='text' class='mini2' name='cor' id='idCor' value='".$rowAlter['pr_color']."' style='{background-color:".$rowAlter['pr_color'].";}';>";
					print "<a href='#' onClick=\"cp.select(document.forms[0].cor,'pickCor');return false;\" name='pickCor' id='pickCor' title='".TRANS('EDIT_CURRENT_COLOR')."'>".
					"<img src='".ICONS_PATH."edit.png' width='16' height='16' border='0'></a>".
					"</td>".
			"</tr>";		
		
		
		print "<tr nowrap>";
		print " <input type='hidden' name='cod' value='".$_GET['cod']."'>";
		print " <input type='hidden' name='nivel_anterior' value='".$nivel_anterior."'>";
		print "</tr>";

		print "<tr><td class='line'><input type='submit'  class='button' name='submit' value='".TRANS('BT_ALTER')."'></td>";
		print "<td class='line'><input type='reset'  class='button' name='reset' value='".TRANS('bt_cancelar')."' onclick=\"javascript:history.back()\"></td></tr>";

		print "</table>";
		print "</form>";
	} else

	if (isset($_GET['action']) && $_GET['action']=="excluir"){
			
		$total = 0; $texto = "";

		$sql_2 = "SELECT * FROM ocorrencias WHERE oco_prior ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="ocorrencias, ";

		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_CANT_DEL').": ".$texto." ".TRANS('LINKED_TABLE')."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$qry = "DELETE FROM prior_atend where pr_cod = ".$_GET['cod']."";
			$exec = mysql_query($qry) or die (TRANS('ERR_DEL')."!");
			if ($exec == 0)
			{
				$aviso = TRANS('ERR_DEL');
			}
			else
			{
				$qry2 = "UPDATE prior_nivel SET prn_used = 0 WHERE prn_level = ".$_GET['cod_nivel']."";
				$exec2 = mysql_query($qry2) or die ($qry2);
				$aviso = TRANS('OK_DEL');
			}
			print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}

	} else

	if (isset($_POST['submit']) && $_POST['submit'] == TRANS('bt_cadastrar')) {
		if ((!empty($_POST['descricao'])) && (!empty($_POST['nivel']))){
			$qry = "select * from prior_atend where pr_desc = '".$_POST['descricao']."' ";
			$exec= mysql_query($qry);
			$achou = mysql_numrows($exec);
			if ($achou){
				print "<script>mensagem('".TRANS('MSG_RECORD_EXISTS','',0)."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
			} else {

				//$data = str_replace("-","/",$_POST['data']);
				//$data = converte_dma_para_amd($data);

				if (isset($_POST['permanente'])){
					$permanente = 1;
					$qryDefault = "UPDATE prior_atend SET pr_default = 0 ";//todas prioridades sao afetadas;
					$execDefault = mysql_query($qryDefault);
				
				} else
					$permanente = 0;

				$qry = "INSERT INTO prior_atend (pr_nivel, pr_default, pr_desc, pr_color) ".
						"values (".$_POST['nivel'].", '".$permanente."','".noHtml($_POST['descricao'])."', '".$_POST['cor']."')";
				$exec = mysql_query($qry) or die ('Erro na inclusão do registro!'.$qry);
				
				$qry2 = "UPDATE prior_nivel SET prn_used = 1 where prn_level = ".$_POST['nivel']."";
				$exec2 = mysql_query($qry2) or die ('ERRO NA ATUALIZACAO DO NÍVEL UTILIZADO'.$qry2);
				
				
				print "<script>mensagem('".TRANS('OK_INSERT')."!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
				}
		} else {
				print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}

	} else

	if (isset($_POST['submit']) && $_POST['submit'] == TRANS('BT_ALTER')) {
		if ((!empty($_POST['descricao'])) ){

			//$data = str_replace("-","/",$_POST['data']);
			//$data = converte_dma_para_amd($data);

			if (isset($_POST['permanente'])){
				$permanente = 1;
				$qryDefault = "UPDATE prior_atend SET pr_default = 0 ";//todas prioridades sao afetadas;
				$execDefault = mysql_query($qryDefault);				
			} else
				$permanente = 0;


			//$qry = "UPDATE feriados set desc_feriado='".noHtml($descricao)."', data_feriado='".$data."' where cod_feriado=".$cod."";
			$qry = "UPDATE prior_atend set pr_desc='".noHtml($_POST['descricao'])."', ".
					"pr_nivel='".$_POST['nivel']."', pr_default=".$permanente.", ".
					"pr_color = '".$_POST['cor']."' ".
				"WHERE pr_cod=".$_POST['cod']."";
			$exec= mysql_query($qry) or die(TRANS('ERR_QUERY'.$qry));

			
			if ($_POST['nivel_anterior'] != $_POST['nivel']){ //O nível foi alterado
			
				$qry2 = "UPDATE prior_nivel SET prn_used = 1 where prn_level = ".$_POST['nivel']."";
				$exec2 = mysql_query($qry2) or die ('ERRO NA ATUALIZACAO DO NÍVEL UTILIZADO'.$qry2);			
			
				$qry3 = "UPDATE prior_nivel SET prn_used = 0 where prn_level = ".$_POST['nivel_anterior']."";
				$exec3 = mysql_query($qry3) or die ('ERRO NA ATUALIZACAO DO NÍVEL UTILIZADO'.$qry3);			
			}
			
			
			
			
			print "<script>mensagem('".TRANS('OK_EDIT')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

		} else {
			print "<script>mensagem('".TRANS('MSG_EMPTY_DATA')."'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
	}





?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idDesc','','Descrição',1);
		if (ok) var ok = validaForm('idNivel','COMBO','Nível',1);
		if (ok) var ok = validaForm('idCor','COR','Cor',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);

		return ok;
	}
-->
</script>
<SCRIPT LANGUAGE="JavaScript">cp.writeDiv()</SCRIPT>
<?php 
print "</body>";
print "</html>";

?>