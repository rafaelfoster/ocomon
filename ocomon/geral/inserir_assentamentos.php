<HTML>
   <HEAD>
     <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
   </HEAD>
</HTML>

<?php

	session_start();
        include ("../../includes/include_geral.inc.php");
        include ("../../includes/include_geral_II.inc.php");
        include ("../../includes/classes/lock.class.php");

	$queryA = "INSERT INTO assentamentos (ocorrencia, assentamento, data, responsavel, asset_privated)".
		" values (".$_GET['num'].",";

	$queryA.= " '".$_GET['registro']."',";

	$queryA.=" '".date('Y-m-d H:i:s')."', ". $_GET['resp'].", ".$_GET['privado'].")";

        $resultado3 = mysql_query($queryA) or die(TRANS('MSG_NOT_SAVE_INFO_EDIT_CALL').'<br>'.$queryA);
?>
