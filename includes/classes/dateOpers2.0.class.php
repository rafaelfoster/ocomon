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
  */


class dateOpers {
	var $data1;
	var $data2;
	var $forma;

	var $dif_sec;
	var $dif_date;
	var $diff;
	var $dDomingo;
	var $tValido;

	function formatDate ($data){
		$ano = "";
		$mes = "";
		$dia = "";
		$hora = "";
		$minuto = "";
		$segundo = "";

		 //formato brasileiro
		if (ereg ("([0-9]{1,2})/([0-9]{1,2})/([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $data, $sep)) {

			$dia = $sep[1];
		 	$mes = $sep[2];
		 	$ano = $sep[3];
		 	$hora = $sep[4];
		  	$minuto = $sep[5];
		  	$segundo = $sep[6];
		} else
			//formato americano
		if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $data, $sep)) {
			$dia = $sep[3];
		 	$mes = $sep[2];
		 	$ano = $sep[1];
		 	$hora = $sep[4];
		  	$minuto = $sep[5];
		  	$segundo = $sep[6];
		}
		$data = strtotime($ano."-".$mes."-".$dia." ".$hora.":".$minuto.":".$segundo);
		return $data;
	}


	function setData1 ($data){
		//$this->formatDate($data);
		//$this->data1 = $data;
		$this->data1 = $this->formatDate($data);
	}

	function setData2 ($data){
 		//$this->formatDate($data);
 		//$this->data2 = $data;

		$this->data2 = $this->formatDate($data);
	}

	function getData1 (){
		print $this->data1;
	}

	function getData2 (){
		print $this->data2;
	}

	function secToHour($sec){
		$h = intval($sec/3600);
		$sec -= $h*3600;
		$m = intval($sec/60);
		$sec -= $m*60;

		if(strlen($h) == 1){$h = "0".$h;}; //Coloca um zero antes
		if(strlen($m) == 1){$m = "0".$m;}; //Coloca um zero antes
		if(strlen($sec) == 1){$sec = "0".$sec;}; //Coloca um zero antes

        	$v = $h.":".$m.":".$sec;
		return $v;
	}

	function hourToSec($hour){
		$s = 0;
		if (ereg ("([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $hour, $sep)) {
			//$sep = explode(":",$hour);
			$s= $sep[3];
			$s+=$sep[2]*60;
			$s+=$sep[1]*3600;
		} else
			return "INVALID HOUR FORMAT!";
		return $s;
	}


	function diff_time($data1,$data2){
	    	$s = strtotime($data2)-strtotime($data1);
		$secs = $s;
		$emHora=$this->secToHour($secs);
		$sep = explode(":",$emHora);
		$hFull = $sep[0];

		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;

        	$v = $d." dias ".$h.":".$m.":".$s;
		$min = $m;

		$dados = explode(":",$v);

		$dias = $d;

		$horas = $h;
		$minutos = $m;
		$segundos = $s;

		$dias *=86400; //Dia de 24 horas
		$horas *=3600;
		$minutos *=60;
		$segundos +=$dias+$horas+$minutos;

		$h = intval($segundos/3600);
		$m = intval($segundos/60);

		if(strlen($h) == 1){$h = "0".$h;}; //Coloca um zero antes
		if(strlen($min) == 1){$min = "0".$min;}; //Coloca um zero antes
		if(strlen($s) == 1){$s = "0".$s;}; //Coloca um zero antes
		//$h:$min:$s
		$this->diff = array("dFull"=>$d, "hFull"=>$hFull, "mFull"=>$m, "sFull"=>$secs, "tHoras"=>$emHora, "tDias"=>$v);

		return $this->diff;
	}




	function somadata($dias,$datahoje){

		// Desmembra Data -------------------------------------------------------------
		//FORMATO VÁLIDO: ANO-MES-DIA
		if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $datahoje, $sep)) {
			$dia = $sep[3];
			$mes = $sep[2];
			$ano = $sep[1];
			$time = $sep[4].":".$sep[5].":".$sep[6];
		} else {
			echo "<b>INVALID DATE FORMAT: ".$datahoje."</b><br>";
			exit;
		}

		$i = $dias;

		for($i = 0;$i<$dias;$i++){

			if ($mes == "01" || $mes == "03" || $mes == "05" || $mes == "07" || $mes == "08" || $mes == "10" || $mes == "12"){
				if($mes == 12 && $dia == 31){
					$mes = 01;
					$ano++;
					$dia = 00;
				}
				if($dia == 31 && $mes != 12){
					$mes++;
					$dia = 00;
				}
			}//fecha if geral

			if($mes == "04" || $mes == "06" || $mes == "09" || $mes == "11"){
				if($dia == 30){
					$dia = 00;
					$mes++;
				}
			}//fecha if geral

			if($mes == "02"){
				if($ano % 4 == 0 && $ano % 100 != 0){ //ano bissexto
					if($dia == 29){
						$dia = 00;
						$mes++;
					}
				} else{
					if($dia == 28){
						$dia = 00;
						$mes++;
					}
				}
			}//FECHA IF DO MÊS 2
			$dia++;
		}//fecha o for()

		// Confirma Saída de 2 dígitos ------------------------------------------------
		if(strlen($dia) == 1){$dia = "0".$dia;}; //Coloca um zero antes
		if(strlen($mes) == 1){$mes = "0".$mes;};

		$nova_data = $ano."-".$mes."-".$dia." ".$time;

		return $nova_data;
	}//fecha a funçâo data



	function diasDomingo($data1,$data2){

		$this->diff_time($data1, $data2);
		$dias_diff = $this->diff["dFull"];

		$domingo=0;

		if ($dias_diff>=1) {
			$temp = $data1;
			for ($i=1;$i<=$dias_diff; $i++){
				$temp = $this->somadata($i,$data1);
				$dias[$i]= date("l",strtotime($temp));
				if ($dias[$i]=="Sunday") {
					$domingo++;
				}
			}
			//$validos = $dias_diff-$domingo;
		}// else $validos=$dias_diff;

		$this->dDomingo=$domingo;
		return $domingo;
	}



	//Retorna o tempo válido em horas ou segundos entre duas datas descontando finais de semana e feriados.
	//Também desconta os horários fora da carga horária de cada área.
	function tempo_valido($data1,$data2,$hora_ini,$hora_fim,$meio_dia,$sabado,$saida){
		set_time_limit(300);
		if (empty($data1)|| empty($data2)) {
			$noData = true;
		} else {
			//Inverte a ordem das datas se os parâmetros estiverem invertidos!!
			$noData = false;
			if ($data1 > $data2) {
				$temp = $data1;
				$data1 = $data2;
				$data2 = $temp;
			}

			//Verifica se existem feriados nos dias úteis cadastrados na tabela feriados entre as duas datas;
			$sql = "SELECT data_feriado AS dia_semana ".
				"FROM feriados ".
				"WHERE data_feriado BETWEEN '".$data1."' AND '".$data2."' AND date_format( data_feriado, '%w' ) NOT IN ( 0, 6 )";
			$resultado = mysql_query($sql);
			$feriados = mysql_num_rows($resultado);//Em dias úteis

			//Verifica os feriados que cairam em Domingo;
			$sql2 = "SELECT data_feriado AS dia_semana ".
					"FROM feriados ".
					"WHERE data_feriado ".
					"BETWEEN '".$data1."' AND '".$data2."' AND date_format( data_feriado, '%w' ) IN ( 0 )";
			$resultado2 = mysql_query($sql2);
			$feriados_domingo = mysql_num_rows($resultado2);

			//Verifica os feriados que cairam em Sábado;
			$sql3 = "SELECT data_feriado AS dia_semana ".
					"FROM feriados ".
					"WHERE data_feriado ".
					"BETWEEN '".$data1."' AND '".$data2."' AND date_format( data_feriado, '%w' ) IN ( 6 )";
			$resultado3 = mysql_query($sql3);
			$feriados_sabado = mysql_num_rows($resultado3);

			$feriados+= $feriados_domingo+$feriados_sabado;
			$invalidos=0; //Inicializando o numero de horas inválidas do intervalo!!

			//$diffSegundos = diff_em_segundos($data1,$data2); //Diferença total em segundos entre as duas datas!
			$this->diff_time($data1,$data2);
			$diffSegundos = $this->diff["sFull"];
			$dias_cheios = $this->diff["dFull"];

			$data1_aux= explode("-",date("d-m-Y-H-i-s",strtotime($data1))); //Formatação da data!
			$data2_aux= explode("-",date("d-m-Y-H-i-s",strtotime($data2)));

			$dia_abert = $data1_aux[0].$data1_aux[1].$data1_aux[2];
			$dia_fech = $data2_aux[0].$data2_aux[1].$data2_aux[2];
			//$t_horas = $horas_completas[0]; //Diferença em horas completas!
			$t_horas = $this->diff["hFull"];

			$hora_1 = $data1_aux[3];
			$hora_2 = $data2_aux[3];

			if ($t_horas >= 1) {

				//Horas invalidas dos dias cheios
				if ($dias_cheios>=1) {//>=
					for ($i=0;$i<24; $i++){

						if ($i>$hora_fim || $i<=$hora_ini || $i==$meio_dia) {
							$invalidos++;
						}
					}
					$invalidos*=$dias_cheios;
				}
				if ($dia_abert!=$dia_fech) {
					//Retirando as horas invalidas do primeiro dia
					for ($i=$hora_1+1;$i<=24; $i++){
						if ($i>$hora_fim || $i <=$hora_ini || $i==$meio_dia) {
							$invalidos++;
						}
					}
					//Retirando as horas inválidas do último dia
					for ($i=1; $i<$hora_2+1; $i++){
						if ($i>$hora_fim || $i <=$hora_ini || $i==$meio_dia) {
							$invalidos++;
						}
					}
					//--------------------------------------------------------------------
				} else { //Verifica as horas inválidas no período dentro do mesmo dia!!
					for ($i=$hora_1+1;$i<=$hora_2;$i++){
						if ($i>$hora_fim || $i <=$hora_ini || $i==$meio_dia) {
							$invalidos++;
						}
					}
				}
			}

			$horas_invalidas_segundos = $invalidos*3600; //Total de horas invalidas em segundos

			//$domingos = dias_invalidos($data1,$data2)-$feriados_domingo;##### //Quantos Domingos existem no período
			$domingos = $this->diasDomingo($data1,$data2)-$feriados_domingo;

			$sabados = $this->diasDomingo($data1,$data2)-$feriados_sabado;
			$domingo = $hora_fim - $hora_ini; //Período de horas normalmente trabalhadas durante a semana que precisam ser...
												//.. descontadas dos Domingos!!
			if ($meio_dia > $hora_ini && $meio_dia < $hora_fim) { //Se existe intervalo (almoço) na carga horária!
				$domingo--;
			}
			$domingo*=3600; //Transformo em segundos
			$sabado*=3600; //Transformo em segundos
			$feriados*=$domingo; //A quantidade de horas inválidas de um feriado é igual às horas de um Domingo!
			$sabado = $domingo-$sabado; //A quantidade de horas inválidas do Sábado é iqual às horas do Domingo menos..
										// ... as horas trabalhadas no sábado.
			$final_de_semana = (($sabado*$sabados)+($domingo*$domingos)+$feriados); //Total de horas inválidas em todo o período!!

			$total_tempo_valido = $diffSegundos-($horas_invalidas_segundos+$final_de_semana);
			//$total_tempo_valido_horas = segundos_em_horas($total_tempo_valido);//$total_tempo_valido_horas;
			$total_tempo_valido_horas = $this->secToHour($total_tempo_valido);
			$auxiliar = explode(":",$total_tempo_valido_horas);

			if(strlen($auxiliar[0]) == 1){$auxiliar[0] = "0".$auxiliar[0];}; //Coloca um zero antes
			if(strlen($auxiliar[1]) == 1){$auxiliar[1] = "0".$auxiliar[1];}; //Coloca um zero antes
			if(strlen($auxiliar[2]) == 1){$auxiliar[2] = "0".$auxiliar[2];}; //Coloca um zero antes
		}
		if ($noData) {
			$msg = "EMPTY DATE!";
			return $msg;
		} else
		if ($saida=="S") {
			$this->diff["hValido"]=$auxiliar[0];
			$this->diff["sValido"]=$total_tempo_valido;
			$this->tValido=$total_tempo_valido;
			return $total_tempo_valido;
		} else
		if ($saida=="H") {
			$this->diff["hValido"]=$auxiliar[0];
			$this->diff["sValido"]=$total_tempo_valido;
			$this->tValido=$auxiliar[0].":".$auxiliar[1].":".$auxiliar[2];
			return  $auxiliar[0].":".$auxiliar[1].":".$auxiliar[2];
		}
	}

}
?>