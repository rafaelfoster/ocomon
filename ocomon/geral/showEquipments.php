<?/*                        Copyright 2005 Flávio Ribeiro

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

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

	$imgsPath = "../../includes/imgs/";

	$auth = new auth;
	$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);


			//print "<tr><td colspan='6' ><div id='Problema'>"; //style='{display:none}'
			print "<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>";


				$qry_config = "SELECT * FROM config ";
 				$exec_config = mysql_query($qry_config) or die (TRANS('ERR_TABLE_CONFIG'));
 				$row_config = mysql_fetch_array($exec_config);

				$selProb = 0;
				if (isset($_GET['prob'])) {
					$selProb = $_GET['prob'];
					$qry_id = "SELECT * FROM problemas WHERE prob_id = ".$selProb."";
					$exec_qry_id = mysql_query($qry_id) or die();
					$rowId = mysql_fetch_array($exec_qry_id);
				}

				$query = $QRY["full_detail_ini"];
				$query.= $QRY["full_detail_fim"];
				$query.= "  order by comp_inv";

				print $query;
				//$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
				//$registros = mysql_num_rows($resultado);


			print "</table>";

?>