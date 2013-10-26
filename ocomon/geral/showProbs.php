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

	//print "<script type='text/javascript' src='../../includes/fckeditor/fckeditor.js'></script>";

	$imgsPath = "../../includes/imgs/";

	$prob_atual = $_GET['prob_atual'];

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

				$query = "SELECT * FROM problemas as p ".
							"LEFT JOIN sistemas as s on p.prob_area = s.sis_id ".
							"LEFT JOIN sla_solucao as sl on sl.slas_cod = p.prob_sla ".
							"LEFT JOIN prob_tipo_1 as pt1 on pt1.probt1_cod = p.prob_tipo_1 ".
							"LEFT JOIN prob_tipo_2 as pt2 on pt2.probt2_cod = p.prob_tipo_2 ".
							"LEFT JOIN prob_tipo_3 as pt3 on pt3.probt3_cod = p.prob_tipo_3 ";


				//if ($rowABS['area_cod'] != -1){
				if (isset($_GET['area_cod']) && $_GET['area_cod'] != -1){
					$clausula = " and (p.prob_area = ".$_GET['area_cod']." OR (p.prob_area is null OR p.prob_area = -1)) ";
				} else
					$clausula = "";


				if (isset($_GET['prob']) && $_GET['prob'] != -1 )  { //&& $_POST['problema'])
					$query.= " WHERE lower(p.problema) like lower(('%".$rowId['problema']."%')) ".$clausula."";
				} else
					$query.= " WHERE p.problema = -1 ".$clausula."";


				$query .=" ORDER  BY s.sistema, p.problema";

				//print $query;
				$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
				$registros = mysql_num_rows($resultado);


				if (mysql_num_rows($resultado) == 0)
				{
					//print "<tr><td align='center'>";
					//echo mensagem(TRANS('NO_CAT_TIL_SEL_PROB'));
					//print "</tr></td>";
				}
				else
				{
					print "<tr><td colspan='8'></tr>";
					print "<TR class='header'><td class='line'>".TRANS('COL_PROB','Problema')."<td class='line'>".TRANS('COL_SLA','SLA')."</TD>". //<td class='line'>".TRANS('COL_AREA','')."</TD>
						"<td class='line'>".$row_config['conf_prob_tipo_1']."</TD><td class='line'>".$row_config['conf_prob_tipo_2']."</TD>".
						"<td class='line'>".$row_config['conf_prob_tipo_3']."</TD></tr>";

					$j=2;
					while ($row = mysql_fetch_array($resultado))
					{
						if ($j % 2)
						{
							$trClass = "lin_par";
						}
						else
						{
							$trClass = "lin_impar";
						}
						$j++;
						print "<tr class=".$trClass." id='linhaxx".$j."' onMouseOver=\"destaca('linhaxx".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhaxx".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhaxx".$j."','".$_SESSION['s_colorMarca']."');\">";

						//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
						//print "<td class='line'><input type='radio' id='idRadioProb' name='radio_prob' value='".$row['prob_id']."'";
						print "<td class='line'><input type='radio' id='idRadioProb".$row['prob_id']."' name='radio_prob' value='".$row['prob_id']."'";
						//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------


							if (isset($_GET['radio_prob']) && $_GET['radio_prob'] == $row['prob_id']) print " checked"; else
							if (isset($_GET['prob']) && $_GET['prob'] == $row['prob_id']) print " checked"; //else

							//if (isset($_POST['radio_prob']) && $_POST['radio_prob'] == $row['prob_id']) print " checked"; else
							//if (isset($_POST['prob']) && $_POST['prob'] == $row['prob_id']) print " checked";
						//------------------------------------------------------------- INICIO ALTERACAO --------------------------------------------------------------
						if (isset($_GET['pathAdmin'])) //se o script estiver sendo chamado da path do módulo de administração
							print " onClick=\"ajaxFunction('divInformacaoProblema', '../../ocomon/geral/showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea' , 'radio_prob=idRadioProb".$row['prob_id']."');\"";
						else
							print " onClick=\"ajaxFunction('divInformacaoProblema', 'showInformacaoProb.php', 'idLoad', 'prob=idProblema', 'area_cod=idArea' , 'radio_prob=idRadioProb".$row['prob_id']."');\"";
						//------------------------------------------------------------- FIM ALTERACAO --------------------------------------------------------------
						print ">".$row['problema']."</td>";

						print "<td class='line'>".NVL($row['slas_desc'])."</td>";
						print "<td class='line'>".NVL($row['probt1_desc'])."</td>";
						print "<td class='line'>".NVL($row['probt2_desc'])."</td>";
						print "<td class='line'>".NVL($row['probt3_desc'])."</td>";

						print "</TR>";
					}
				}

			print "</table>";
			//print "</div></td></tr>";
			//print "</tr>";

?>
