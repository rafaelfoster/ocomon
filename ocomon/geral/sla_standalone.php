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
	}//if !isset($_POST['ok'])

	else { //if $ok==Pesquisar


		print "<html><body class='relatorio'>";

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


		$query = "";

    		$query = "SELECT o.numero, o.data_abertura, o.data_atendimento, o.data_fechamento, o.sistema as cod_area, o.date_first_queued, ".
					"s.sistema as area, 	p.problema as problema, sl.slas_desc as sla, sl.slas_tempo as tempo , l.*, pr.*, ".
					"res.slas_tempo as resposta, res.slas_desc as resposta_desc, u.nome as operador ".
				"FROM localizacao as l left join prioridades as pr on pr.prior_cod = l.loc_prior left join sla_solucao as res on ".
					"res.slas_cod = pr.prior_sla, problemas as p left join sla_solucao as sl on p.prob_sla = sl.slas_cod, ".
					"ocorrencias as o, sistemas as s, usuarios as u ".
				"WHERE  s.sis_id=o.sistema and p.prob_id = o.problema  and o.local =l.loc_id and ".
					"o.operador=u.user_id"; //o.status=4 and

		$query.= " AND o.numero = ".$_POST['numero']."";


		//$query .= " AND o.data_fechamento >= '".$d_ini_completa."' and o.data_fechamento <= '".$d_fim_completa."' and ".
				//"o.data_atendimento is not null order by o.data_abertura";
		$resultado = mysql_query($query);       // print "<b>Query--></b> $query<br><br>";
		$linhas = mysql_num_rows($resultado);  //print "Linhas: $linhas";

		//print $query."<br>";

		if($linhas==0) {

			print "<script>window.alert('".TRANS('MSG_NO_REGISTER_PERIOD')."'); history.back();</script>";
		} else  { //if($linhas==0)
			$campos=array();


			$saida = -1;
			switch($saida)
			{
				case -1:
					$criterio = "<br>";

					//echo "<br><br>";
					$background = '#C7D0D9';
					print "<p class='titulo'>".TRANS('TLT_REP_SLAS_INDICES')."".$criterio."</p>";
				print "<table class='centro' cellspacing='0' border='1' >";

				print "<tr bgcolor='".$background."'><td ><B>".TRANS('OCO_FIELD_NUMBER')."</td>
					<td ><b><a title='tempo de resposta'>".TRANS('COL_TIT_TEMP_VAL_RESP')."</a></td>
					<td ><b><a title='tempo de solução'>".TRANS('COL_TIT_TEMP_VAL_SOL')."</a></td></B>
					<td ><b><a title='tempo definido para resposta para cada setor'>".TRANS('COL_TIT_SLA_RESP')."</a></td></B>
					<td ><b><a title='tempo definido para solução para cada problema'>".TRANS('COL_TIT_SLA_SOL')."</a></td></B>
					<td ><b><a title='indicador de resposta'>".TRANS('COL_TIT_REPLY')."</a></td></B>
					<td ><b><a title='indicador de solução'>".TRANS('COL_TIT_SOLUTION')."</a></td></B>
					<td ><b><a title='indicador de solução a partir da primeira resposta'>".TRANS('COL_SOL_RESP')."</a></td></B>
					<td ><b><a title='tempo em que o chamado esteve pendente no usuário'>".TRANS('COL_USER_DEPEN')."</a></td></B>
					<td ><b><a title='tempo em que o chamado esteve pendente por algum serviço de terceiros'>".TRANS('COL_DEPEN_THIRD')."</a></td></B>
					<td ><b><a title='tempo em equipamento de backup ou alterado após encerramento'>".TRANS('COL_IT_ARRE_DEPEN')."</a></td></B>
					<td ><b><a title='Tempo de solução menos o tempo em pendência do usuário'>".TRANS('COL_RECALC_SOLUTION')."</a></td></B>
					<td ><b><a title='indicador atualizado descontando a pendência do usuário'>".TRANS('COL_POINTER_UPDATE')."</a></td></B>
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
					if (isset($row['data_atendimento'])) $data_atendimento = $row['data_atendimento'];
					
					
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
					if (isset($row['data_fechamento'])) $data_final = $row['data_fechamento'];
					
					$dtS->setData2($data_final);
					$dtS->tempo_valido($dtS->data1,$dtS->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");
					$t_horas = $dtS->diff["hValido"];

					#TRABALHA SOBRE O TEMPO DE SOLUÇÃO A PARTIR DO TEMPO DE RESPOSTA
					$dtM->setData1($data_atendimento);
					$dtM->setData2($data_final);
					$dtM->tempo_valido($dtM->data1,$dtM->data2,$H_horarios[$area][0],$H_horarios[$area][1],$H_horarios[$area][2],$H_horarios[$area][3],"H");

					$sql_status = "SELECT sum(T.ts_tempo) as segundos, sec_to_time(sum(T.ts_tempo)) as tempo, ".
									"T.ts_status as codStat, A.sistema as area, CAT.stc_desc as dependencia, CAT.stc_cod as cod_dependencia ".
								"FROM ocorrencias as O, tempo_status as T, `status` as S, sistemas as A, status_categ as CAT ".
								"WHERE O.numero = T.ts_ocorrencia and O.numero = ".$row['numero']." and S.stat_id = T.ts_status ".
									"and S.stat_cat = CAT.stc_cod and O.sistema = A.sis_id and O.sistema =".$areaReal." ".
									//" and O.data_fechamento >= '".$d_ini_completa."' and O.data_fechamento <='".$d_fim_completa."' ".
								"GROUP BY A.sis_id,CAT.stc_desc ".
								"ORDER BY CAT.stc_cod";
					$exec_sql_status = mysql_query($sql_status);
					//print $sql_status."<br>";
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
					//print "<tr id='linha".$cont."' onMouseOver=\"destaca('linha".$cont."', '".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linha".$cont."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linha".$cont."', '".$_SESSION['s_colorMarca']."');\">";
					print "<tr id='linha".$cont."'  onMouseDown=\"marca('linha".$cont."', '".$_SESSION['s_colorMarca']."');\">";

					print "<td ><a onClick= \"javascript: popup_alerta('mostra_consulta.php?popup=true&numero=".$row['numero']."')\"><font color='blue'>".$row['numero']."</font></a></td>
						<td ><font color='".$corR."'>".$dtR->tValido."</font></td>
						<td ><font color='".$corR."'>".$dtS->tValido."</font></td>
						<td >".$row['resposta_desc']."</font></td>
						<td >".$row['sla']."</font></td>
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
					//print "</td>";
					print "<td >";//coluna do tempo vinculado ao usuário
					if ($dependUser != 0)
						$dependUser = $dtS->secToHour($dependUser); else
						$dependUser = "-";
					print $dependUser;
					print "</td>";
					print "<td >";//coluna do tempo vinculado a terceiros
					if ($dependTerc != 0)
						$dependTerc = $dtS->secToHour($dependTerc); else
						$dependTerc = "-";
					print $dependTerc;
					print "</td>";

					print "<td >";//coluna do tempo independente (encerrados - em backup..)
					if ($dependNone != 0)
						$dependNone = $dtS->secToHour($dependNone); else
						$dependNone = "-";
					print $dependNone;
					print "</td>";

					print "<td >";//Solução recalculada
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
					print "<td ><img height='14' width='14' src='../../includes/imgs/".$imgSlaSR."'></td>";

					print "</tr>";
					$cont++;
				}//while chamados

				$media_resposta_geral = $dtR->secToHour(floor($total_res_segundos/$linhas));

				$media_solucao_geral = $dtS->secToHour(floor($total_sol_segundos/$linhas));
				$media_resposta_valida = $dtR->secToHour(floor($total_res_valido/$linhas));
				$media_solucao_valida = $dtS->secToHour(floor($total_sol_valido/$linhas));

				//print "<tr><td colspan=5><b>".TRANS('COL_AVERAGE')."</td><td ><b>".$media_resposta_valida."</td><td ><B>".$media_solucao_valida."</td></tr>";

			} // switch
		} //if($linhas==0)
/*	else 	{
		$aviso = "".TRANS('MSG_DATE_FINISH_UNDERAGE_DATE_BEGIN')."";
		print "<script>mensagem('".$aviso."'); history.back();</script>";
	}*/
	//}//if ((empty($d_ini)) and (empty($d_fim)))

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

