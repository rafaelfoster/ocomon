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
  */session_start();


	include ("../../includes/include_geral.inc.php");
	include ("../../includes/include_geral_II.inc.php");

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],1);

	print "<br><br>";
	print TRANS('MSG_UPDATE_PRIOR'); 
	print "<br><br>";

	
	$qry = "SELECT * FROM prior_atend WHERE pr_default = 1";
	$exec = mysql_query($qry) or die ('ERRO NA TENTATIVA DE BUSCAR AS INFORMAÇÕES DA PRIORIDADE PADRÃO!');
	
	if(mysql_numrows($exec)== 0 ){
		print "<b><font color='red'>".TRANS('DEFAULT_PRIOR_NOT_DEFINED')."</font></b>";
		exit;
	
	}
	$row = mysql_fetch_array($exec);
	
	
	//exit;
	$qry_update = "UPDATE ocorrencias SET oco_prior = '".$row['pr_cod']."' WHERE oco_prior is null ";
	$exec_update = mysql_query($qry_update) or die('OCORREU UM ERRO NA TENTATIVA DE ATUALIZAR A PRIORIDADE PADRÃO DAS OCORRÊNCIAS!');

	print "<br><br>";
	print "<b><font color='green'>".TRANS('MSG_UPDATE_PRIOR_OK')."</font></b>";


print "</body>";
print "</html>";
?>