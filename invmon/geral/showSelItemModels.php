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

	$auth = new auth;
	$auth->testa_user_hidden($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],4);

				if (isset($_GET['cod'])) {
					$qryDesc = "SELECT * FROM estoque WHERE estoq_cod = '".$_GET['cod']."' ";
					$execDesc = mysql_query($qryDesc); //or die($qryDesc);
					$row = mysql_fetch_array($execDesc);

					$estoq_tipo = $row['estoq_tipo'];
					$estoq_model = $row['estoq_desc'];
				} else {
					//$estoq_tipo =
					$estoq_model = -1;
				}

				if (isset($_GET['tipo'])){
					$estoq_tipo = $_GET['tipo'];
				}


				print "<select class='select' name='estoque_desc' id='idDesc'>";
				$select ="select * from modelos_itens ";

				if (isset($_GET['cod']) || isset($_GET['tipo'])) {
					$select.= "WHERE mdit_tipo = '".$estoq_tipo."' ";
				}
				$select.= "order by mdit_tipo, mdit_fabricante, mdit_desc, mdit_desc_capacidade";

				$exec = mysql_query($select);
				while($desc = mysql_fetch_array($exec)){
					print "<option value=".$desc['mdit_cod']."";
					if ($desc['mdit_cod']==$estoq_model)
						print " selected";
					print ">".$desc['mdit_fabricante']." ".$desc['mdit_desc']." ".$desc['mdit_desc_capacidade']." ".$desc['mdit_sufixo']."</option>";
				} // while
				print "</select>";



?>