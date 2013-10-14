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

	//print "<HTML>";
	//print "<meta http-equiv='Content-Type' content='text/html; charset=iso-8859-1'/>";
	$auth = new auth;
	$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);
	

				if (isset($_GET['prob'])){
					$qryDesc = "SELECT * FROM problemas WHERE prob_id = ".$_GET['prob']."";
					$execDesc = mysql_query($qryDesc);
					$rowDesc = mysql_fetch_array($execDesc);

				}

				##A DIV "divProblema" DEVE SER UTILIZADA PARA A EXIBICAO DAS CATEGORIAS DE PROBLEMAS
				print "<SELECT class='select' name='problema' id='idProblema' onChange=\"ajaxFunction('divProblema', 'showProbs.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea')\">";
	        		        print "<option value='-1'>".TRANS('OCO_SEL_PROB')."</option>";
	                		$query = "SELECT * from problemas ";


					if (isset($_GET['area_cod']) && $_GET['area_cod']!="" && $_GET['area_cod']!=-1) {
						$query.= " WHERE prob_area = ".$_GET['area_cod']." OR prob_area IS NULL OR prob_area = -1";
					}
			                $query.=" group by problema order by problema";//group by problema

			                $exec_prob = mysql_query($query);
					while ($row_prob = mysql_fetch_array($exec_prob))
					{
						print "<option value=".$row_prob['prob_id']."";
							if ($row_prob['problema'] == $rowDesc['problema']) {
								print " selected";
							}
						print " >".$row_prob['problema']." </option>";
					}
				print "</select>";



?>