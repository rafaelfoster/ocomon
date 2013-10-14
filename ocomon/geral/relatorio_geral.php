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

	if (!isset($_POST['ok'])) { //&& $_POST['ok'] != 'Pesquisar')
		print "<html>";
		print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
		print "	<BR><BR>";
		print "	<B><center>:::Relatório Geral:::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit=\"return valida()\" >"; //onSubmit=\"return valida()\"
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Área Responsável:</td>";


		print "					<td class='line'><Select name='area' class='select' size=1 onChange=\"fillSelectFromArray(this.form.operador, ((this.selectedIndex == -1) ? null : team[this.selectedIndex-1]));\">";
		print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
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
		print "				<TD bgcolor=".TD_COLOR.">Problema:</TD>";
		print "			  		<td class='line'><SELECT  class='select' NAME='problema'>";
		print "						<OPTION value=-1 selected>-->Todos<--</OPTION>";
										$query = "select * from problemas order by problema";
										$resultado=mysql_query($query);
										$linhas=mysql_num_rows($resultado);
										while($row=mysql_fetch_array($resultado))
										{
											$prob_id=$row['prob_id'];
											$prob_name=$row['problema'];
											print "<OPTION value=$prob_id> $prob_name </OPTION>";
										} // while
		print "	 					</SELECT>";
		print "					 </TD>";
		print "				</TR>";

		print "				<tr>";
		print "						<td bgcolor=".TD_COLOR.">Unidade:</td>";
		print "						<td class='line'><Select class='select'  name='instituicao' size=1>";
		print "								<OPTION value=-1 selected>-->Todos<--</OPTION>";
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
		print "					<td bgcolor=".TD_COLOR.">Local:</td>";
		print "					<td class='line'><select  class='select' name='local' size=1> ";
		print "							<option value=-1  selected>-->Todos<--</option>";
										$query="select * from localizacao order by local";
										$resultado=mysql_query($query);
										while($row=mysql_fetch_array($resultado))
										{
										$local_id=$row['loc_id'];
											$local_name=$row['local'];
											print "<option value=$local_id>$local_name</option>";
										}
		print "		 				</select>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Operador:</td>";
		print "					<td class='line'><select  class='select' name='operador' size=1>";
		print "							<option value=-1 selected>-->Todos<--</option>";
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
		print "					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
		//print "					<td class='line'><INPUT  type='text' class='data' name='d_ini'>  (Exemplo: 20/01/2004)</td>";
		print "					<td><INPUT type='text' name='d_ini' class='data' id='idD_ini' value='01-".date("m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_ini,'dd-mm-yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
		//print "					<td class='line'><INPUT type='text'  class='data' name='d_fim'>  (Exemplo: 27/01/2004)</td>";
		print "					<td><INPUT type='text' name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_fim,'dd-mm-yyyy',this)\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Data de:</td>";
		print "					<td class='line'><SELECT class='select'  name='tipo_data'>";
		print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
		print "							<option value=1>Abertura</option>";
		print "							<option value=2>Encerramento</option>";
		print "						</SELECT>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Status:</td>";
		print "					<td class='line'><select  class='select' name='status_oco'>";
		print "							<option value=-1 selected>-->Todos<--</option>";
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
		print "					<td bgcolor=".TD_COLOR.">Ordena por:</td>";
		print "					<td class='line'><SELECT  class='select' name='ordem' size=1>";
		print "							<option value='numero' selected>Número</option>";
		print "							<option value='problema'>Problema</option>";
		print "							<option value='sistema'>Área Responsável</option>";
		print "							<option value='instituicao'>Unidade</option>";
		print "							<option value='local'>Local</option>";
		print "							<option value='operador'>Operador</option>";
		print "							<option value='data_abertura'>Data</option>";
		print "						</SELECT>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">Tipo de relatório:</td>";
		print "					<td class='line'><select  class='select' name='saida' size=1>";
		print "							<option value=-1 selected>Normal</option>";
		print "							<option value=1>Relatório 1 linha</option>";
		print "						</select>";
		print "					</td>";
		print "				</tr>";
		print "				<tr>
							<td colspan='2'><input type='checkbox' name='novaJanela' title='Selecione para que a saída seja em uma nova janela.'>Nova Janela (para impressão)
							</td>
						</tr>";

		print "		</TABLE><br>";
		print "		<TABLE align='center'>";
		print "			<tr>";
		print "	            <td class='line'>";
		print "					<input type='submit' class='button' value='Pesquisar' name='ok' onClick=\"submitForm();\">";//onclick='ok=sim'
		print "	            </TD>";
		print "	            <td class='line'>";
		print "					<INPUT type='reset' class='button' value='Limpar campos' name='cancelar'>";
		print "				</TD>";
		print "			</tr>";
		print "	    </TABLE>";
		print " </form>";
		print "</BODY>";
		print "</html>";
	}  else {//if $ok!=Pesquisar

		$linhas=-1;
		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';           // letra.campo_tab as apelido
		$query = "SELECT ".
					"o.numero, o.problema as oco_problema, p.problema, ".
					"o.sistema as oco_sistema, s.sistema, ".
					"o.instituicao as oco_instituicao, i.inst_nome, ".
					"o.local as oco_local, l.local, ".
					"o.operador, u.login, u.nome, ".
					"o.data_abertura, o.data_fechamento, ".
					"o.status, st.stat_id ".
				"FROM ".
					"((ocorrencias as o left join sistemas as s on s.sis_id=o.sistema) ".
					"left join instituicao as i on o.instituicao=i.inst_cod), ".
					"problemas as p, localizacao as l, usuarios as u, `status` as st ".
				"WHERE ".
					"o.problema=p.prob_id and o.local=l.loc_id and o.operador=u.user_id and o.status=st.stat_id";


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

		if (!isset($_POST['d_ini']) || $_POST['d_ini'] == "")
		{
			$d_ini = '01/03/2002';
		} else
			$d_ini = $_POST['d_ini'];

		if (!isset($_POST['d_fim']) || $_POST['d_fim'] == "")
		{
			$hoje = getdate();
			$mes = $hoje['mon'];
			$dia = $hoje['mday'];
			$ano = $hoje['year'];
			$d_fim = $dia."/".$mes."/".$ano;
		} else
			$d_fim = $_POST['d_fim'];

		if (($d_ini <= $d_fim) )
		{
			$d_ini_nova = converte_dma_para_amd($d_ini);
			$d_fim_nova = converte_dma_para_amd($d_fim);

			$d_ini_completa = $d_ini_nova.$hora_inicio;
			$d_fim_completa = $d_fim_nova.$hora_fim;

			switch ($_POST['tipo_data'])
			{
				case -1:
				$query .= " and o.data_abertura>='".$d_ini_completa."' and o.data_abertura<='".$d_fim_completa."' ".
							"and (o.data_fechamento>='".$d_ini_completa."' or o.data_fechamento is null) ".
							"and (o.data_fechamento<='".$d_fim_completa."' or o.data_fechamento is null)";
				break;
				case 1:
				$query .= " and o.data_abertura>='".$d_ini_completa."' and o.data_abertura<='".$d_fim_completa."'";
				break;
				case 2:
				$query .= " and (o.data_fechamento>='".$d_ini_completa."' or o.data_fechamento is null) ".
							"and (o.data_fechamento<='".$d_fim_completa."' or o.data_fechamento is null)";
				break;
			} // switch

			$query .= " order by ".$_POST['ordem'].""; //   print "<h2>$query</h2>";
			//print $query."<br>";
			$resultado = mysql_query($query) or die('ERRO NA TENTATIVA DE RECUPERAR AS INFORMAÇÕES DE CONSULTA!<br>'.$query);    //   print "<b>Query--></b> $query<br><br>";
			$linhas = mysql_num_rows($resultado);
		} else { //(($d_ini < $d_fim) or ($d_ini == $d_fim))
			print "<script>window.alert('A data final não pode ser menor do que a data inicial!'); history.back();</script>";
  		}

		switch($linhas) {
			case 0:
				print "<script>mensagem('Nenhuma registro encontrado!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
				break;
			case 1:
				$frase= "Foi encontrada somente <font color=red>1</font> ocorrência.";
			break;
			case -1:
			default:
				$frase="Foram encontradas <font color=red>".$linhas."</font> ocorrências.";
		} // switch
	//print $query."<br>";
		switch($_POST['saida'])
		{
			case -1:
				$cor1=TD_COLOR;
				print "<BR><BR><B>::: Relatório de ocorrências :::</B><BR><BR>";
				print "<fieldset><legend><FONT FACE=Arial, sans-serif><FONT SIZE=2 STYLE=font-size: 9pt>".$frase."</font></font></legend>";
	       			print "<table border=0 cellpadding=3 cellspacing=0 align=center>";
				print "<tr>";
				print "<td bgcolor='".$cor1."' align=left width='48'> <font size='2'><b>Número</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='170'><font size='2'><b>Problema</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='150'><font size='2'><b>Área</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='90'> <font size='2'><b>Unidade</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='150'><font size='2'><b>Local</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='50'> <font size='2'><b>Operador</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='125'><font size='2'><b>Data Início</b></font></td>";
				print "<td bgcolor='".$cor1."' align=left width='110'><font size='2'><b>Data Fim</b></font></td>";
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
					print "<td width=48  align=left bgcolor='".$color."'> ".$row['numero']."  </td>"; //nome q eu dei no select name=
					print "<td width=170 align=left bgcolor='".$color."'> ".$row['problema']."</td>";
					print "<td width=150 align=left bgcolor='".$color."'> ".$row['sistema']." </td>";
					if ($row['inst_nome']=='')
					{
						print "<td width=90 align=center bgcolor='".$color."'> - </td>";
					}
					else {
						print "<td width=90 align=left bgcolor='".$color."'> " . $row['inst_nome'] . "</td>";
					}
					print "<td width=150 align=left bgcolor='".$color."'> ".$row['local']."</td>";
					print "<td width=50 align=left bgcolor='".$color."'> ".$row['nome']."</td>";
					print "<td width=125 align=left bgcolor='".$color."'> ".converte_datacomhora($row['data_abertura'])."</td>";

					if ($row['data_fechamento'] == null)
					{
						print "<td width=110 align=center bgcolor='".$color."'>  -  </td>";
					}
					else
					{
			            		print "<td width=110 align=left bgcolor='".$color."'> ".converte_datacomhora($row['data_fechamento'])."</td>";
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
				$campos[]="sistema";
				$campos[]="inst_nome";
				$campos[]="local";
				$campos[]="operador";
				$campos[]="data_abertura";
				$campos[]="data_fechamento";

				$cabs=array();
				$cabs[]="Número";
				$cabs[]="Problema";
				$cabs[]="Área Responsável";
				$cabs[]="Unidade";
				$cabs[]="Local";
				$cabs[]="Operador";
				$cabs[]="Data Abertura";
				$cabs[]="Data Encerramento";

				$hoje=date('d/m/Y H:m');

				gera_relatorio(1,$query,$campos,$cabs,"../../includes/logos/logo_unilasalle.gif","OCOMON", $hoje, "Relatório de Ocorrências");
				break;
		} // switch
	} //if $ok==Pesquisar
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

		//window.setInterval("checar()",1000);

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