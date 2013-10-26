<?php

// Verifica Chamados
session_start();

//	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_III.inc.php");

	if (!isset($_SESSION['s_language']))  $_SESSION['s_language']= "pt_BR.php";
	if (!isset($_SESSION['s_usuario']))  $_SESSION['s_usuario']= "";
	if (!isset($_SESSION['s_logado']))  $_SESSION['s_logado']= "";
	if (!isset($_SESSION['s_nivel']))  $_SESSION['s_nivel']= "";

	$queryOK = "SELECT u.*, n.*,s.* FROM usuarios u left join sistemas as s on u.AREA = s.sis_id ".
			"left join nivel as n on n.nivel_cod =u.nivel WHERE u.login = '".$_SESSION['s_usuario']."'";
	$resultadoOK = mysql_query($queryOK) or die('IMPOSSIVEL ACESSAR A BASE DE DADOS DE USUARIOS: LOGIN.PHP');
	$row = mysql_fetch_array($resultadoOK);
	$s_nivel = $row['nivel'];

	if ($s_nivel<4){
		$s_logado=1;
	}

	$s_nivel_desc = $row['nivel_nome'];
	$s_area = $row['AREA'];
	$s_uid = $row['user_id'];
	$s_area_admin =  $row['user_admin'];
	$s_screen = $row['sis_screen'];

	/*VERIFICA EM QUAIS AREAS O USUARIO ESTAA CADASTRADO*/
	$qryUa = "SELECT * FROM usuarios_areas where uarea_uid=".$s_uid.""; //and uarea_sid=".$s_area."
	$execUa = mysql_query($qryUa) or die('IMPOSSÍVEL ACESSAR A BASE DE USUÁRIOS 02: LOGIN.PHP');
	$uAreas = "".$s_area.",";

	while ($rowUa = mysql_fetch_array($execUa)){
		$uAreas.=$rowUa['uarea_sid'].",";
	}

	$uAreas = substr($uAreas,0,-1);
	$s_uareas = $uAreas;

	/*CHECA QUAIS OS MÓDULOS PODEM SER ACESSADOS PELAS ÁREAS QUE O USUÁRIO PERTENCE*/
	$qry = "SELECT * FROM permissoes where perm_area in (".$uAreas.")";
	$exec = mysql_query($qry) or die('IMPOSSÍVEL ACESSAR A BASE DE PERMISSÕES: LOGIN.PHP');


	$s_nivel_desc = $row['nivel_nome'];
	$s_area = $row['AREA'];
	$s_uid = $row['user_id'];
	$s_area_admin =  $row['user_admin'];
	$s_screen = $row['sis_screen'];
	$uareas = $s_uareas;

	// echo "<br>$uareas<br>";

	$query = $QRY["ocorrencias_full_ini"]." WHERE s.stat_painel in (1) and o.operador = '$s_uid' and o.oco_scheduled=0";

	function verifica_nchamados($sql){

		$resultado = mysql_query($sql);
		$linhas = mysql_num_rows($resultado);

		echo "$linhas";

	}

	$resultado = verifica_nchamados($query);
	echo "$resultado";

?>
