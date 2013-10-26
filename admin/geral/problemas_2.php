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

	$_SESSION['s_page_admin'] = $_SERVER['PHP_SELF'];

	print "<HTML>";
	print "<BODY bgcolor='".BODY_COLOR."'>";

	$auth = new auth;
	$auth->testa_user($_SESSION['s_usuario'],$_SESSION['s_nivel'],$_SESSION['s_nivel_desc'],2);

	//print "<BR><B>".TRANS('ADM_PROBS')."</B><BR>";

	print "<FORM method='POST' action='".$_SERVER['PHP_SELF']."' onSubmit=\"return valida()\">";

	if (!isset($_GET['cellStyle'])) {
		$cellStyle = "cellpadding='5' cellspacing='0'";
	} else
		$cellStyle = "cellpadding='0' cellspacing='1'";
	print "<TABLE border='0' align='left' ".$cellStyle."  width='100%' bgcolor='".BODY_COLOR."'>";


		if (isset($_GET['id'])) {
			$qry_id = "SELECT * FROM problemas WHERE prob_id = ".$_GET['id']."";
			$exec_qry_id = mysql_query($qry_id);
			$rowId = mysql_fetch_array($exec_qry_id);
		}
		
		
		
		
		
		$qry_config = "SELECT * FROM config ";
        	$exec_config = mysql_query($qry_config) or die (TRANS('ERR_TABLE_CONFIG'));
		$row_config = mysql_fetch_array($exec_config);

		$query = "SELECT * FROM problemas as p ".
					"LEFT JOIN sistemas as s on p.prob_area = s.sis_id ".
					"LEFT JOIN sla_solucao as sl on sl.slas_cod = p.prob_sla ".
					"LEFT JOIN prob_tipo_1 as pt1 on pt1.probt1_cod = p.prob_tipo_1 ".
					"LEFT JOIN prob_tipo_2 as pt2 on pt2.probt2_cod = p.prob_tipo_2 ".
					"LEFT JOIN prob_tipo_3 as pt3 on pt3.probt3_cod = p.prob_tipo_3 ";

		if (isset($_GET['cod'])) {
			$query.= " WHERE p.prob_id = ".$_GET['cod']." ";
		} else
		if (isset($_GET['id'])) {
			$query.= " WHERE p.problema like ('%".$rowId['problema']."%') ";
		}



		$query .=" ORDER  BY s.sistema, p.problema";
		$resultado = mysql_query($query) or die(TRANS('ERR_QUERY'));
		$registros = mysql_num_rows($resultado);

	if ((!isset($_GET['action'])) && empty($_POST['submit'])) {

		//print "<TR><TD bgcolor='".BODY_COLOR."'><a href='".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true'>Incluir novo tipo de Problema</a></TD></TR>";
		//print "<TR><TD><input type='button' class='button' id='idBtIncluir' value='".TRANS('BT_NEW_RECORD','',0)."' onClick=\"redirect('".$_SERVER['PHP_SELF']."?action=incluir&cellStyle=true');\"></TD></TR>";
		if (mysql_num_rows($resultado) == 0)
		{
			print "<tr><td align='center'>";
			echo mensagem(TRANS('NO_RECORDS'));
			print "</tr></td>";
		}
		else
		{
			print "<tr><td colspan='8'>";
			print "".TRANS('THERE_IS_ARE')." <b>".$registros."</b> ".TRANS('POSSIBLE_RECORDS_IN_SYSTEM').".</td>";
			print "</tr>";
			print "<TR class='header'><td class='line'>".TRANS('COL_PROB','Problema')."</TD><td class='line'>".TRANS('COL_AREA','')."</TD><td class='line'>".TRANS('COL_SLA','SLA')."</TD>".
				"<td class='line'>".$row_config['conf_prob_tipo_1']."</TD><td class='line'>".$row_config['conf_prob_tipo_2']."</TD>".
				"<td class='line'>".$row_config['conf_prob_tipo_3']."</TD>";

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
				print "<tr class=".$trClass." id='linhax".$j."' onMouseOver=\"destaca('linhax".$j."','".$_SESSION['s_colorDestaca']."');\" onMouseOut=\"libera('linhax".$j."','".$_SESSION['s_colorLinPar']."','".$_SESSION['s_colorLinImpar']."');\"  onMouseDown=\"marca('linhax".$j."','".$_SESSION['s_colorMarca']."');\">";
				print "<td class='line'><input type='radio' name='radio_prob' value='".$row['prob_id']."'";
				
					if (isset($_GET['id']) && $_GET['id'] == $row['prob_id']) print " checked";
				
				print ">".$row['problema']."</td>";
				
				print "<td class='line'>".NVL($row['sistema'])."</td>";
				print "<td class='line'>".NVL($row['slas_desc'])."</td>";
				print "<td class='line'>".NVL($row['probt1_desc'])."</td>";
				print "<td class='line'>".NVL($row['probt2_desc'])."</td>";
				print "<td class='line'>".NVL($row['probt3_desc'])."</td>";

				print "</TR>";
			}
			//print "</TABLE>";
		}

	}
	
	

	print "</table>";

?>
<script type="text/javascript">
<!--
	function valida(){
		var ok = validaForm('idProblema','','Problema',1);
		//if (ok) var ok = validaForm('idArea','COMBO','Área',1);
		if (ok) var ok = validaForm('idSla','COMBO','SLA',1);

		return ok;
	}

-->
</script>


<?php 
print "</body>";
print "</html>";
