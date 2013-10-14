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

?>
<script type='text/javascript'>
	function popup_alerta(pagina)	{ //Exibe uma janela popUP
		x = window.open(pagina,'_blank','width=700,height=470,scrollbars=yes,statusbar=no,resizable=yes');
		x.moveTo(window.parent.screenX+50, window.parent.screenY+50);
		return false
	}
</script>
<?

print "<HTML><head><title>Histórico de status</title></head>";
print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

        $hoje = date("Y-m-d H:i:s");

	$sql = "SELECT T.ts_ocorrencia as chamado, S.status as status,  sum(T.ts_tempo) as total, sec_to_time(sum(T.ts_tempo)) as tempo, ".
		"SAT.status as status_atual, T.ts_status as codStat, O.status as codStatAtual, T.ts_data as data ".
		"FROM ocorrencias as O, tempo_status as T, `status` as S, `status` as SAT ".
		"WHERE O.numero = T.ts_ocorrencia and S.stat_id = T.ts_status and T.ts_ocorrencia = ".$_GET['numero']." and O.status = SAT.stat_id ".
		"GROUP BY T.ts_ocorrencia, T.ts_status ".
		"ORDER BY T.ts_ocorrencia, T.ts_status";
	$exec_sql = mysql_query($sql);
	$qtd = mysql_num_rows($exec_sql);
	$tempoTotalSec = 0;
	$tempoSecStatAtual = 0;
	if ($qtd==0) {
		print  "<br><br><b>Não existe histórico de status para esse chamado!</b> <br>Essa consulta só é possível para chamados abertos apartir de 09 de Setembro de 2004.<br><br>";
	}  else {

		print "<br><b>Histórico de status para a ocorrência <font color='red'>".$_GET['numero']."</font>:</b><br>";
		print "<table cellspacing='1' border='1' cellpadding='1' align='left' width='100%'><tr><td class='line'><b>Status</b></td><td class='line'><b>Tempo</b></td></tr>";
		while ($row = mysql_fetch_array($exec_sql)) {
			$tempoTotalSec+=$row['total'];

			if ($row['codStatAtual'] != 4){
				if ($row['codStat'] == $row['codStatAtual']) {//Verifico o status atual para buscar a data
					$data = $row['data']; //só preciso dessa data se o chamado não estiver encerrado!!
				}
			} else {
			//.....//
			}
			$codStatAtual = $row['codStatAtual'];
			$statAtual = $row['status_atual'];
			if ($row['codStat'] != $row['codStatAtual']) { //vou imprimir o status atual fora do loop
				print "<tr><td class='line'>".$row['status']."</td><td class='line'>".$row['tempo']."</td></tr>";
			} else
				$tempoSecStatAtual = $row['total'];
		}

		$dt = new dateOpers;
		$tempoHoraStatAtual = "";

		if ($codStatAtual == 4) {//encerrada
			$tempoHora = $dt->secToHour($tempoTotalSec);
		} else {
			//chamados ainda não encerrados
			$areaChamado = 1;
			$dt->setData1($data);
			$dt->setData2($hoje);

			$dt->tempo_valido($dt->data1,$dt->data2,$H_horarios[$areaChamado][0],$H_horarios[$areaChamado][1],$H_horarios[$areaChamado][2],$H_horarios[$areaChamado][3],"H");
			$segundos = $dt->diff["sValido"]; //segundos válidos
			$tempoHoraStatAtual = $dt->secToHour($segundos+$tempoSecStatAtual);

			$tempoHora = $dt->secToHour($segundos+$tempoTotalSec);
		}
		print "<tr><td class='line'><b><font color='green'>".$statAtual."</b> (Status Atual)</td><td class='line'><b><font color='green'>".$tempoHoraStatAtual."</b></font></td></tr>";
		print "<tr><td class='line'>TEMPO TOTAL</td><td class='line'>".$tempoHora."</td></tr>";
		print "</table>";
	}

print "</body>";
print "</html>";
?>