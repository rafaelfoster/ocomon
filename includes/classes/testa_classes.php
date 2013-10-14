<?
	include ("dateOpersDEV.class.php");

	require_once ('../../includes/config.inc.php');
	require_once ('../../includes/functions/funcoes.inc');

	if (is_file("../../includes/classes/conecta.class.php"))
		require_once ("../../includes/classes/conecta.class.php"); else
		require_once ("../classes/conecta.class.php");

	$conec = new conexao;
	$conec->conecta('MYSQL');


	print "<html><head>Script para testes em classes do Ocomon!</head><body>";

	print "<br><br>";

	$data1 = "2008-06-06 12:07:39";
	$data2 = "2008-06-06 12:08:35";

	$dt = new dateOpers;
	$dta = new dateOpers;

	//print $dt->formatDate('11-04-2008 20:43:34');

	$dt->setData1($data1);
	$dt->setData2($data2);
	print "Data 1: ";
	$dt->getData1();
	print "<br>Data 2: ";
	$dt->getData2();

	//print "<br><br>";

	//print $dt->secToHour(2700);

	//print "<br><br>";
	//print $dt->hourToSec('02:20:00');

	print "<br><br>";

	$dt->diff_time($dt->data1,$dt->data2);
	//array("dFull"=>$d, "hFull"=>$hFull, "mFull"=>$m, "sFull"=>$secs, "tHoras"=>$emHora, "tDias"=>$v);
	print "Casa cheias:<br>".$dt->diff['dFull']." dias ou ".$dt->diff['hFull']." horas ou ".$dt->diff['mFull']." minutos ou ".$dt->diff['sFull']." segundos";
	print "<br>";
	//print "<br>".$dt->secToHour(diff_em_segundos($data1,$data2));

	print "<br>tHoras: ".$dt->diff['tHoras'];
	print "<br>tDias: ".$dt->diff['tDias'];
	print "<br>hFull: ".$dt->diff['hFull'];

	//print "<br><br>Função somadata:<br>";
	//print $dt->somadata(30,$dt->data2);

	print "<br><br>Domingos no período: ";
	print $dt->diasDomingo($dt->data1,$dt->data2);


	print "<br><br>TEMPO VÁLIDO:<br>";

	print "<b>Horas válidas: <font color='green'>".$dt->tempo_valido($dt->data1,$dt->data2,8,22,13,4,"H")."</font></b>";


	print "</body></html>";
?>