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
	include ("../../includes/classes/paging.class.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];
	
	$OPERADOR_AREA = false;
	if(isset($_SESSION['s_area_admin']) && $_SESSION['s_area_admin'] == '1' && $_SESSION['s_nivel'] != '1')
		$OPERADOR_AREA = true;	

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	if($OPERADOR_AREA)
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);


	print "<BR><B>".TRANS('ADM_PROBS')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";


	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";

	if (isset($_POST['search'])){
		$search = $_POST['search'];
	} else
		$search = "";

		$qry_config = "SELECT * FROM config ";
        	$exec_config = mysql_query($qry_config) or die (TRANS('ERR_TABLE_CONFIG'));
		$row_config = mysql_fetch_array($exec_config);

		$WHERE = false;
		$query = "SELECT * FROM problemas as p ".
					"LEFT JOIN sistemas as s on p.prob_area = s.sis_id ".
					"LEFT JOIN sla_solucao as sl on sl.slas_cod = p.prob_sla ".
					"LEFT JOIN prob_tipo_1 as pt1 on pt1.probt1_cod = p.prob_tipo_1 ".
					"LEFT JOIN prob_tipo_2 as pt2 on pt2.probt2_cod = p.prob_tipo_2 ".
					"LEFT JOIN prob_tipo_3 as pt3 on pt3.probt3_cod = p.prob_tipo_3 ";

		if($OPERADOR_AREA){
			!$WHERE?$query.=" WHERE p.prob_area = ".$_SESSION['s_area']." ":$query.=" AND p.prob_area = ".$_SESSION['s_area']." ";
			$WHERE = true;
		}
		
		
		if (isset($_GET['cod'])) {
			!$WHERE?$query.= " WHERE p.prob_id = ".$_GET['cod']." ":$query.=" AND p.prob_id = ".$_GET['cod']." " ;
			$WHERE = true;
		} else
		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		//if (isset($_POST['search'])) {
		//	$query.= " WHERE lower(p.problema) like lower(('%".noHtml($_POST['search'])."%')) ";
		//}
		{
			if(isset($_POST['id_sistema']))
				$_SESSION['id_sistema_filtro'] = $_POST['id_sistema']; 
			
			if($OPERADOR_AREA)
				$_SESSION['id_sistema_filtro']=$_SESSION['s_area'];
			
			if (isset($_POST['search']) || isset($_SESSION['id_sistema_filtro'])) {
				!$WHERE?$query.= " WHERE p.problema IS NOT NULL":$query.= " AND p.problema IS NOT NULL";
				
				if (isset($_SESSION['id_sistema_filtro']) && $_SESSION['id_sistema_filtro'] != '-1')
					$query.= " AND p.prob_area = ".$_SESSION['id_sistema_filtro'];
				if ((isset($_POST['search'])) && !empty($_POST['search']))
					$query.= " AND lower(p.problema) like lower(('%".noHtml($_POST['search'])."%')) ";
			}
		}
		//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------

		$query .=" ORDER  BY s.sistema, p.problema";
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY')."<br>".$query);
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
			$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		$PAGE->execSQL();
		//print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir novo tipo de Problema</a></TD></TR>";
		print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";

		print "<tr>".//<td>".TRANS('FIELD_SEARCH')."</td>".
		"<td colspan='4'>".
			"<input type='text' class='text' name='search' id='idSearch' value='".$search."'>&nbsp;";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			if($OPERADOR_AREA)
				$qryarea = "SELECT sis_id, sistema FROM sistemas WHERE sis_id = ".$_SESSION['s_area']." ORDER BY sistema"; else
				$qryarea = "SELECT sis_id, sistema FROM sistemas ORDER BY sistema";
			$execarea = mysql_query($qryarea);
			print "<SELECT class='select' name='id_sistema' size='1'>";
				print "<option value='-1'>".TRANS('OCO_SEL_AREA')."</option>";
				while ($rowArea=mysql_fetch_array($execarea)){
					$isSelecionado = "";
					if ($rowArea['sis_id'] == $_SESSION['id_sistema_filtro'])
						$isSelecionado = " selected";
					print "<option value='".$rowArea['sis_id']."' ".$isSelecionado.">".$rowArea['sistema']."</option>";
				}
			print "</SELECT>&nbsp;".
			//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
			"<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
		"</td></tr>";

		if ((isset($_POST['search'])) && !empty($_POST['search'])) {
			print "<script>foco('idSearch');</script>";
		}

		if (mysql_num_rows($resultado) == 0)
		{
			print "<tr><td align='center'>";
			echo mensagem(TRANS('NO_RECORDS'));
			print "</tr></td>";
		}
		else
		{
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='8'>";
			//print "<TD colspan='5' width='400' align='left'><B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('TXT_ITEM_SUPPLY').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			//print "<TR class='header'><td class='line'>".TRANS('COL_PROB','Problema')."</TD><td class='line'>".TRANS('COL_AREA','')."</TD><td class='line'>".TRANS('COL_SLA','SLA')."</TD>".
			print "<TR class='header'><td class='line'>".TRANS('COL_PROB','Problema')."</TD><td class='line'>".TRANS('COL_DESC','Descrição')."</TD><td class='line'>".TRANS('COL_ALIMENTA_BANCO_SOLUCAO')."</TD><td class='line'>".TRANS('COL_AREA','')."</TD><td class='line'>".TRANS('COL_SLA','SLA')."</TD>".
			//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
				"<td class='line'>".$row_config['conf_prob_tipo_1']."</TD><td class='line'>".$row_config['conf_prob_tipo_2']."</TD>".
				"<td class='line'>".$row_config['conf_prob_tipo_3']."</TD><td class='line'>".TRANS('COL_EDIT','')."</TD><td class='line'>".TRANS('COL_DEL','')."</TD></tr>";

			$j=2;
			while ($row = mysql_fetch_array($PAGE->RESULT_SQL))
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
				print "<td class='line'>".$row['problema']."</td>";
				print "<td class='line'>".NVL($row['prob_descricao'])."</td>";
				
				//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
				print "<td class='line'>".transbool($row['prob_alimenta_banco_solucao'])."</td>";
				//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
				print "<td class='line'>".NVL($row['sistema'])."</td>";
				print "<td class='line'>".($row['slas_desc']==''?'&nbsp;':$row['slas_desc'])."</td>";
				print "<td class='line'>".($row['probt1_desc']==''?'&nbsp;':$row['probt1_desc'])."</td>";
				print "<td class='line'>".($row['probt2_desc']==''?'&nbsp;':$row['probt2_desc'])."</td>";
				print "<td class='line'>".($row['probt3_desc']==''?'&nbsp;':$row['probt3_desc'])."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['prob_id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['prob_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
			print "<tr><td colspan='8'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('CADASTRE_PROB')."</B><BR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_PROB').":</TD>";
		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		//print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='problema' class='text' id='idProblema'></td>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' name='problema' class='text' id='idProblema'>";
		 print "<input type='checkbox' name='alimentabancosolucao' value='1' checked>".TRANS('COL_ALIMENTA_BANCO_SOLUCAO')." ";
		print "</td>";
		//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_AREA').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='area' id='idArea'>";
				print "<option value=-1>".TRANS('SEL_AREA')."</option>";
					if($OPERADOR_AREA)
						$sql="select * from sistemas where sis_status not in (0) and sis_atende=1 and sis_id = ".$_SESSION['s_area']." order by sistema"; else
						$sql="select * from sistemas where sis_status not in (0) and sis_atende=1 order by sistema";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['sis_id'].">".$row["sistema"]."</option>";
						$i++;
					} // while
			print "</select>";

		print "</td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SLA').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select class='select' name='sla' id='idSla'>";
			print "<option value=-1>".TRANS('SEL_SLA')."</option>";

				$sql="select * from sla_solucao order by slas_tempo";
				$commit = mysql_query($sql);
				while($row = mysql_fetch_array($commit)){
					print "<option value=".$row['slas_cod'].">".$row["slas_desc"]."</option>";
				} // while
		print "</select>";
		print "</td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_1'].":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='tipo_1' id='idTipo_1'>";
				print "<option value=-1>".TRANS('SEL_TYPE')."</option>";
					$sql="select * from prob_tipo_1 ";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['probt1_cod'].">".$row['probt1_desc']."</option>";
						$i++;
					} // while
			print "</select>";

		print "<input type='button' value='".TRANS('MANAGE','',0)."' name='tipo1' class='minibutton' onClick=\"javascript:popup_alerta('cat_prob1.php?popup=true')\">";
		print "</td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_2'].":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='tipo_2' id='idTipo_2'>";
				print "<option value=-1>".TRANS('SEL_TYPE')."</option>";
					$sql="select * from prob_tipo_2 ";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['probt2_cod'].">".$row['probt2_desc']."</option>";
						$i++;
					} // while
			print "</select>";

		print "<input type='button' value='".TRANS('MANAGE')."' name='tipo2' class='minibutton' onClick=\"javascript:popup_alerta('cat_prob2.php?popup=true')\">";
		print "</td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_3'].":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";

			print "<select class='select' name='tipo_3' id='idTipo_3'>";
				print "<option value=-1>".TRANS('SEL_TYPE')."</option>";
					$sql="select * from prob_tipo_3 ";
					$commit = mysql_query($sql);
					$i=0;
					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['probt3_cod'].">".$row['probt3_desc']."</option>";
						$i++;
					} // while
			print "</select>";

		print "<input type='button' value='".TRANS('MANAGE')."' name='tipo3' class='minibutton' onClick=\"javascript:popup_alerta('cat_prob3.php?popup=true')\">";
		print "</td>";
		print "</tr>";

		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_DESC').":</TD>";
		print "<TD colspan='2' align='left' bgcolor=".BODY_COLOR.">";		
		
		if (!$_SESSION['s_formatBarOco']) {
			print "<TEXTAREA class='textarea' name='descricao' id='idDescricao'  ></textarea>"; //onChange=\"Habilitar();\"
		} else 
			print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
		?>
		
		<script type="text/javascript">
			var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
			if (bar ==1) {
				var oFCKeditor = new FCKeditor( 'descricao' ) ;
				oFCKeditor.BasePath = '../../includes/fckeditor/';
				
				oFCKeditor.ToolbarSet = 'ocomon';
				//oFCKeditor.ToolbarSet = 'Basic';
				oFCKeditor.Width = '570px';
				oFCKeditor.Height = '100px';
				oFCKeditor.Create() ;
			}
		</script>
		<?php 
		
		print "</td></tr>";
		//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
				
		print "<tr><td colspan='2'>&nbsp;</td></tr>";
		print "<TR>";

		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('TTL_EDIT_RECORD').":</B><BR>";

		print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_PROB').":</TD>";
                //------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
                //print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='text' name='problema' id='idProblema' value='".$row['problema']."'></td>";

		if ($row['prob_alimenta_banco_solucao']) $check3 = " checked"; else $check3 = "";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>
			<INPUT type='text' class='text' name='problema' id='idProblema' value='".$row['problema']."'>";
				print "<input type='checkbox' name='alimentabancosolucao'  ".$check3.">".TRANS('COL_ALIMENTA_BANCO_SOLUCAO')."";
                	  print "</TD>";
                //------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_AREA').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='area' id='idArea'>";

				$sql = "select * from sistemas where sis_id=".$row["prob_area"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >".TRANS('SEL_AREA')."</option>";
					if($OPERADOR_AREA)
						$sql="select * from sistemas WHERE sis_id = ".$_SESSION['s_area']." order by sistema"; else
						$sql="select * from sistemas order by sistema";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["sis_id"]."";
                        			if ($rowB['sis_id'] == $row['prob_area'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["sistema"]."</option>";
					} // while

		print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_SLA').":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='sla' id='idSla'>";

			$sql = "select * from sla_solucao where slas_cod=".$row["slas_cod"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >".TRANS('SEL_SLA')."</option>";
					$sql="select * from sla_solucao order by slas_tempo";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["slas_cod"]."";
                        			if ($rowB['slas_cod'] == $row['slas_cod'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["slas_desc"]."</option>";
					} // while

		print "</select>";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".$row_config['conf_prob_tipo_1'].":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='tipo_1' id='idTipo_1'>";

			$sql = "select * from prob_tipo_1 where probt1_cod=".$row["prob_tipo_1"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >".TRANS('SEL_TYPE')."</option>";
					$sql="select * from prob_tipo_1 order by probt1_desc";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["probt1_cod"]."";
                        			if ($rowB['probt1_cod'] == $row['prob_tipo_1'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["probt1_desc"]."</option>";
					} // while

		print "</select>";
		print "<input type='button' value='".TRANS('MANAGE')."' name='tipo1' class='minibutton' onClick=\"javascript:popup_alerta('cat_prob1.php?popup=true')\">";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".$row_config['conf_prob_tipo_2'].":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='tipo_2' id='idTipo_2'>";

			$sql = "select * from prob_tipo_2 where probt2_cod=".$row["prob_tipo_2"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >".TRANS('SEL_TYPE')."</option>";
					$sql="select * from prob_tipo_2 order by probt2_desc";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["probt2_cod"]."";
                        			if ($rowB['probt2_cod'] == $row['prob_tipo_2'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["probt2_desc"]."</option>";
					} // while

		print "</select>";
		print "<input type='button' value='".TRANS('MANAGE')."' name='tipo2' class='minibutton' onClick=\"javascript:popup_alerta('cat_prob2.php?popup=true')\">";
		print "</TD>";
        	print "</TR>";

        	print "<TR>";
                print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".$row_config['conf_prob_tipo_3'].":</TD>".
			"<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='tipo_3' id='idTipo_3'>";

			$sql = "select * from prob_tipo_3 where probt3_cod=".$row["prob_tipo_3"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value=-1 >".TRANS('SEL_TYPE')."</option>";
					$sql="select * from prob_tipo_3 order by probt3_desc";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["probt3_cod"]."";
                        			if ($rowB['probt3_cod'] == $row['prob_tipo_3'] ) {
                            				print " selected";
                        			}
                        			print ">".$rowB["probt3_desc"]."</option>";
					} // while

		print "</select>";
		print "<input type='button' value='".TRANS('MANAGE')."' name='tipo3' class='minibutton' onClick=\"javascript:popup_alerta('cat_prob3.php?popup=true')\">";
		print "</TD>";
        	print "</TR>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_DESC').":</TD>";
		print "<TD colspan='2' align='left' bgcolor=".BODY_COLOR.">";		
		
		if (!$_SESSION['s_formatBarOco']) {
			print "<TEXTAREA class='textarea' name='descricao' id='idDescricao'>".$row['prob_descricao']."</textarea>"; //onChange=\"Habilitar();\"
		} else 
			print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
		
		$texto1 = str_replace("\r","\n",$row['prob_descricao']);
		$texto1 = str_replace("\n","",$texto1);			
		?>
		
		<script type="text/javascript">
			var bar = '<?php print $_SESSION['s_formatBarOco'];?>'
			if (bar ==1) {
				var oFCKeditor = new FCKeditor( 'descricao' ) ;
				oFCKeditor.BasePath = '../../includes/fckeditor/';
				oFCKeditor.Value = '<?php print $texto1;?>';
				oFCKeditor.ToolbarSet = 'ocomon';
				//oFCKeditor.ToolbarSet = 'Basic';
				oFCKeditor.Width = '570px';
				oFCKeditor.Height = '100px';
				oFCKeditor.Create() ;
			}
		</script>
		<?php 
		
		print "</td></tr>";
		//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------

		print "<tr><td colspan='2'>&nbsp;</td></tr>";

		print "<TR>";
		print "<BR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
		print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";

		$sql_2 = "SELECT * FROM ocorrencias where problema ='".$_GET['cod']."'";
		$exec_2 = mysql_query($sql_2);
		$total+= mysql_numrows($exec_2);
		if (mysql_numrows($exec_2)!=0) $texto.="ocorrencias, ";

		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_CANT_DEL','',0).": ".$texto." ".TRANS('LINKED_TABLE')."!');
				redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
		else
		{
			$query2 = "DELETE FROM problemas WHERE prob_id='".$_GET['cod']."'";
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


	} else

	if ($_POST['submit'] == TRANS('bt_cadastrar')){

		$erro=false;

// 		$qryl = "SELECT * FROM problemas WHERE problema='".$_POST['problema']."' and prob_area = '".$_POST['area']."'";
// 		$resultado = mysql_query($qryl);
// 		$linhas = mysql_num_rows($resultado);
//
// 		if ($linhas > 0)
// 		{
// 				$aviso =TRANS('MSG_RECORD_EXISTS');
// 				$erro = true;
// 		}

		if (!$erro)
		{
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			/*$query = "INSERT INTO problemas (problema, prob_area, prob_sla, prob_tipo_1, prob_tipo_2, prob_tipo_3) ".
						"values ('".noHtml($_POST['problema'])."', ".$_POST['area'].", ".$_POST['sla'].", ".
						"".$_POST['tipo_1'].", ".$_POST['tipo_2'].", ".$_POST['tipo_3'].")";*/

			if (isset($_POST['alimentabancosolucao'])) {
				$alimentabancosolucao = 1;
			} else
				$alimentabancosolucao = 0;
			//$descProb = str_replace("\r\n","",$_POST['descricao']);
			$descProb = $_POST['descricao'];
			$query = "INSERT INTO problemas (problema, prob_area, prob_sla, prob_tipo_1, prob_tipo_2, prob_tipo_3, prob_descricao, prob_alimenta_banco_solucao) ".
						"values ('".$_POST['problema']."', ".$_POST['area'].", ".$_POST['sla'].", ".
						"".$_POST['tipo_1'].", ".$_POST['tipo_2'].", ".$_POST['tipo_3'].", '".$descProb."', ".$alimentabancosolucao.")";
			//--------------------------------------------------------------- FIM ALTERACAO ---------------------------------------------------------------
			
			$resultado = mysql_query($query);
			if ($resultado == 0)
			{
				$aviso = TRANS('ERR_INSERT');
			}
			else
			{
				$aviso = TRANS('OK_INSERT');
			}
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')){
		//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
		/*$query2 = "UPDATE problemas SET ".
					"problema='".noHtml($_POST['problema'])."', prob_area = ".$_POST['area'].", prob_sla = ".$_POST['sla'].",  ".
					"prob_tipo_1 = ".$_POST['tipo_1'].", prob_tipo_2 = ".$_POST['tipo_2'].", prob_tipo_3 = ".$_POST['tipo_3']." ".
					"WHERE prob_id='".$_POST['cod']."'";*/

		if (isset($_POST['alimentabancosolucao'])) {
				$alimentabancosolucao = 1;
			} else
				$alimentabancosolucao = 0;
		//$descProb = str_replace("\r\n","",$_POST['descricao']);
		$descProb = $_POST['descricao'];
		$query2 = "UPDATE problemas SET ".
					"problema='".noHtml($_POST['problema'])."', prob_area = ".$_POST['area'].", prob_sla = ".$_POST['sla'].",  ".
					"prob_tipo_1 = ".$_POST['tipo_1'].", prob_tipo_2 = ".$_POST['tipo_2'].", prob_tipo_3 = ".$_POST['tipo_3']." ".", ".
					"prob_descricao = '".$descProb."', ".
					"prob_alimenta_banco_solucao = ".$alimentabancosolucao." ".
					"WHERE prob_id='".$_POST['cod']."'";
		//--------------------------------------------------------------- FIM ALTERACAO ---------------------------------------------------------------
		$resultado2 = mysql_query($query2);

		if ($resultado2 == 0)
		{
			$aviso =  TRANS('ERR_EDIT');
		}
		else
		{
			$aviso =  TRANS('OK_EDIT');
			//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
			// Comentada pelo Foster 
			//$queryAtualizaOcorrencias = "UPDATE ocorrencias o, problemas p SET o.sistema = p.prob_area WHERE o.problema = p.prob_id AND o.sistema <> p.prob_area";
			//$resultadoAtualizacao = mysql_query($queryAtualizaOcorrencias) or die ('ERRO NA TENTATIVA DE ATUALIZAR OCORRENCIAS!<BR>'.$queryAtualizaOcorrencias);
			// Fim comentário Foster 
			//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
		}

		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	print "</table>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idProblema','','Problema',1);
		//if (ok) var ok = validaForm('idArea','COMBO','Área',1);
		if (ok) var ok = validaForm('idSla','COMBO','SLA',1);

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
