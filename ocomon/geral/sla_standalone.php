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
	//$_SESSION['s_page_ocomon'] = $_SERVER['PHP_SELF'];

	$auth = new auth;
	if (isset($_GET['popup'])){
		$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);
	} else
		$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	if (!isset($_POST['ok']) && !isset($_GET['numero'])) { //&& $_POST['ok'] != 'Pesquisar')
		print "<html>";
		print "<head><script language=\"JavaScript\" src=\"../../includes/javascript/calendar.js\"></script></head>";
		print "<body>";
		print "	<BR><BR>";
		print "	<B><center>:::".TRANS('TLT_INDICE_STATUS_CALL').":::</center></B><BR><BR>";
		print "		<FORM action='".$_SERVER['PHP_SELF']."' method='post' name='form1' onSubmit=\"return valida()\" >"; //onSubmit=\"return valida()\"
		print "		<TABLE border='0' align='center' cellspacing='2'  bgcolor=".BODY_COLOR." >";
		
		print "				<tr>";
		print "					<td bgcolor=".TD_COLOR.">".TRANS('OCO_NUMBER').":</td>";
		//print "					<td ><INPUT type='text' name='d_ini' class='data' id='idD_ini'><a href=\"javascript:cal1.popup();\"><img height='14' width='14' src='../../includes/javascript/img/cal.gif' width='16' height='16' border='0' alt='Selecione a data'></a></td>";
		print "					<td><INPUT type='text' name='numero' class='text' id='idNumero' value=''></td>";
		print "				</tr>";
		
		print "<tr><td colspan='2'><input type='checkbox' name='novaJanela' title='".TRANS('HNT_NEW_WINDOW').".'>".TRANS('OPT_NEW_WINDOW')."<td><tr>";
		print "		</TABLE><br>";

		print "		<TABLE align='center'>";
		print "			<tr>";
		print "	            <td>";
		//print"					<input type='hidden' name='sis_name' value='$sis_name' ";
		print "					<input type='submit'  class='button' value='".TRANS('BT_SEARCH')."' name='ok' >";//onClick=\"submitForm();\"
		print "	            </TD>";
		print "	            <td>";
		print "					<INPUT type='reset'  class='button' value='".TRANS('BT_CLEAR')."' name='cancelar'>";
		print "				</TD>";
		print "			</tr>";
		print "	    </TABLE>";
		print "</form>";
		print "</BODY>";
		print "</html>";
	} else { //if $ok==Pesquisar


		$newicon = false;
		$icon = ICONS_PATH.'sla.png';
		if(isset($_GET['SCHEDULED']) && $_GET['SCHEDULED']==1) { //SE O CHAMADO ESTIVER AGENDADO NAO SERAO EXIBIDAS AS INFORMACOES
			$newicon = true;
			//oco_scheduled=1
			//<img height='16' width='16' src='".ICONS_PATH."sla.png' title='".TRANS('HNT_REMAIN_TIME')."'>
			//exit;
		}

		print "<html><body class='relatorio'>";

		$numero = $_REQUEST['numero'];
		
		//PARAMETRIZAR ESSES VALORES

		//SLA 1 é menor do que o SLA 2 - VERDE
		$sla3 = 6; //INICIO DO VERMELHO - Tempo de SOLUÇÃO EM HORAS
		$sla2 = 4; //INÍCIO DO AMARELO
		$slaR3 = 3600; //Tempo de RESPOSTA em segundos VERMELHO
		$slaR2 = 1800; //AMARELO
		$percLimit = 20; //Limite em porcento que um chamado pode estourar para ficar no SLA2 antes de ficar no vermelho

		//$sla3 = 6; //INICIO DO VERMELHO - Tempo de SOLUÇÃO EM HORAS
		//$sla2 = 4; //INÍCIO DO AMARELO
		//$slaR3 = 14400; //Tempo de RESPOSTA em segundos VERMELHO
		//$slaR2 = 7200; //AMARELO
		//$percLimit = 20; //Limite em porcento que um chamado pode estourar para ficar no SLA2 antes de ficar no vermelho


		$corSla1 = "green";
		$corSla2 = "orange";
		$corSla3 = "red";


		$chamadosSgreen = array();
		$chamadosSyellow = array();
		$chamadosSred = array();

		$chamadosRgreen = array();
		$chamadosRyellow = array();
		$chamadosRred = array();

		$hora_inicio = ' 00:00:00';
		$hora_fim = ' 23:59:59';


		$qryUpdOco = "UPDATE ocorrencias SET data_abertura = oco_real_open_date WHERE data_abertura = '0000-00-00 00:00:00' ";
		$execUpdoco = mysql_query($qryUpdOco);


		
		$clausula = "p.prob_id = o.problema";
		if (isset($_GET['new_prob']) && $_GET['new_prob']!=-1){
			$clausula = "p.prob_id = ".$_GET['new_prob']." ";
		}
		
		$query = "";

    		$query = "SELECT o.numero, o.data_abertura, o.data_atendimento, o.data_fechamento, o.sistema as cod_area, o.date_first_queued, ".
					"o.`status` as status, o.oco_scheduled, ".
					"st.stat_painel as painel, ".
					"s.sistema as area, p.problema as problema, sl.slas_desc as sla, sl.slas_tempo as tempo , l.*, pr.*, ".
					"res.slas_tempo as resposta, res.slas_desc as resposta_desc, u.nome as operador ".
				"FROM localizacao as l left join prioridades as pr on pr.prior_cod = l.loc_prior left join sla_solucao as res on ".
					"res.slas_cod = pr.prior_sla, problemas as p left join sla_solucao as sl on p.prob_sla = sl.slas_cod, ".
					"ocorrencias as o, sistemas as s, usuarios as u, `status` as st ".
				"WHERE  s.sis_id=o.sistema and ".$clausula." and o.local =l.loc_id and ".
					"o.operador=u.user_id and o.`status` = st.stat_id"; //o.status=4 and

		$query.= " AND o.numero = ".$numero."";

		$resultado = mysql_query($query);
		//$resultado2 = mysql_query($query); 
		//$rowPainel = mysql_fetch_array($resultado2);
		$linhas = mysql_num_rows($resultado);  


		if($linhas==0) {

			print "<script>window.alert('".TRANS('MSG_NO_REGISTER_PERIOD')."'); history.back();</script>";
		} else  
		
		//if($rowPainel['painel']!=3) //SÓ REALIZARÁ OS CÁLCULOS PARA CHAMADOS EM ABERTO NO SISTEMA;
		{ //if($linhas==0)
			$campos=array();


			$saida = -1;
			switch($saida)
			{
				case -1:
					$criterio = "<br>";
					$background = '#C7D0D9';
				print "<table class='centro' cellspacing='0' border='0' width='90%'>";

				print "<tr bgcolor='".TD_COLOR."'>".
					//"<td class='line'><B>".TRANS('OCO_FIELD_NUMBER')."</td>".
					"<td class='line'><b><a title='".TRANS('HNT_RESPONSE_TIME')."'>".TRANS('OCO_RESPONSE')."</a></td>";
					print "<td class='line'><b><a title='".TRANS('HNT_SOLUTION_TIME')."'>".TRANS('OCO_SOLUC')."</a></td></B>";
					print "<td class='line'><b><a title='tempo definido para resposta para cada setor'>".TRANS('COL_TIT_SLA_RESP')."</a></td></B>
					<td class='line'><b><a title='tempo definido para solução para cada problema'>".TRANS('COL_TIT_SLA_SOL')."</a></td></B>
					<td class='line'><b><a title='indicador de resposta'>".TRANS('COL_TIT_REPLY')."</a></td></B>
					<td class='line'><b><a title='indicador de solução'>".TRANS('COL_TIT_SOLUTION')."</a></td></B>
					<td class='line'><b><a title='indicador de solução a partir da primeira resposta'>".TRANS('COL_SOL_RESP')."</a></td></B>
					<td class='line'><b><a title='tempo em que o chamado esteve pendente no usuário'>".TRANS('COL_USER_DEPEN')."</a></td></B>
					<td class='line'><b><a title='tempo em que o chamado esteve pendente por algum serviço de terceiros'>".TRANS('COL_DEPEN_THIRD')."</a></td></B>
					<td class='line'><b><a title='tempo em equipamento de backup ou alterado após encerramento'>".TRANS('COL_IT_ARRE_DEPEN')."</a></td></B>
					<td class='line'><b><a title='Tempo de solução menos o tempo em pendência do usuário'>".TRANS('COL_RECALC_SOLUTION')."</a></td></B>
					<td class='line'><b><a title='indicador atualizado descontando a pendência do usuário'>".TRANS('COL_POINTER_UPDATE')."</a></td></B>
					<td class='line'><b><a title='".TRANS('HNT_REMAIN_TIME_IN_SLA')."'>".TRANS('COL_REMAIN_TIME')."</a></td></B>
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


				$total_sol_segundos = 0;
				$total_res_segundos = 0;
				$total_res_valido = 0;
				$total_sol_valido = 0;


				$dtS = new dateOpers; //solução
				$dtR = new dateOpers; //resposta
				$dtM = new dateOpers; //tempo entre resposta e solução
				$cont = 0;
				while ($row = mysql_fetch_array($resultado)) {
					// if (array_key_exists($row['cod_area'],$H_horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
						// $area = $row['cod_area']; //Recebe o valor da área de atendimento do chamado
					// } else $area = 1; //Carga horária default definida no arquivo config.inc.php
										
					
					$areaReal=$row['cod_area'];
					$area = "";
					$area=testaArea($row['cod_area'],$row['cod_area'],$H_horarios);

					#TRABALHA SOBRE O TEMPO DE RESPOSTA
					$data_atendimento = date("Y-m-d H:i:s");
					if (isset($row['data_atendimento'])) $data_atendimento = $row['data_atendimento']; else
					if(!isset($row['data_atendimento']) && isset($row['data_fechamento']) ) $data_atendimento = $row['data_fechamento'];
					
					
					if (isset($row['date_first_queued'])){
						$dtR->setData1($row['date_first_queued']);
					} else {
						$dtR->setData1($row['data_abertura']);
					}
					
					$dtR->setData2($data_atendimento);
					$dtR->tempo_valido($dtR->data1,$dtR->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

					#TRABALHA SOBRE O TEMPO DE SOLUÇÃO
					if (isset($row['date_first_queued'])){
						$dtS->setData1($row['date_first_queued']);
					} else {
						$dtS->setData1($row['data_abertura']);
					}
					
					$data_final = date("Y-m-d H:i:s");
					if (isset($row['data_fechamento'])) $data_final = $row['data_fechamento']; else
					if (!isset($row['data_fechamento']) && $row['painel'] == 3 ) $data_final = $row['data_abertura'];
					
					$dtS->setData2($data_final);
					$dtS->tempo_valido($dtS->data1,$dtS->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");
					$t_horas = $dtS->diff["hValido"];

					#TRABALHA SOBRE O TEMPO DE SOLUÇÃO A PARTIR DO TEMPO DE RESPOSTA
					$dtM->setData1($data_atendimento);
					$dtM->setData2($data_final);
					$dtM->tempo_valido($dtM->data1,$dtM->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

					//-----------------------------------------------------------------------------------
					##TRATANDO O CONTADOR DE TEMPO EM CADA STATUS PARA OS CASOS ONDE O CHAMADO AINDA ESTIVER EM ABERTO
						$sql_ts_anterior = "select * from tempo_status where ts_ocorrencia = ".$row['numero']." and ts_status = ".$row['status']." ";
						$exec_sql = mysql_query($sql_ts_anterior);
	
						if ($exec_sql == 0) $error= " erro 1";
	
						$achou = mysql_num_rows($exec_sql);
						if ($achou >0){ //esse status já esteve setado em outro momento
							$row_ts = mysql_fetch_array($exec_sql);
	
							$dtSt = new dateOpers;
							$dtSt->setData1($row_ts['ts_data']);
							$dtSt->setData2(date("Y-m-d H:i:s"));
							$dtSt->tempo_valido($dtSt->data1,$dtSt->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");
							$segundosNovos = $dtSt->diff["sValido"]; //segundos válidos
							
// 							$sql_upd = "SELECT (ts_tempo+".$segundosNovos.") as ts_tempo , '".date("Y-m-d H:i:s")."' as ts_data FROM tempo_status WHERE ts_ocorrencia = ".$row['numero']." and
// 									ts_status = ".$row['status']." ";							
// 							$exec_upd = mysql_query($sql_upd);
// 							$row_openStat = mysql_fetch_array($exec_upd);
	
						}					
					
					
					//-----------------------------------------------------------------------------------
					
					$sql_status = "SELECT sum(T.ts_tempo) as segundos, sec_to_time(sum(T.ts_tempo)) as tempo, ".
									"T.ts_status as codStat, A.sistema as area, CAT.stc_desc as dependencia, CAT.stc_cod as cod_dependencia ".
								"FROM ocorrencias as O, tempo_status as T, `status` as S, sistemas as A, status_categ as CAT ".
								"WHERE O.numero = T.ts_ocorrencia and O.numero = ".$row['numero']." and S.stat_id = T.ts_status ".
									"and S.stat_cat = CAT.stc_cod and O.sistema = A.sis_id and O.sistema =".$areaReal." ".
									//" and O.data_fechamento >= '".$d_ini_completa."' and O.data_fechamento <='".$d_fim_completa."' ".
								"GROUP BY A.sis_id,CAT.stc_desc ".
								"ORDER BY CAT.stc_cod";
					$exec_sql_status = mysql_query($sql_status);
					//PARA CHECAR O SLA DO PROBLEMA -  TEMPO DE SOLUÇÃO
					$t_segundos_total = $dtS->diff["sValido"];

					if ($row['tempo'] !=""){
						
						if ($t_segundos_total <= ($row['tempo']*60))  { //transformando em segundos
							//$corSLA = $corSla1;
							$imgSlaS = 'sla1.png';
							$c_slaS_blue++;
						} else
						if ($t_segundos_total <= ( ($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){ //mais 20%
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

					//PARA CHECAR O SLA DO SETOR - TEMPO DE RESPOSTA
					$t_segundos_resposta = $dtR->diff["sValido"];
					if ($row['resposta'] != "") {
						if ($t_segundos_resposta <= ($row['resposta']*60))  { //transformando em segundos
							//$corSLA = $corSla1;
							$imgSlaR = 'sla1.png';
							$c_slaR_blue++;
							$chamadosRgreen[]=$row['numero'];
						} else
						if ($t_segundos_resposta <= ( ($row['resposta']*60) + (($row['resposta']*60) *$percLimit/100)) ){ //mais 20%
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

					$t_segundos_m = $dtM->diff["sValido"];

					if ($row['tempo'] !=""){ //está em minutos
						if ($t_segundos_m <= ($row['tempo']*60))  { //transformando em segundos
							$imgSlaM = 'sla1.png';
							$c_slaM_blue++;
						} else if ($t_segundos_m <= ( ($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){ //mais 20%
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

					if ($t_horas>=$sla3) {//>=6
						$cor = $corSla3;
						$sla_red++;
					} else
					if ($t_horas>=$sla2) {
						$cor = $corSla2;
						$sla_yellow++;
					} else {
						$cor = $corSla1;
						$sla_green++;
					}
					$t_resp = $dtR->diff["sValido"];

					if ($t_resp>=$slaR3) {//>=6
						$corR = $corSla3;
						$slaR_red++;
					} else
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
					print "<tr id='linha".$cont."'  onMouseDown=\"marca('linha".$cont."', '".$_SESSION['s_colorMarca']."');\">";

					//print "<td><a onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['numero']."</font></a></td>";
						print "<td><font color='".$corR."'>".$dtR->tValido."</font></td>";
						print "<td><font color='".$corR."'>".$dtS->tValido."</font></td>";
						print "<td>".$row['resposta_desc']."</font></td>
						<td >".$row['sla']."</font></td>";
						
						if ($newicon){//CHAMADOS AGENDADOS
							$imgSlaR = $icon;
							$imgSlaS = $icon;
							$imgSlaM = $icon;
						}
						
						
						print "<td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaR."'></a></td>
						<td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaS."'></a></td>
						<td align='center'><a onClick=\"javascript:popup('mostra_hist_status.php?popup=true&numero=".$row['numero']."')\"><img height='14' width='14' src='../../includes/imgs/".$imgSlaM."'></a></td>";

					$dependUser = 0;
					$dependTerc = 0;
					$dependNone = 0;
					while ($row_status = mysql_fetch_array($exec_sql_status)){
						if ($row_status['cod_dependencia'] == 1) {//dependente ao usuário
							$dependUser+= $row_status['segundos'];
							$row_status['codStat']== $row['status']? $dependUser+=$segundosNovos:''; //Atualiza o tempo para o status atual caso esteja em aberto
						} else
						if ($row_status['cod_dependencia'] == 3 ){ //dependente de terceiros
							$dependTerc+=$row_status['segundos'];
							$row_status['codStat']== $row['status']? $dependTerc+=$segundosNovos:'';
						} else
						if ($row_status['cod_dependencia'] == 4 ){ //dependente de terceiros
							$dependNone+=$row_status['segundos'];
							$row_status['codStat']== $row['status']? $dependNone+=$segundosNovos:'';
						}
					}
					//print "</td>";
					print "<td>";//coluna do tempo vinculado ao usuário
					if ($dependUser != 0)
						$dependUser = $dtS->secToHour($dependUser); else
						$dependUser = "-";
					print $dependUser;
					print "</td>";
					print "<td>";//coluna do tempo vinculado a terceiros
					if ($dependTerc != 0)
						$dependTerc = $dtS->secToHour($dependTerc); else
						$dependTerc = "-";
					print $dependTerc;
					print "</td>";

					print "<td>";//coluna do tempo independente (encerrados - em backup..)
					if ($dependNone != 0)
						$dependNone = $dtS->secToHour($dependNone); else
						$dependNone = "-";
					print $dependNone;
					print "</td>";

					print "<td>";//Solução recalculada
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

					//print $solucRecalc; //Novo tempo de solução - recalculado tirando as dependências ao usuário ou status independentes
					!$newicon?print $solucRecalc:print "-";//Se o chamado estiver agendado nao eh exibido o indicador


					$tempo_restante = '-';
					if ($row['tempo'] !=""){
						if ($dtS->hourToSec($solucRecalc) <= ($row['tempo']*60))  { //transformando em segundos
								$imgSlaSR = 'sla1.png';
								$c_slaSR_blue++;
								$chamadosSgreen[]= $row['numero'];
						
							$tempo_restante = $row['tempo']*60 - $dtS->hourToSec($solucRecalc);
							$tempo_restante = $dtS->secToHour($tempo_restante);
						
						}
						else if ($dtS->hourToSec($solucRecalc) <= ( ($row['tempo']*60) + (($row['tempo']*60) *$percLimit/100)) ){ //mais 20%
								$imgSlaSR = 'sla2.png';
								$c_slaSR_yellow++;
								$chamadosSyellow[]= $row['numero'];
						} else {
							$imgSlaSR = 'sla3.png';
							$c_slaSR_red++;
							$chamadosSred[]= $row['numero'];
						}
					} else {
						$imgSlaSR = 'checked.png';
						$c_slaSR_checked++;
					}
					print "</td>";
					
					if ($newicon){//CHAMADOS AGENDADOS
						$imgSlaSR = $icon;
					}
					
					print "<td><img height='14' width='14' src='../../includes/imgs/".$imgSlaSR."'></td>";
					print "<td>".$tempo_restante."</td>";
					print "</tr>";
					$cont++;
					
					if ($row['painel']!= 3 ){//CHAMADOS JÁ CONCLUIDOS NO SISTEMA
						//VERIFICA SE O CHAMADO CONSTA NA TABELA DE CHECAGEM DE SLAS ESTOURADOS
						$qryTmp = "SELECT * FROM sla_out WHERE out_numero = ".$row['numero']." ";
						$execTmp = mysql_query($qryTmp) OR die(mysql_error());					
						if ($c_slaSR_red > 0){//SLA ESTOUROU
							$OUT = 1;
						} else {
							$OUT = 0;
						
						}
						if(mysql_num_rows($execTmp)) {
							$qryUpdate = "UPDATE sla_out SET out_sla=".$OUT." WHERE out_numero = ".$row['numero']."";
							$execUpdate = mysql_query($qryUpdate) OR die(mysql_error());
						} else {
							$qryInsert = "INSERT INTO sla_out (out_numero, out_sla) values (".$row['numero'].", ".$OUT.") ";
							$execInsert = mysql_query($qryInsert) OR die(mysql_error()."<BR>".$qryInsert);
						}						
					}
				}//while chamados


			} // switch
		} //if($linhas==0)

	
		
	print "</body></html>";
}//if $ok==Pesquisar

?>
<script type='text/javascript'>
<!--

		function popup(pagina)	{ //Exibe uma janela popUP
			x = window.open(pagina,'popup','dependent=yes,width=400,height=200,scrollbars=yes,statusbar=no,resizable=yes');
			x.moveTo(window.parent.screenX+100, window.parent.screenY+100);
			return false
		}

		function popup_alerta(pagina)	{ //Exibe uma janela popUP
                	x = window.open(pagina,'_blank','dependent=yes,width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
	                x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
                	return false
             	}

		function checar() {
			var checado = false;
			if (document.form1.novaJanela.checked){
		      	checado = true;
			} else {
				checado = false;
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

