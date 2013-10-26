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
	$COD = $_GET['cod'];

	print "<table border='0'>";

	$queryC = mysql_query("SELECT descricao, aberto_por, data_abertura, data_fechamento, operador FROM ocorrencias WHERE numero = $COD") or die( mysql_error() );
	$linhas = mysql_num_rows($queryC);
	if ( $linhas >= 1 ){

		while ( $row3 = mysql_fetch_array($queryC) ){
			print "<tr>";
			print "<TD width='10%' align='left' bgcolor='". TD_COLOR."'> Chamado: </td>";
			print "<TD colspan='2' width='10%' align='left'> $COD  </td>";
			print "<TD colspan='2' width='10%' align='left' bgcolor='". TD_COLOR."'> Aberto por: </td>";

			$queryD = mysql_query("SELECT nome FROM usuarios WHERE user_id = '".$row3['aberto_por']."'");
			while ( $row4 = mysql_fetch_array($queryD) ){
				print "<TD colspan='2' width='15%' align='left'>".$row4['nome']."</td>";
			}

			print "<TD colspan='2' align='left' bgcolor='". TD_COLOR."'> Data Abertura: </td>";
			print "<TD colspan='5' align='left'>".$row3['data_abertura']."</td>";
			print "</tr>";

			print "<tr>";
			print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'> Descricao: </td>";
			print "<TD colspan='100' width='100%' align='left'><textarea cols='100' rows='3' disabled>".$row3['descricao']."</textarea></td>";
			print "</tr>";
			print "<tr>";
			print "<TD colspan='2' width='20%' align='left' bgcolor='". TD_COLOR."'> Data de Fechamento: </td>";
			print "<TD colspan='5' width='30%' align='left'>";

			if ( $row3['data_fechamento'] != NULL ){
				print " ".$row3['data_fechamento']."</td>";
			} else {
				print "Chamado continua em aberto</td>";
			}

			print "<TD width='20%' align='left' bgcolor='". TD_COLOR."'> Operador: </td>";
			$queryD = mysql_query("SELECT nome FROM usuarios WHERE user_id = '".$row3['operador']."'");
			while ( $row4 = mysql_fetch_array($queryD) ){
				print "<TD colspan='4' width='30%' align='left'>".$row4['nome']."</td>";
			}

			print "</tr>";
		}// Fim While
	print "</table>";
	} else {
		print "<div align='center' valign='center'>";
		print "<font color='red'><b> NENHUM REGISTRO ENCONTRADO </b></font>";
		print "</div>";
	}

?>
