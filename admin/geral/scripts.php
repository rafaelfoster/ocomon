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
	print "<BODY bgcolor='".BODY_COLOR."' onLoad=\"ajaxFunction('Problema', '../../ocomon/geral/showSelProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'pathAdmin=idPathAdmin', 'area_habilitada=idAreaHabilitada'); ajaxFunction('divProblema', '../../ocomon/geral/showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'pathAdmin=idPathAdmin');\">";

	print "<div id='idLoad' class='loading' style='{display:none}'><img src='../../includes/imgs/loading.gif'></div>";

	$auth = new auth;
	if (isset($_GET['action']) && $_GET['action']=='popup')
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	
	else 
	if($OPERADOR_AREA)
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);
	
		

	print "<BR><B>".TRANS('ADM_SCRIPTS')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
		print "<input type='hidden' name='areaHabilitada' id='idAreaHabilitada' value='sim'>";


	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);
	//$PAGE->setRegPerPage(10);

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
		$query = "SELECT sr.*, prsc.*, pr.*, a.* FROM scripts as sr ".
				"LEFT JOIN prob_x_script as prsc on prsc.prscpt_scpt_id = sr.scpt_id ".
				"LEFT JOIN problemas as pr on pr.prob_id = prsc.prscpt_prob_id ".
				"LEFT JOIN sistemas as a on a.sis_id = pr.prob_area ".
				"";
		
		if($OPERADOR_AREA){
			!$WHERE?$query.=" WHERE pr.prob_area = ".$_SESSION['s_area']." ":$query.=" AND pr.prob_area = ".$_SESSION['s_area']." ";
			$WHERE = true;
		}		
		
		if (isset($_GET['cod'])) {
			!$WHERE?$query.= " WHERE sr.scpt_id = ".$_GET['cod']." ":$query.=" AND sr.scpt_id = ".$_GET['cod']." ";
			$WHERE = true;
		}
		if (isset($_GET['prob'])){
			!$WHERE?$query.=" WHERE pr.prob_id = ".$_GET['prob']." ":$query.=" AND pr.prob_id = ".$_GET['prob']." ";
			$WHERE = true;
		} 
		else
		{
			if(isset($_POST['id_sistema']))
				$_SESSION['id_sistema_filtro'] = $_POST['id_sistema']; 
			
			if($OPERADOR_AREA)
				$_SESSION['id_sistema_filtro']=$_SESSION['s_area'];
			
			if (isset($_POST['search']) || isset($_SESSION['id_sistema_filtro'])) {
				if($OPERADOR_AREA) {
					!$WHERE?$query.= " WHERE pr.problema IS NOT NULL":$query.= " AND pr.problema IS NOT NULL";
				} else
					!$WHERE?$query.= " WHERE scpt_id = scpt_id":$query.= " AND scpt_id = scpt_id";
					
				if (isset($_SESSION['id_sistema_filtro']) && $_SESSION['id_sistema_filtro'] != '-1')
					$query.= " AND pr.prob_area = ".$_SESSION['id_sistema_filtro'];
				if ((isset($_POST['search'])) && !empty($_POST['search']))
					$query.= " AND (lower(pr.problema) like lower('%".noHtml($_POST['search'])."%') OR lower(scpt_nome) like lower('%".noHtml($_POST['search'])."%') OR lower(scpt_desc) like lower('%".noHtml($_POST['search'])."%')) ";
			}
		}
		
		if ((!isset($_GET['action']) || $_GET['action']=='popup')) {
			$query.= " GROUP BY sr.scpt_id ";
		}

		$query.=" ORDER BY sr.scpt_nome";
		
		//print $query; //dump($_GET['action'],'ACTION'); dump($_GET,'GET');
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY')."<br>".$query);
		$resultado2 = mysql_query($query);
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
			$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		$PAGE->execSQL();
		print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";

		print "<tr>".
		"<td colspan='4'>".
			"<input type='text' class='text' name='search' id='idSearch' value='".$search."'>&nbsp;";
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
			print "<tr><td colspan='6'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD>";
			print "</tr>";
			
			print "<TR class='header'><td class='line'>".TRANS('COL_SCRIPT_NAME','')."</TD><td class='line'>".TRANS('COL_DESC')."</TD><td class='line'>".TRANS('COL_SCRIPT_ENDUSER')."</TD><td class='line'>".TRANS('COL_PROB')."</TD>".
				"<td class='line'>".TRANS('COL_EDIT','')."</TD><td class='line'>".TRANS('COL_DEL','')."</TD></tr>";

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
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=details&cod=".$row['scpt_id']."&cellStyle=true')\">".NVL($row['scpt_nome'])."</a></td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=details&cod=".$row['scpt_id']."&cellStyle=true')\">".NVL($row['scpt_desc'])."</a></td>";
				print "<td class='line'>".transbool($row['scpt_enduser'])."</td>";
				$texto = trim($row['scpt_script']);
				if (strlen($texto)>50){
					$texto = substr($texto,0,45)." ..... ";
				};				
				
				$qryProb = "SELECT * FROM prob_x_script ".
						"LEFT JOIN problemas on prob_id = prscpt_prob_id ".
						"WHERE prscpt_scpt_id = ".$row['scpt_id']." ".
						"AND prscpt_prob_id = prob_id ".
						"GROUP BY problema ".
						"ORDER BY problema ";
				$execProb = mysql_query($qryProb);
				
				$allProbs = "";
				while ($rowProb = mysql_fetch_array($execProb)){
					!empty($allProbs)?$allProbs.=",<br>":$allProbs.="";
					$allProbs.= $rowProb['problema'];
				}
				
				print "<td class='line'>".NVL($allProbs)."</td>";
				print "<td class='line'><a onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['scpt_id']."&cellStyle=true')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('ENSURE_DEL')."?','".$_SERVER['PHP_SELF']."', 'action=excluir&cod=".$row['scpt_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
			print "<tr><td colspan='6'>";
			$PAGE->showOutputPages();
			print "</td></tr>";
		}

	} else
	if ((isset($_GET['action'])  && ($_GET['action'] == "details") )&& empty($_POST['submit'])) {
	
		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('TTL_RECORD_INFO').":</B><BR>";

		print "<TR>";
		print "<TD bgcolor='".TD_COLOR."'>".TRANS('COL_SCRIPT_NAME').":</TD>";
		
		if ($row['scpt_enduser']) $check = " checked"; else $check = "";
		print "<TD bgcolor='".BODY_COLOR."'>".$row['scpt_nome']."</td><td colspan='3'><input type='checkbox' name='enduser' ".$check." disabled>".TRANS('COL_SCRIPT_ENDUSER')."</td>";
		print "</TR>";
		print "<tr>";
		print "<TD bgcolor='".TD_COLOR."'>".TRANS('COL_DESC').":</TD>";
		print "<TD colspan='4' class='cborda' bgcolor='".BODY_COLOR."'>".$row['scpt_desc']."";//<INPUT class='disable' value='".$row['scpt_desc']."' disabled></td>";
		print "</tr>";
		print "<tr><td colspan='5'>&nbsp;</td></tr>";
		
		$titleShown = false;
		$j=2;
		while ($row2=mysql_fetch_array($resultado2)) {
		
			if (!empty($row2['problema'])){	
				
				if (!$titleShown){
					print "<tr class='header'><TD class='cborda' colspan='5' align='center' bgcolor='".TD_COLOR."'>".TRANS('LINKED_PROBLEM').":</TD></tr>";	
					print "<TR class='header'>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".TRANS('COL_AREA','')."</TD>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".TRANS('COL_PROB')."</TD>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_1']."</TD>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_2']."</TD>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_3']."</TD>".
						"</tr>";	
					$titleShown = true;
				}				
				
				$queryCat = "SELECT * FROM problemas AS p ".
						"LEFT JOIN sistemas AS s ON p.prob_area = s.sis_id ".
						"LEFT JOIN sla_solucao AS sl ON sl.slas_cod = p.prob_sla ".
						"LEFT JOIN prob_tipo_1 AS pt1 ON pt1.probt1_cod = p.prob_tipo_1 ".
						"LEFT JOIN prob_tipo_2 AS pt2 ON pt2.probt2_cod = p.prob_tipo_2 ".
						"LEFT JOIN prob_tipo_3 AS pt3 ON pt3.probt3_cod = p.prob_tipo_3 ".
					"WHERE prob_id = ".$row2['prob_id']."  ".
						" ".
					"ORDER BY s.sistema, p.problema";
				//print $queryCat."<br />";
				$execCat = mysql_query($queryCat) or die($queryCat);
				$rowCat = mysql_fetch_array($execCat);
				
				
				$area = TRANS('ALL');
				if (!empty($row2['sistema'])){
					$area = $row2['sistema'];
				}
				
				print "<tr>";			
				print "<TD class='cborda' bgcolor='".BODY_COLOR."'>".$area.":</TD>";
				print "<TD class='cborda' bgcolor='".BODY_COLOR."'>".$row2['problema']."</td>";
				print "<TD class='cborda' bgcolor='".BODY_COLOR."'>".$rowCat['probt1_desc']."</td>";
				print "<TD class='cborda' bgcolor='".BODY_COLOR."'>".$rowCat['probt2_desc']."</td>";
				print "<TD class='cborda' bgcolor='".BODY_COLOR."'>".$rowCat['probt3_desc']."</td>";
				print "</tr>";
			}
		}
		print "<tr><td colspan='5'>&nbsp;</td></tr>";

		print "<tr class='header'><TD class='cborda' colspan='6' align='center' bgcolor='".TD_COLOR."'>".TRANS('COL_SCRIPT')."</td></tr>";
		print "<tr>";
		print "<TD colspan='5' class='wide' align='left' bgcolor='".BODY_COLOR."'>".toHtml(nl2br($row['scpt_script']))."</td>";
		print "</tr>";


		print "<tr><td colspan='5'>&nbsp;</td></tr>";
		print "<tr>";
		print "<TD colspan='2' bgcolor='".BODY_COLOR."'><INPUT type='button' class='button' value='".TRANS('TXT_RETURN')."' name='voltar' onClick=\"javascript:history.back()\"></TD>";
		print "<TD colspan='3' bgcolor='".BODY_COLOR."'><INPUT type='button' class='button' value='".TRANS('OPT_EDIT')."' name='voltar' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=alter&cod=".$row['scpt_id']."&cellStyle=true')\"></TD>";
		print "</tr>";
	} else
	
	if ((isset($_GET['action'])  && ($_GET['action'] == "popup") )&& empty($_POST['submit'])) {
	
		if ($registros > 1) {
		
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><td colspan='2'><b>".TRANS('SEL_LINE_SCRIPT').":</b></td></tr>";	
			print "<tr><td colspan='2'>&nbsp;</td></tr>";		
			print "<TR class='header'><td class='line'>".TRANS('COL_SCRIPT_NAME','')."</TD><td class='line'>".TRANS('COL_DESC')."</TD></tr>";		
			$titleShown = false;
			$j=2;
			while ($row=mysql_fetch_array($resultado)) {
				if (!$titleShown){
					print "<BR><B>".TRANS('TTL_PROB_SCRIP_CLUE')." <font color='green'>".$row['problema']."</font></B><BR>";
					$titleShown = true;
				}
			
				if ($j % 2)
				{
					$trClass = "lin_par";
				}
				else
				{
					$trClass = "lin_impar";
				}
				$j++;
				if ($_SESSION['s_nivel']!=3 || $row['scpt_enduser']==1){			
					print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."'); redirect('".$_SERVER['PHP_SELF']."?action=popup&cod=".$row['scpt_id']."&cellStyle=true&bbutton=true')\">";			
				
					print "<td class='line'>".$row['scpt_nome']."</td><td class='line'>".$row['scpt_desc']."</td>";
					print "</tr>";
				}
			}
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			print "<tr><TD colspan='2' bgcolor='".BODY_COLOR."'><INPUT type='button' class='button' value='".TRANS('LINK_CLOSE')."' name='fechar' onClick=\"javascript:window.close()\"></TD></tr>";	
		} else 
		
		if ($registros == 1) {
		
			$row = mysql_fetch_array($resultado);
			
			print "<BR><B>".TRANS('TTL_PROB_SCRIP_CLUE')." <font color='green'>".$row['problema']."</font></B><BR>";
			print "<BR><i>".$row['prob_descricao']."</font></i><BR>";
			print "<tr>";
			print "<TD colspan='2' class='wide' align='left' bgcolor='".BODY_COLOR."'>".toHtml(nl2br($row['scpt_script']))."</td>";
			print "</tr>";			
			
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
	
			print "<tr><td colspan='2'>&nbsp;</td></tr>";
			
			print "<tr>";
			if (isset($_GET['bbutton'])){
				print "<TD bgcolor='".BODY_COLOR."'><INPUT type='button' class='button' value='".TRANS('TXT_RETURN')."' name='voltar' onClick=\"javascript:history.back()\"></TD>";
				print "<TD bgcolor='".BODY_COLOR."'><INPUT type='button' class='button' value='".TRANS('LINK_CLOSE')."' name='fechar' onClick=\"javascript:window.close()\"></TD>";	
			} else			
				print "<TD colspan='2' bgcolor='".BODY_COLOR."'><INPUT type='button' class='button' value='".TRANS('LINK_CLOSE')."' name='fechar' onClick=\"javascript:window.close()\"></TD>";		
			print "</tr>";
		} else {
			print "<tr><td align='center'>";
			print mensagem(TRANS('NO_RECORDS'));
			print "</tr></td>";
		}
	} else	
	
	if ((isset($_GET['action'])  && ($_GET['action'] == "incluir") )&& empty($_POST['submit'])) {

		print "<BR><B>".TRANS('CADASTRE_SCRIPT')."</B><BR>";

		print "<TR>";
		print "<TD bgcolor='".TD_COLOR."'>".TRANS('COL_SCRIPT_NAME').":</TD>";
		
		print "<TD colspan='3' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nome' class='text' id='idNome'><input type='checkbox' name='enduser' value='0'>".TRANS('COL_SCRIPT_ENDUSER')."</td>";
		
		print "</TR>";
		print "<tr>";
		print "<TD bgcolor='".TD_COLOR."'>".TRANS('COL_DESC').":</TD>";
		//print "<TD colspan='3' bgcolor='".BODY_COLOR."'><INPUT type='text' name='desc' class='text' id='idDesc'></td>";
		print "<TD colspan='3' bgcolor='".BODY_COLOR."'><textarea id='idDesc' name='desc' class='textarea_desc'></textarea></td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_SCRIPT').":</TD>";
		print "<TD colspan='3' align='left' bgcolor='".BODY_COLOR."'>";
		
		print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
			?>
			<script type="text/javascript">
				
				var oFCKeditor = new FCKeditor( 'script' ) ;
				oFCKeditor.BasePath = '../../includes/fckeditor/';
				oFCKeditor.Value = '';
				oFCKeditor.ToolbarSet = 'Scripts';
				//oFCKeditor.ToolbarSet = 'Default';
				oFCKeditor.Width = '570px';
				oFCKeditor.Height = '400px';
				oFCKeditor.Create() ;
				
			</script>
			<?php 
		
		
		print "</td>";
		
		print "</tr>";

		print "<tr><td colspan='4'>&nbsp;</td></tr>";
			
		print "<tr class='header'><TD class='cborda' colspan='4' align='center' bgcolor='".TD_COLOR."'>".TRANS('LINK_TO_PROBLEM').":</TD></tr>";						
			
		print "<TR>";

			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_AREA').":</TD>";
        		print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
			print "<SELECT class='select' name='sistema' id='idArea' size='1' onChange=\" ";

				print "ajaxFunction('Problema', '../../ocomon/geral/showSelProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'pathAdmin=idPathAdmin', 'area_habilitada=idAreaHabilitada');";
				print "ajaxFunction('divProblema', '../../ocomon/geral/showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea');";
				print "ajaxFunction('divInformacaoProblema', '../../ocomon/geral/showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea'); ";			
			
			print "\">";

			$query = "SELECT s.* from sistemas s WHERE s.sis_status NOT IN (0) AND s.sis_atende = 1 ORDER BY sistema"; //NOT in (0) = INATIVO 
			
			if($OPERADOR_AREA)
				$query = "SELECT s.* from sistemas s WHERE s.sis_status NOT IN (0) AND s.sis_atende = 1 AND sis_id = ".$_SESSION['s_area']." ORDER BY sistema";
			else {	
				$query = "SELECT s.* from sistemas s WHERE s.sis_status NOT IN (0) AND s.sis_atende = 1 ORDER BY sistema"; //NOT in (0) = INATIVO 
				print "<option value=-1 selected>".TRANS('OCO_SEL_AREA')."</option>";
			}
			$resultado = mysql_query($query);

			while ($rowArea=mysql_fetch_array($resultado)){
				$isSelecionado = "";
				if ($rowArea['sis_id'] == $_SESSION['id_sistema_filtro'])
					$isSelecionado = " selected";
				print "<option value='".$rowArea['sis_id']."' ".$isSelecionado.">".$rowArea['sistema']."</option>";				
			}
			print "</select>";
			print "</td>";

			print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_PROB').":</td>";
			print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";
				print "<div id='Problema'>";
					print "<input type='hidden' name='problema' id='idProblema' value='-1'>";
				print "</div>";
            		print "</TD>";

		print "</TR>";

		print "<tr><td colspan='4'><div id='divProblema'>"; //style='{display:none}' colspan='6' 
			print "<input type='hidden' name='radio_prob' id='idRadioProb' value='-1'>"; //id='idRadioProb'
		print "</div></td></tr>";
		
		print "<tr><td colspan='4'><div id='divInformacaoProblema'></div></td></tr>";// colspan='6'

		//Para indicar que o script está localizado no path do módulo de administração
		print "<input type='hidden' name='pathAdmin' id='idPathAdmin' value='fromPathAdmin'>";
			
		print "<tr><td colspan='4'>&nbsp;</td></tr>";
		print "<TR>";
		print "<TD align='center' colspan='2' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "</TD>";
		print "<TD colspan='2' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";

	} else

	if ((isset($_GET['action']) && $_GET['action']=="alter") && empty($_POST['submit'])) {

		$row = mysql_fetch_array($resultado);

		print "<BR><B>".TRANS('TTL_EDIT_RECORD').":</B><BR>";

		print "<TR>";
		print "<TD bgcolor='".TD_COLOR."'>".TRANS('COL_SCRIPT_NAME').":</TD>";
		
		if ($row['scpt_enduser']) $check = " checked"; else $check = "";
		
		print "<TD colspan='4' bgcolor='".BODY_COLOR."'><INPUT type='text' name='nome' class='text' id='idNome' value='".$row['scpt_nome']."'><input type='checkbox' name='enduser' ".$check.">".TRANS('COL_SCRIPT_ENDUSER')."</td>";
		print "</TR>";
		print "<tr>";
		print "<TD bgcolor='".TD_COLOR."'>".TRANS('COL_DESC').":</TD>";
		print "<TD colspan='4' bgcolor='".BODY_COLOR."'><textarea class='textarea_desc' id='idDesc' name='desc'>".$row['scpt_desc']."</textarea></td>";
		print "</tr>";

		print "<tr>";
		print "<TD class='cborda' bgcolor='".TD_COLOR."'>".TRANS('COL_SCRIPT').":</TD>";
		
		print "<TD colspan='4' bgcolor='".BODY_COLOR."'>";
		
		print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";
					
		//Enfim resolvido o problema da edição no FCKeditor - testado no Firefox, IE e Opera: by Flávio
		$texto1 = str_replace("\r","\n",$row['scpt_script']);
		$texto1 = str_replace("\n","",$texto1);
			?>
			<script type="text/javascript">
				
				var oFCKeditor = new FCKeditor( 'script' ) ;
				oFCKeditor.BasePath = '../../includes/fckeditor/';
				oFCKeditor.Value = '<?php print $texto1;?>';
				oFCKeditor.ToolbarSet = 'Scripts';
				//oFCKeditor.ToolbarSet = 'Default';
				oFCKeditor.Width = '570px';
				oFCKeditor.Height = '400px';
				oFCKeditor.Create() ;
				
			</script>
			<?php 		
		
		
		print "</td>";		
		print "</tr>";

		print "<tr><td colspan='5'>&nbsp;</td></tr>";
			
		
		print "<tr><td colspan='5'>&nbsp;</td></tr>";		
		
		
		$titleShown = false;
		$j=2;
		while ($row2=mysql_fetch_array($resultado2)) {
		
			if (!empty($row2['problema'])){	
				
				if ($j % 2)
				{
					$trClass = "lin_par";
				}
				else
				{
					$trClass = "lin_impar";
				}
				$j++;
				
				if (!$titleShown){
					print "<tr class='header'><TD class='cborda' colspan='5' align='center' bgcolor='".TD_COLOR."'>".TRANS('LINKED_PROBLEM').":</TD></tr>";	
					
					print "<TR class='header'>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".TRANS('COL_AREA')."</TD>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".TRANS('COL_PROB')."</TD>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_1']."</TD>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_2']."</TD>".
						"<td class='cborda' bgcolor='".TD_COLOR."'>".$row_config['conf_prob_tipo_3']."</TD>".
						"</tr>";
					$titleShown = true;
				}				
				
				$queryCat = "SELECT * FROM problemas AS p ".
						"LEFT JOIN sistemas AS s ON p.prob_area = s.sis_id ".
						"LEFT JOIN sla_solucao AS sl ON sl.slas_cod = p.prob_sla ".
						"LEFT JOIN prob_tipo_1 AS pt1 ON pt1.probt1_cod = p.prob_tipo_1 ".
						"LEFT JOIN prob_tipo_2 AS pt2 ON pt2.probt2_cod = p.prob_tipo_2 ".
						"LEFT JOIN prob_tipo_3 AS pt3 ON pt3.probt3_cod = p.prob_tipo_3 ".
					"WHERE prob_id = ".$row2['prob_id']."  ".
						" ".
					"ORDER BY s.sistema, p.problema";
				$execCat = mysql_query($queryCat) or die($queryCat);
				$rowCat = mysql_fetch_array($execCat);
				
				$area = TRANS('ALL');
				if (!empty($row2['sistema'])){
					$area = $row2['sistema'];
				}
				
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";			
				print "<TD class='cborda'>".$area.":</TD>";
				print "<TD class='cborda'><input type='checkbox' name='delProb[".$j."]' value='".$row2['prscpt_id']."'><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'>".$row2['problema']."</td>";
				print "<TD class='cborda'>".$rowCat['probt1_desc']."</td>";
				print "<TD class='cborda'>".$rowCat['probt2_desc']."</td>";
				print "<TD class='cborda'>".$rowCat['probt3_desc']."</td>";
				print "</tr>";
			}
		}
		print "<tr><td colspan='5'>&nbsp;</td></tr>";		
		print "<tr class='header'><TD class='cborda' colspan='5' align='center' bgcolor='".TD_COLOR."'>".TRANS('LINK_TO_PROBLEM').":</TD></tr>";			
		
		print "<TR>";

			print "<TD bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_AREA').":</TD>";
        		print "<TD bgcolor=".BODY_COLOR.">";
			print "<SELECT class='select' name='sistema' id='idArea' onChange=\" ";

				print "ajaxFunction('Problema', '../../ocomon/geral/showSelProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea', 'pathAdmin=idPathAdmin', 'area_habilitada=idAreaHabilitada');";
				print "ajaxFunction('divProblema', '../../ocomon/geral/showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea');";
				print "ajaxFunction('divInformacaoProblema', '../../ocomon/geral/showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea'); ";			
			
			print "\">";

			if($OPERADOR_AREA)
				$query = "SELECT s.* from sistemas s WHERE s.sis_status NOT IN (0) AND s.sis_atende = 1 AND sis_id = ".$_SESSION['s_area']." ORDER BY sistema";
			else {	
				$query = "SELECT s.* from sistemas s WHERE s.sis_status NOT IN (0) AND s.sis_atende = 1 ORDER BY sistema"; //NOT in (0) = INATIVO 
				print "<option value=-1 selected>".TRANS('OCO_SEL_AREA')."</option>";
			}
			$resultado = mysql_query($query);

			while ($rowArea=mysql_fetch_array($resultado)){
				$isSelecionado = "";
				if ($rowArea['sis_id'] == $_SESSION['id_sistema_filtro'])
					$isSelecionado = " selected";
				print "<option value='".$rowArea['sis_id']."' ".$isSelecionado.">".$rowArea['sistema']."</option>";				
			}
			print "</select>";
			print "</td>";

			print "<TD bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_PROB').":</td>";
			print "<TD bgcolor=".BODY_COLOR.">";
				print "<div id='Problema'>";
					print "<input type='hidden' name='problema' id='idProblema' value='-1'>";
				print "</div>";
            		print "</TD>";

		print "</TR>";

		print "<tr><td colspan='5'><div id='divProblema'>"; //style='{display:none}' colspan='6' 
			print "<input type='hidden' name='radio_prob' id='idRadioProb' value='-1'>"; //id='idRadioProb'
		print "</div></td></tr>";
		
		print "<tr><td colspan='5'><div id='divInformacaoProblema'></div></td></tr>";// colspan='6'

		//Para indicar que o script está localizado no path do módulo de administração
		print "<input type='hidden' name='pathAdmin' id='idPathAdmin' value='fromPathAdmin'>";

		print "<tr><td colspan='5'>&nbsp;</td></tr>";

		print "<TR>";
		//print "<BR>";
		print "<TD align='left' colspan='2' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_ALTER')."' name='submit'>";
			print "<input type='hidden' name='j' value='".$j."'>";
			print "<input type='hidden' name='cod' value='".$_GET['cod']."'>";
			print "<input type='hidden' name='id_script_problema' value='".$row['prscpt_id']."'>";
			print "</TD>";
		print "<TD align='left' colspan='3' bgcolor='".BODY_COLOR."'><INPUT type='reset' class='button' value='".TRANS('bt_cancelar')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

		print "</TR>";


	} else

	if (isset($_GET['action']) && $_GET['action'] == "excluir"){

		$total = 0; $texto = "";

		$query2 = "DELETE FROM scripts WHERE scpt_id='".$_GET['cod']."'";
		$resultado2 = mysql_query($query2) or die(TRANS('ERR_DEL'));

		$aviso = "";
		if ($resultado2 == 0)
		{
				$aviso.= TRANS('ERR_DEL');
		}
		else
		{
				$aviso.= TRANS('OK_DEL');
		}			
		
		$query3 = "DELETE FROM prob_x_script WHERE prscpt_scpt_id = ".$_GET['cod']." ";
		$resultado3 = mysql_query($query3) or $aviso.=TRANS('MSG_NOT_EXCLUDED_LINKS');


		print "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";


	} else

	if ($_POST['submit'] == TRANS('bt_cadastrar')){

		$erro=false;

		if (!$erro)
		{

			if (isset($_POST['enduser'])) {
				$enduser = 1;
			} else
				$enduser = 0;	
						
			$descScript = $_POST['script'];
			
			$query = "INSERT INTO scripts (scpt_nome, scpt_desc, scpt_script, scpt_enduser) ".
						"values ('".$_POST['nome']."', '".$_POST['desc']."', '".$descScript."', ".$enduser.") ";
			
			$resultado = mysql_query($query) or die($query.'<br>'.mysql_error());
			
			$script_id = mysql_insert_id();
			
			if (isset($_POST['radio_prob'])){
				$query2 = "INSERT INTO prob_x_script (prscpt_prob_id, prscpt_scpt_id) values ".
						"(".$_POST['radio_prob'].", ".$script_id.") ";
				$execQuery2 = mysql_query($query2) or die($query2);
			}			
			
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

		if (isset($_POST['enduser'])) {
			$enduser = 1;
		} else
			$enduser = 0;
		
		$descScript = $_POST['script'];
		
		$query2 = "UPDATE scripts SET ".
					"scpt_nome='".noHtml($_POST['nome'])."', scpt_desc = '".$_POST['desc']."', ".
					"scpt_script = '".$descScript."', scpt_enduser = ".$enduser."  ".
					"WHERE scpt_id='".$_POST['cod']."'";
		$resultado2 = mysql_query($query2);

		
		$catProb = "";
		if (isset($_POST['radio_prob']) && $_POST['radio_prob'] != -1){
			$catProb = $_POST['radio_prob'];
		} else 
		if (isset($_POST['problema']) && $_POST['problema'] != -1)
		{
			$catProb = $_POST['problema'];
		}		
		
		if (!empty($catProb)){
			$query2 = "INSERT INTO prob_x_script (prscpt_prob_id, prscpt_scpt_id) values ".
					"(".$catProb.", ".$_POST['cod'].") ";
			$execQuery2 = mysql_query($query2) or die($query2);
		}			
		
		if ($resultado2 == 0)
		{
			$aviso =  TRANS('ERR_EDIT');
		}
		else
		{
			$aviso =  TRANS('OK_EDIT');
		}

		//Exclui os problemas marcados			
		if (isset($_POST['j'])) {
			for ($j=1; $j<=$_POST['j']; $j++) {
				if (isset($_POST['delProb'][$j])){
					$qryDel = "DELETE FROM prob_x_script WHERE prscpt_id = ".$_POST['delProb'][$j]."";
					$execDel = mysql_query($qryDel) or die (TRANS('MSG_NOT_EXCLUDED_PROBLEM'));
				}
			}			
		}		
		
		
		echo "<script>mensagem('".$aviso."'); redirect('".$_SERVER['PHP_SELF']."');</script>";

	}

	print "</table>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idNome','','Nome',1);
		if (ok) var ok = validaForm('idDesc','','Descricao',1);
		if (ok) var ok = validaForm('idScript','','Script',1);

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
?>