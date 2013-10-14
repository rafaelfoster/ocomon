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

	$_SESSION['s_page_invmon'] = $_SERVER['PHP_SELF'];

	$cab = new headers;
	$cab->set_title(TRANS('TTL_INVMON'));

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	$hoje = date("Y-m-d H:i:s");


	$cor  = TD_COLOR;
	$cor1 = TD_COLOR;
	$cor3 = BODY_COLOR;

	$query = $QRY["vencimentos_full"];
	$result = mysql_query($query);

	//----------------TABELA  -----------------//
	print "<br><br><p align='center'>".TRANS('TTL_PREVIEWS_EXP_GUARANTEE_FULL').": <a href='estat_vencimentos.php'>".TRANS('SHOW_ONLY_3_YEARS')."</a></p>";
	print "<table cellspacing='0' border='1' align='center' style=\"{border-collapse:collapse;}\">";
	print "<tr><td ><b>".TRANS('COL_DATE_2')."</b></td><td ><b>".TRANS('COL_AMOUNT')."</b></td><td ><b>".TRANS('COL_TYPE_2')."</b></td><td ><b>".TRANS('COL_MODEL_2')."</b></td></tr>";
	//-----------------FINAL DA TABELA  -----------------------//

	$tt_garant = 0;
	while ($row=mysql_fetch_array($result)) {
		$temp1 = explode(" ",$row['vencimento']);
		$temp = explode(" ",datab($row['vencimento']));
		$vencimento1 = $temp1[0];
		$vencimento = $temp[0];
		$tt_garant+= $row['quantidade'];
		print "<tr><td ><a onClick=\"popup('mostra_consulta_comp.php?VENCIMENTO=".$vencimento1."')\">".$vencimento."</a></td>".
			"<td align='center'><a onClick=\"popup('mostra_consulta_comp.php?VENCIMENTO=".$vencimento1."')\">".$row['quantidade']."</a></td>".
			"<td >".$row['tipo']."</td><td >".$row['fabricante']." ".$row['modelo']."</td></tr>";
	} // while
	print "<tr><td ><b>".TRANS('COL_OVERALL')."</b></td><td colspan='3'><b>".$tt_garant."</b></td></tr>";
	print "</table><br><br>";


	print "<TABLE width='80%' align='center'>";
	print "<tr><td ></TD></tr>";
	print "<tr><td ></TD></tr>";
	print "<tr><td ></TD></tr>";
	print "<tr><td ></TD></tr>";


	print "<tr><td width='80%' align='center'><b>".TRANS('SLOGAN_OCOMON')." <a href='http://www.unilasalle.edu.br' target='_blank'>".TRANS('COMPANY')."</a>.</b></td></tr>";
	print "</TABLE>";

print "</BODY>";
print "</HTML>";
?>