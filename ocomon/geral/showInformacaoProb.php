<?php /*                        Copyright 2005 Flávio Ribeiro

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

	$imgsPath = "../../includes/imgs/";

	$auth = new auth;
	$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
				$selProb = 0;
				
				if (isset($_GET['prob']) && !isset($_GET['radio_prob'])) {
					$selProb = $_GET['prob'];
				}else if (isset($_GET['prob']) && isset($_GET['radio_prob'])) {
					$selProb = $_GET['radio_prob'];
				}				
				
				$qry_problema = "SELECT p.prob_descricao, sc.*, script.* FROM problemas as p ".
							"LEFT JOIN prob_x_script as sc on sc.prscpt_prob_id = p.prob_id ".
							"LEFT JOIN scripts as script on script.scpt_id = sc.prscpt_scpt_id ". 
						"WHERE p.prob_id = ".$selProb." ";
				
				$exec_problema = mysql_query($qry_problema) or die($qry_problema);
				$row_problema = mysql_fetch_array($exec_problema);
					
				if (mysql_num_rows($exec_problema) == 0) {
					print "<div></div>";
				} else {
					
					if (!empty($row_problema['prscpt_prob_id']) && ($_SESSION['s_nivel']!=3 || $row_problema['scpt_enduser']==1)) {
						$script = "<a onClick=\"popup('../../admin/geral/scripts.php?action=popup&prob=".$selProb."')\"><br /><b><font color='green'>".TRANS('TIPS')."</font></b></a>";
					} else
						$script = "";
					
					if(!empty($row_problema['prob_descricao']) OR !empty($script)) {
					?>
						<TABLE border='0' cellpadding='2' cellspacing='0' width='90%'>
							<TR>
								<TD>
									<div style='
										margin-left: auto; margin-right: auto; margin-top: 10px; margin-bottom: 10px; 
										padding: 5px; width: 90%; border: 1px solid black; background-color: <?php print $_SESSION['s_colorMarca'] ?>;
										line-height: 150%;
										'>
										<?php print $row_problema['prob_descricao']."&nbsp;".$script.""?>
									<div>
								</TD>
							</TR>
						</TABLE>
					<?php
					}
				}
?>
