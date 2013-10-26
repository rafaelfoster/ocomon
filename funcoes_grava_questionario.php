<?php
        include ("includes/include_geral_III.inc.php");

	$usuario = $_POST['name'];
	$login =   $_POST['login'];
	$comentarios = $_POST['comentarios'];
	$pergunta1 = $_POST['pergunta1'];
	$pergunta2 = $_POST['pergunta2'];
	$pergunta3 = $_POST['pergunta3'];
	$pergunta4 = $_POST['pergunta4'];
	$pergunta5 = $_POST['pergunta5'];

	$sql = mysql_query("INSERT INTO questionario values('','$usuario', '$login','$comentarios','$pergunta1','$pergunta2','$pergunta3','$pergunta4','$pergunta5')") or die( mysql_error() );
?>
