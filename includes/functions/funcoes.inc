<? /*                        Copyright 2005 Flávio Ribeiro

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

	define ( "MENU_USUARIO","<link rel='stylesheet' type='text/css' href='../includes/css/estilos.css.php'>");
	define ( "MENU_ADMIN","");

	function NVL($VALOR){
		if ($VALOR=='')
			return '&nbsp'; else
			return $VALOR;
	}

	function NL ($colspan=1) { //NEW LINE
		print "<tr><td colspan='".$colspan."'>&nbsp;</td></tr>";
	}


	function TRANS($INDEX, $SUGGEST='', $ASPAS=1){
		$aviso ="";
		//$aviso = "&nbsp;<font color=green>OK</font>";
		$destaca1 = ""; $destaca2 = "";
		//$destaca1 = "<u>"; $destaca2 = "</u>";
		//$destaca1 = "<font color=green>"; $destaca2 = "</font>";

		if (is_file ("../../includes/languages/".$_SESSION['s_language'].""))
			include ("../../includes/languages/".$_SESSION['s_language'].""); else
		if (is_file ("../languages/".$_SESSION['s_language'].""))
			include ("../languages/".$_SESSION['s_language'].""); else
		if (is_file ("./includes/languages/".$_SESSION['s_language'].""))
			include ("./includes/languages/".$_SESSION['s_language'].""); else
		if (is_file ("../includes/languages/".$_SESSION['s_language'].""))
			include ("../includes/languages/".$_SESSION['s_language'].""); else {

			print "SORRY! WRONG PATH TO THE LANGUAGE FILE!<BR>See ocomon/includes/languages/ to see the available language files!";
			exit;
		}

		if (!isset($TRANS[$INDEX])) {
			if ($ASPAS) {
				return '<font color=red>$TRANS[\''.$INDEX.'\']="</font>'.$SUGGEST.'<font color=red>";</font>';
			} else
				return '<font color=red>$TRANS['.$INDEX.']="</font>'.$SUGGEST.'<font color=red>";</font>';
		}else
			return $destaca1.$TRANS[$INDEX].$destaca2.$aviso;
	}



	function start_session(){
		static $started=false;
		if(!$started){
		session_start();
		$started = true;
		}
	}

	function dump($variavel,$info="",$cor='magenta'){
		//if ( isLocked() ){
			if (trim($info)!="") {
				echo "<br><font color='".$cor."'>".$info."</font>";
			}

			if (is_array($variavel)) {
				echo "<pre>";
				print_r($variavel);
				echo "</pre>";
			} else {
				echo "<pre>";
				echo $variavel;
				echo "</pre>";
			}
		//}
	}

	function normaliza ($str){
		//$str2 = $str;
		//$str2 = toHtml($str2);
		return toHtml($str);
	}


	function reIndexArray(&$array) {
		$tmpArray = array();

		if (is_array($array)){
			$array = array_unique($array);
			foreach($array as $value) {
				$tmpArray[] = $value;
			}
			//unset ($array);
			for ($i = 0; $i<=count($array); $i++){
				array_pop($array);
			}
		}

		$array = $tmpArray;
	}



	function reIndexArray_05_03_07(&$array) {
		$tmpArray = array();

		if (is_array($array)){
			$array = array_unique($array);
			foreach($array as $value) {
				$tmpArray[] = $value;
			}
			//unset ($array);
			for ($i = 0; $i<count($array); $i++){
				array_pop($array);
			}
		}
		$array = $tmpArray;
	}

	function reIndexArray2(&$array) {
		$tmpArray = array();

		$array = array_unique($array);
		foreach($array as $value) {
			if ($value!="")
				$tmpArray[] = $value;
		}
		//unset ($array);
		for ($i = 0; $i<count($array); $i++){
			array_pop($array);
		}

		$array = $tmpArray;
	}



	function conecta($host, $bd, $user, $senha, $sistema)
	{
		// $host = servidor do mysql, $bd = nome do banco de dados, $user = usuário do mysql, $senha = senha dp mysql, $sistema = sistema que esta sendo usado
		$conexao=mysql_connect($host,$user,$senha) or die(mysql_error());
		$db=mysql_select_db($bd,$conexao);
		if ($conexao == 0)
		{
			$retorno = "ERRO DE CONEXÃO - Servidor ".$host." - Sistema ".$sistema."<br>";
		}
		else
		if ($db == 0)
		{
			$retorno = "ERRO DE CONEXÃO - Banco de dados ".$bd." - Sistema ".$sistema."<br>";
		}
		else
		{
			$retorno = "ok";
		}
		return  $retorno;
	}

	function desconecta($conexao){
		mysql_close($conexao);
	}

	function senha_system($user,$password,$tabela)
	{
		// $user = usuário para login, $senha = senha do usuário, $tabela = nome da tabela de usuários
		$login = md5($password);
		$query = "SELECT * from ".$tabela." where (login = '".$user."' and password = '".$login."')";

		$resultado = mysql_query($query) or die(mysql_error());
		if ($resultado == 0)
		{
			$retorno = "ERRO DE LOGIN - Tabela ".$tabela."<br>";
		}
		else
		if (mysql_numrows($resultado) == 0)
		{
			$retorno = "ERRO DE LOGIN - Usuário ".$user."<br>";
		}
		else
		{
			$retorno = "ok";
		}
		return $retorno;
	}

	function senha_ldap($user,$password,$tabela)
	{
		// $user = usuário para login, $senha = senha do usuário, $tabela = nome da tabela de usuários
		$login = md5($password);
		$query = "SELECT * from $tabela where (login = '$user')";

		$resultado = mysql_query($query) or die(mysql_error());
		if ($resultado == 0)
		{
			$retorno = "ERRO DE LOGIN - Tabela $tabela<br>";
		}
		else
		if (mysql_numrows($resultado) == 0)
		{
			$retorno = "ERRO DE LOGIN - Usuário $user<br>";
		}
		else
		{
			$retorno = "ok";
		}
		return $retorno;
	}

	function geraLog($filename,$data,$usuario,$pagina,$acao){
		$conteudo = "DATA: $data\t";
		$conteudo.= "USUÁRIO: $usuario\t";
		$conteudo.= "PAGINA: $pagina\t";
		$conteudo.= " AÇÃO: $acao\t";
		$conteudo.="\n";

		if (is_writable($filename)) {

			if (!$handle = fopen($filename, 'a')) {
				$warning = "O arquivo não pode ser aberto (".$filename.")!";
				exit;
			}
			if (!fwrite($handle, $conteudo)) {
				$warning = "O arquivo não pode ser escrito (".$filename.")!";
				exit;
			}
			$warning= "Sucesso, (".$conteudo.") escrito no arquivo (".$filename.")!";
			fclose($handle);

			} else {
				$warning = "O arquivo ".$filename." não tem permissão de escrita!";
			}

			return $warning;
	}

###############################################################################



#################################################################################
function cabecalho($logo,$msg1,$msg2,$titulo)
{
	return "
		<table width=80% border=0 cellspacing=1 cellpadding=1 align=center bgcolor=black>
		  <tr bgcolor=#FFFFFF>
		    <td width=30%>
		      <div align=center><font size=3 face=Arial, sans-serif><img src=./$logo></font></div>
		    </td>
		    <td width=40%>
		      <div align=center><font size=3 face=Arial, sans-serif> <b>$msg1</b></font></div>
		    </td>
		    <td width=30%>
		      <div align=center><font size=3 face=Arial, sans-serif><b><font size=2>$msg2</font></b></font></div>
		    </td>
		  </tr>
		  <tr bgcolor=#FFFFFF>
		    <td colspan=3>
		      <div align=center><font size=2 face=Arial, sans-serif><b>$titulo</b></font></div>
		    </td>
		  </tr>
		</table>

	";
}


function testaArea($area,$rowArea,$horarios){

	if (array_key_exists($rowArea,$horarios)){  //verifica se o código da área possui carga horária definida no arquivo config.inc.php
		$area = $rowArea; //Recebe o valor da área de atendimento do chamado
	} else $area= 1; //Carga horária default definida no arquivo config.inc.php
	return $area;
}

###################################################################################################

		//TIPO: tipo de relatório - formatação específica
		//SQL: Query no banco de dados
		//CAMPOS: Array com o nome dos campos que eu quero imprimir no relatório
		//HEADERS: Array com os cabeçalhos de cada coluna do relatório
		function gera_relatorio($tipo,$sql,$campos,$headers,$logo,$msg1,$msg2,$msg3){
			//Estilo aplicado nos relatórios
			print "<style type=\"text/css\"><!--";
			print "table.relatorio_1 {width:80%; margin-left:auto; margin-right: auto; text-align:left;
					border: 0px; border-spacing:1 ;background-color:white; padding-top:10px;
					page-break-after: auto;}";
			print "td.linha {font-family:arial; font-size:12px; line-height:0.8em;}";
			print "td.linha_par {font-family:arial; font-size:12px; line-height:0.8em; background-color:#EAEAEA}";
			print "td.linha_impar {font-family:arial; font-size:12px; line-height:0.8em;background-color:#C8C8C8}";
			print "td.cabs {font-family:arial; font-size:12px; font-weight:bold; background-color: #A3A352;}";
			print "td.foot {font-family:arial; font-size:12px; font-weight:bold; line-height:0.8em; background-color: #A8A8A8;}";
			//print "{page-break-after: always;}";
			print "--></STYLE>";

			if (count($campos) != count($headers))  {//Verifica se cada campo da tabela possui um header!
				print "O número de campos não fecha com o número de headers!";
				exit;
			}//if campos == headers


			$commit = mysql_query($sql);
	        	$linhas = mysql_num_rows($commit);
			$k=0;
			$fields ="";
			$total = "";
			while($k < mysql_num_fields($commit)){ //quantidade de campos retornados da consulta
				$field = mysql_fetch_field($commit,$k);//Retorna um objeto com informações dos campos
				$fields.=$field->name; //Joga os nomes dos campos para uma string
				$k++;
			} // while

			if ($linhas==0) {
			    print "Nenhuma linha retornada pela consulta";
			}else{
				print cabecalho($logo,$msg1,$msg2,$msg3);


				if ($tipo==1|| $tipo==0) {//Tipo definido de relatório //
					print "<TABLE class=\"relatorio_1\" cellpadding=4>";
					print "<tr>";
					for ($i=0; $i<count($headers); $i++){
						print "<td class=\"cabs\">".$headers[$i]."</td>";
					}//for
					print "</tr>";
					$l = 0; //variável que controla se a linha é par ou impar
					while($row=mysql_fetch_array($commit)){
						if ($l % 2) {
							$par_impar = "_par";
						} else {
							$par_impar = "_impar";
						}//if - else
						print "<tr>";
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							print "<td class=\"linha$par_impar\">";
							$sep = explode(",",$campos[$i]); //Se algum campo passado tem mais de um argumento é separado
							for ($j=0; $j<count($sep); $j++){
								$pos = strpos($fields,$sep[$j]); //Verifica na string gerada se o argumento passado existe como um nome de campo
								if ($pos===false) {
									print $sep[$j]; //Se o campo não existe é impresso literalmente
								} else
									print $row[$sep[$j]]; //Se o campo existe é impresso seu valor;
							} //for J//
							print "</td>";
						}//for i//
						print "</tr>";
						$l++;
					} // while
						//RODAPÉ
						print "<tr>";
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							if ($i==count($campos)-1) {
							    $total = $linhas;
							} else
							if ($i==count($campos)-2) {
							    $total = "TOTAL";
							}
							print "<td class=\"foot\">$total</td>";
						}//for
						print "</tr>";
					print "</table>";
				} else

				if ($tipo==2) {//Outra formatação para saída do relatório//
					print "<table class=\"relatorio_1\">";
					while($row=mysql_fetch_array($commit)){
						//print "<tr>";
						for ($i=0; $i<count($campos); $i++){ //IMPRIME CAMPO A CAMPO
							print "<tr>";
							//print "<td>".$headers[$i]."</td>";
							print "<td>";
							$sep = explode(",",$campos[$i]); //Se algum campo passado tem mais de um argumento é separado
							for ($j=0; $j<count($sep); $j++){
								$pos = strpos($fields,$sep[$j]); //Verifica na string gerada se o argumento passado existe como um nome de campo
								if ($pos===false) {
									print $sep[$j]; //Se o campo não existe é impresso literalmente
								} else {
									print $row[$sep[$j]]; //Se o campo existe é impresso seu valor;
								}
								//print "</td>";
							} //for J//
							print "</td>";


						}//for i//
						print "</tr>";
					}//while
					print "</table>";
				} //fim do tipo==2 //
			} //else linhas != 0 //
		}//função


function menu_admin(){
	return "";
}



function veremail($email)
{
	if(!eregi("^[0-9a-z]([-_.]?[0-9a-z])*@[0-9a-z]([-.]?[0-9a-z])*\\.[a-z]{2,3}$",$email))
		$passou = "erro";
	else
		$passou = "ok";
	return $passou;
}


function mensagem($msg){

	return "<TABLE class='msg' ". //#EFEFE7  "STYLE='{border-bottom:  solid #999999; border-top:  thin solid #999999; border-left:thin  ".
					//"solid #999999; border-right: thin solid #999999; }' ".
			"cellspacing='1' border='0' cellpadding='1' align='center' width='320'>".//#5E515B
			"<TR>".
			"<TD align='center'><b>".$msg."<b></td>".
                	"</TR>".
		"</TABLE>";

}

################################################################################

function putComma ($vetor){
	$chamados="";
	if (count($vetor) > 1) {

		for ($i=0; $i<count($vetor); $i++){
			$chamados.= "$vetor[$i],";
		}
		if (strlen($chamados)>0) {
			$chamados = substr($chamados,0,-1);
		}
	} else
		//$chamados = $vetor[0];
		$chamados = $vetor;
	return $chamados;
}

###############################################################################

function converte_dma_para_amd($dataform)//converte a data do formato dd/mm/aaaa para aaaa-mm-dd
{
        if (empty($dataform)) {
        	$data = "";
        } else {

		if (strpos($dataform," ")){
			$datatransHora = explode(" ",$dataform);

			$hora = $datatransHora[1];
			$datatrans = $datatransHora[0];
		} else {
			$hora = "00:00:00";
			$datatrans = $dataform;
		}
		if (strpos($datatrans,"-")) {
			$datatransf = explode("-",$datatrans);
		} else
			$datatransf = explode("/",$datatrans);
		$data = "$datatransf[2]-$datatransf[1]-$datatransf[0]";
        }
        return $data;
}


function converte_datacomhora($dataform)//pega a data do formato aaaa-mm-dd +hora, e transforma p/ dd-mm-aaaa +hora
{
	if (empty($dataform)){
		$datacompleta = "";
	} else {
		//separando o dia e a hora
	        $data_hora = explode(" ",$dataform);
		$data="$data_hora[0]";
		$hora="$data_hora[1]";

		//formatando o dia em dd-mm-aaaa
		$datatransf = explode("-",$data);
        	$data = "$datatransf[2]/$datatransf[1]/$datatransf[0]";
		$datacompleta = $data." ".$hora;
	}
        return $datacompleta;
}


function diff_em_segundos($data1, $data2)
{
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d."-".$h."-".$m."-".$s;

		$dados = explode("-",$v);

		$dias = $dados[0];
		//Verificar dias válidos
		$horas = $dados[1];
		//Verificar horas válidas
		$minutos = $dados[2];
		$segundos = $dados[3];

		$dias *=86400; //Dia de 24 horas
		//$dias *=50400; //Dia de 14 horas
		$horas *=3600;
		$minutos *=60;
		$segundos +=$dias+$horas+$minutos;

		return $segundos;
}

################################################################################

function date_diff($data1, $data2)
{
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d." dias, ".$h.":".$m.":".$s;
        return $v;
}


function date_diff2($data1, $data2)
{
        $s = strtotime($data2)-strtotime($data1);
        $d = intval($s/86400);
        $s -= $d*86400;
        $h = intval($s/3600);
        $s -= $h*3600;
        $m = intval($s/60);
        $s -= $m*60;

        $v = $d;
        return $v;
}

function date_diff_dias($data1, $data2)
{
	if (empty($data1) || empty($data2)){
		$v = "";
	} else {
		$s = strtotime($data2)-strtotime($data1);
		$d = intval($s/86400);
		$s -= $d*86400;
		$h = intval($s/3600);
		$s -= $h*3600;
		$m = intval($s/60);
		$s -= $m*60;

		$v = $d;
	}
	return $v;
}



function segundos_em_horas($segundos){

	$h=0;
	$m=0;

	while($segundos >=60){
		if ($segundos >= 3600) {
			while ($segundos >= 3600) { //ORDEM DE HORAS
				$segundos-=3600;
					$h+=1;
			}
		} else
		if ($segundos >= 60) {
			while ($segundos >= 60) {//ORDEM DE MINUTOS
				$segundos-=60;
				$m+=1;
			}
		}
	}

	if(strlen($h) == 1){$h = "0".$h;}; //Coloca um zero antes
	if(strlen($m) == 1){$m = "0".$m;}; //Coloca um zero antes
	if(strlen($segundos) == 1){$segundos = "0".$segundos;}; //Coloca um zero antes

	$horas = $h.":".$m.":".$segundos;

	return $horas;
}

#######################################################

function formatDate($DATE, $TIMEFORMAT = "" )
{
	if (strtotime($DATE) != -1) {
		//DATE = "2007/05/25"; //Always American format
		$Date = $_SESSION['s_date_format'];

		if ($TIMEFORMAT!=""){
			if (strpos($_SESSION['s_date_format']," ")){
				$DateParts= explode(" ",$_SESSION['s_date_format']);
				$Date = $DateParts[0];
				$Time = $DateParts[1];
			}
		}
		$output = trim(strftime($Date.$TIMEFORMAT, strtotime($DATE)));

		return $output;
	} else
		return "INVALID DATE FORMAT!";
}



function datam($dataform)//pega a data informada, e formata dd-mm-aaaa c/ a hora atual
{
	if (empty($dataform)){
		$data = "";
	} else {
		$data = "";
		$datatrans = array();
		$datatransHora = array();
		//$dataArray = array();

		if (strpos($dataform," ")){
			$datatransHora = explode(" ",$dataform);

			$hora = $datatransHora[1];
			$datatrans = $datatransHora[0];
		} else {
			$datatransHora[0] = $dataform;
			$hora = strftime("%H:%M:%S");
		}

		if (strpos($datatransHora[0],"-")){
				$datatrans = explode("-",$datatransHora[0]);
		} else
		if (strpos($datatransHora[0],"/")) {
				$datatrans = explode("/",$datatransHora[0]);
		}
		$data = $datatrans[2]."-".$datatrans[1]."-".$datatrans[0];
		$data = $data." ".$hora;
	}
        return $data;
}

function datam2($dataform)
{
	if (empty($dataform)) {
		$data = "";
	} else {
		$datatrans = explode("-",$dataform);
		$data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
		$hora = strftime("%H:%M:%S");
		$data = $data." ".$hora;
	}
        return $data;
}


function inteiro ($string){

	settype ($string,"integer");
	return $string;
}


function horaAhora($valor,$hora){

	for ($i=1; $i<=$valor; $i++){
		if ($hora>=23) {
			$hora=-1;
		}
		$hora++;
	}
	return $hora;
}

function datab($dataform)
{
        if (empty($dataform)) {
        	$data = "";
        } else {
		$hora = substr($dataform,11,17);
		$data = substr($dataform,0,10);
		$datatrans = explode("-",$data);
		$data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
		$data = $data." ".$hora;
	}
        return $data;
}
function datab2($dataform)
{
        if (empty($dataform)) {
        	$data = "";
        } else {
		$hora = substr($dataform,11,17);
		$data = substr($dataform,0,10);
		$datatrans = explode("-",$data);
		$data = "$datatrans[2]/$datatrans[1]/$datatrans[0]";
	}

        return $data;
}


function dataRED($dataform)
{
		//Formato de Data Reduzido
        $hora = substr($dataform,11,17);
        $data = substr($dataform,0,10);

        $hora = substr($hora,0,5);

        $datatrans = explode("-",$data);
		$anored= substr($datatrans[0],2,2);
        $data = "$datatrans[2]/$datatrans[1]/$anored";
        $data = $data."-".$hora;

        return $data;
}
#################################################################################


function ajusta ($palavra,$num) {
	$limite = $num;
	$i = strlen($palavra);
	while ($i < $limite) {
		$palavra.='-';
		$i++;
	}

	return $palavra;

}

################################################################################

function envia_email_new_user($login, $nome, $email, $rand, $site)
{
	$msg = "";
	$msg .= "SISTEMA OCOMON\t\n";
	$msg .= "Prezado ".$nome."\t\n";
	$msg .= "Sua solicitação para criação do login: ".$login." foi bem sucedida!\t\n";
	$msg .= "Para confirmar sua inscrição clique no link abaixo: \t\n";
	$msg .= "".$site."/ocomon/geral/confirma.php?rand=".$rand." \t\n";

	$mailheaders = "From: SISTEMA_OCOMON\n";
	$mailheaders .= "Replay-to: ".$email."\n\n";

	mail($email, "OCOMON - CONFIRMAÇÃO DE CADASTRO", $msg, $mailheaders);
	return "OK";
}


function noHtml ($string) {

	$string=addslashes($string);
	$string=addslashes($string);
	return stripslashes(htmlspecialchars($string));
}

function toHtml ($string){

   $trans_tbl = get_html_translation_table (HTML_ENTITIES);
   $trans_tbl = array_flip ($trans_tbl);
   return strtr ($string, $trans_tbl);
}

function isIn ($pattern, $values){

	$found = false;
	if (strpos($values,",")){
		$valuesArray = explode(",",$values);

		for ($i = 0; $i < count($valuesArray); $i++){
			if ($valuesArray[$i] == (int)$pattern) $found = true;

		}

	} else
	if ($values == (int)$pattern) $found = true;

	return $found;

}

function sepComma($value,$array){
	if (strpos($value,",")) {
		$array = explode(",",$value);

	} else $array = $value;

	return $array;
}



function random (){
	$rand ="";
	for ($i=0; $i<10; $i++) {
		$rand.= mt_rand(1,300);
	}

	return ($rand);
}

function transbool ($bool){
	if ($bool == 0) $trans = TRANS('NOT'); else
	if ($bool == 1) $trans = TRANS('YES'); else
	$trans = $bool;

	return $trans;
}


function transvars ($msg, $arrayEnv) {

	foreach ($arrayEnv as $id=> $var){
		$msg = str_replace($id, $var, $msg);
	}

	return $msg;
}


function send_mail($event, $e_destino,$mailConf, $msgConf, $envVars){


//$event: Tipo de evento, os eventos são definidos pela situação (abertura, edição ou assentamento)
//        e pelo destino (usuário, operador ou área)
//$e_destino: e-mail de destino
//$mailConf: array com as informações de conexão smtp
//$msgConf: array com as informações de mensagem
//$envVar: array com as variáveis de ambiente
	if (is_file( "./.root_dir" )) {
 		require_once("./includes/phpmailer/class.phpmailer.php");
 	} else {
 		require_once("../../includes/phpmailer/class.phpmailer.php");
 	}

	$mail = new PHPMailer();
	$mail->SetLanguage("en", "../../includes/phpmailer/language/");


	if ($mailConf['mail_issmtp']) {
		$mail->IsSMTP();
	}                                      // set mailer to use SMTP
	$mail->Host = $mailConf['mail_host'];  // specify main and backup server
	$mail->SMTPAuth = $mailConf['mail_isauth'];     // turn on SMTP authentication
	$mail->Username = $mailConf['mail_user'];  // SMTP username
	$mail->Password = $mailConf['mail_pass']; // SMTP password

	$mail->From = $mailConf['mail_from'];
	//$mail->IsHTML(false);
	$mail->IsHTML($mailConf['mail_ishtml']);                 //$mail->IsHTML(true); // set email format to HTML



	$mail->FromName = $msgConf['msg_fromname'];
	$mail->AddAddress($e_destino);
	//$mail->AddAddress("josh@example.net", "Josh Adams");
	//$mail->AddAddress("ellen@example.com");                  // name is optional
	$mail->AddReplyTo($msgConf['msg_replyto'], "SISTEMA OCOMON");

	//$mail->WordWrap = 50;                                 // set word wrap to 50 characters
	//$mail->AddAttachment("/var/tmp/file.tar.gz");         // add attachments
	//$mail->AddAttachment("/tmp/image.jpg", "new.jpg");    // optional name
	$mail->Subject = transvars($msgConf['msg_subject'],$envVars);

	if ($mailConf['mail_ishtml']){
		$mail->Body = transvars($msgConf['msg_body'],$envVars);
	} else {
		$mail->Body = nl2br(transvars($msgConf['msg_altbody'],$envVars));
	}

	$mail->AltBody = nl2br(transvars($msgConf['msg_altbody'],$envVars));

/*		## TO USE ONLY FOR DEVELOPERS WHEN DEBUGING
		dump ($mailConf['mail_issmtp'],'IsSMTP');
		dump ($mail->Host,'Host');
		dump ($mail->SMTPAuth,'SMTPAuth');
		dump ($mail->Username,'Username');
		dump ($mail->Password,'Password');
		dump ($mail->From,'From');
		dump ($mailConf['mail_ishtml'],'IsHTML');
		dump ($mail->FromName,'FromName');
		dump ($e_destino,'AddAddress');
		dump ($msgConf['msg_replyto'],'AddReplyTo');
		dump ($mail->Subject,'Subject');
		dump ($mail->Body,'Body');
		dump ($mail->AltBody,'AltBody');
		exit;
	*/
	if(!$mail->Send())
	{
		echo "A mensagem não pôde ser enviada. <p>";
		echo "Mailer Error: " . $mail->ErrorInfo;
		exit;
	}

	return true;
}

function showArray($array){
	print "<pre>";
	print_r ($array);
	print "</pre>";
	return true;
}

function isImage($type){
	if(eregi("^image\/(pjpeg|jpeg|png|gif|x-ms-bmp)$", $type))
		return true; else
		return false;
}

function noSpace($WORD) {

	$newWord = trim(str_replace(" ","_", $WORD));
	return $newWord;
}


function upload($img_file,$config, $FILE_TYPES="%%IMG%") {
	// Prepara a variável caso o formulário tenha sido postado
	$arquivo = isset($_FILES[$img_file]) ? $_FILES[$img_file] : FALSE;

/*	$config = array();
	// Tamano máximo da imagem, em bytes
	$config["tamanho"] = 307200; //Aproximadamente 300k
	// Largura Máxima, em pixels
	$config["largura"] = 800;
	// Altura Máxima, em pixels
	$config["altura"] = 600;*/

	$saida = "OK";

	if($arquivo) {
		$erro = array();

		// Verifica o mime-type do arquivo para ver se é de imagem.
		// Caso fosse verificar a extensão do nome de arquivo, o código deveria ser:
		//
		// if(!eregi("\.(jpg|jpeg|bmp|gif|png){1}$", $arquivo["name"])) {
		//      $erro[] = "Arquivo em formato inválido! A imagem deve ser jpg, jpeg, bmp, gif ou png. Envie outro arquivo"; }
		//
		// Mas, o que ocorre é que alguns usuários mal-intencionados, podem pegar um vírus .exe e simplesmente mudar a extensão
		// para alguma das imagens e enviar. Então, não adiantaria em nada verificar a extensão do nome do arquivo.

		$mime = array();

		$TYPE = explode("%",$FILE_TYPES);

		reIndexArray2($TYPE);

		$mime['PDF'] = "application\/pdf";
		$mime['TXT'] = "text\/plain";
		$mime['RTF'] = "application\/rtf";
		$mime['HTML'] = "text\/html";
		$mime['IMG'] = "image\/(pjpeg|jpeg|png|gif|x-ms-bmp)";

		$mime['ODF'] = "application\/vnd.oasis.opendocument.(text|spreadsheet|presentation|graphics)";
		$mime['OOO'] = "application\/vnd.sun.xml.(writer|calc|draw|impress)";
		$mime['MSO'] = "application\/(msword|vnd.ms-excel|vnd.ms-powerpoint)";
		//application/vnd.ms-powerpoint
		//application/vnd.sun.xml.writer; application/vnd.sun.xml.calc; application/vnd.sun.xml.draw; application/vnd.sun.xml.impress

		//if(preg_match('/^(.*)\.(doc|txt|pdf|xls|htm|html|rtf)$/', $arquivo["type"])) //application/pdf
		//if(!eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"])) {

		$typeOK = false;
		$types = "";
		for ($i=0; $i<count($TYPE); $i++) {
			if (strlen($types)>0) $types.=", ";
			$types.=$TYPE[$i];
			if(eregi("^".$mime[$TYPE[$i]]."$", $arquivo["type"])) {
				$typeOK = true;
			}
		}

		if (!$typeOK) {
			$erro[] = "Arquivo em formato inválido! Os Tipos permitidos são: ".$types;

		} else  {
			// Verifica tamanho do arquivo
			if($arquivo["size"] > $config["conf_upld_size"]) {
				$kbytes = $config["conf_upld_size"]/1024;

				$erro[] = "Arquivo em tamanho muito grande! O arquivo deve ser de no máximo " . $kbytes . " Kbytes.";
			}

			if(eregi("^image\/(pjpeg|jpeg|png|gif|bmp)$", $arquivo["type"])) {
				// Para verificar as dimensões da imagem
				$tamanhos = getimagesize($arquivo["tmp_name"]);
				// Verifica largura
				if($tamanhos[0] > $config["conf_upld_width"]) {
					$erro[] = "Largura da imagem não deve ultrapassar " . $config["conf_upld_width"] . " pixels";
				}
				// Verifica altura
				if($tamanhos[1] > $config["conf_upld_height"]) {
					$erro[] = "Altura da imagem não deve ultrapassar " . $config["conf_upld_height"] . " pixels";
				}
			}
		}

		if(sizeof($erro)) {
			$saida = "<b>ALERTA:</b><br />";
			foreach($erro as $err) {
				$saida.=" - ".$err."<br>";
			}
		}

		if($arquivo && !sizeof($erro)) {
			//echo "<BR><BR>UPLOAD REALIZADO COM SUCESSO!";
			$saida = "OK";
		}

	} else print "ERRO NO ARQUIVO!";

	return $saida;

}


	function montaArray($mysql_result,$mysql_fetch_array){
		$k=0;
		$valores = array();
		while($k < mysql_num_fields($mysql_result)){ //quantidade de campos retornados da consulta
			$field = mysql_fetch_field($mysql_result,$k);//Retorna um objeto com informações dos campos
			$valores[$field->name] = $mysql_fetch_array[$field->name];
			$k++;
		} // while
		return $valores;
	}

	function findAllOccurences($Haystack, $needle, $limit=0)
	{
		$Positions = array();
		$currentOffset = 0;
		$count=0;
		while(($pos = strpos($Haystack, $needle, $offset)) && ($count < $limit || $limit == 0))
		{
			$Positions[] = $pos;
			$offset = $pos + strlen($needle);
			$count++;
		}
		return $Positions;
	}


	//Destaca as entradas '$string' em um texto '$texto' passado
	function destaca($string, $texto) {

		$string.="|".noHtml($string)."|".toHtml($string);

		//$string = htmlentities($string);

		$pattern = explode("|", $string);
		$pattern = array_unique($pattern);
		$destaque = array();

		reIndexArray($pattern);

		$texto2 = toHtml(strtolower($texto));
		//$texto2 = htmlentities($texto);

		for ($i=0; $i<count($pattern); $i++) {
			$destaque = "<font style='{background-color:yellow}'>".$pattern[$i]."</font></b>";
			$texto2 = str_replace(strtolower($pattern[$i]), strtolower($destaque), $texto2);
		}

		return $texto2;
	}


	function valida($CAMPO, $VALOR, $TIPO, $OBRIGATORIO, &$ERR) {

		$LISTA = array();
		$LISTA['INTFULL'] = "/^\d*$/"; //INTEIRO QUALQUER
		$LISTA['INTEIRO'] = "/^[1-9]\d*$/"; //NÃO INICIADOS POR ZERO
		$LISTA['MAIL']= "/^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*@(([\w-]+\.)+[A-Za-z]{2,6}|\[\d{1,3}(\.\d{1,3}){3}\])$/";
		$LISTA['DATA'] = "/^((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2}$/";
		$LISTA['DATA_'] = "/^((0?[1-9]|[12]\d)\-(0?[1-9]|1[0-2])|30\-(0?[13-9]|1[0-2])|31\-(0?[13578]|1[02]))\-(19|20)?\d{2}$/";
		$LISTA['DATAHORA'] = "/^(((0?[1-9]|[12]\d)\/(0?[1-9]|1[0-2])|30\/(0?[13-9]|1[0-2])|31\/(0?[13578]|1[02]))\/(19|20)?\d{2})[ ]([0-1]\d|2[0-3])+:[0-5]\d:[0-5]\d$/";
		$LISTA['MOEDA'] = "/^\d{1,3}(\.\d{3})*\,\d{2}$/";
		$LISTA['MOEDASIMP'] = "/^\d*\,\d{2}$/";
		$LISTA['ETIQUETA'] = "/^[1-9]\d*(\,\d+)*$/"; //expressão para validar consultas separadas por vírgula;
		$LISTA['ALFA'] = "/^[A-Z]|[a-z]([A-Z]|[a-z])*$/";
		$LISTA['ALFANUM'] = "/^([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*\.?([A-Z]|[a-z]|[0-9])([A-Z]|[a-z]|[0-9])*$/"; //Valores alfanumérias aceitando separação com no máximo um ponto.
		$LISTA['ALFAFULL'] = "/^[\w!#$%&'*+\/=?^`{|}~-]+(\.[\w!#$%&'*+\/=?^`{|}~-]+)*$/";
		$LISTA['FONE'] = "/^(([+][\d]{2,2})?([-]|[\s])?[\d]*([-]|[\s])?[\d]+)+([,][\s]([+][\d]{2,2})?([-]|[\s])?[\d]*([-]|[\s])?[\d]+)*$/";
		$LISTA['COR'] = "/^([#]([A-F]|[a-f]|[\d]){6,6})|([I][M][G][_][D][E][F][A][U][L][T])$/";

		$ERRO = array();
		$ERRO['OBRIGATORIO'] = "O campo ".$CAMPO." é obrigatório!";
		$ERRO['INTFULL'] = "O campo ".$CAMPO." deve conter apenas numeros inteiros!";
		$ERRO['INTEIRO'] = "O campo ".$CAMPO." deve conter apenas numeros inteiros não iniciados por ZERO!";
		$ERRO['MAIL']= "Formato de e-mail inválido!";
		$ERRO['DATA'] ="Formato de data invalido! dd/mm/aaaa";
		$ERRO['DATA_'] = "Formato de data invalido! dd-mm-aaaa";
		$ERRO['DATAHORA'] = "Formato de data invalido! dd/mm/aaaa H:m:s";
		$ERRO['MOEDA'] = "Formato de moeda inválido!";
		$ERRO['MOEDASIMP'] ="Formato de moeda inválido! XXXXXX,XX";
		$ERRO['ETIQUETA'] = "o Formato do campo ".$CAMPO." deve ser de valores inteiros não iniciados por Zero e separados por vírgula!";
		$ERRO['ALFA'] = "Esse o campo ".$CAMPO." só aceita carateres do alfabeto sem espaços!";
		$ERRO['ALFANUM'] ="O campo ".$CAMPO." só aceita valores alfanuméricos sem espaços ou separados por um ponto(no máximo um)!";
		$ERRO['ALFAFULL'] = "O campo ".$CAMPO." só aceita valores alfanuméricos sem espaços!";
		$ERRO['FONE'] = "O campo ".$CAMPO." só aceita valores formatados para telefones (algarismos, traços e espaços) separados por vírgula.";
		$ERRO['COR'] ="O campo ".$CAMPO." só aceita valores formatados para cores HTML! Ex: #FFCC99";

		if ($LISTA[$TIPO] == '') {
			print "ÍNDICE INVÁLIDO!";
			return false;
		} else
		if ($OBRIGATORIO) {
			if ($VALOR=='') {
				$ERR = $ERRO['OBRIGATORIO'];
				return false;
			} else
			if (preg_match($LISTA[$TIPO], $VALOR)) {
				return true;
			} else {
				$ERR = $ERRO[$TIPO];
				return false;
			}

		} else
		if ($VALOR != ''){
			if (preg_match($LISTA[$TIPO], $VALOR)) {
				return true;
			} else {
				$ERR = $ERRO[$TIPO];
				return false;
			}

		} else {
			return true;
		}
	}


	function getDirFileNames($dir, $ext='php|PHP') {
		// Abre um diretorio conhecido, e faz a leitura de seu conteudo de acordo com a extensão solicitada

		$array = array();
		if (is_dir($dir)) {
			if ($dh = opendir($dir)) {
				while (($file = readdir($dh)) !== false) {
					if($file != '..' && $file !='.' && $file !=''){
						if (eregi("\.(".$ext."){1}$", $file))
							$array[]=$file;
					}
				}
				closedir($dh);
			}
		}
		return $array;
	}

	function dPgetIniSize($val) {
		$val = trim($val);
		if (strlen($val <= 1)) return $val;
		$last = $val{strlen($val)-1};
		switch($last) {
			case 'k':
			case 'K':
				return (int) $val * 1024;
			break;
			case 'm':
			case 'M':
				return (int) $val * 1048576;
			break;
			default:
				return $val;
		}
	}

?>
