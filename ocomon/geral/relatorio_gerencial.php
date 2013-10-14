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


	$PERIODO = 1; //PERÍDO EM ANOS PRÓXIMOS PARA VERIFICAÇÃO DE VENCIMENTO DE GARANTIA DOS EQUIPAMENTOS
	$ANO_ATUAL = date("Y");
	$ANO_PROX = $ANO_ATUAL+$PERIODO;
	$TOP_TEN = 15;  //Quantidade de registros que serão mostrados quando for definido um limite

	$tt_pendente_ini = 0;
	$tt_pendente_fim = 0;

if (!isset($_POST['ok']))
{
	print "<html>";
	print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
	print "	<BR><BR>";
	print "	<B><center>::: Relatório gerencial por período :::</center></B><BR><BR>";
	print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit='return valida()'>";
	print "		<TABLE border='0' align='center' bgcolor=".BODY_COLOR." cellspacing='2'";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Área Responsável:</td>";
	print "					<td class='line'><Select name='area' class='select'>";
	print "							<OPTION value=-1 selected>-->Todos<--</OPTION>";
									$query="select * from sistemas order by sistema";
									$resultado=mysql_query($query);
									$linhas = mysql_num_rows($resultado);
									while($row=mysql_fetch_array($resultado))
									{
										$sis_id=$row['sis_id'];
										$sis_name=$row['sistema'];
										print "<option value='".$sis_id."'>".$sis_name."</option>";
									} // while
	print "		 				</Select>";
	print "					 </td>";
	print "				</tr>";

	print "					<td bgcolor=".TD_COLOR.">Data Inicial:</td>";
	print "					<td class='line'><INPUT name='d_ini' class='data' id='idD_ini' value='01-".date("m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_ini,'dd-mm-yyyy',this)\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";
	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Data Final:</td>";
	print "					<td class='line'><INPUT name='d_fim' class='data' id='idD_fim' value='".date("d-m-Y")."'><a onclick=\"displayCalendar(document.forms[0].d_fim,'dd-mm-yyyy',this)\"><img src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
	print "				</tr>";

	print "				<tr>";
	print "					<td bgcolor=".TD_COLOR.">Tipo de relatório:</td>";
	print "					<td class='line'><select name='saida' class='data'>";
	print "							<option value=-1 selected>Normal</option>";
//	print "							<option value=1>Relatório 1 linha</option>";
	print "						</select>";
	print "					</td>";
	print "				</tr>";
	print "		</TABLE><br>";
	print "		<TABLE align='center'>";
	print "			<tr>";
	print "	            <td class='line'>";
	print "					<input type='submit' value='Pesquisar' name='ok' class='button'>";
	print "	            </TD>";
	print "	            <td class='line'>";
	print "					<INPUT type='reset' value='Limpar campos' name='cancelar' class='button'>";
	print "				</TD>";
	print "			</tr>";
	print "	    </TABLE>";
	print " </form>";
	print "</BODY>";
	print "</html>";
}//if $ok!=Pesquisar
else //if $ok==Pesquisar
{

	$hora_inicio = ' 00:00:00';
	$hora_fim = ' 23:59:59';

	//<GAMBIARRA>
	$SW = 9; $HW = 11;//CÓDIGOS DAS ÁREAS HELPDESK E CONSTAT (SOFTWARE E HARDWARE)//
	$SW_PREV = 107; $HW_PREV = 87; //CÓDIGOS DAS PREVENTIVAS DE SOFTWARE E HARDWARE//
	$SUCATA = 7; $TROCADO_FURTADO = '4,5';
	//</GAMBIARRA>



	if ((!isset($_POST['d_ini'])) || (!isset($_POST['d_fim'])))
	{
		print "<script>window.alert('O período deve ser informado!'); history.back();</script>";
	}
	else
	{
		$d_ini = str_replace("-","/",$_POST['d_ini']);
		$d_fim = str_replace("-","/",$_POST['d_fim']);
		$d_ini_nova = converte_dma_para_amd($d_ini);
		$d_fim_nova = converte_dma_para_amd($d_fim);

		$d_ini_completa = $d_ini_nova.$hora_inicio;
		$d_fim_completa = $d_fim_nova.$hora_fim;

		if($d_ini_completa <= $d_fim_completa)
		{
			//TOTAL DE HORAS VÁLIDAS NO PERÍODO:
			$default = 1;//Padrao
			$dt = new dateOpers;
			$dt->setData1($d_ini_completa);
			$dt->setData2($d_fim_completa);
			$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$default][0],$H_horarios[$default][1],$H_horarios[$default][2],$H_horarios[$default][3],"H");
			$hValido = $dt->diff["hValido"]+1; //Como o período passado não é arredondado (xx/xx/xx 23:59:59) é necessário arrendondar o total de horas.

			print "<table class='centro' cellspacing='0' border='0' >";
				print "<tr><td colspan='2'><b>PERÍODO DE ".$d_ini." a ".$d_fim."</b></td></tr>";
				print "<tr><td class='line'>Horas válidas no período: </td><td class='line'>".$hValido." horas</td></tr>";
			print "</table><br><br>";


			if(isset($_POST['area']) && $_POST['area']==-1) {
				$query_areas="SELECT * FROM sistemas WHERE sis_status not in (0) order by sistema";
			} else
				$query_areas="SELECT * FROM sistemas WHERE sis_id in (".$_POST['area'].") order by sistema";

			$exec_qry_areas = mysql_query($query_areas);
			$exec_qry_areasA = mysql_query($query_areas);
			$exec_qry_areasB = mysql_query($query_areas);
			$exec_qry_areasD = mysql_query($query_areas);
			$exec_qry_areasF = mysql_query($query_areas);
			$exec_qry_areasH = mysql_query($query_areas);
			$exec_qry_areasJ = mysql_query($query_areas);


			####################### QUADRO GERAL DE CHAMADOS DO PERÍODO
			print "<p>QUADRO GERAL DE CHAMADOS DO PERÍODO</p>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>ÁREA DE ATENDIMENTO</b></TD><td colspan='3' align='center'><b>CHAMADOS</b></td></tr>";
			print "<tr><td class='line'>&nbsp;</td><td class='line'>ABERTOS</td><td class='line'>FECHADOS</td><td class='line'>CANCELADOS</td></tr>";

			$totalAbertos = 0;
			$totalFechados = 0;
			$totalCancelados = 0;
			while ($row = mysql_fetch_array($exec_qry_areas)){

				$query_ab_sw = "SELECT count(*) AS abertos, s.sistema AS area ".
								"FROM ocorrencias AS o, sistemas AS s ".
								"WHERE o.sistema = s.sis_id AND o.data_abertura >= '".$d_ini_completa."' AND ".
									"o.data_abertura <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].") group by area";
				$exec_ab_sw = mysql_query($query_ab_sw);
				$row_ab_sw = mysql_fetch_array($exec_ab_sw);

				$query_fe_sw = "SELECT count(*) AS fechados, s.sistema AS area ".
								"FROM ocorrencias AS o, sistemas AS s ".
								"WHERE o.sistema = s.sis_id AND o.data_fechamento >= '".$d_ini_completa."' AND ".
								"o.data_fechamento <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].")  group by area";
				$exec_fe_sw = mysql_query($query_fe_sw);
				$row_fe_sw = mysql_fetch_array($exec_fe_sw);

				$query_ca_sw = "SELECT count(*) AS cancelados, s.sistema AS area ".
								"FROM ocorrencias AS o, sistemas AS s ".
								"WHERE o.sistema = s.sis_id AND o.data_abertura >= '".$d_ini_completa."' AND ".
									"o.data_abertura <= '".$d_fim_completa."' and s.sis_id in (".$row['sis_id'].") and ".
									"status in (12) group by area";
				$exec_ca_sw = mysql_query($query_ca_sw);
				$row_ca_sw = mysql_fetch_array($exec_ca_sw);

				$totalAbertos+=$tt_ab = $row_ab_sw['abertos'];
				$totalFechados+=$tt_fe = $row_fe_sw['fechados'];
				$totalCancelados+=$tt_ca = $row_ca_sw['cancelados'];

				print "<tr><td class='line'>".NVL($row['sistema'])."</td><td class='line'>".NVL($row_ab_sw['abertos'])."</td><td class='line'>".NVL($row_fe_sw['fechados'])."</td><td class='line'>".NVL($row_ca_sw['cancelados'])."</td></tr>";
			}

			print "<tr><td class='line'><b>TOTAL</b></td><td class='line'><b>".$totalAbertos."</b></td><td class='line'><b>".$totalFechados."</b></td><td class='line'><b>".$totalCancelados."</b></td></tr>";
			print "</table><br><br>";
				//------------FINAL DA TABELA 1------------//

			print "<p>QUADRO DE CHAMADOS PENDENTES NO PERÍODO</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'>&nbsp;</TD><td colspan='2' align='center'><b>INÍCIO DO PERÍODO</b></td><td colspan='2' align='center'><b>FINAL DO PERÍODO</b></td></tr>";
			print "<tr><td class='line'>&nbsp;</TD><td class='line'>AGUARDANDO</td><td class='line'>EM ATENDIMENTO</td><td class='line'>AGUARDANDO</td><td class='line'>EM ATENDIMENTO</td></tr>";

			while ($rowA = mysql_fetch_array($exec_qry_areasA)){

				//PENDENTES = NÃO FECHADOS
				$query_pendentes_sw_ini = "select count(*)pendentes from ocorrencias ".
						"where date_format(data_abertura,'%Y-%m-%d')< '".$d_ini_nova."' and ".
						"(data_fechamento IS NULL or data_fechamento >'".$d_ini_completa."') AND ".
						"status not in(12) and sistema in (".$rowA['sis_id'].")";
				$exec_pendentes_sw_ini = mysql_query($query_pendentes_sw_ini);
				$row_pendentes_sw_ini = mysql_fetch_array($exec_pendentes_sw_ini);

				$query_aguardando_sw_ini = "select count(*)aguardando from ocorrencias ".
						"where date_format(data_abertura,'%Y-%m-%d')< '".$d_ini_nova."' and ".
						"(data_fechamento IS NULL or data_fechamento >'".$d_ini_completa."') AND ".
						"(data_atendimento IS NULL or data_atendimento >'".$d_ini_completa."') AND ".
						"status not in(12) and sistema in (".$rowA['sis_id'].")";
				$exec_aguardando_sw_ini = mysql_query($query_aguardando_sw_ini);
				$row_aguardando_sw_ini = mysql_fetch_array($exec_aguardando_sw_ini);

				$query_pendentes_sw_fim = "select count(*) pendentes from ocorrencias ".
						"where data_abertura< '".$d_fim_completa."' and ".
						"(data_fechamento is null or data_fechamento >'".$d_fim_completa."') ".
						"and status not in (12) ".
						"and sistema in (".$rowA['sis_id'].")";
				$exec_pendentes_sw_fim = mysql_query($query_pendentes_sw_fim);
				$row_pendentes_sw_fim = mysql_fetch_array($exec_pendentes_sw_fim);


				$query_aguardando_sw_fim = "select count(*) aguardando from ocorrencias ".
						"where data_abertura< '".$d_fim_completa."' and ".
						"(data_fechamento is null or data_fechamento >'".$d_fim_completa."') and ".
						"(data_atendimento is null or data_atendimento >'".$d_fim_completa."') ".
						"and status not in (12) ".
						"and sistema in (".$rowA['sis_id'].")";
				$exec_aguardando_sw_fim = mysql_query($query_aguardando_sw_fim);
				$row_aguardando_sw_fim = mysql_fetch_array($exec_aguardando_sw_fim);

				$tt_pendente_ini+= $row_pendentes_sw_ini['pendentes'];//+$row_pendentes_hw_ini['pendentes'];
				$tt_pendente_fim+= $row_pendentes_sw_fim['pendentes'];//+$row_pendentes_hw_fim['pendentes'];
				$tt_em_atendimento_sw_ini= $row_pendentes_sw_ini['pendentes'] - $row_aguardando_sw_ini['aguardando'];
				$tt_em_atendimento_sw_fim= $row_pendentes_sw_fim['pendentes'] - $row_aguardando_sw_fim['aguardando'];

				//----------------------- TABELA 1B --------------------//
				print "<tr><td class='line'>".NVL($rowA['sistema'])."</TD><td align='center'>".NVL($row_aguardando_sw_ini['aguardando'])."</td><td align='center'>".NVL($tt_em_atendimento_sw_ini)."</td><td align='center'>".NVL($row_aguardando_sw_fim['aguardando'])."</td><td align='center'>".NVL($tt_em_atendimento_sw_fim)."</td></tr>";
				//--------------------------FINAL DA TABELA 1B--------------------//

			}	//FIM DO WHILE
			print "<tr><td class='line'><b>TOTAL</b></TD><td colspan='2'align='center'><b>".NVL($tt_pendente_ini)."</b></td><td colspan='2' align='center'><b>".$tt_pendente_fim."</b></td></tr>";
			print "</table><br><br>";
			print "</blockquote>";
			### FIM DA TABELA

			#####NOVA TABELA
			print "<p>SETORES QUE MAIS ABRIRARM CHAMADOS (preventivas não inclusas)</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>ÁREA</b></td><td class='line'><b>SETOR</b></td><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>MÉDIA POR EQUIPAMENTO</b></td></tr>";

			while ($rowB = mysql_fetch_array($exec_qry_areasB)) {

				$query_ab_sw_setor = "SELECT count(*) AS quantidade, l.local AS setor, s.sistema AS area, ".
						"o.local as local_cod ".
						"FROM ocorrencias AS o, localizacao AS l, sistemas AS s ".
						"WHERE o.sistema = s.sis_id AND o.local = l.loc_id AND ".
						"o.data_abertura >= '".$d_ini_completa."' AND o.data_abertura <=  '".$d_fim_completa."' ".
						"and s.sis_id in (".$rowB['sis_id'].") and o.problema not in (".$SW_PREV.") ".
						"GROUP  BY l.local, s.sistema order by quantidade desc limit 0,".$TOP_TEN."";
				$exec_qry_ab_sw_setor = mysql_query($query_ab_sw_setor);
				$t_rowB = mysql_numrows($exec_qry_ab_sw_setor);
				//----------TABELA 2 -------------------//
				print "<tr><td colspan='1' rowspan='".$t_rowB."'>".NVL($rowB['sistema'])."</td>";
					while($rowC=mysql_fetch_array($exec_qry_ab_sw_setor)){
						print "<td class='line'>".NVL($rowC['setor'])."</td><td class='line'>".NVL($rowC['quantidade'])."</td>";
						$qry_qtd = "select count(*) total from equipamentos where comp_local = ".$rowC['local_cod']." and
									comp_tipo_equip in (1,2)";
						$exec_qtd = mysql_query($qry_qtd);
						$r_qtd = mysql_fetch_array($exec_qtd);
						if ($r_qtd['total']== 0){$total=1;} else {$total=$r_qtd['total'];}
						$media_por_equip = round($rowC['quantidade']/$total,1);
						print "<td class='line'>".NVL($media_por_equip)."</td>";
						print "</tr>";
					} // while
			}
			print "</table><br><br>";
			print "</blockquote>";
			//------------- FINAL DA TABELA 3 --------------//

			#####NOVA TABELA
			print "<p>SETORES QUE MAIS TIVERAM CHAMADOS FECHADOS (preventivas não inclusas)</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>ÁREA</b></td><td class='line'><b>SETOR</b></td><td class='line'><b>QUANTIDADE</b></td></tr>";

			while ($rowD = mysql_fetch_array($exec_qry_areasD)) {
				$query_fe_sw_setor = "SELECT count(*) AS quantidade, l.local AS setor, s.sistema AS area, ".
						"o.local as local_cod ".
						"FROM ocorrencias AS o, localizacao AS l, sistemas AS s ".
						"WHERE o.sistema = s.sis_id AND o.local = l.loc_id AND ".
						"o.data_fechamento >= '".$d_ini_completa."' AND o.data_fechamento <=  '".$d_fim_completa."' ".
						"and s.sis_id in (".$rowD['sis_id'].") and o.problema not in (".$SW_PREV.") ".
						"GROUP  BY l.local, s.sistema order by quantidade desc limit 0,".$TOP_TEN."";
				$exec_qry_fe_sw_setor = mysql_query($query_fe_sw_setor);
				$t_rowD = mysql_numrows($exec_qry_fe_sw_setor);
				//----------TABELA 2 -------------------//
				print "<tr><td colspan='1' rowspan='".$t_rowD."'>".NVL($rowD['sistema'])."</td>";
				while($rowE=mysql_fetch_array($exec_qry_fe_sw_setor)){
					print "<td class='line'>".NVL($rowE['setor'])."</td><td class='line'>".NVL($rowE['quantidade'])."</td>";
					print "</tr>";
				} // while
			}
			print "</table><br><br>";
			print "</blockquote>";
			//------------- FINAL DA TABELA 4 --------------//

						//----------TABELA 6 -------------------//
			print "<p>USUÁRIOS QUE MAIS ABRIRAM CHAMADOS (preventivas não inclusas)</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>ÁREA</b></td><td class='line'><b>USUÁRIO</b></td><td class='line'><b>SETOR</b></td><td class='line'><b>QUANTIDADE</b></td></tr>";
			while ($rowF = mysql_fetch_array($exec_qry_areasF)) {

				$query_us_sw = "SELECT count(*) AS quantidade, l.local AS setor, s.sistema AS area, ".
						"lower(o.contato) as usuario ".
						"FROM ocorrencias AS o, localizacao AS l, sistemas AS s ".
						"WHERE o.sistema = s.sis_id AND o.local = l.loc_id AND ".
						"o.data_abertura >=  '".$d_ini_completa."' AND o.data_abertura <= '".$d_fim_completa."' ".
						"and s.sis_id in (".$rowF['sis_id'].") and o.problema not in (".$SW_PREV.") ".
						"GROUP  BY l.local, usuario, s.sistema order by quantidade desc limit 0,".$TOP_TEN."";
				$exec_qry_us_sw = mysql_query($query_us_sw);
				$t_rowF = mysql_numrows($exec_qry_us_sw);
				print "<tr><td colspan='1' rowspan='".$t_rowF."'>".NVL($rowF['sistema'])."</td>";
				while($rowG=mysql_fetch_array($exec_qry_us_sw)){
					print "<td class='line'>".NVL($rowG['usuario'])."</td><td class='line'>".NVL($rowG['setor'])."</td><td class='line'>".NVL($rowG['quantidade'])."</td></tr>";
				} // while
			}
			print "</table><br><br>";
			print "</blockquote>";
			//------------- FINAL DA TABELA 6 --------------//

			//----------TABELA 7 -------------------//
			print "<p>CHAMADOS ABERTOS POR REITORIA</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>ÁREA</b></td><td class='line'><b>REITORIA</b></td><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>PERCENTUAL</b></td><td class='line'><b>MÉDIA POR EQUIPAMENTO</b></td></tr>";
			while ($rowH = mysql_fetch_array($exec_qry_areasH)){
				$query_tt = "select count(*) as total from ocorrencias ".
							"where data_abertura >='".$d_ini_completa."' and data_abertura <='".$d_fim_completa."' and ".
							"sistema in (".$rowH['sis_id'].")";
				$exec_tt = mysql_query($query_tt);
				$row_ab_reit = mysql_fetch_array($exec_tt);

				$query_ab_sw_reit = "SELECT count(*) AS total, concat(count(*)/".$row_ab_reit['total']."*100,'%') as porcento, ".
						"s.sistema AS area, r.reit_nome AS reitoria, l.loc_reitoria as reitoria_cod ".
						"FROM localizacao AS l, reitorias AS r, ocorrencias AS o, sistemas AS s ".
						"WHERE l.loc_reitoria = r.reit_cod AND o.local = l.loc_id AND s.sis_id = o.sistema AND ".
						"o.data_abertura >= '".$d_ini_completa."' AND o.data_abertura <= '".$d_fim_completa."' AND ".
						"s.sis_id IN (".$rowH['sis_id'].") ".
						"GROUP BY l.loc_reitoria, s.sistema ".
						"ORDER BY s.sistema, total DESC";
				$exec_qry_ab_sw_reit = mysql_query($query_ab_sw_reit);
				$t_rowH = mysql_numrows($exec_qry_ab_sw_reit);
				print "<tr><td colspan='1' rowspan='".$t_rowH."'>".NVL($rowH['sistema'])."</td>";
				while($rowI=mysql_fetch_array($exec_qry_ab_sw_reit)){
					print "<td class='line'>".NVL($rowI['reitoria'])."</td><td class='line'>".NVL($rowI['total'])."</td><td class='line'>".NVL($rowI['porcento'])."</td>";

					$qry_qtd_equip_reit = "select count(*) total from equipamentos as e, localizacao as l, reitorias as r where ".
							"l.loc_reitoria = r.reit_cod and e.comp_local = l.loc_id and comp_tipo_equip in (1,2) and ".
							"r.reit_cod = ".$rowI['reitoria_cod']."";
					$exec_qtd_equip = mysql_query($qry_qtd_equip_reit);
					$r_qtd_reit = mysql_fetch_array($exec_qtd_equip);
					if ($r_qtd_reit['total']==0) {
						$qtd_reit = 1;
					} else $qtd_reit = $r_qtd_reit['total'];
					$media_reit = round($rowI['total']/$qtd_reit,1);
					print "<td class='line'>".NVL($media_reit)."</td>";
					print "</tr>";
				} // while
			}
			print "</table><br><br>";
			print "</blockquote>";
			//------------- FINAL DA TABELA 7 --------------//

		//----------TABELA 9 -------------------//
			print "<p>PRINCIPAIS PROBLEMAS</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>ÁREA</b></td><td class='line'><b>PROBLEMA</b></td><td class='line'><b>QUANTIDADE</b></td></tr>";

			while ($rowJ=mysql_fetch_array($exec_qry_areasJ)) {

				$query_fe_sw_prob = "SELECT count(*) AS quantidade, s.sistema AS area,  p.problema as problema ".
						"FROM ocorrencias AS o, localizacao AS l, sistemas AS s, problemas as p ".
						"WHERE o.sistema = s.sis_id AND o.local = l.loc_id AND ".
						"o.data_fechamento >= '".$d_ini_completa."' AND o.data_fechamento <= '".$d_fim_completa."' and ".
						"o.problema = p.prob_id and s.sis_id in (".$rowJ['sis_id'].") ".
						"GROUP  BY p.problema, s.sistema order by area, quantidade desc limit 0,".$TOP_TEN."";
				$exec_qry_fe_sw_prob = mysql_query($query_fe_sw_prob);
				$t_rowJ = mysql_numrows($exec_qry_fe_sw_prob);

				print "<tr><td colspan='1' rowspan='".$t_rowJ."'>".NVL($rowJ['sistema'])."</td>";
				while($rowK=mysql_fetch_array($exec_qry_fe_sw_prob)){
					print "<td class='line'>".NVL($rowK['problema'])."</td><td class='line'>".NVL($rowK['quantidade'])."</td></tr>";
				} // while
			}
			print "</table><br><br>";
			print "</blockquote>";
			//------------- FINAL DA TABELA 9 --------------//

			$query_cadastrados = "SELECT count(*)as cadastrados FROM equipamentos ".
						"WHERE comp_data >= '".$d_ini_completa."' AND comp_data <= '".$d_fim_completa."'";
			$exec_qry_cadastrados = mysql_query($query_cadastrados);
			$row_cadastrados = mysql_fetch_array($exec_qry_cadastrados);
			print "<p>TOTAL DE EQUIPAMENTOS ETIQUETADOS E CADASTRADOS NO PERÍODO: <b>".NVL($row_cadastrados['cadastrados'])."</b></p><br>";

			$query_sw_prev = "select count(*) as preventivas from ocorrencias ".
				"where problema in (".$SW_PREV.") and ".
				"data_fechamento >= '".$d_ini_completa."' AND data_fechamento <= '".$d_fim_completa."'";
			$exec_qry_sw_prev = mysql_query($query_sw_prev);
			$row_sw_prev = mysql_fetch_array($exec_qry_sw_prev);

			$query_hw_prev = "select count(*) as preventivas from ocorrencias ".
				"where problema in (".$HW_PREV.") and ".
				"data_fechamento >= '".$d_ini_completa."' AND data_fechamento <= '".$d_fim_completa."'";
			$exec_qry_hw_prev = mysql_query($query_hw_prev);
			$row_hw_prev = mysql_fetch_array($exec_qry_hw_prev);

			$tt_prev = $row_sw_prev['preventivas']+$row_hw_prev['preventivas'];
			//------------ TABELA 11 ----------------//
			print "<p>QUADRO DE PREVENTIVAS</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>TIPO</b></td><td class='line'><b>QUANTIDADE</b></td></tr>";
			print "<tr><td class='line'>SOFTWARE</td><td class='line'>".NVL($row_sw_prev['preventivas'])."</td></tr>";
			print "<tr><td class='line'>HARDWARE</td><td class='line'>".NVL($row_hw_prev['preventivas'])."</td></tr>";
			print "<tr><td class='line'><b>TOTAL</b></td><td class='line'><b>".NVL($tt_prev)."</b></td></tr>";
			print "</table><BR><BR>";
			print "</blockquote>";
			//-------------FINAL DA TABELA 11 -----------//

			$query_tt_equip = "select count(*)as total from equipamentos where comp_tipo_equip not in (5)
					and comp_inst in(1,2,3)";
			$exec_tt_equip = mysql_query($query_tt_equip);
			$row_tt_equip = mysql_fetch_array($exec_tt_equip);

			$query_equip_reit = "SELECT r.reit_nome AS reitoria, count(*) AS qtd, ".
				"concat(count(*)/".$row_tt_equip['total']."*100,'%') as porcento ".
				"FROM equipamentos AS c, tipo_equip AS t, localizacao AS l, reitorias AS r ".
				"WHERE c.comp_tipo_equip = t.tipo_cod and c.comp_tipo_equip not in (5) ".
				"AND c.comp_local = l.loc_id AND l.loc_reitoria = r.reit_cod and c.comp_inst in (1,2,3) ".
				"GROUP BY l.loc_reitoria ".
				"ORDER BY qtd DESC";
			$exec_equip_reit = mysql_query($query_equip_reit);
			//--------------TABELA 12 ------------//
			print "<p>DISTRIBUIÇÃO DOS EQUIPAMENTOS POR REITORIA (monitores não inclusos)</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>REITORIA</b></td><td class='line'><b>PERCENTUAL</b></td></tr>";
			while($row=mysql_fetch_array($exec_equip_reit)){
				print "<tr><td class='line'>".NVL($row['reitoria'])."</td><td class='line'>".NVL($row['porcento'])."</td></tr>";
			} // while
			print "</table><BR><BR>";
			print "</blockquote>";
			//--------------------FIM DA TABELA 12 ---------------//

			$query_em_garantia = "SELECT count(*) AS EM_GARANTIA ".
				"FROM equipamentos, tempo_garantia ".
				"WHERE date_add( comp_data_compra, INTERVAL tempo_meses MONTH ) >= curdate() ".
				"AND comp_garant_meses = tempo_cod AND comp_tipo_equip IN(1,2) and comp_inst in (1,2,3) ".
				"and comp_situac not in (".$TROCADO_FURTADO.")";
			$exec_em_garantia = mysql_query($query_em_garantia);
			$row_em_garantia = mysql_fetch_array($exec_em_garantia);

			$query_em_contrato = "SELECT count(*) AS EM_CONTRATO ".
				"FROM (equipamentos AS E LEFT JOIN tempo_garantia AS T ON E.comp_garant_meses = T.tempo_cod) ".
				"WHERE (date_add( E.comp_data_compra, INTERVAL T.tempo_meses MONTH ) < curdate() OR ".
				"E.comp_garant_meses IS NULL ) AND E.comp_tipo_equip IN (1,2) and ".
				"comp_situac not in (".$SUCATA.",".$TROCADO_FURTADO.") and comp_inst in (1,2,3)";
			$exec_em_contrato = mysql_query($query_em_contrato);
			$row_em_contrato = mysql_fetch_array($exec_em_contrato);

			$query_sucata = "select count(*) as SUCATA from equipamentos ".
					"where comp_situac in (".$SUCATA.") and comp_inst in (1,2,3) and comp_tipo_equip in (1,2)";
			$exec_sucata = mysql_query($query_sucata);
			$row_sucata = mysql_fetch_array($exec_sucata);
			//----------------TABELA 13 --------------------//
			print "<p>SITUAÇÃO DOS <b>COMPUTADORES</b> QUANTO A COBERTURA DE MANUTENÇÃO</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>SITUAÇÃO</b></td><td class='line'><b>QUANTIDADE</b></td></tr>";
			print "<tr><td class='line'>EM GARANTIA DO FABRICANTE</td><td class='line'>".NVL($row_em_garantia['EM_GARANTIA'])."</td></tr>";
			print "<tr><td class='line'>EM CONTRATO</td><td class='line'>".NVL($row_em_contrato['EM_CONTRATO'])."</td></tr>";
			print "<tr><td class='line'>SUCATEADOS</td><td class='line'>".NVL($row_sucata['SUCATA'])."</td></tr>";
			print "</table><br><br>";
			print "</blockquote>";
			//----------------- FIM DA TABELA 13 ----------------//

			//extract(year FROM date_add( comp_data_compra, INTERVAL tempo_meses MONTH )) AS ano_vencimento,
			$query_vencimento = "SELECT count(*)AS quantidade, ".
					"date_add(date_format(comp_data_compra,'%Y-%m-%d'), INTERVAL tempo_meses	MONTH ) AS vencimento, ".
					"marc_nome as modelo, fab_nome as fabricante, tipo_nome as tipo ".
					"FROM equipamentos, tempo_garantia, marcas_comp, fabricantes, tipo_equip ".
					"WHERE date_add( comp_data_compra, INTERVAL tempo_meses MONTH ) >= curdate( ) ".
					"AND comp_garant_meses = tempo_cod AND comp_tipo_equip not IN (5) AND comp_marca = marc_cod and ".
					"comp_fab = fab_cod and comp_tipo_equip = tipo_cod and ".
					"(date_format( curdate( ) , '%Y' ) = date_format( date_add( comp_data_compra, INTERVAL tempo_meses MONTH ) , '%Y' ) ".
					"OR date_format( curdate( ) , '%Y' )+".$PERIODO." >= date_format( date_add( comp_data_compra, INTERVAL tempo_meses MONTH ) , '%Y')) ".
					"GROUP BY vencimento,modelo ".
					"ORDER BY vencimento,modelo";
			$exec_vencimento = mysql_query($query_vencimento);
			//----------------TABELA 14 -----------------//
			print "<p>PRÓXIMOS VENCIMENTOS DE GARANTIA(".$ANO_ATUAL." a ".$ANO_PROX.")</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>DATA</b></td><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>TIPO</b></td><td class='line'><b>MODELO</b></td></tr>";
			$tt_garant = 0;
			while($row=mysql_fetch_array($exec_vencimento)){
				$temp = explode(" ",datab($row['vencimento']));
				$vencimento = $temp[0];
				$tt_garant+= $row['quantidade'];
				print "<tr><td class='line'>".NVL($vencimento)."</td><td align='center'>".NVL($row['quantidade'])."</td><td class='line'>".NVL($row['tipo'])."</td><td class='line'>".NVL($row['fabricante'])." ".NVL($row['modelo'])."</td></tr>";
			} // while
			print "<tr><td class='line'><b>TOTAL</b></td><td colspan='3'><b>".NVL($tt_garant)."</b></td></tr>";
			print "</table><br><br>";
			print "</blockquote>";
			//-----------------FINAL DA TABELA 14 -----------------------//

			$query_sw_sla = "select o.numero, o.data_abertura, o.data_atendimento, o.data_fechamento, ".
					"o.sistema as cod_area, s.sistema ".
					"from ocorrencias as o, sistemas as s ".
					"where o.status in (4) and s.sis_id=o.sistema and o.sistema in (".$SW.") and ".
					"o.data_fechamento >= '".$d_ini_completa."' and o.data_fechamento <= '".$d_fim_completa."' and ".
					"o.data_atendimento is not null order by o.data_abertura";
			$exec_sw_sla = mysql_query($query_sw_sla);
			$linhas = mysql_num_rows($exec_sw_sla);

			$dtS = new dateOpers; //solução
			$dtR = new dateOpers; //resposta

			$sla3 = 6; //INICIO DO VERMELHO - Tempo de SOLUÇÃO
			$sla2 = 4; //INÍCIO DO AMARELO
			$slaR3 = 3600; //Tempo de RESPOSTA em segundos VERMELHO
			$slaR2 = 1800; //AMARELO
			$sla_green=0;
			$sla_red=0;
			$sla_yellow=0;


			while($row=mysql_fetch_array($exec_sw_sla)){

				// if (array_key_exists($row['cod_area'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
					// $area = $row['cod_area']; //Recebe o valor da área de atendimento do chamado
				// } else $area = 1; //Carga horária default definida no arquivo config.inc.php
				$area=testaArea($area,$row['cod_area'],$H_horarios);

				$dtS->setData1($row['data_abertura']);
				$dtS->setData2($row['data_fechamento']);
				$dtS->tempo_valido($dtS->data1,$dtS->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

				$dtR->setData1($row['data_abertura']);
				$dtR->setData2($row['data_atendimento']);
				$dtR->tempo_valido($dtR->data1,$dtR->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

				$t_horas = $dtS->diff["hValido"];
				if ($t_horas>=$sla3) //>=6
				{
				    $cor = $corSla3;
					$sla_red++;
				}
				else
				if ($t_horas>=$sla2)
				{
					$cor = $corSla2;
					$sla_yellow++;
				}
				else
				{
					$cor = $corSla1;
					$sla_green++;
				}

				$t_resp = $dtR->diff["sValido"];

				if ($t_resp>=$slaR3) //>=6
				{
					$corR = $corSla3;
					$slaR_red++;
				}
				else
				if ($t_resp>=$slaR2)
				{
					$corR = $corSla2;
					$slaR_yellow++;
				}
				else
				{
					$corR = $corSla1;
					$slaR_green++;
				}


				$total_sol_segundos+= $dtS->diff["sFull"];
				$total_res_segundos+=$dtR->diff["sFull"];
				$total_res_valido+=$dtR->diff["sValido"];
				$total_sol_valido+=$dtS->diff["sValido"];


			} // while

			if ($linhas!=0) {
				//$media_resposta_geral = $dtR->secToHour(floor($total_res_segundos/$linhas));
				//$media_solucao_geral = $dtS->secToHour(floor($total_sol_segundos/$linhas));
				$media_resposta_valida = $dtR->secToHour(floor($total_res_valido/$linhas));
				$media_solucao_valida = $dtS->secToHour(floor($total_sol_valido/$linhas));

				//MEDIAS DE SOLUÇÃO
				$perc_ate_sla2=round((($sla_green*100)/$linhas),2);
				$perc_ate_sla3=round((($sla_yellow*100)/$linhas),2);
				$perc_mais_sla3=round((($sla_red*100)/$linhas),2);
				//MEDIAS DE RESPOSTA
				$perc_ate_slaR2=round((($slaR_green*100)/$linhas),2);
				$perc_ate_slaR3=round((($slaR_yellow*100)/$linhas),2);
				$perc_mais_slaR3=round((($slaR_red*100)/$linhas),2);

			}

			$query_hw_sla = "select o.numero, o.data_abertura, o.data_atendimento, o.data_fechamento, ".
					"o.sistema as cod_area, s.sistema ".
					"from ocorrencias as o, sistemas as s ".
					"where o.status=4 and s.sis_id=o.sistema and o.sistema in (".$HW.") and ".
					"o.data_fechamento >= '".$d_ini_completa."' and o.data_fechamento <= '".$d_fim_completa."' and ".
					"o.data_atendimento is not null order by o.data_abertura";
			$exec_hw_sla = mysql_query($query_hw_sla);
			$linhas_hw = mysql_num_rows($exec_hw_sla);

			$dtS_hw = new dateOpers; //solução
			$dtR_hw = new dateOpers; //resposta

		  	$sla_green_hw=0;
			$sla_red_hw=0;
			$sla_yellow_hw=0;


			while($row=mysql_fetch_array($exec_hw_sla)){

				// if (array_key_exists($row['cod_area'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
					// $area = $row['cod_area']; //Recebe o valor da área de atendimento do chamado
				// } else $area = 1; //Carga horária default definida no arquivo config.inc.php
				$area=testaArea($area,$row['cod_area'],$H_horarios);


				$dtS_hw->setData1($row['data_abertura']);
				$dtS_hw->setData2($row['data_fechamento']);
				$dtS_hw->tempo_valido($dtS_hw->data1,$dtS_hw->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

				$dtR_hw->setData1($row['data_abertura']);
				$dtR_hw->setData2($row['data_atendimento']);
				$dtR_hw->tempo_valido($dtR_hw->data1,$dtR_hw->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

				$t_horas = $dtS_hw->diff["hValido"];
				if ($t_horas>=$sla3) //>=6
				{
					$sla_red_hw++;
				}
				else
				if ($t_horas>=$sla2)
				{
					$sla_yellow_hw++;
				}
				else
				{
					$sla_green_hw++;
				}

				$t_resp = $dtR_hw->diff["sValido"];

				if ($t_resp>=$slaR3) //>=6
				{
					$slaR_red_hw++;
				}
				else
				if ($t_resp>=$slaR2)
				{
					$slaR_yellow_hw++;
				}
				else
				{
					$slaR_green_hw++;
				}

				$total_sol_segundos_hw+= $dtS_hw->diff["sFull"];
				$total_res_segundos_hw+=$dtR_hw->diff["sFull"];
				$total_res_valido_hw+=$dtR_hw->diff["sValido"];
				$total_sol_valido_hw+=$dtS_hw->diff["sValido"];
			} // while

			//$media_resposta_geral = $dtR->secToHour(floor($total_res_segundos/$linhas));
			//$media_solucao_geral = $dtS->secToHour(floor($total_sol_segundos/$linhas));
			if ($linhas_hw!=0) {

				$media_resposta_valida_hw = $dtR_hw->secToHour(floor($total_res_valido_hw/$linhas_hw));
				$media_solucao_valida_hw = $dtS_hw->secToHour(floor($total_sol_valido_hw/$linhas_hw));

				//MEDIAS DE SOLUÇÃO
				$perc_ate_sla2_hw=round((($sla_green_hw*100)/$linhas_hw),2);
				$perc_ate_sla3_hw=round((($sla_yellow_hw*100)/$linhas_hw),2);
				$perc_mais_sla3_hw=round((($sla_red_hw*100)/$linhas_hw),2);
				//MEDIAS DE RESPOSTA
				$perc_ate_slaR2_hw=round((($slaR_green_hw*100)/$linhas_hw),2);
				$perc_ate_slaR3_hw=round((($slaR_yellow_hw*100)/$linhas_hw),2);
				$perc_mais_slaR3_hw=round((($slaR_red_hw*100)/$linhas_hw),2);

			}
			// --------------- TABELA 15  -----------------//
			print "<p>MÉDIAS DE TEMPO DE RESPOSTA E DE SOLUÇÃO</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>TIPO</b></td><td class='line'><b>MÉDIA DE RESPOSTA</b></td><td class='line'><b>MÉDIA DE SOLUÇÃO</b></td></tr>";
			print "<tr><td class='line'>HARDWARE</td><td class='line'>".$media_resposta_valida_hw."</td><td class='line'>".$media_solucao_valida_hw."</td></tr>";
			print "<tr><td class='line'>SOFTWARE</td><td class='line'>".$media_resposta_valida."</td><td class='line'>".$media_solucao_valida."</td></tr>";
			print "</table><br><br>";
			print "</blockquote>";
			//------------ FINAL DA TABELA 15 -----------------//

			$nome = "titulo=MÉDIA DE TEMPOS DE RESPOSTA PARA CHAMADOS DE SOFTWARE";
			$valores = "data%5B%5D=".$slaR_green."&";
			$valores.= "data%5B%5D=".$slaR_yellow."&";
			$valores.= "data%5B%5D=".$slaR_red;
			$legenda = "legenda%5B%5D=até 30 minutos&";
			$legenda.= "legenda%5B%5D=até 60 minutos&";
			$legenda.= "legenda%5B%5D=mais de 60 minutos";
			// --------------- TABELA 16  -----------------//
			print "<p>MÉDIA SLA POR CHAMADOS DE SOFTWARE - RESPOSTA</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>RESPOSTA</b></td><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>PERCENTUAL</b></td></tr>";
			print "<tr><td class='line'>ATÉ 30 MINUTOS</td><td class='line'>".$slaR_green."</td><td class='line'>".$perc_ate_slaR2."</td></tr>";
			print "<tr><td class='line'>ATÉ 60 MINUTOS</td><td class='line'>".$slaR_yellow."</td><td class='line'>".$perc_ate_slaR3."</td></tr>";
			print "<tr><td class='line'>MAIS DE 60 MINUTOS</td><td class='line'>".$slaR_red."</td><td class='line'>".$perc_mais_slaR3."</td></tr>";
			print "</table>";
			print "<input type='button' value='Gráfico' onClick=\"return popup('graph_geral_pizza.php?".$valores."&".$nome."&".$legenda."')\"><br><br>";
			print "</blockquote>";
			//------------ FINAL DA TABELA 16 -----------------//


			$nome = "titulo=MÉDIA DE TEMPOS DE SOLUÇÃO PARA CHAMADOS DE SOFTWARE";
			$valores = "data%5B%5D=".$sla_green."&";
			$valores.= "data%5B%5D=".$sla_yellow."&";
			$valores.= "data%5B%5D=".$sla_red;
			$legenda = "legenda%5B%5D=até 4 horas&";
			$legenda.= "legenda%5B%5D=entre 4 e 6 horas&";
			$legenda.= "legenda%5B%5D=mais de 6 horas";
			// --------------- TABELA 17  -----------------//
			print "<p>MÉDIA SLA POR CHAMADOS DE SOFTWARE - SOLUÇÃO</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>SOLUÇÃO</b></td><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>PERCENTUAL</b></td></tr>";
			print "<tr><td class='line'>ATÉ 4 HORAS</td><td class='line'>".$sla_green."</td><td class='line'>".$perc_ate_sla2."</td></tr>";
			print "<tr><td class='line'>ENTRE 4 E 6 HORAS</td><td class='line'>".$sla_yellow."</td><td class='line'>".$perc_ate_sla3."</td></tr>";
			print "<tr><td class='line'>MAIS DE 6 HORAS</td><td class='line'>".$sla_red."</td><td class='line'>".$perc_mais_sla3."</td></tr>";
			print "</table>";
			print "<input type='button' value='Gráfico' onClick=\"return popup('graph_geral_pizza.php?".$valores."&".$nome."&".$legenda."')\"><br><br>";
			print "</blockquote>";
			//------------ FINAL DA TABELA 17 -----------------//

			$nome = "titulo=MÉDIA DE TEMPOS DE RESPOSTA PARA CHAMADOS DE HARDWARE";
			$valores = "data%5B%5D=".$slaR_green_hw."&";
			$valores.= "data%5B%5D=".$slaR_yellow_hw."&";
			$valores.= "data%5B%5D=".$slaR_red_hw;
			$legenda = "legenda%5B%5D=até 30 minutos&";
			$legenda.= "legenda%5B%5D=até 60 minutos&";
			$legenda.= "legenda%5B%5D=mais de 60 minutos";
			// --------------- TABELA 18  -----------------//
			print "<p>MÉDIA SLA POR CHAMADOS DE HARDWARE - RESPOSTA</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>RESPOSTA</b></td><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>PERCENTUAL</b></td></tr>";
			print "<tr><td class='line'>ATÉ 30 MINUTOS</td><td class='line'>".$slaR_green_hw."</td><td class='line'>".$perc_ate_slaR2_hw."</td></tr>";
			print "<tr><td class='line'>ATÉ 60 MINUTOS</td><td class='line'>".$slaR_yellow_hw."</td><td class='line'>".$perc_ate_slaR3_hw."</td></tr>";
			print "<tr><td class='line'>MAIS DE 60 MINUTOS</td><td class='line'>".$slaR_red_hw."</td><td class='line'>".$perc_mais_slaR3_hw."</td></tr>";
			print "</table>";
			print "<input type='button' value='Gráfico' onClick=\"return popup('graph_geral_pizza.php?".$valores."&".$nome."&".$legenda."')\"><br><br>";
			print "</blockquote>";
			//------------ FINAL DA TABELA 18 -----------------//

			$nome = "titulo=MÉDIA DE TEMPOS DE SOLUÇÃO PARA CHAMADOS DE HARDWARE";
			$valores = "data%5B%5D=".$sla_green_hw."&";
			$valores.= "data%5B%5D=".$sla_yellow_hw."&";
			$valores.= "data%5B%5D=".$sla_red_hw;
			$legenda = "legenda%5B%5D=até 4 horas&";
			$legenda.= "legenda%5B%5D=entre 4 e 6 horas&";
			$legenda.= "legenda%5B%5D=mais de 6 horas";
			// --------------- TABELA 19  -----------------//
			print "<p>MÉDIA SLA POR CHAMADOS DE HARDWARE - SOLUÇÃO</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>SOLUÇÃO</b></td><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>PERCENTUAL</b></td></tr>";
			print "<tr><td class='line'>ATÉ 4 HORAS</td><td class='line'>".$sla_green_hw."</td><td class='line'>".$perc_ate_sla2_hw."</td></tr>";
			print "<tr><td class='line'>ENTRE 4 E 6 HORAS</td><td class='line'>".$sla_yellow_hw."</td><td class='line'>".$perc_ate_sla3_hw."</td></tr>";
			print "<tr><td class='line'>MAIS DE 6 HORAS</td><td class='line'>".$sla_red_hw."</td><td class='line'>".$perc_mais_sla3_hw."</td></tr>";
			print "</table>";
			print "<input type='button' value='Gráfico' onClick=\"return popup('graph_geral_pizza.php?".$valores."&".$nome."&".$legenda."')\"><br><br>";
			print "</blockquote>";
			//------------ FINAL DA TABELA 19 -----------------//

			$query_etiqueta_hw = "SELECT count(*) AS total, I.inst_nome as instituicao, ".
						"I.inst_cod as inst_cod, O.equipamento as etiqueta, S.sistema as area, L.local as local ".
						"FROM ocorrencias AS O, instituicao as I, sistemas as S, localizacao as L ".
						"WHERE (instituicao IS NOT NULL OR instituicao NOT IN (0)) AND equipamento NOT IN (0) ".
						"and O.instituicao = I.inst_cod and S.sis_id =O.sistema and O.sistema in (".$HW.") and ".
						"L.loc_id = O.local and ".
						"O.data_abertura >= '".$d_ini_completa."' and O.data_abertura <= '".$d_fim_completa."' ".
						"GROUP BY inst_nome, equipamento,area ORDER BY total DESC limit 0,15";
			$exec_etiqueta_hw = mysql_query($query_etiqueta_hw);
			//----------------------------- TABELA 20 ---------------//
			print "<p>EQUIPAMENTOS QUE MAIS TIVERAM CHAMADOS DE HARDWARE NO PERÍODO</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>INSTITUIÇÃO</b></td><td class='line'><b>ETIQUETA</b></td><td class='line'><b>LOCALIZAÇÃO</b></td></tr>";
			while($row=mysql_fetch_array($exec_etiqueta_hw)){
				print "<tr><td class='line'>".$row['total']."</td><td class='line'>".$row['instituicao']."</td><td class='line'><a onClick= \"popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst=".$row['inst_cod']."&comp_inv=".$row['etiqueta']."&popup=true')\">".$row['etiqueta']."</a></td><td class='line'>".$row['local']."</td></tr>";
			} // while
			print "</table><BR><br>";
			print "</blockquote>";
			//----------------------------- FIM DA TABELA 20 -------------------//

			$query_etiqueta_sw = "SELECT count(*) AS total, I.inst_nome as instituicao, ".
						"I.inst_cod as inst_cod, O.equipamento as etiqueta, S.sistema as area, L.local as local ".
						"FROM ocorrencias AS O, instituicao as I, sistemas as S, localizacao as L ".
						"WHERE (instituicao IS NOT NULL OR instituicao NOT IN (0)) AND equipamento NOT IN (0) ".
						"and O.instituicao = I.inst_cod and S.sis_id =O.sistema and O.sistema in (".$SW.") and ".
						"L.loc_id = O.local and ".
						"O.data_abertura >= '".$d_ini_completa."' and O.data_abertura <= '".$d_fim_completa."' ".
						"GROUP BY inst_nome, equipamento,area ORDER BY total DESC limit 0,15";
			$exec_etiqueta_sw = mysql_query($query_etiqueta_sw);
			//----------------------------- TABELA 21 ---------------//
			print "<p>EQUIPAMENTOS QUE MAIS TIVERAM CHAMADOS DE SOFTWARE NO PERÍODO</p>";
			print "<blockquote>";
			print "<table cellspacing='0' border='1'>";
			print "<tr><td class='line'><b>QUANTIDADE</b></td><td class='line'><b>INSTITUIÇÃO</b></td><td class='line'><b>ETIQUETA</b></td><td class='line'><b>LOCALIZAÇÃO</b></td></tr>";
			while($row=mysql_fetch_array($exec_etiqueta_sw)){
				print "<tr><td class='line'>".$row['total']."</td><td class='line'>".$row['instituicao']."</td><td class='line'><a onClick= \"popup_alerta('../../invmon/geral/mostra_consulta_inv.php?comp_inst=".$row['inst_cod']."&comp_inv=".$row['etiqueta']."&popup=true')\">".$row['etiqueta']."</a></td><td class='line'>".$row['local']."</td></tr>";
			} // while
			print "</table><br><br>";
			print "</blockquote>";
			//----------------------------- FIM DA TABELA 21 -------------------//

		}else {//ERRO NO PREENCHIMENTO DO PERÍODO
			print "<script>mensagem('A data final não pode ser menor do que a data inicial!'); redirect('".$_SERVER['PHP_SELF']."');</script>";
		}
	}//if ((empty($d_ini)) and (empty($d_fim)))
}//if $ok==Pesquisar
?>
<script type='text/javascript'>

	function popup_alerta(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Alerta','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}


	function popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'Gráfico','dependent=yes,width=600,height=480,scrollbars=no,statusbar=no,resizable=no');
		x.moveTo(10,10);
		return false
	}
		function valida(){
			var ok = validaForm('idD_ini','DATA-','Data Inicial',1);
			if (ok) var ok = validaForm('idD_fim','DATA-','Data Final',1);

			if (ok) submitForm();

			return ok;
		}


</script>

