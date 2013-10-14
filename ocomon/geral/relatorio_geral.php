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

	print "<html>";

	if (!isset($_POST['ok'])) { //&& $_POST['ok'] != 'Pesquisar')

		print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
		print "<body>";
		print "	<BR><BR>";
		print "	<B><center>:::".TRANS('REP_GENERAL').":::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit=\"return valida()\" >"; //onSubmit=\"return valida()\"
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_AREA').":</td>";


		print "					<td class='line'><Select name='area' class='select' size=1 onChange=\"fillSelectFromArray(this.form.operador, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));\">";
		print "							<OPTION value=-1 selected".TRANS('OPT_ALL')."</OPTION>";
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


		print "			<TR>";
		print "				<TD bgcolor=".TD_COLOR.">".TRANS('OCO_PROB').":</TD>";
		print "			  		<td class='line'><SELECT  class='select' NAME='problema'>";
		print "						<OPTION value=-1 selected>".TRANS('OPT_ALL_2')."</OPTION>";
										$query = "select * from problemas order by problema";
										$resultado=mysql_query($query);
										$linhas=mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											$prob_id=$row['prob_id'];
											$prob_name=$row['problema'];
											print "<OPTION value=".$prob_id."> $prob_name </OPTION>";
										} // while
		print "	 					</SELECT>";
		print "					 </TD>";
		print "				</TR>";

		print "				<tr>";
		print "						<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_UNIT').":</td>";
		print "						<td class='line'><Select class='select'  name='instituicao' size=1>";
		print "								<OPTION value=-1 selected".TRANS('OPT_ALL_2')."</OPTION>";
										$query="select * from instituicao";
										$resultado=mysql_query($query);
										$linhas = mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											$insti_id=$row['inst_cod'];
											$insti_name=$row['inst_nome'];
											print "<option value=$insti_id>$insti_name</option>";
										} // while
		print "			 				</Select>";
		print "						 </td>";
		print "					</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_LOCAL').":</td>";
		print "					<td class='line'><select  class='select' name='local' size=1> ";
		print "							<option value=-1  selected".TRANS('OPT_ALL_2')."</option>";
										$query="select * from localizacao order by local";
										$resultado=mysql_query($query);
										while($row=mysql_fetch_array($resultado))
										{
										$local_id=$row['loc_id'];
											$local_name=$row['local'];
											print "<option value=".$local_id.">".$local_name."</option>";
										}
		print "		 				</select>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_OPERATOR').":</td>";
		print "					<td class='line'><select  class='select' name='operador' size=1>";
		print "							<option value=-1 selected".TRANS('OPT_ALL_2')."</option>";
										$query="select * from usuarios order by nome";
										$resultado=mysql_query($query);
										while($row=mysql_fetch_array($resultado))
										{
											//$operador=$row['login'];
											print "<option value=$row[user_id]>$row[nome]</option>";
										}
		print "		 				</select>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_BEGIN').":</td>";
		//print "					<td class='line'><INPUT  type='text' class='data' name='d_ini'>  (Exemplo: 20/01/2004)</td>";
		print "					<td><INPUT type='text' name='d_ini' class='data' id='idD_ini' value='01-".date("m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_ini,'dd-mm-yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_FIELD_DATE_FINISH').":</td>";
		//print "					<td class='line'><INPUT type='text'  class='data' name='d_fim'>  (Exemplo: 27/01/2004)</td>";
		print "					<td><INPUT type='text' name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_fim,'dd-mm-yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='".TRANS('HNT_SEL_DATE')."'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_DATE_THIS').":</td>";
		print "					<td class='line'><SELECT class='select'  name='tipo_data'>";
		//print "							<OPTION value=-1 selected".TRANS('OPT_ALL_2')."</OPTION>";
		print "							<option value=1 selected>".TRANS('OCO_SEL_OPEN')."</option>";
		print "							<option value=2>".TRANS('OCO_SEL_CLOSE')."</option>";
		print "						</SELECT>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('MNL_STATUS').":</td>";
		print "					<td class='line'><select  class='select' name='status_oco'>";
		print "							<option value=-1 selected".TRANS('OPT_ALL_2')."</option>";
										$query="select * from status order by status";
										$resultado=mysql_query($query);
										while($row=mysql_fetch_array($resultado))
										{
											$status_id=$row['stat_id'];
											$status_name=$row['status'];
											print "<option value=$status_id>$status_name</option>";
										}
		print "						</select>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_ORDER_BY').":</td>";
		print "					<td class='line'><SELECT  class='select' name='ordem' size=1>";
		print "							<option value='numero' selected>".TRANS('OCO_FIELD_NUMBER')."</option>";
		print "							<option value='problema'>".TRANS('OCO_PROB')."</option>";
		print "							<option value='sistema'>".TRANS('OCO_FIELD_AREA')."</option>";
		print "							<option value='instituicao'>".TRANS('OCO_FIELD_UNIT')."</option>";
		print "							<option value='local'>".TRANS('OCO_FIELD_LOCAL')."</option>";
		print "							<option value='operador'>".TRANS('OCO_FIELD_OPERATOR')."</option>";
		print "							<option value='data_abertura'>".TRANS('OCO_DATE')."</option>";
		print "						</SELECT>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('FIELD_REPORT_TYPE').":</td>";
		print "					<td class='line'><select  class='select' name='saida' size=1>";
		print "							<option value=-1 selected>".TRANS('SEL_PRIORITY_NORMAL')."</option>";
		print "							<option value=1>".TRANS('FIELD_REPORT_ONE_LINE')."</option>";
		print "						</select>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>
							<td colspan='2'><input type='checkbox' name='novaJanela' title='".TRANS('HNT_NEW_WINDOW').".'>".TRANS('OPT_NEW_WINDOW')."
							</td>
						</tr>";

		print "		</TABLE><br>";
		print "		<TABLE align='center'>";
		print "			<tr>";
		print "	            <td class='line'>";
		print "					<input type='submit' class='button' value='".TRANS('BT_SEARCH')."' name='ok' onClick=\"submitForm();\">";//onclick='ok=sim'
		print "	            </TD>";
		print "	            <td class='line'>";
		print "					<INPUT type='reset' class='button' value='".TRANS('BT_CLEAR')."' name='cancelar'>";
		print "				</TD>";
		print "			</tr>";
		print "	    </TABLE>";
		print " </form>";
	}  else {//if $ok!=Pesquisar

		print "<body>";

		$linhas=-1;
		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';           // letra.campo_tab as apelido

		//$query_ini = $QRY["ocorrencias_full_ini"];


		$query_ini = $QRY["ocorrencias_full_ini"];
                $query = "";

		if (isset($_POST['problema']) && !empty($_POST['problema']) && ($_POST['problema'] != -1))
		{
			$query .= " and o.problema = '".$_POST['problema']."'";
		}

		if (isset($_POST['area']) && !empty($_POST['area']) && ($_POST['area'] != -1))
		{
			$query .= " and o.sistema = '".$_POST['area']."'";
		}

		if (isset($_POST['instituicao']) && !empty($_POST['instituicao']) && ($_POST['instituicao'] != -1))
		{
			$query .= " and o.instituicao = '".$_POST['instituicao']."'";
		}

		if (isset($_POST['local']) && !empty($_POST['local']) && ($_POST['local'] != -1))
		{
			$query .= " and o.local = '".$_POST['local']."'";
		}

		if (isset($_POST['operador']) && !empty($_POST['operador']) && ($_POST['operador'] != -1))
		{
			$query .= " and o.operador = '".$_POST['operador']."'";
		}

		if (isset($_POST['status_oco']) && !empty($_POST['status_oco']) && ($_POST['status_oco'] != -1))
		{
			$query .= " and o.status = '".$_POST['status_oco']."'";
		}


                if ($_POST['tipo_data']==1)
                {
                        if (!empty($_POST['d_ini']) )
                        {
                                $data_inicial = str_replace("-","/",$_POST['d_ini']);
				$data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:01";
                                $query.=" and o.data_abertura>='".$data_inicial."' ";
                        }

                        if (!empty($_POST['d_fim']))
                        {
                                $data_final = str_replace("-","/",$_POST['d_fim']);
				$data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.=" and o.data_abertura<='".$data_final."'";
                        }
                }
                else
                if ($_POST['tipo_data']==2)
                {
                        if (!empty($_POST['d_ini']) )
                        {
                                $data_inicial = str_replace("-","/",$_POST['d_ini']);
				$data_inicial = substr(datam($data_inicial),0,10);
                                $data_inicial.=" 00:00:01";
                                $query.=" and o.data_fechamento>='".$data_inicial."' ";
                        }

                        if (!empty($_POST['d_fim']))
                        {
                                $data_final = str_replace("-","/",$_POST['d_fim']);
				$data_final = substr(datam($data_final),0,10);
                                $data_final.=" 23:59:59";
                                $query.=" and o.data_fechamento<='".$data_final."'";
                        }

                }





			$query .= " order by ".$_POST['ordem'].""; //   print "<h2>$query</h2>";

			if (strlen($query)>0) {
				$query_ini.=" WHERE o.numero = o.numero ".$query;
			}

			//print $query_ini;

			$resultado = mysql_query($query_ini) or die(''.TRANS('ERR_QUERY').'<br>'.$query_ini);    //   print "<b>Query--></b> $query<br><br>";
			$linhas = mysql_num_rows($resultado);

// 		} else { //(($d_ini < $d_fim) or ($d_ini == $d_fim))
// 			print "<script>window.alert('".TRANS('MSG_COMPARE_DATE')."'); history.back();</script>";
//   		}

		switch($linhas) {
			case 0:
				print "<script>mensagem('".TRANS('MSG_NONE_REGISTER')."); redirect('".$_SERVER['PHP_SELF']."');</script>";
				$frase = "";
				break;
			case 1:
				$frase= "".TRANS('MSG_REGISTER_FIND_ONLY')." <font color=red>1</font> ".TRANS('OCO_OCORRENCIA')."";
			break;
			case -1:
			default:
				$frase="".TRANS('MSG_REGISTER_FIND')." <font color=red>".$linhas."</font> ".TRANS('OCO_OCORRENCIAS').".";
		} // switch
	//print $query."<br>";
		switch($_POST['saida'])
		{
			case -1:
				$cor1=TD_COLOR;
				print "<BR><BR><B>::: ".TRANS('TLT_REPORT_OCCOR')." :::</B><BR><BR>";
				print "<fieldset><legend><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>".$frase."</font></font></legend>";
	       			print "<table border='0' cellpadding='3' cellspacing='0' align='center'>";
				print "<tr>";
				print "<td bgcolor='".$cor1."' align=left width='12%'> <font size='2'><b>".TRANS('OCO_FIELD_NUMBER')."</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='12%'><font size='2'><b>".TRANS('OCO_PROB')."</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='12%'><font size='2'><b>".TRANS('COL_SCRIPT_SOLUTION')."</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='12%'><font size='2'><b>".TRANS('OCO_AREA')."</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='12%'> <font size='2'><b>".TRANS('OCO_FIELD_UNIT')."</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='12%'><font size='2'><b>".TRANS('OCO_LOCAL')."</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='12%'> <font size='2'><b>".TRANS('OCO_FIELD_OPERATOR')."</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='12%'><font size='2'><b>".TRANS('OCO_FIELD_DATE_BEGIN')."</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='12%'><font size='2'><b>".TRANS('OCO_FIELD_DATE_FINISH')."</b></font></td>";
				print "</tr>";
				$i=0;
		       		$j=2;
		       		while ($row = mysql_fetch_array($resultado)) {
					if ($j % 2) {
						$color =  BODY_COLOR;
					} else {
					$color = 'white';
					}
		           		$j++;
					print "<tr>";
					print "<td align=left bgcolor='".$color."'><a onClick=\"javascript:popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\">".$row['numero']."</a></td>"; //nome q eu dei no select name=
					print "<td align=left bgcolor='".$color."'>".$row['problema']."</td>";
					print "<td align=left bgcolor='".$color."'>".$row['script_desc']."</td>";
					print "<td align=left bgcolor='".$color."'>".$row['area']."</td>";
					if ($row['unidade_cod']=='')
					{
						print "<td align=center bgcolor='".$color."'> - </td>";
					}
					else {
						print "<td align=left bgcolor='".$color."'> " . $row['unidade'] . "</td>";
					}
					print "<td align=left bgcolor='".$color."'> ".$row['setor']."</td>";
					print "<td align=left bgcolor='".$color."'> ".$row['nome']."</td>";
					print "<td align=left bgcolor='".$color."'> ".converte_datacomhora($row['data_abertura'])."</td>";

					if ($row['data_fechamento'] == null)
					{
						print "<td align=center bgcolor='".$color."'>  -  </td>";
					}
					else
					{
			            		print "<td align=left bgcolor='".$color."'> ".converte_datacomhora($row['data_fechamento'])."</td>";
					}
		           	 	print "</tr>";
		        	} // while
		       		print "</table>";
				print "</fieldset>";
				break;

			case 1:
				$campos=array();
				$campos[]="numero";
				$campos[]="problema";
				$campos[]="area";
				$campos[]="unidade";
				$campos[]="setor";
				$campos[]="nome";
				$campos[]="data_abertura";
				$campos[]="data_fechamento";

				$cabs=array();
				$cabs[]=TRANS('OCO_FIELD_NUMBER');
				$cabs[]=TRANS('OCO_PROB');
				$cabs[]=TRANS('OCO_FIELD_AREA');
				$cabs[]=TRANS('OCO_FIELD_UNIT');
				$cabs[]=TRANS('OCO_LOCAL');
				$cabs[]=TRANS('OCO_FIELD_OPERATOR');
				$cabs[]=TRANS('OCO_FIELD_DATE_OPEN');
				$cabs[]=TRANS('OCO_DATE_CLOSING');

				$hoje=date('d/m/Y H:m');

				gera_relatorio(1,$query_ini,$campos,$cabs,"../../includes/logos/logo_unilasalle.gif","OCOMON", $hoje, "Relatório de Ocorrências");
				break;
		} // switch
	} //if $ok==Pesquisar

	print "</BODY>";
	print "</html>";


?>
<script type='text/javascript'>

<!--

		function checar() {
			var checado = false;
			if (document.form1.novaJanela.checked){
		      	checado = true;
				//document.form1.target = "_blank";

			} else {
		      	checado = false;
				//document.form1.target = "";
			}
			return checado;
		}

		window.setInterval("checar()",1000);

		function submitForm()
		{

			if (checar() == true) {
				document.form1.target = "_blank";
				document.form1.submit();
			} else {
				document.form1.target = "";
				document.form1.submit();
			}

		}

-->
</script>