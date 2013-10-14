<?php 
 /*                        Copyright 2005 Fl·vio Ribeiro

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


## CRIAR VARI¡VEL PARA DEFINI«√O DO PADR√O DE DATA EX: PADR√O AMERICANO, PADR√O BRASILEIRO

class dateOpers {
	var $data1; //Primeira par‚metro de data
	var $data2; //Segundo par‚metro de data
	var $forma;

	var $dif_sec;
	var $dif_date;
	var $diff;
	var $dDomingo;
	var $tValido;
	var $dFullAll;

	function formatDate ($data){ //Retorna saÌda no formado AAAA-MM-DD HH:mm:SS

		$ano = 0;
		$mes = 0;
		$dia = 0;
		$hora = 0;
		$minuto = 0;
		$segundo = 0;

		 //formato brasileiro com hora!!!
		if (ereg ("([0-9]{1,2})[/|-]([0-9]{1,2})[/|-]([0-9]{4}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $data, $sep)) {

			$dia = $sep[1];
		 	$mes = $sep[2];
		 	$ano = $sep[3];
		 	$hora = $sep[4];
		  	$minuto = $sep[5];
		  	$segundo = $sep[6];
		} else
			//formato americano com hora
		if (ereg ("([0-9]{4})[/|-]([0-9]{1,2})[/|-]([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $data, $sep)) {
			$dia = $sep[3];
		 	$mes = $sep[2];
		 	$ano = $sep[1];
		 	$hora = $sep[4];
		  	$minuto = $sep[5];
		  	$segundo = $sep[6];
		} else
			print "Invalid date format!!";

		//$data = strtotime($ano."-".$mes."-".$dia." ".$hora.":".$minuto.":".$segundo);
		$data = $ano."-".$mes."-".$dia." ".$hora.":".$minuto.":".$segundo;
		return $data;
	//...
	}


	function setData1 ($data){

		$this->data1 = $this->formatDate($data);
		//....
	}

	function setData2 ($data){
		$this->data2 = $this->formatDate($data);
		//....
	}

	function getData1 (){
		print $this->data1;
	}

	function getData2 (){
		print $this->data2;
	}

	function secToHour($sec){ //Recebe valor formatado em segundos (valor inteiro) e retorna em formato de hora
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
		//if (ereg ("([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $hour, $sep)) {
		if (ereg ("([0-9]{1,}):([0-9]{1,2}):([0-9]{1,2})", $hour, $sep)) {
			//$sep = explode(":",$hour);
			$s= $sep[3];
			$s+=$sep[2]*60;
			$s+=$sep[1]*3600;
		}
		return $s;
	}


	function somadata($dias,$datahoje){ //Formato americano de data com hora

		// Desmembra Data -------------------------------------------------------------
		//FORMATO V√ÅLIDO: ANO-MES-DIA
		if (ereg ("([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})", $datahoje, $sep)) {
			$dia = $sep[3];
			$mes = $sep[2];
			$ano = $sep[1];
			$time = $sep[4].":".$sep[5].":".$sep[6];

		} else {
		  echo "<b>Invalid date format (valid format: aaaa-mm-dd) - $datahoje</b><br>";
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
				}
				else{
					if($dia == 28){
						$dia = 00;
						$mes++;
					}
				}
			}//FECHA IF DO MES 2
			$dia++;
		}//fecha o for()

		// Confirma Sa√≠da de 2 d√≠gitos ------------------------------------------------

		if(strlen($dia) == 1){$dia = "0".$dia;}; //Coloca um zero antes
		if(strlen($mes) == 1){$mes = "0".$mes;};

		// Monta Sa√≠da ----------------------------------------------------------------

		$nova_data = $ano."-".$mes."-".$dia." ".$time;

		//print $nova_data;
		return $nova_data;
	}//fecha a fun√ß√¢o data


	function fullDays($data1,$data2){
		$fullDays = false;
		$fisrt_is_minor = false;

		$sep1 = explode(" ",$this->somadata(2,$data1));
		$sep2 = explode(" ",$data2);

		if ($sep1['0'] <= $sep2['0']) {
			$fullDays = true;
		} else
			$fullDays = false;

		$sep1['1'] = $this->hourToSec($sep1['1']);
		$sep2['1'] = $this->hourToSec($sep2['1']);

		if ($sep1['1']<=$sep2['1']){
			$first_is_minor = true;
			//print "<br>O hora de inÌcio È menor!<br>";
		} else {
			$first_is_minor = false;
			//print "<br>O hora de inÌcio È maior!<br>";
		}

		$this->dFullAll = array("fullDays"=>$fullDays, "first_is_minor"=>$first_is_minor);
		//return $fullDays;
		return $this->dFullAll;
	}

	function fullDays_old($data1,$data2){
		$fullDays = false;
		if ($this->somadata(2,$data1) <= $data2) {
			$fullDays = true;
		} else
			$fullDays = false;
		return $fullDays;
	}


	//Par‚metros em segundos - data1: data mais antiga - data2: data mais recente (SEMPRE NO FORMATO AMERICANO!!!
	function diff_time($data1,$data2){
		$s = strtotime($data2)-strtotime($data1);
		$secs = $s;
		$emHora=$this->secToHour($secs);
		$sep = explode(":",$emHora);
		$hFull = $sep[0];

		$dFullOK = 0; //criado em 14-05-08

		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;

		if(strlen($h) == 1){$h = "0".$h;}; //Coloca um zero antes
		if(strlen($m) == 1){$m = "0".$m;}; //Coloca um zero antes
		if(strlen($s) == 1){$s = "0".$s;}; //Coloca um zero antes

		$v = $d." dias ".$h.":".$m.":".$s;
		$min = $m;

		$dias = $d;

		//ALTERA«√O PARA AJUSTAR O TOTAL DE DIAS CHEIOS - 14-15-08
		//if ($this->fullDays($data1,$data2)){

		$this->fullDays($data1,$data2);

		## CONTROLE PARA IDENTIFICAR DIAS CHEIOS NO INTERVALO
		if ($this->dFullAll['fullDays']){
			if ($dias>1) {
				if ($this->dFullAll['first_is_minor']) {
			 		$hFull>24?$dFullOK=$dias-1:$dFullOK=$dias;
			 	} else {
			 		$dFullOK=$dias;
			 	}
			} else
				$dFullOK=$dias;
		} else {
			$dFullOK = 0;
		}

		$horas = $h;
		$minutos = $m;
		$segundos = $s;

		$dias *=86400; //Dia de 24 horas
		$horas *=3600;
		$minutos *=60;
		$segundos +=$dias+$horas+$minutos;

		$h = intval($segundos/3600);
		$m = intval($segundos/60);

		//Alterado em 14-05-08
		//$this->diff = array("dFull"=>$d, "hFull"=>$hFull, "mFull"=>$m, "sFull"=>$secs, "tHoras"=>$emHora, "tDias"=>$v);
		$this->diff = array("dFullTotal"=>$d, "dFull"=>$dFullOK, "hFull"=>$hFull, "mFull"=>$m, "sFull"=>$secs, "tHoras"=>$emHora, "tDias"=>$v);

		return $this->diff;
	}


	function diasDomingo($data1,$data2){//Retorna a quantidade de Domingos do perÌodo

		$this->diff_time($data1, $data2);
		$dias_diff = $this->diff["dFullTotal"];

		//print "<br>Funcao diasDomingo()";
		//print "<br>dias_diff: ".$this->diff["dFullTotal"]."<br><br>";

		$domingo=0;

		if ($dias_diff>=1) {
			$temp = $data1;
			//for ($i=1;$i<=$dias_diff; $i++){
			for ($i=0;$i<=$dias_diff; $i++){
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


	//Retorna o tempo v√°lido em horas ou segundos entre duas datas descontando finais de semana e feriados.
	//Tamb√©m desconta os hor√°rios fora da carga hor√°ria de cada √°rea.

	//Colocar par‚metro para identificar o tempo de intervalo
	//PARAMETROS:
	//	hora_ini: inicio da jornada
	//	hora_fim: final da jornada
	//	meio_dia: intervalo
	//	sabado: total de horas trabalhadas nos s·bados
	//	saida: formato da saÌda da funÁ„o, se ser· em horas ou segundos
	function tempo_valido($data1,$data2,$hora_ini,$hora_fim,$meio_dia,$sabado,$saida){
		@set_time_limit(300);
		$noData = false;
		if (empty($data1)|| empty($data2)) {
			$noData = true;
		} else {

			$data1 = $this->formatDate($data1);
			$data2 = $this->formatDate($data2);

			//Inverte a ordem das datas se os par√¢metros estiverem invertidos!!
			if ($data1>$data2) {
				$temp = $data1;
				$data1 = $data2;
				$data2 = $temp;
			}

			/*-------------------------------------------------------------------------------------------*/
			$data1_aux= explode("-",date("Y-m-d-H-i-s",strtotime($data1))); //EXTRAINDO OS ELEMENTOS DA DATA
			$data2_aux= explode("-",date("Y-m-d-H-i-s",strtotime($data2)));
			/*-------------------------------------------------------------------------------------------*/



			if ($data1_aux['3']<$hora_ini){ //ABERTURA COMPARADA A HORA DE INÕCIO
				$data1_aux['3'] = $hora_ini;
				$data1_aux['4'] = "00";
				$data1_aux['5'] = "00";

				$data1 = $data1_aux['0']."-".$data1_aux['1']."-".$data1_aux['2']." ".$data1_aux['3'].":".$data1_aux['4'].":".$data1_aux['5'];
			} else
			if ($data1_aux['3']>=$hora_fim){ //ABERTURA COMPARADA A HORA DE FIM
				$data1_aux['3'] = $hora_fim;
				$data1_aux['4'] = "00";
				$data1_aux['5'] = "00";

				$data1 = $data1_aux['0']."-".$data1_aux['1']."-".$data1_aux['2']." ".$data1_aux['3'].":".$data1_aux['4'].":".$data1_aux['5'];
			} else
			if (($data1_aux['3']>=($meio_dia-1)) && ($data1_aux['3'] <$meio_dia) && $meio_dia!=0) { //ABERTURA COMPARADA A HORA MEIO DIA
				$data1_aux['3'] = $meio_dia;
				$data1_aux['4'] = "00";
				$data1_aux['5'] = "00";

				$data1 = $data1_aux['0']."-".$data1_aux['1']."-".$data1_aux['2']." ".$data1_aux['3'].":".$data1_aux['4'].":".$data1_aux['5'];
			}


			if ($data2_aux['3']>=$hora_fim){//HORA FECHAMENTO COMPARADA A HORA FIM
				$data2_aux['3'] = $hora_fim;
				$data2_aux['4'] = "00";
				$data2_aux['5'] = "00";
				$data2 = $data2_aux['0']."-".$data2_aux['1']."-".$data2_aux['2']." ".$data2_aux['3'].":".$data2_aux['4'].":".$data2_aux['5'];
			} else
			if ($data2_aux['3']<$hora_ini){//HORA FECHAMENTO COMPARADA A HORA INI
				$data2_aux['3'] = $hora_ini;
				$data2_aux['4'] = "00";
				$data2_aux['5'] = "00";
				$data2 = $data2_aux['0']."-".$data2_aux['1']."-".$data2_aux['2']." ".$data2_aux['3'].":".$data2_aux['4'].":".$data2_aux['5'];
			} else
			if (($data2_aux['3']>=($meio_dia-1)) && ($data2_aux['3'] <$meio_dia) && $meio_dia!=0) { //FECHAMENTO COMPARADA A HORA MEIO DIA

				#CONTROLE PARA IDENTIFICAR CHAMADOS ABERTOS E CONSULTADOS NO INTERVALO (DENTRO DO MESMO DIA)
				$abDiasMesAno = $data1_aux['0']."-".$data1_aux['1']."-".$data1_aux['2'];
				$feDiasMesAno = $data2_aux['0']."-".$data2_aux['1']."-".$data2_aux['2'];

				if ($abDiasMesAno == $feDiasMesAno && $data1_aux['3'] == $meio_dia) {
					$data2_aux['3'] = $meio_dia;
				} else {
					$data2_aux['3'] = $meio_dia-1;
				}
				$data2_aux['4'] = "00";
				$data2_aux['5'] = "00";

				$data2 = $data2_aux['0']."-".$data2_aux['1']."-".$data2_aux['2']." ".$data2_aux['3'].":".$data2_aux['4'].":".$data2_aux['5'];
			}


			//Verifica se existem feriados nos dias uteis cadastrados na tabela feriados
			//	entre as duas datas (tambÈm verifica os feriados permanentes);
			$sql = "SELECT data_feriado AS dia_semana, fixo_feriado as permanente ".
				"\nFROM feriados ".
				"\nWHERE ".
					"\n\t(data_feriado BETWEEN '".$data1."' AND '".$data2."' AND date_format( data_feriado, '%w' ) NOT IN ( 0, 6 ))".
						"\n\t\tOR ( fixo_feriado = 1 AND ".

							"\n\t\t\tdate_format(data_feriado,'%m-%d' ) BETWEEN date_format('".$data1."' , '%m-%d' ) ".
							"\n\t\t\tAND date_format('".$data2."' , '%m-%d' ) ".
							"\n\t\t\tAND CONCAT_WS('-','".$data2_aux['0']."', date_format(data_feriado , '%m-%d' )) BETWEEN  '".$data1."' AND '".$data2."' ".
							"\n\t\t\tAND date_format( CONCAT_WS('-','".$data2_aux['0']."', date_format(data_feriado , '%m-%d' )) , '%w' ) NOT IN ( 0, 6 ) ".
							"\n\t\t ) ".
					"\n\tGROUP BY date_format(data_feriado,'%m-%d' )";

			$resultado = mysql_query($sql);
			$feriados = mysql_num_rows($resultado);//Em dias √∫teis

			//Verifica os feriados que cairam em Domingo;
			$sql2 = "SELECT data_feriado AS dia_semana, fixo_feriado as permanente ".
				"\nFROM feriados ".
				"\nWHERE ".
					"\n\t(data_feriado BETWEEN '".$data1."' AND '".$data2."' AND date_format( data_feriado, '%w' ) IN ( 0 ))".
						"\n\t\tOR ( fixo_feriado = 1 AND ".

							"\n\t\t\tdate_format(data_feriado,'%m-%d' ) BETWEEN date_format('".$data1."' , '%m-%d' ) ".
							"\n\t\t\tAND date_format('".$data2."' , '%m-%d' ) ".

							"\n\t\t\tAND CONCAT_WS('-','".$data2_aux['0']."', date_format(data_feriado , '%m-%d' )) BETWEEN  '".$data1."' AND '".$data2."' ".
							"\n\t\t\tAND date_format( CONCAT_WS('-','".$data2_aux['0']."', date_format(data_feriado , '%m-%d' )) , '%w' ) IN ( 0 ) ".
							"\n\t\t ) ".
					"\n\tGROUP BY date_format(data_feriado,'%m-%d' )";

			$resultado2 = mysql_query($sql2);
			$feriados_domingo = mysql_num_rows($resultado2);

			//Verifica os feriados que cairam em S√°bado;
			$sql3 = "SELECT data_feriado AS dia_semana, fixo_feriado as permanente ".
				"\nFROM feriados ".
				"\nWHERE ".
					"\n\t(data_feriado BETWEEN '".$data1."' AND '".$data2."' AND date_format( data_feriado, '%w' ) IN ( 6 ))".
						"\n\t\tOR ( fixo_feriado = 1 AND ".

							"\n\t\t\tdate_format(data_feriado,'%m-%d' ) BETWEEN date_format('".$data1."' , '%m-%d' ) ".
							"\n\t\t\tAND date_format('".$data2."' , '%m-%d' ) ".

							"\n\t\t\tAND CONCAT_WS('-','".$data2_aux['0']."', date_format(data_feriado , '%m-%d' )) BETWEEN  '".$data1."' AND '".$data2."' ".
							"\n\t\t\tAND date_format( CONCAT_WS('-','".$data2_aux['0']."', date_format(data_feriado , '%m-%d' )) , '%w' ) IN ( 6 ) ".
							"\n\t\t ) ".
					"\n\tGROUP BY date_format(data_feriado,'%m-%d' )";

			$resultado3 = mysql_query($sql3);
			$feriados_sabado = mysql_num_rows($resultado3);

			$feriados+= $feriados_domingo+$feriados_sabado;
			$invalidos=0; //Inicializando o numero de horas inv√°lidas do intervalo!!

			//$diffSegundos = diff_em_segundos($data1,$data2); //Diferen√ßa total em segundos entre as duas datas!
			$this->diff_time($data1,$data2);
			$diffSegundos = $this->diff["sFull"];
			$dias_cheios = $this->diff["dFull"];

			##
			$data1_aux= explode("-",date("d-m-Y-H-i-s",strtotime($data1))); //EXTRAINDO OS ELEMENTOS DA DATA
			$data2_aux= explode("-",date("d-m-Y-H-i-s",strtotime($data2)));

			$dia_abert = $data1_aux[0].$data1_aux[1].$data1_aux[2];
			$dia_fech = $data2_aux[0].$data2_aux[1].$data2_aux[2];
			//$t_horas = $horas_completas[0]; //Diferen√ßa em horas completas!
			$t_horas = $this->diff["hFull"];

			$hora_1 = $data1_aux[3];
			$hora_2 = $data2_aux[3];

			if ($t_horas>=1) {

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
					//Retirando as horas inv√°lidas do √∫ltimo dia
					for ($i=1; $i<$hora_2+1; $i++){
						if ($i>$hora_fim || $i <=$hora_ini || $i==$meio_dia) {
							$invalidos++;
						}
					}
				} else { //Verifica as horas inv√°lidas no per√≠odo dentro do mesmo dia!!
					for ($i=$hora_1+1;$i<=$hora_2;$i++){
						if ($i>$hora_fim || $i <=$hora_ini || $i==$meio_dia) {
							$invalidos++;
						}

					}
				}
			}

			$horas_invalidas_segundos = $invalidos*3600; //Total de horas invalidas em segundos

			//$domingos = dias_invalidos($data1,$data2)-$feriados_domingo;##### //Quantos Domingos existem no per√≠odo
			$domingos = $this->diasDomingo($data1,$data2)-$feriados_domingo;
			$sabados = $this->diasDomingo($data1,$data2)-$feriados_sabado;

			$domingo = $hora_fim - $hora_ini; //Per√≠odo de horas normalmente trabalhadas durante a semana que precisam ser...
												//.. descontadas dos Domingos!!
			if ($meio_dia > $hora_ini && $meio_dia < $hora_fim) { //Se existe intervalo (almo√ßo) na carga hor√°ria!
				$domingo--;
			}
			$domingo*=3600; //Transformo em segundos
			$sabado*=3600; //Transformo em segundos
			$feriados*=$domingo; //A quantidade de horas inv√°lidas de um feriado √© igual √†s horas de um Domingo!
			$sabado = $domingo-$sabado; //A quantidade de horas inv√°lidas do S√°bado √© iqual √†s horas do Domingo menos..
										// ... as horas trabalhadas no s√°bado.
			$final_de_semana = (($sabado*$sabados)+($domingo*$domingos)+$feriados); //Total de horas inv√°lidas em todo o per√≠odo!!

			$total_tempo_valido = $diffSegundos-($horas_invalidas_segundos+$final_de_semana);
			//$total_tempo_valido_horas = segundos_em_horas($total_tempo_valido);//$total_tempo_valido_horas;
			$total_tempo_valido_horas = $this->secToHour($total_tempo_valido);
			$auxiliar = explode(":",$total_tempo_valido_horas);

				if(strlen($auxiliar[0]) == 1){$auxiliar[0] = "0".$auxiliar[0];}; //Coloca um zero antes
				if(strlen($auxiliar[1]) == 1){$auxiliar[1] = "0".$auxiliar[1];}; //Coloca um zero antes
				if(strlen($auxiliar[2]) == 1){$auxiliar[2] = "0".$auxiliar[2];}; //Coloca um zero antes

				if ($auxiliar['1']<0) $auxiliar['1']="00";
				if ($auxiliar['2']<0) $auxiliar['2']="00";

		}
		if ($noData) {
		    $msg = "Data vazia!";
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