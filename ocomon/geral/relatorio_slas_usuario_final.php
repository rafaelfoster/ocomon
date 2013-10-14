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
	print "<link rel='stylesheet' href='../../includes/css/calendar.css.php' media='screen'></LINK>";

	$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	print "<HTML><BODY bgcolor='".BODY_COLOR."'>";


	//SLA 1 é menor do que o SLA 2 - VERDE
	$sla3 = 6; //INICIO DO VERMELHO - Tempo de SOLUÇÃO
	$sla2 = 4; //INÍCIO DO AMARELO
	$slaR3 = 3600; //Tempo de RESPOSTA em segundos VERMELHO
	$slaR2 = 1800; //AMARELO

	$corSla1 = "green";
	$corSla2 = "orange";
	$corSla3 = "red";
	$percLimit = 20; //Limite em porcento que um chamado pode estourar para ficar no SLA2 antes de ficar no vermelho

	$chamadosSgreen = array();
	$chamadosSyellow = array();
	$chamadosSred = array();

	$chamadosRgreen = array();
	$chamadosRyellow = array();
	$chamadosRred = array();



	$hora_inicio = ' 00:00:00';
	$hora_fim = ' 23:59:59';

    $query = "SELECT o.numero, o.data_abertura, o.data_atendimento, o.data_fechamento, o.sistema as cod_area,
				n.nivel_cod as cod_nivel, s.sistema as area, p.problema as problema, sl.slas_desc as sla,
				sl.slas_tempo as tempo , l.*, pr.*, res.slas_tempo as resposta, u.nome as operador
            FROM
				localizacao as l left join prioridades as pr on pr.prior_cod = l.loc_prior
				left join sla_solucao as res on res.slas_cod = pr.prior_sla, problemas as p
				left join sla_solucao as sl on p.prob_sla = sl.slas_cod, ocorrencias as o, sistemas as s,
				usuarios as u, nivel as n
            WHERE
				o.status=4 and s.sis_id=o.sistema and p.prob_id = o.problema  and o.local =l.loc_id and
				o.aberto_por=u.user_id and u.nivel = n.nivel_cod and n.nivel_cod = 3 ";

	if ((isset($_GET['area'])) and ($_GET['area'] != -1))
	{
		$query .= " and o.sistema = ".$_GET['area']." ";
		$getAreaName = "select * from sistemas where sis_id = ".$_GET['area']."";
		$exec = mysql_query($getAreaName);
		$rowAreaName = mysql_fetch_array($exec);
		$nomeArea = $rowAreaName['sistema'];
	} else
		$nomeArea = "TODAS";


	if(isset($_GET['ini']) && isset($_GET['end']) && $_GET['ini']<= $_GET['end']) {
		//$dias_va  //Alterado de data_abertura para data_fechamento -- ordena mudou de fechamento para abertura
		$query .= " and o.data_fechamento >= '".$_GET['ini']."' and o.data_fechamento <= '".$_GET['end']."' and
					o.data_atendimento is not null order by o.data_abertura";
		$resultado = mysql_query($query);       // print "<b>Query--></b> $query<br><br>";
		$linhas = mysql_num_rows($resultado);  //print "Linhas: $linhas";

		if($linhas==0) {
			print "<script>window.alert('Não há dados no período informado!'); history.back();</script>";
		} else {  //if($linhas==0)

			$criterio = "<br>";
			$criterio.="Chamados abertos pelo usuário-final para a área: ".$nomeArea."";

			$background = '#C7D0D9';
			print "<p class='titulo'>RELATÓRIO DE SLAS: INDICADORES DE RESPOSTA e INDICADORES DE SOLUÇÃO".$criterio."</p>";
			print "<table class='centro' cellspacing='0' border='1' >";

			print "<tr bgcolor='".$background."'><td class='line'><B>NUMERO</td>
					<td class='line'><b><a title='tempo de resposta'>T RESPOSTA VALIDO</a></td>
					<td class='line'><b><a title='tempo de solução'>T SOLUCAO VALIDO</a></td></B>
					<td class='line'><b><a title='tempo definido para resposta para cada setor'>SLA Resposta</a></td></B>
					<td class='line'><b><a title='tempo definido para solução para cada problema'>SLA Solução</a></td></B>
					<td class='line'><b><a title='indicador de resposta'>Resposta</a></td></B>
					<td class='line'><b><a title='indicador de solução'>Solução</a></td></B>
					<td class='line'><b><a title='indicador de solução a partir da primeira resposta'>SOL - RESP</a></td></B>
					<td class='line'><b><a title='tempo em que o chamado esteve pendente no usuário'>Dependência ao usuário</a></td></B>
					<td class='line'><b><a title='tempo em que o chamado esteve pendente por algum serviço de terceiros'>Dependência de terceiros</a></td></B>
					<td class='line'><b><a title='tempo em equipamento de backup ou alterado após encerramento'>Fora de dependência</a></td></B>
					<td class='line'><b><a title='Tempo de solução menos o tempo em pendência do usuário'>T Solucao recalculado</a></td></B>
					<td class='line'><b><a title='indicador atualizado descontando a pendência do usuário'>Indicador atualizado</a></td></B>
				</tr>";


			//INICIALIZANDO CONTADORES!!
			$sla_green=0;
			$sla_red=0;
			$sla_yellow=0;
			$slaR_green=0;
			$slaR_red=0;
			$slaR_yellow=0;
			$c_slaS_blue = 0;
			$c_slaS_yellow = 0;
			$c_slaS_red = 0;
			$c_slaR_blue = 0;
			$c_slaR_yellow = 0;
			$c_slaR_red = 0;
			$c_slaM_blue = 0;
			$c_slaM_yellow = 0;
			$c_slaM_red = 0;
			$c_slaR_checked = 0;
			$c_slaS_checked = 0;
			$c_slaM_checked = 0;
			$imgSlaS = 'checked.png';
			$imgSlaR = 'checked.png';
			$imgSlaM = 'checked.png';

			$c_slaSR_blue = 0;
			$c_slaSR_yellow = 0;
			$c_slaSR_red = 0;
			$c_slaSR_checked = 0;

			$total_sol_segundos=0;
			$total_res_segundos=0;
			$total_res_valido=0;
			$total_sol_valido=0;


			$dtS = new dateOpers; //solução
			$dtR = new dateOpers; //resposta
			$dtM = new dateOpers; //tempo entre resposta e solução

			$cont = 0;
			while ($row = mysql_fetch_array($resultado)) {

			// if (array_key_exists($row['cod_area'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
				// $area = $row['cod_area']; //Recebe o valor da área de atendimento do chamado
			// } else $area = 1; //Carga horária default definida no arquivo config.inc.php
				$areaReal=$row['cod_area'];
				$area=testaArea($_GET['area'],$row['cod_area'],$H_horarios);

				$dtR->setData1($row['data_abertura']);
				$dtR->setData2($row['data_atendimento']);
				$dtR->tempo_valido($dtR->data1,$dtR->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

				$dtS->setData1($row['data_abertura']);
				$dtS->setData2($row['data_fechamento']);
				$dtS->tempo_valido($dtS->data1,$dtS->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");
				$t_horas = $dtS->diff["hValido"];

				$dtM->setData1($row['data_atendimento']);
				$dtM->setData2($row['data_fechamento']);
				$dtM->tempo_valido($dtM->data1,$dtM->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");
				//-----------------------------------------------------------------

				$sql_status = "select sum(T.ts_tempo) as segundos, sec_to_time(sum(T.ts_tempo)) as tempo,
							T.ts_status as codStat, A.sistema as area, CAT.stc_desc as dependencia, CAT.stc_cod as cod_dependencia
						from ocorrencias as O, tempo_status as T, `status` as S, sistemas as A, status_categ as CAT
						where O.numero = T.ts_ocorrencia and O.numero = ".$row['numero']." and S.stat_id = T.ts_status and S.stat_cat = CAT.stc_cod and
							O.sistema = A.sis_id and O.sistema =".$areaReal." and O.status = 4 and O.data_fechamento >= '".$_GET['end']."'
							and O.data_fechamento <='".$_GET['ini']."'
						group by A.sis_id,CAT.stc_desc
						order by CAT.stc_cod";
				$exec_sql_status = mysql_query($sql_status);

				//-----------------------------------------------------------------
				//PARA CHECAR O SLA DO PROBLEMA -  TEMPO DE SOLUÇÃO
				$t_segundos_total = $dtS->diff["sValido"];

				if ($row['tempo'] !=""){
					if ($t_segundos_total <= ($row['tempo']*60))  { //transformando em segundos
							//$corSLA = $corSla1;
							$imgSlaS = 'sla1.png';
							$c_slaS_blue++;
					}
					else if ($t_segundos_total <= ( ($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){ //mais 20%
							//$corSLA = $corSla2;
							$imgSlaS = 'sla2.png';
							$c_slaS_yellow++;
					} else {
						//$corSLA = $corSla3;
						$imgSlaS = 'sla3.png';
						$c_slaS_red++;
					}
				} else {
					$imgSlaS = 'checked.png';
					$c_slaS_checked++;
				}
			   //-------------------------------------------------------------------
					//PARA CHECAR O SLA DO SETOR - TEMPO DE RESPOSTA

				$t_segundos_resposta = $dtR->diff["sValido"];
				if ($row['resposta'] != "") {
					if ($t_segundos_resposta <= ($row['resposta']*60))  { //transformando em segundos
							//$corSLA = $corSla1;
							$imgSlaR = 'sla1.png';
							$c_slaR_blue++;
							$chamadosRgreen[]=$row['numero'];
					}
					else if ($t_segundos_resposta <= ( ($row['resposta']*60) + (($row['resposta']*60) *$percLimit/100)) ){ //mais 20%
							//$corSLA = $corSla2;
							$imgSlaR = 'sla2.png';
							$c_slaR_yellow++;
							$chamadosRyellow[]=$row['numero'];
					} else {
						//$corSLA = $corSla3;
						$imgSlaR = 'sla3.png';
						$c_slaR_red++;
						$chamadosRred[]=$row['numero'];
					}
				} else {
					$c_slaR_checked++;
					$imgSlaR = 'checked.png';
				}
			//-----------------------------------------------------------------------

				$t_segundos_m = $dtM->diff["sValido"];

				if ($row['tempo'] !=""){ //está em minutos
					if ($t_segundos_m <= ($row['tempo']*60))  { //transformando em segundos
							$imgSlaM = 'sla1.png';
							$c_slaM_blue++;
					}
					else if ($t_segundos_m <= ( ($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){ //mais 20%
							$imgSlaM = 'sla2.png';
							$c_slaM_yellow++;
					} else {
						$imgSlaM = 'sla3.png';
						$c_slaM_red++;
					}
				} else {
					$imgSlaM = 'checked.png';
					$c_slaM_checked++;
				}

				if ($t_horas>=$sla3) { //>=6
					$cor = $corSla3;
					$sla_red++;
				}
				else
				if ($t_horas>=$sla2) {
					$cor = $corSla2;
					$sla_yellow++;
				}
				else {
					$cor = $corSla1;
					$sla_green++;
				}
				#######################################################################
				$t_resp = $dtR->diff["sValido"];

				if ($t_resp>=$slaR3) {//>=6
					$corR = $corSla3;
					$slaR_red++;
				}
				else
				if ($t_resp>=$slaR2) {
					$corR = $corSla2;
					$slaR_yellow++;
				} else {
					$corR = $corSla1;
					$slaR_green++;
				}

				$total_sol_segundos+= $dtS->diff["sFull"];
				$total_res_segundos+=$dtR->diff["sFull"];
				$total_res_valido+=$dtR->diff["sValido"];
				$total_sol_valido+=$dtS->diff["sValido"];
				//Linhas de dados do relatório
				print "<tr id='linha".$cont."' onMouseOver=\"destaca('linha".$cont."');\" onMouseOut=\"libera('linha".$cont."');\"  onMouseDown=\"marca('linha".$cont."');\">";

				print "<td class='line'><a onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>$row[numero]</font></a></td>
						<td class='line'><font color='".$corR."'>".$dtR->tValido."</font></td>
						<td class='line'><font color='".$cor."'>".$dtS->tValido."</font></td>
						<td class='line'>".$row['resposta']." minutos</font></td>
						<td class='line'>".$row['sla']."</font></td>
						<td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaR."'></a></td>
						<td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaS."'></a></td>
						<td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaM."'></a></td>";
				$dependUser = 0;
				$dependTerc = 0;
				$dependNone = 0;
				while ($row_status = mysql_fetch_array($exec_sql_status)){
					//print $row_status['dependencia'].": ".$row_status['tempo']." | ";
					if ($row_status['cod_dependencia'] == 1) {//dependente ao usuário
						$dependUser+= $row_status['segundos'];
					} else
					if ($row_status['cod_dependencia'] == 3 ){ //dependente de terceiros
						$dependTerc+=$row_status['segundos'];
					} else
					if ($row_status['cod_dependencia'] == 4 ){ //dependente de terceiros
						$dependNone+=$row_status['segundos'];
					}

				}
				print "<td class='line'>";//coluna do tempo vinculado ao usuário
					if ($dependUser != 0)
						$dependUser = $dtS->secToHour($dependUser); else
						$dependUser = "----";
					print $dependUser;
				print "</td>";
				print "<td class='line'>";//coluna do tempo vinculado a terceiros
					if ($dependTerc != 0)
						$dependTerc = $dtS->secToHour($dependTerc); else
						$dependTerc = "----";
					print $dependTerc;
				print "</td>";

				print "<td class='line'>";//coluna do tempo independente (encerrados - em backup..)
					if ($dependNone != 0)
						$dependNone = $dtS->secToHour($dependNone); else
						$dependNone = "----";
					print $dependNone;
				print "</td>";



				print "<td class='line'>";//Solução recalculada
					$solucTotal = $dtS->diff["sValido"];
					//$solucRecalc = $dtS->secToHour($solucTotal);
					$solucRecalc = $solucTotal;
					$imgSlaSR=$imgSlaS;//Solução recalculada

				if ((strpos($dependUser,":")) || (strpos($dependNone,":"))){
					if (strpos($dependUser,":")) {
						$dependUser = $dtS->hourToSec($dependUser);
						$solucRecalc-=$dependUser;
						//$solucRecalc = $dtS->secToHour($solucRecalc);
					}
					if (strpos($dependNone,":")) {
						$dependNone = $dtS->hourToSec($dependNone);
						$solucRecalc-=$dependNone;
						//$solucRecalc = $dtS->secToHour($solucRecalc);
					}
				}
				if ($solucRecalc <0) $solucRecalc*=-1;

				$solucRecalc = $dtS->secToHour($solucRecalc);

				//if ($solucRecalc == $dtS->secToHour($solucTotal)) $solucRecalc = "----";

				print $solucRecalc; //Novo tempo de solução - recalculado tirando as dependências ao usuário ou status independentes

				if ($row['tempo'] !=""){
					if ($dtS->hourToSec($solucRecalc) <= ($row['tempo']*60))  { //transformando em segundos
							$imgSlaSR = 'sla1.png';
							$c_slaSR_blue++;
							$chamadosSgreen[]= $row['numero'];
					}
					else if ($dtS->hourToSec($solucRecalc) <= ( ($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){ //mais 20%
							$imgSlaSR = 'sla2.png';
							$c_slaSR_yellow++;
							$chamadosSyellow[]= $row['numero'];
					} else {
						$imgSlaS = 'sla3.png';
						$c_slaSR_red++;
						$chamadosSred[]= $row['numero'];
					}
				} else {
					$imgSlaSR = 'checked.png';
					$c_slaSR_checked++;
				}


				print "</td>";
				print "<td class='line'><img height='14' width='14' src='../../includes/imgs/".$imgSlaSR."'></td>";

				print "</tr>";
				$cont++;
			}//while chamados

			$media_resposta_geral = $dtR->secToHour(floor($total_res_segundos/$linhas));
			$media_solucao_geral = $dtS->secToHour(floor($total_sol_segundos/$linhas));
			$media_resposta_valida = $dtR->secToHour(floor($total_res_valido/$linhas));
			$media_solucao_valida = $dtS->secToHour(floor($total_sol_valido/$linhas));

			print "<tr><td colspan=5><b>MÉDIAS -></td><td class='line'><b>$media_resposta_valida</td><td class='line'><B>$media_solucao_valida</td></tr>";

			//MEDIAS DE SOLUÇÃO
			$perc_ate_sla2=round((($sla_green*100)/$linhas),2);
			$perc_ate_sla3=round((($sla_yellow*100)/$linhas),2);
			$perc_mais_sla3=round((($sla_red*100)/$linhas),2);
			//MEDIAS DE RESPOSTA
			$perc_ate_slaR2=round((($slaR_green*100)/$linhas),2);
			$perc_ate_slaR3=round((($slaR_yellow*100)/$linhas),2);
			$perc_mais_slaR3=round((($slaR_red*100)/$linhas),2);

			$slaR2M = $slaR2/60;
			$slaR3M = $slaR3/60;
			#####################################################################################
			//TOTAL DE HORAS VÁLIDAS NO PERÍODO:
			$area_fixa = 1;//Padrao
			$dt = new dateOpers;
			$dt->setData1($_GET['ini']);
			$dt->setData2($_GET['end']);
			$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$area_fixa][0],$H_horarios[$area_fixa][1],$H_horarios[$area_fixa][2],$H_horarios[$area_fixa][3],"H");
			$hValido = $dt->diff["hValido"]+1; //Como o período passado não é arredondado (xx/xx/xx 23:59:59) é necessário arrendondar o total de horas.
     ####################################################################################
			print "</table>";


			##TRANSFORMAÇÕES DOS ARRAYS

			$numerosRed=putComma($chamadosSred);
			$numerosYellow=putComma($chamadosSyellow);
			$numerosGreen=putComma($chamadosSgreen);

			$numerosRred=putComma($chamadosRred);
			$numerosRyellow=putComma($chamadosRyellow);
			$numerosRgreen=putComma($chamadosRgreen);



			## QUADROS DE ESTATÍSTICAS

			print "<table align='center' cellspacing='0'>";
			print "  <tr><td colspan =4></td><td class='line'></td></tr>";
			print "  <tr bgcolor='#C7D0D9'><td colspan=4 align=center><b>Período: ".$_GET['ini']." a ".$_GET['end']."</b></td></tr>";
			print "  <tr bgcolor='#C7D0D9'><td colspan=4 align=center><b>Total de horas válidas no período: ".$hValido."</b></td></tr>";
			print "  <tr bgcolor='#C7D0D9'><td colspan='4' align='center'><b>Total de chamados fechados no período: $linhas.</b></td></tr>";
			print "  <tr><td colspan =4></td></tr>";
			print "<tr><td class='line'><b>Resposta em até ".$slaR2M." minutos:</b></TD><td class='line'><font color=".$corSla1."> $slaR_green chamados = </font></TD><td class='line'><font color=".$corSla1.">$perc_ate_slaR2%</font></td><td class='line'></td></tr>";
			print "<tr><td class='line'><b>Resposta em até ".$slaR3M." minutos:</b></TD><td class='line'><font color=".$corSla2."> $slaR_yellow chamados = </font></TD><td class='line'><font color=".$corSla2.">$perc_ate_slaR3%</font></td><td class='line'></td></tr>";
			print "<tr><td class='line'><b>Resposta em mais de ".$slaR3M." minutos:</b></TD><td class='line'><font color=".$corSla3."> $slaR_red chamados = </font></TD><td class='line'><font color=".$corSla3.">$perc_mais_slaR3%</font></td><td class='line'></td></tr>";
			print "  <tr><td colspan=4><hr></td></tr>";

			print "<tr><td class='line'><b>Solução em até ".$sla2." horas:</b></TD><td class='line'><font color=".$corSla1."> $sla_green chamados = </font></TD><td class='line'><font color=".$corSla1.">$perc_ate_sla2%</font></td><td class='line'></td></tr>";
			print "<tr><td class='line'><b>Solução em até ".$sla3." horas:</b></TD><td class='line'><font color=".$corSla2."> $sla_yellow chamados = </font></TD><td class='line'><font color=".$corSla2.">$perc_ate_sla3%</font></td><td class='line'></td></tr>";
			print "<tr><td class='line'><b>Solução em mais de ".$sla3." horas:</TD><td class='line'></b><font color=".$corSla3."> $sla_red chamados = </font></TD><td class='line'><font color=".$corSla3.">$perc_mais_sla3%</font></td><td class='line'></td></tr>";
			print "  <tr><td colspan=4><hr></td></tr>";


			$perc_blueS = (round($c_slaS_blue*100/$linhas,2));
			$perc_yellowS = (round($c_slaS_yellow*100/$linhas,2));
			$perc_redS = (round($c_slaS_red*100/$linhas,2));
			$perc_checkedS = (round($c_slaS_checked*100/$linhas,2));
			$perc_blueR = (round($c_slaR_blue*100/$linhas,2));
			$perc_yellowR = (round($c_slaR_yellow*100/$linhas,2));
			$perc_redR = (round($c_slaR_red*100/$linhas,2));
			$perc_checkedR = (round($c_slaR_checked*100/$linhas,2));
			$perc_blueM = (round($c_slaM_blue*100/$linhas,2));
			$perc_yellowM = (round($c_slaM_yellow*100/$linhas,2));
			$perc_redM = (round($c_slaM_red*100/$linhas,2));
			$perc_checkedM = (round($c_slaM_checked*100/$linhas,2));

			$perc_blueSR = (round($c_slaSR_blue*100/$linhas,2));
			$perc_yellowSR = (round($c_slaSR_yellow*100/$linhas,2));
			$perc_redSR = (round($c_slaSR_red*100/$linhas,2));
			$perc_checkedSR = (round($c_slaSR_checked*100/$linhas,2));




			print "<tr bgcolor='#C7D0D9'><td colspan='4' align='center'><b>Tempo de Resposta X SLA definidos</b></td></tr>";
			print "<tr><td class='line'><b>Resposta dentro do SLA:</td><td class='line'><font color='blue'><a onClick= \"javascript: popup_alerta('mostra_chamados.php?popup=true&numero=".$numerosRgreen."')\">".$c_slaR_blue."</a></font> chamados</b></td><td class='line'>".$perc_blueR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td></tr>";
			print "<tr><td class='line'><b>Resposta até ".$percLimit."% acima do SLA:</td><td class='line'><font color='blue'><a onClick= \"javascript: popup_alerta('mostra_chamados.php?popup=true&numero=".$numerosRyellow."')\">".$c_slaR_yellow."</a></font> chamados</b></td><td class='line'>".$perc_yellowR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td></tr>";
			print "<tr><td class='line'><b>Resposta acima de ".$percLimit."% do SLA:</td><td class='line'><font color='blue'><a onClick= \"javascript: popup_alerta('mostra_chamados.php?popup=true&numero=".$numerosRred."')\">".$c_slaR_red."</a></font> chamados</b></td><td class='line'>".$perc_redR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td></tr>";
			print "<tr><td class='line'><b>Tempo de resposta não definido para o setor:</td><td class='line'>".$c_slaR_checked." chamados</b></td><td class='line'>".$perc_checkedR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/checked.png'></td></tr>";
			print "  <tr><td colspan=4><hr></td></tr>";


			print "<tr bgcolor='#C7D0D9'><td colspan='4' align='center'><b>Tempo de Solução X SLA definidos</b></td></tr>";
			print "<tr><td class='line'><b>Solução dentro do SLA:</td><td class='line'>".$c_slaS_blue." chamados</b></td><td class='line'>".$perc_blueS."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td></tr>";
			print "<tr><td class='line'><b>Solução até ".$percLimit."% acima do SLA:</b></td><td class='line'>".$c_slaS_yellow." chamados</td><td class='line'>".$perc_yellowS."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td></tr>";
			print "<tr><td class='line'><b>Solução acima de ".$percLimit."% do SLA:</b></td><td class='line'>".$c_slaS_red." chamados</td><td class='line'>".$perc_redS."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td></tr>";
			print "<tr><td class='line'><b>Tempo de solução não definido para o problema:</b></td><td class='line'>".$c_slaS_checked." chamados</td><td class='line'>".$perc_checkedS."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/checked.png'></td></tr>";
			print "  <tr><td colspan=4><hr></td></tr>";


			print "<tr bgcolor='#C7D0D9'><td colspan='4' align='center'><b>Tempo de Solução a partir da 1.ª resposta</b></td></tr>";
			print "<tr><td class='line'><b>Solução dentro do SLA:</td><td class='line'>".$c_slaM_blue." chamados</b></td><td class='line'>".$perc_blueM."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td></tr>";
			print "<tr><td class='line'><b>Solução até ".$percLimit."% acima do SLA:</b></td><td class='line'>".$c_slaM_yellow." chamados</td><td class='line'>".$perc_yellowM."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td></tr>";
			print "<tr><td class='line'><b>Solução acima de ".$percLimit."% do SLA:</b></td><td class='line'>".$c_slaM_red." chamados</td><td class='line'>".$perc_redM."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td></tr>";
			print "<tr><td class='line'><b>Tempo de solução não definido para o problema:</b></td><td class='line'>".$c_slaM_checked." chamados</td><td class='line'>".$perc_checkedM."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/checked.png'></td></tr>";
			print "  <tr><td colspan=4><hr></td></tr>";

			print "<tr bgcolor='#C7D0D9'><td colspan='4' align='center'><b>Tempo de Solução recalculado</b></td></tr>";
			print "<tr><td class='line'><b>Solução dentro do SLA:</td><td class='line'><font color='blue'><a onClick= \"javascript: popup_alerta('mostra_chamados.php?popup=true&numero=".$numerosGreen."')\">".$c_slaSR_blue."</a></font> chamados</b></td><td class='line'>".$perc_blueSR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla1.png'></td></tr>";
			print "<tr><td class='line'><b>Solução até ".$percLimit."% acima do SLA:</b></td><td class='line'><font color='blue'><a onClick= \"javascript: popup_alerta('mostra_chamados.php?popup=true&numero=".$numerosYellow."')\">".$c_slaSR_yellow."</a></font> chamados</td><td class='line'>".$perc_yellowSR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla2.png'></td></tr>";
			print "<tr><td class='line'><b>Solução acima de ".$percLimit."% do SLA:</b></td><td class='line'><font color='blue'><a onClick= \"javascript: popup_alerta('mostra_chamados.php?popup=true&numero=".$numerosRed."')\">".$c_slaSR_red."</a></font> chamados</td><td class='line'>".$perc_redSR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/sla3.png'></td></tr>";
			print "<tr><td class='line'><b>Tempo de solução não definido para o problema:</b></td><td class='line'>".$c_slaSR_checked." chamados</td><td class='line'>".$perc_checkedSR."%</td><td class='line'><img height='14' width='14' src='../../includes/imgs/checked.png'></td></tr>";
			print "  <tr><td colspan=4><hr></td></tr>";


			$sql_total_sec = "select sum(T.ts_tempo) as segundos
					from ocorrencias as O, tempo_status as T, `status` as S, sistemas as A
					where O.numero = T.ts_ocorrencia and S.stat_id = T.ts_status and
						O.sistema = A.sis_id and O.sistema = ".$areaReal." and O.status = 4 and O.data_fechamento >=  '".$_GET['ini']."'  and
						O.data_fechamento <= '".$_GET['end']."'
					group by A.sis_id,T.ts_status
					order by segundos desc, A.sistema,T.ts_status";
			$exec_total_sec = mysql_query($sql_total_sec);
			$total_sec = 0;
			while ($row_total_sec = mysql_fetch_array($exec_total_sec)){
				$total_sec+=$row_total_sec['segundos'];
			}
							 //$total_sol_valido;

			$sql_cada_status = "select S.status as status,  sum(T.ts_tempo) as segundos, concat(sum(T.ts_tempo) /$total_sec*100,'%') as porcento, sec_to_time(sum(T.ts_tempo)) as tempo,
					T.ts_status as codStat, A.sistema as area
					from ocorrencias as O, tempo_status as T, `status` as S, sistemas as A
					where O.numero = T.ts_ocorrencia and S.stat_id = T.ts_status and
						O.sistema = A.sis_id and O.sistema =".$areaReal." and O.status = 4 and O.data_fechamento >=  '".$_GET['ini']."'  and
						O.data_fechamento <= '".$_GET['end']."'
					group by A.sis_id,T.ts_status
					order by segundos desc, A.sistema,T.ts_status";
			$exec_cada_status = mysql_query($sql_cada_status);
			print "<tr><td colspan='4' align='center'><b>Quadro de chamados por tempo em cada status</b></td></tr>";
			print "<tr bgcolor='#C7D0D9'><td class='line'>STATUS</td><td colspan='2'>TEMPO</td><td class='line'>PERCENTUAL</td></tr>";

			//print $sql_cada_status."<br>";
			while ($row_cada_status = mysql_fetch_array($exec_cada_status)) {
				print "<tr><td class='line'>".$row_cada_status['status']."</td><td colspan='2'>".$row_cada_status['tempo']."</td><td class='line'>".$row_cada_status['porcento']."</td></tr>";
			}
			print "  <tr><td colspan=4><hr></td></tr>";

			$sql_total_sec2 = "select sum(T.ts_tempo) as segundos, sec_to_time(sum(T.ts_tempo)) as tempo,
					T.ts_status as codStat, A.sistema as area, CAT.stc_desc as dependencia, CAT.stc_cod as cod_dependencia
					from ocorrencias as O, tempo_status as T, `status` as S, sistemas as A, status_categ as CAT
					where O.numero = T.ts_ocorrencia and S.stat_id = T.ts_status and S.stat_cat = CAT.stc_cod and
						O.sistema = A.sis_id and O.sistema =".$areaReal." and O.status = 4 and O.data_fechamento >='".$_GET['ini']."' and
						O.data_fechamento <='".$_GET['end']."'
					group by A.sis_id,CAT.stc_desc
					order by segundos desc, A.sistema,T.ts_status	";
			$exec_total_sec2 = mysql_query($sql_total_sec2);
			$total_sec2 = 0;
			while ($row_total_sec2 = mysql_fetch_array($exec_total_sec2)){
				$total_sec2+=$row_total_sec2['segundos'];
			}



			$sql_vinc_status = "select sum(T.ts_tempo) as segundos, sec_to_time(sum(T.ts_tempo)) as tempo,
					concat(sum(T.ts_tempo) /$total_sec2*100,'%') as porcento,
					T.ts_status as codStat, A.sistema as area, CAT.stc_desc as dependencia, CAT.stc_cod as cod_dependencia
					from ocorrencias as O, tempo_status as T, `status` as S, sistemas as A, status_categ as CAT
					where O.numero = T.ts_ocorrencia and S.stat_id = T.ts_status and S.stat_cat = CAT.stc_cod and
						O.sistema = A.sis_id and O.sistema =".$areaReal." and O.status = 4 and O.data_fechamento >='".$_GET['ini']."' and
						O.data_fechamento <='".$_GET['end']."'
					group by A.sis_id,CAT.stc_desc
					order by segundos desc, A.sistema,T.ts_status	";
			$exec_vinc = mysql_query($sql_vinc_status);
			print "<tr><td colspan='4' align='center'><b>Quadro chamados por tempo de dependência de atendimento</b></td></tr>";
			print "<tr bgcolor='#C7D0D9'><td class='line'>DEPENDÊNCIA</td><td colspan='2'>TEMPO</td><td class='line'>PERCENTUAL</td></tr>";
			while ($row_vinc = mysql_fetch_array($exec_vinc)) {
				print "<tr><td class='line'>".$row_vinc['dependencia']."</td><td colspan='2'>".$row_vinc['tempo']."</td><td class='line'>".$row_vinc['porcento']."</td></tr>";

			}
			print "  <tr><td colspan=4><hr></td></tr>";

			print "  <tr><td colspan=4><hr></td></tr>";
			print "</table>";
		} //if($linhas==0)
	//if  $d_ini_completa <= $d_fim_completa
	} else {
		$aviso = "A data final não pode ser menor do que a data inicial. Refaça sua pesquisa.";
		print "<script>mensagem('".$aviso."'); history.back();</script>";
	}



?>

<script type='text/javascript'>

<!--
	function popup(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
		//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
		return false
	}

	function popup_alerta(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
		//x.moveTo(100,100);
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}

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


	function valida(){
		var ok = validaForm('idD_ini','DATA-','Data Inicial',1);
		if (ok) var ok = validaForm('idD_fim','DATA-','Data Final',1);

		if (ok) submitForm();

		return ok;
	}


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

