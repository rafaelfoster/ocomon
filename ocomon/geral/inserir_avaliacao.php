<?php

	session_start();
        include ("../../includes/include_geral.inc.php");
        include ("../../includes/include_geral_II.inc.php");
        include ("../../includes/classes/lock.class.php");

	$queryA = "INSERT INTO avaliacao (OCORRENCIA, LOGIN, DATA, COD_AVALIACAO, DESC_AVALIACAO)".
		" values (".$_GET['num'].",";

	$queryA.= " '".$_GET['login']."',";

	$queryA.=" '".date('Y-m-d H:i:s')."', ". $_GET['cod'].", '".$_GET['desc']."')";

	$resultado = mysql_query($queryA) or die(TRANS('MSG_NOT_SAVE_INFO_EDIT_CALL').'<br>'.$queryA .'<br>'.mysql_error() );

?>
