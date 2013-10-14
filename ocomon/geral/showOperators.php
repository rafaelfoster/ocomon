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
	

		if (isset($_GET['op'])){
			$qryDesc = "SELECT * FROM usuarios WHERE user_id = ".$_GET['op']."";
			$execDesc = mysql_query($qryDesc);
			$rowDesc = mysql_fetch_array($execDesc);

		}

               	print "<TD width='30%' align='left' bgcolor=".BODY_COLOR.">";

			print "<SELECT class='select' name='foward' id='idFoward' onChange=\"checkMailOper();\">";
				print "<option value='-1' selected>".TRANS('OCO_SEL_OPERATOR')."</option>";
			$query = "SELECT u.*, a.* from usuarios u, sistemas a where u.AREA = a.sis_id and a.sis_atende='1' and u.nivel not in (3,4,5)";
			 
			if(isset($_GET['area_cod'])){
				$query.=" and a.sis_id = '".$_GET['area_cod']."'";
			}
			$query.=" order by login";
			$exec_oper = mysql_query($query);
			while ($row_oper = mysql_fetch_array($exec_oper))
			{
				print "<option value=".$row_oper['user_id'].""; 
				
				if (isset($_GET['op'])){
					if ($_GET['op'] == $row_oper){
						print " selected";
					}
				}
				
				print ">".$row_oper['nome']."</option>";
			}
			print "</SELECT>";
		print "</TD>";



?>