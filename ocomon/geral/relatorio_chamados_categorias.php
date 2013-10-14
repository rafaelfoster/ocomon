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
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$qry_config = "SELECT * FROM config ";
	$exec_config = mysql_query($qry_config) or die (TRANS('ERR_QUERY'));
	$row_config = mysql_fetch_array($exec_config);
	$criterio = "";

	if (!isset($_POST['ok']) && !isset($_GET['action'])  ) { //&& $_POST['ok'] != 'Pesquisar')
		print "<html>";
		print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
		print "	<BR><BR>";
		print "	<B><center>:::".TRANS('TTL_REP_CALL_X_PROBCAT').":::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit=\"return valida()\" >"; //onSubmit=\"return valida()\"
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";

		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_AREA').":</td>";
		print "					<td class='line'><Select name='area' class='select'>";
		print "							<OPTION value=-1 selected>".TRANS('OPT_ALL')."</OPTION>";
										$query="select * from sistemas where sis_status not in (0) order by sistema";
										$resultado=mysql_query($query);
										$linhas = mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											print "<option value=".$row['sis_id']."";
											if ($row['sis_id']==$_SESSION['s_area']) print " selected";
											print ">".$row['sistema']."</option>";
										} // while
		print "		 				</Select>";
		print "					 </td>";
		print "				</tr>";

		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".$row_config['conf_prob_tipo_1'].":</td>";
		print "					<td class='line'><Select name='cat1' class='select'>";
		print "							<OPTION value=-1 selected>".TRANS('OPT_ALL')."</OPTION>";
										$query="select * from prob_tipo_1 order by probt1_desc";
										$resultado=mysql_query($query);
										$linhas = mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											print "<option value=".$row['probt1_cod'].">".$row['probt1_desc']."</option>";
										} // while
		print "		 				</Select>";
		print "					 </td>";
		print "				</tr>";


		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".$row_config['conf_prob_tipo_2'].":</td>";
		print "					<td class='line'><Select name='cat2' class='select'>";
		print "							<OPTION value=-1 selected>".TRANS('OPT_ALL')."</OPTION>";
										$query="select * from prob_tipo_2 order by probt2_desc";
										$resultado=mysql_query($query);
										$linhas = mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											print "<option value=".$row['probt2_cod'].">".$row['probt2_desc']."</option>";
										} // while
		print "		 				</Select>";
		print "					 </td>";
		print "				</tr>";


		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".$row_config['conf_prob_tipo_3'].":</td>";
		print "					<td class='line'><Select name='cat3' class='select'>";
		print "							<OPTION value=-1 selected>".TRANS('OPT_ALL')."</OPTION>";
										$query="select * from prob_tipo_3 order by probt3_desc";
										$resultado=mysql_query($query);
										$linhas = mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											print "<option value=".$row['probt3_cod'].">".$row['probt3_desc']."</option>";
										} // while
		print "		 				</Select>";
		print "					 </td>";
		print "				</tr>";



		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_BEGIN').":</td>";
		print "					<td class='line'><INPUT name='d_ini' class='data' value='01-".date("m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_ini,'dd-mm-yyyy',this)\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_FINISH').":</td>";
		print "					<td class='line'><INPUT name='d_fim' class='data' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_fim,'dd-mm-yyyy',this)\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
		print "				</tr>";

		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('FIELD_REPORT_TYPE').":</td>";
		print "					<td class='line'><select name='saida' class='data'>";
		print "							<option value=-1 selected>".TRANS('SEL_PRIORITY_NORMAL')."</option>";
		//	print "							<option value=1>Relatório 1 linha</option>";
		print "						</select>";
		print "					</td>";
		print "				</tr>";
		print "		</TABLE><br>";
		print "		<TABLE align='center'>";
		print "			<tr>";
		print "	            <td class='line'>";
		print "					<input type='submit' class='button' value='".TRANS('BT_SEARCH')."' name='ok' >";//onclick='ok=sim'
		print "	            </TD>";
		print "	            <td class='line'>";
		print "					<INPUT type='reset' class='button' value='".TRANS('BT_CLEAR')."' name='cancelar'>";
		print "				</TD>";
		print "			</tr>";
		print "	    </TABLE>";
		print " </form>";
	}  else

	if (isset($_POST['ok'])) {//if $ok!=Pesquisar

		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';


		$query = "SELECT count(*)  AS quantidade, s.sistema AS area, s.sis_id,  p.problema as problema, pt1.*, pt2.*, pt3.* ".
					"FROM ocorrencias AS o, sistemas AS s, problemas as p ".
					"LEFT JOIN prob_tipo_1 as pt1 on pt1.probt1_cod = p.prob_tipo_1 ".
					"LEFT JOIN prob_tipo_2 as pt2 on pt2.probt2_cod = p.prob_tipo_2 ".
					"LEFT JOIN prob_tipo_3 as pt3 on pt3.probt3_cod = p.prob_tipo_3 ".
					"WHERE o.sistema = s.sis_id AND o.problema = p.prob_id ";

		if (isset($_POST['area']) && !empty($_POST['area']) && ($_POST['area'] != -1))
		{
			$query .= " and o.sistema = '".$_POST['area']."'";
			$qry_criterio = "SELECT sistema FROM sistemas WHERE sis_id = ".$_POST['area']." ";
			$exec_criterio = mysql_query($qry_criterio);
			$row_criterio = mysql_fetch_array($exec_criterio);
			$criterio .= " Área= ".$row_criterio['sistema'].",";
		}

		if (isset($_POST['cat1']) && ($_POST['cat1'] != -1))
		{
			$query .= " and pt1.probt1_cod = '".$_POST['cat1']."' ";

			$qry_criterio = "SELECT probt1_desc FROM prob_tipo_1 WHERE probt1_cod = ".$_POST['cat1']." ";
			$exec_criterio = mysql_query($qry_criterio);
			$row_criterio = mysql_fetch_array($exec_criterio);
			$criterio .= " ".$row_config['conf_prob_tipo_1']."= ".$row_criterio['probt1_desc'].",";

		}

		if (isset($_POST['cat2']) && ($_POST['cat2'] != -1))
		{
			$query .= " and pt2.probt2_cod = '".$_POST['cat2']."' ";
			$qry_criterio = "SELECT probt2_desc FROM prob_tipo_2 WHERE probt2_cod = ".$_POST['cat2']." ";
			$exec_criterio = mysql_query($qry_criterio);
			$row_criterio = mysql_fetch_array($exec_criterio);
			$criterio .= " ".$row_config['conf_prob_tipo_2']."= ".$row_criterio['probt2_desc'].",";

		}

		if (isset($_POST['cat3']) && ($_POST['cat3'] != -1))
		{
			$query .= " and pt3.probt3_cod = '".$_POST['cat3']."' ";
			$qry_criterio = "SELECT probt3_desc FROM prob_tipo_3 WHERE probt3_cod = ".$_POST['cat3']." ";
			$exec_criterio = mysql_query($qry_criterio);
			$row_criterio = mysql_fetch_array($exec_criterio);
			$criterio .= " ".$row_config['conf_prob_tipo_3']."= ".$row_criterio['probt3_desc'].",";

		}

		if (strlen($criterio)==0) {
			$criterio = "Todos";
		} else {
			$criterio = substr($criterio, 0 , -1);
		}

		if ((empty($_POST['d_ini'])) || (empty($_POST['d_fim'])))
		{
			print "<script>window.alert('".TRANS('MSG_PERIOD_INFO')."'); history.back();</script>";
		} else {
			$d_ini_nova = converte_dma_para_amd($_POST['d_ini']);
			$d_fim_nova = converte_dma_para_amd($_POST['d_fim']);

			$d_ini_completa = $d_ini_nova.$hora_inicio;
			$d_fim_completa = $d_fim_nova.$hora_fim;


			if($d_ini_completa <= $d_fim_completa) {
				print "<table class='centro' cellspacing='0' border='0' align='center'>";
				print "<tr><td colspan='2'><b>".TRANS('TTL_PERIOD_FROM')." ".$_POST['d_ini']." a ".$_POST['d_fim']."</b></td></tr>";
				print "</table>";

			$query .= " and o.data_fechamento >= '".$d_ini_completa."' and o.data_fechamento <= '".$d_fim_completa."' and ".
						"o.data_atendimento is not null ".
					"GROUP  BY s.sistema, pt1.probt1_cod, pt2.probt2_cod, pt3.probt3_cod order by area, quantidade desc ".
					" ";

			//print $query; exit;
			$resultado = mysql_query($query);
			$linhas = mysql_num_rows($resultado);

			if ($linhas==0) {

				print "<script>window.alert('".TRANS('MSG_NO_REGISTER_PERIOD')."'); history.back();</script>";
			} else { //if($linhas==0)
				$campos=array();
				switch($_POST['saida'])
				{
					case -1:

					echo "<br><br>";
					$background = '#CDE5FF';
					print "<p class='titulo'>".TRANS('TTL_REP_CALL_CLOSED_X_PROBCAT')."</p>";
					print "<table class='centro' cellspacing='0' border='1' align='center' >";

					print "<tr><td colspan='5' align='center'>".TRANS('FIELD_CRITE_EXIBIT').": ".$criterio."</td></tr>";
					print "<tr><td colspan='5'>&nbsp;</td></tr>";
					print "<tr><td bgcolor='".$background."'><B>".TRANS('COL_QTD')."</td>".
							"<td bgcolor='".$background."'><B>".TRANS('COL_ATTEN_AREA')."</td>".
							"<td bgcolor='".$background."'><B>".$row_config['conf_prob_tipo_1']."</td>".
							"<td bgcolor='".$background."'><B>".$row_config['conf_prob_tipo_2']."</td>".
							"<td bgcolor='".$background."'><B>".$row_config['conf_prob_tipo_3']."</td>".
						"</tr>";
					$total = 0;
					while ($row = mysql_fetch_array($resultado)) {

						print "<tr>";
						print "<td class='line'><a onClick=\"javascript: popup_alerta('".$_SERVER['PHP_SELF']."?action=list&area=".$row['sis_id']."&p1=".$row['probt1_cod']."&p2=".$row['probt2_cod']."&p3=".$row['probt3_cod']."&date1=".$d_ini_completa."&date2=".$d_fim_completa."')\">".$row['quantidade']."</a></td>".
								"<td class='line'>".$row['area']."</td><td class='line'>".NVL($row['probt1_desc'])."</td>".
								"<td class='line'>".NVL($row['probt2_desc'])."</td><td class='line'>".NVL($row['probt3_desc'])."</td> ";
						print "</tr>";
						$total+=$row['quantidade'];

					}

					print "<tr><td colspan='2'><b>".TRANS('TOTAL')."</b></td><td colspan='3'><b>".$total."</b></td></tr>";

					break;

					case 1:
					$campos=array();
					$campos[]="numero";
					$campos[]="data_abertura";
					$campos[]="data_atendimento";
					$campos[]="data_fechamento";
					$campos[]="t_res_hora";
					$campos[]="t_sol_hora";
					$campos[]="t_res_valida_hor";
					$campos[]="t_sol_valida_hor";

					$cabs=array();
					$cabs[]="Número";
					$cabs[]="Abertura";
					$cabs[]="1ª Resposta";
					$cabs[]="Fechamento";
					$cabs[]="T Resposta Total";
					$cabs[]="T Solução Total";
					$cabs[]="T Resposta Válido";
					$cabs[]="T Solução Válido";

					$logo="logo_unilasalle.gif";
					$msg1="Centro de Informática";
					$msg2=date('d/m/Y H:m');
					$msg3= "Relatório de SLA's";

					gera_relatorio(1,$query,$campos,$cabs,$logo,$msg1, $msg2, $msg3);
					break;
				} // switch
				} //if($linhas==0)
			}   else { //if  $d_ini_completa <= $d_fim_completa
			print "<script>window.alert('".TRANS('MSG_COMPARE_DATE')."'); history.back();</script>";
			}
		}//if ((empty($d_ini)) and (empty($d_fim)))
	} else

	if (isset($_GET['action']) && $_GET['action'] == "list"){

		$catArea = $_GET['area'];

		if ($_GET['p1'] != "") $catP1 = " = ".$_GET['p1']; else $catP1 = " IS NULL";
		if ($_GET['p2'] != "") $catP2 = " = ".$_GET['p2']; else $catP2 = " IS NULL";
		if ($_GET['p3'] != "") $catP3 = " = ".$_GET['p3']; else $catP3 = " IS NULL";

		$qryCat = "SELECT o.numero, o.data_fechamento, s.sistema AS area, p.problema as problema, pt1.*, pt2.*, pt3.*, ".
					"l.local as localizacao, u.nome ".
				"FROM ocorrencias AS o, sistemas AS s, usuarios as u, localizacao as l, problemas as p ".
					"LEFT JOIN prob_tipo_1 as pt1 on pt1.probt1_cod = p.prob_tipo_1 ".
					"LEFT JOIN prob_tipo_2 as pt2 on pt2.probt2_cod = p.prob_tipo_2 ".
					"LEFT JOIN prob_tipo_3 as pt3 on pt3.probt3_cod = p.prob_tipo_3 ".
				"WHERE o.sistema = s.sis_id AND o.problema = p.prob_id ".
					"and o.data_fechamento >= '".$_GET['date1']."' ".
					"and o.data_fechamento <= '".$_GET['date2']."' ".
					"and o.data_atendimento is not null ".
					"and s.sis_id = ".$_GET['area']." ".
					"and pt1.probt1_cod ".$catP1." ".
					"and pt2.probt2_cod ".$catP2." ".
					"and pt3.probt3_cod ".$catP3." ".
					"and o.operador = u.user_id and o.local = l.loc_id ".
					"order by area ".
					"";
		$execCat = mysql_query($qryCat) or die(mysql_error());
		$linhas = mysql_num_rows($execCat);

		print "<table border='0' cellspacing='1' summary=''";
		print "<TR>";
		print "<TD colspan='3' align='left' ><B>".TRANS('FOUND')." ".$linhas." ".TRANS('TXT_REG_OF_CLOSED_CALLS').":</B></TD>";
		print "</tr>";
		print "<tr><td>&nbsp;</td></tr>";
		print "</table>";

		print "<TABLE border='0' cellpadding='5' cellspacing='0' align='left' width='100%'>";

		print "<TR class='header'>".
				"<TD class='line'>".TRANS('COL_AREA')."</TD>".
				"<TD class='line'>".TRANS('COL_NUMBER')."</TD>".
				"<TD class='line'>".TRANS('OCO_PROB')."</TD>".
				"<TD class='line'>".TRANS('COL_LOCAL')."</TD>".
				"<TD class='line'>".TRANS('TECHNICIAN')."</TD>".
				"<TD class='line'>".TRANS('FIELD_DATE_CLOSING')."</TD>";
		$i=0;
		$j=2;
		while ($rowlist = mysql_fetch_array($execCat))
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
			print "<td class='line'>".$rowlist['area']."</td>";
			print "<td class='line'><a onClick=\"javascript: popup_alerta('mostra_consulta.php?numero=".$rowlist['numero']."')\">".NVL($rowlist['numero'])."</a></td>";
			print "<td class='line'>".NVL($rowlist['problema'])."</td>";
			print "<td class='line'>".NVL($rowlist['localizacao'])."</td>";
			print "<td class='line'>".NVL($rowlist['nome'])."</td>";
			print "<td class='line'>".NVL(formatDate($rowlist['data_fechamento']))."</td>";


			print "</TR>";
		}
		print "</table>";
	}

	?>
        <script type='text/javascript'>

			 function popup(pagina)	{ //Exibe uma janela popUP
				x = window.open(pagina,'popup','width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
				//x.moveTo(100,100);
				x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
				return false
			 }

			 function popup_alerta(pagina)	{ //Exibe uma janela popUP
                x = window.open(pagina,'_blank','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
                //x.moveTo(100,100);
                x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
                return false
             }
        </script>
<?php 
		print "</BODY>";
    		print "</html>";

?>
