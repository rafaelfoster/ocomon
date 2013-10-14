<?php 

	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");
	$cab = new headers;
	$cab->set_title($TRANS["html_title"]);

	
	print "<table>";
	print "<tr><td class='line'><input type=button value='click' onClick=\"return mensagem('TESTE')\"></td></tr>";				
	print "</table>";
	
	
	$cab->set_foot();	    	
	
?>