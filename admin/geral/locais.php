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
	include ("../../includes/classes/paging.class.php");

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor=".BODY_COLOR.">";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	$PAGE = new paging("PRINCIPAL");
	$PAGE->setRegPerPage($_SESSION['s_page_size']);

	print "<BR><B>".TRANS('TTL_ADMIN_LOCAL_SECTORS')."</B><BR>";


		if (isset($_POST['search'])){
			$search = $_POST['search'];
		} else
			$search = "";


		$query = $QRY['locais'];

		if (isset($_GET['cod'])) {
			$query.= "WHERE l.loc_id = ".$_GET['cod']." ";
		} else
		if (isset($_POST['search'])) {
			$query.= " WHERE lower(l.local) like lower(('%".noHtml($_POST['search'])."%')) ";
		}
		$query .="ORDER  BY reit_nome, LOCAL";
		$resultado = mysql_query($query) or die(TRANS('MSG_ERR_QRY_CONS'));
		$registros = mysql_num_rows($resultado);

		if (isset($_GET['LIMIT']))
			$PAGE->setLimit($_GET['LIMIT']);
		$PAGE->setSQL($query,(isset($_GET['FULL'])?$_GET['FULL']:0));

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
	print "<TABLE border='0' align='left' width='100%' bgcolor=".BODY_COLOR.">";

	if ((!isset($_GET['action'])) && !isset($_POST['submit'])) {

		$PAGE->execSQL();

		print "<tr><TD align='left'><a href='locais.php?action=incluir'>".TRANS('TXT_INCLUDE_LOCAL')."</a></TD></tr>";


		if (mysql_num_rows($resultado) == 0)
		{
			if (isset($_POST['search'])) {
				print "<tr>".//<td>".TRANS('FIELD_SEARCH')."</td>".
					"<td colspan='4'><input type='text' class='text' name='search' id='idSearch' value='".$search."'>&nbsp;".
					"<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
					"</td></tr>";

					print "<script>foco('idSearch');</script>";
			}

			print "<tr><td>";
			print mensagem(TRANS('MSG_NO_RECORDS'));
			print "</tr></td>";
		} else {
			$cor=TD_COLOR;
			$cor1=TD_COLOR;
			print "<tr><td colspan='8' class='line'>";
			print "<B>".TRANS('FOUND')." <font color=red>".$PAGE->NUMBER_REGS."</font> ".TRANS('RECORDS_IN_SYSTEM').". ".TRANS('SHOWING_PAGE')." ".$PAGE->PAGE." (".$PAGE->NUMBER_REGS_PAGE." ".TRANS('RECORDS').")</B></TD></tr>";

			print "<tr><td>";
			print "<TABLE border='0' cellpadding='5' cellspacing='0' align='center' width='100%'>";

			print "<tr>".//<td>".TRANS('FIELD_SEARCH')."</td>".
					"<td colspan='4'><input type='text' class='text' name='search' id='idSearch' value='".$search."'>&nbsp;".
					"<input type='submit' name='BT_SEARCH' class='button' value='".TRANS('BT_FILTER')."'>".
				"</td></tr>";
				if (isset($_POST['search'])) {
					print "<script>foco('idSearch');</script>";
				}

			print "<TR class='header'><td class='line'><b>".TRANS('OCO_LOCAL')."</b></TD><td class='line'><b>".TRANS('COL_BUILDING')."</b></TD><td class='line'><b>".TRANS('COL_MAJOR')."</b></TD><td class='line'><b>".TRANS('COL_DOMAIN')."</b></TD><td class='line'><b>".TRANS('COL_PRIORITY')."</b></TD><td class='line'><b>".TRANS('OCO_STATUS')."</b></TD><td class='line'><b>".TRANS('COL_EDIT')."</b></TD><td class='line'><b>".TRANS('COL_DEL')."</b></TD>";

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
				if ($row['loc_status'] == 0) $lstatus =TRANS('INACTIVE');else $lstatus = TRANS('ACTIVE');
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'>".$row['local']."</td>";
				print "<td class='line'>".($row['predio']==''?'&nbsp;':$row['predio'])."</td>";
				print "<td class='line'>".($row['reit_nome']==''?'&nbsp;':$row['reit_nome'])."</td>";
				print "<td class='line'>".($row['dominio']==''?'&nbsp;':$row['dominio'])."</td>";
				print "<td class='line'>".($row['prioridade']==''?'&nbsp;':$row['prioridade'])."</td>";
				print "<td class='line'>".$lstatus."</td>";
				print "<td class='line'><a onClick=\"redirect('locais.php?action=alter&cod=".$row['loc_id']."')\"><img height='16' width='16' src='".ICONS_PATH."edit.png' title='".TRANS('HNT_EDIT')."'></a></td>";
				print "<td class='line'><a onClick=\"confirmaAcao('".TRANS('MSG_DEL_REG')."','locais.php','action=excluir&cod=".$row['loc_id']."')\"><img height='16' width='16' src='".ICONS_PATH."drop.png' title='".TRANS('HNT_DEL')."'></a></TD>";

				print "</TR>";
			}
               		print "</TABLE>";
               		print "</td></tr>";
			print "<tr><td colspan='8'>";
				$PAGE->showOutputPages();
			print "</td></tr>";
         	}
	} else
	if ((isset($_GET['action']) && $_GET['action'] == "incluir") && (!isset($_POST['submit']))) {

		print "<BR><B>".TRANS('SUBTTL_CAD_LOCAL')."</B><BR>";

// 		print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
// 		print "<TABLE border='0' align='left' width='100%' bgcolor=".BODY_COLOR.">";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_LOCAL').":</TD>";
		print "<TD width='80%' align='left' bgcolor=".BODY_COLOR."><INPUT type='text' name='local' class='text' id='idLocal'></TD>";
		print "</TR>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor=".TD_COLOR.">".TRANS('COL_BUILDING').":</TD>";
		print "<TD width='30%' align='left' bgcolor=".BODY_COLOR."><select class='select' name='predio' id='idPredio'>";
			print "<option value='-1'>".TRANS('SEL_BUILDING')."</option>";
					$sql="select * from predios order by pred_desc";
					$commit = mysql_query($sql);
					while($rowp = mysql_fetch_array($commit)){
						 print "<option value=".$rowp['pred_cod'].">".$rowp['pred_desc']."</option>";
					} // while
			print "</select>";
			print "<input type='button' name='predio' value='".TRANS('ACT_NEW')."' class='minibutton' onClick=\"javascript:mini_popup('predios.php?action=incluir&popup=true')\"></td>";

		print "</tr>";
		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_MAJOR').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='reitoria' id='idReitoria'>";
			print "<option value=-1>".TRANS('SEL_MAJOR')."</option>";
					$sql="select * from reitorias order by reit_nome";
					$commit = mysql_query($sql);
					$i=0;
					while($rowr = mysql_fetch_array($commit)){
						print "<option value=".$rowr['reit_cod'].">".$rowr["reit_nome"]."</option>";
						$i++;
					} // while

				print "</select>";
			print "<input type='button' name='reitoria' value='".TRANS('ACT_NEW')."' class='minibutton' onClick=\"javascript:mini_popup('reitorias.php?action=incluir&popup=true')\"></td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('COL_DOMAIN').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='dominio' id='idDominio'>";
			print "<option value='-1'>".TRANS('SEL_DOMAIN')."</option>";
					$sql="select * from dominios order by dom_desc";
					$commit = mysql_query($sql);

					while($rowd = mysql_fetch_array($commit)){
						print "<option value=".$rowd['dom_cod'].">".$rowd["dom_desc"]."</option>";

					} // while

			print "</select>";
			print "<input type='button' name='dominio' value='".TRANS('ACT_NEW')."' class='minibutton' onClick=\"javascript:mini_popup('dominios.php?action=incluir&popup=true')\"></td>";
		print "</tr>";

		print "<tr>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."'>".TRANS('TXT_PRIORITY_RESP').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><select class='select' name='sla' id='idSla'>";
			print "<option value='-1'>".TRANS('SEL_SLA')."</option>";
					$sql="select * from prioridades order by prior_nivel";
					$commit = mysql_query($sql);

					while($row = mysql_fetch_array($commit)){
						print "<option value=".$row['prior_cod'].">".$row["prior_nivel"]."</option>";

					} // while

				print "</select>";
        	print "</td>";
		print "</tr>";

		print "<TR>";
		print "<TD align='left' width='20%' bgcolor='".BODY_COLOR."'><input type='submit'  class='button' value='".TRANS('BT_CAD')."' name='submit'>";
		print "</TD>";
		print "<TD align='left' width='80%' bgcolor='".BODY_COLOR."'><INPUT type='reset'  class='button' value='".TRANS('BT_CANCEL')."' name='cancelar' onClick=\"javascript:history.back()\"></TD>";

        	print "</TR>";

	} else

	if ((isset($_GET['action'])  && $_GET['action']=="alter") && (!isset($_POST['submit']))) {

		$row = mysql_fetch_array($resultado);
		print "<BR><B>".TRANS('SUBTTL_ALTER_LOCAL')."</B><BR>";

		//print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";
		//print "<TABLE border='0'  align='left' width='80%' bgcolor='".BODY_COLOR."'>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('OCO_FIELD_LOCAL').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'><INPUT type='text' class='select' name='local' id='idLocal' value='".$row['local']."'></TD>";
		print "</TR>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_MAJOR').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select class='select' name='reitoria' id='idReitoria'>";
			print "<option value=-1 selected>".TRANS('SEL_MAJOR')."</option>";
			$sql = "select * from reitorias where reit_cod=".$row["loc_reitoria"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				//print "<option value=".$row["loc_reitoria"]." selected>".$rowR["reit_nome"]."</option>";

					$sql="select * from reitorias order by reit_nome";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["reit_cod"]." ";
						if ($rowB['reit_cod'] == $row["loc_reitoria"])
							print " selected";
						print ">".$rowB["reit_nome"]."</option>";
					} // while

			print "</select>";
		print "<input type='button' name='reitoria' value='".TRANS('ACT_NEW')."' class='minibutton' onClick=\"javascript:mini_popup('reitorias.php?action=incluir&popup=true')\"></td>";
        	print "</TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_BUILDING').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
        	print "<select class='select'  name='predio'>";
			$sql = "select * from predios where pred_cod=".$row["loc_predio"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value='-1'>".TRANS('SEL_BUILDING')."</option>";
					$sql="select * from predios order by pred_desc";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["pred_cod"]."";
					    if ($rowB['pred_cod'] == $row['loc_predio'] ) {
                            print " selected";
                        }
                        print " >".$rowB["pred_desc"]."</option>";

                    } // while

		print "</select>";
		print "<input type='button' name='predio' value='".TRANS('ACT_NEW')."' class='minibutton' onClick=\"javascript:mini_popup('predios.php?action=incluir&popup=true')\"></td>";

        	print "</TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_DOMAIN').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
		print "<select  class='select' name='dominio'>";
			$sql = "select * from dominios where dom_cod=".$row["loc_dominio"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value='-1'>".TRANS('SEL_DOMAIN')."</option>";
					$sql="select * from dominios order by dom_desc";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["dom_cod"]."";
					    if ($rowB['dom_cod'] == $row['loc_dominio'] ) {
                            print " selected";
                        }
                        print " >".$rowB["dom_desc"]."</option>";

                    } // while

		print "</select>";
		print "<input type='button' name='dominio' value='".TRANS('ACT_NEW')."' class='minibutton' onClick=\"javascript:mini_popup('dominios.php?action=incluir&popup=true')\"></td>";
        	print "</TR>";

		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('COL_PRIORITY').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select  class='select' name='p_nivel'>";
			$sql = "select * from prioridades where prior_cod=".$row["loc_prior"]."";
			$commit = mysql_query($sql);
			$rowR = mysql_fetch_array($commit);
				print "<option value='-1'>".TRANS('SEL_SLA')."</option>";

					$sql="select * from prioridades  order by prior_nivel";
					$commit = mysql_query($sql);
					while($rowB = mysql_fetch_array($commit)){
						print "<option value=".$rowB["prior_cod"]."";
					    if ($rowB['prior_cod'] == $row['loc_prior'] ) {
                            print " selected";
                        }
                        print " >".$rowB["prior_nivel"]."</option>";

                    } // while
			print "</select>";
		print "</td>";
		print "</TR>";
		print "<TR>";
		print "<TD width='20%' align='left' bgcolor='".TD_COLOR."' valign='top'>".TRANS('OCO_STATUS').":</TD>";
		print "<TD width='80%' align='left' bgcolor='".BODY_COLOR."'>";
			print "<select  class='select' name='lstatus'>";
					print"<option value=1";
					if ($row['loc_status']==1) print " selected";
					print ">".TRANS('ACTIVE')."</option>";
					print"<option value=0";
					if ($row['loc_status']==0) print " selected";
					print">".TRANS('INACTIVE')."</option>";
			print "</select>";
		print "</td>";
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

		$sql_3 = "SELECT * FROM ocorrencias where local ='".$_GET['cod']."'";
		$exec_3 = mysql_query($sql_3) or die(TRANS('MSG_ERR_NOT_RESCUE_INFO_CALL_LOCAL'));
		$total= mysql_num_rows($exec_3);

		if ($total!=0)
		{
			print "<script>mensagem('".TRANS('MSG_NOT_EXCLUDE_LOCAL_ASSOC_OCCO')."');
				redirect('locais.php');</script>";
		}
		else
		{
			$query2 = "DELETE FROM localizacao WHERE loc_id=".$_GET['cod']."";
			$resultado2 = mysql_query($query2) or die(TRANS('ERR_DEL'));

			$aviso = TRANS('MSG_OK_RESG_EXCLUDE');

			print "<script>mensagem('".$aviso."'); redirect('locais.php');</script>";

		}


	} else

	if ($_POST['submit'] == TRANS('BT_CAD')){

		$erro=false;

		$qryl = "SELECT local FROM localizacao WHERE local='".$_POST['local']."'";
		$resultado = mysql_query($qryl);
		$linhas = mysql_num_rows($resultado);

		if ($linhas > 0)
		{
				$aviso = TRANS('MSG_REG_IN_CAD');
				$erro = true;
		}

		if (!$erro)
		{
			$query = "INSERT INTO localizacao (local,loc_reitoria, loc_prior, loc_dominio, loc_predio) ".
						"values ('".noHtml($_POST['local'])."',".$_POST['reitoria'].",".$_POST['sla'].", ".$_POST['dominio'].",".$_POST['predio'].")";
			$resultado = mysql_query($query) or die(TRANS('MSG_ERR_INCLUDE_LOCAL').$query);
			$aviso = TRANS('MSG_OK_RESG_INCLUDE');
		}

		echo "<script>mensagem('".$aviso."'); redirect('locais.php');</script>";

	} else

	if ($_POST['submit'] == TRANS('BT_ALTER')) {

		$query2 = "UPDATE localizacao SET local='".noHtml($_POST['local'])."', loc_reitoria=".$_POST['reitoria'].", ".
				"loc_prior=".$_POST['p_nivel'].", loc_dominio=".$_POST['dominio'].", loc_predio=".$_POST['predio'].", ".
				"loc_status=".$_POST['lstatus']." WHERE loc_id=".$_POST['cod']."";
		$resultado2 = mysql_query($query2) or die(TRANS('MSG_ERR_UPDATE_REG'). $query2);

		$aviso = TRANS('MSG_LOCAL_ALTER_OK');
		echo "<script>mensagem('".$aviso."'); redirect('locais.php');</script>";

	}



?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idLocal','','Local',1);
		//if (ok) var ok = validaForm('idReitoria','COMBO','Reitoria',1);
		//if (ok) var ok = validaForm('idStatus','COMBO','Status',1);

		return ok;
	}
-->
</script>


<?
print "</table>";
print "</form>";
print "</body>";
print "</html>";
