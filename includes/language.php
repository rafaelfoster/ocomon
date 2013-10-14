<?php 
	//require_once ("../../includes/classes/conecta.class.php");
	//require_once ("../../includes/functions/funcoes.inc");

	//include ("../../includes/config.inc.php");

	if (is_file ("../../includes/classes/conecta.class.php"))
		require_once ("../../includes/classes/conecta.class.php"); else
	if (is_file ("../conecta.class.php"))
		require_once ("../conecta.class.php"); else
	if (is_file ("./includes/classes/conecta.class.php"))
		require_once ("./includes/classes/conecta.class.php");


	$conec = new conexao;
	$conec->conecta('MYSQL');

	$qryConf = "SELECT * FROM config";
	$execConf = mysql_query($qryConf);
	$rowConf = mysql_fetch_array($execConf);

	define ( "LANG", $rowConf['conf_language']);

	$conec->desconecta('MYSQL');
?>