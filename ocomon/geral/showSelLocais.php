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

				if (isset($_GET['unidade']) && isset($_GET['etiqueta'])) {
					$qryDesc = "SELECT * FROM equipamentos where comp_inst = '".$_GET['unidade']."' AND comp_inv ='".$_GET['etiqueta']."' ";
					$execDesc = mysql_query($qryDesc); //or die($qryDesc);
					$rowDesc = mysql_fetch_array($execDesc);

				}

				print "<SELECT class='select' name='local' id='idLocal'>"; //onChange=\"Habilitar();\"
				print "<option value=-1 selected>".TRANS('OCO_SEL_LOCAL')."</option>";
					$query ="SELECT l .  * , r.reit_nome, pr.prior_nivel AS prioridade, d.dom_desc AS dominio, pred.pred_desc as predio
							FROM localizacao AS l
							LEFT  JOIN reitorias AS r ON r.reit_cod = l.loc_reitoria
							LEFT  JOIN prioridades AS pr ON pr.prior_cod = l.loc_prior
							LEFT  JOIN dominios AS d ON d.dom_cod = l.loc_dominio
							LEFT JOIN predios as pred on pred.pred_cod = l.loc_predio
							WHERE loc_status not in (0)
							ORDER  BY LOCAL ";
					$resultado = mysql_query($query);
					$linhas = mysql_numrows($resultado);
					while ($rowi = mysql_fetch_array($resultado))
					{
						print "<option value='".$rowi['loc_id']."'";
							//if ($rowi['loc_id'] == $invLoc) print " selected";
							if (isset($rowDesc) && $rowDesc['comp_local'] == $rowi['loc_id']) print " selected";
							//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
							else if (isset($_REQUEST['invLoc']) && $rowi['loc_id'] == $_REQUEST['invLoc']) print " selected"; 
							//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
							//if ($rowi['loc_id'] == $rowDesc['comp_local']) print " selected";
						print ">".$rowi['local']." ".$rowi['predio']."</option>";
					}
				print "</SELECT>";
				print "<a onClick=\"checa_por_local()\"> &nbsp;&nbsp;<img title='".TRANS('CONS_EQUIP_LOCAL')."' width='15' height='15' src='".$imgsPath."consulta.gif' border='0'></a>";

	                //print "</TD>";


?>
